<?php

namespace ThisPageCannotBeFound\Signals;

use ThisPageCannotBeFound\Signals\Exception\CannotAddListenerException;
use ThisPageCannotBeFound\Signals\Exception\InvalidTypeException;
use ThisPageCannotBeFound\Signals\Exception\ListenerNotCallableException;
use ThisPageCannotBeFound\Signals\Exception\MissingArgumentsException;
use ThisPageCannotBeFound\Signals\Exception\ValueTypeMismatchException;

/**
 * A Signal executes callbacks, passing optional values. This signal removes its
 * listeners after they have been executed.
 *
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class OnceSignal implements OnceSignalInterface {

    /**
     * @var array
     */
    protected static $nativeTypes = array(
        'array' => 'array',
        'boolean' => 'bool',
        'float' => 'float',
        'integer' => 'integer',
        'object' => 'object',
        'resource' => 'resource',
        'string' => 'string',
    );

    /**
     * @var Slot[]
     */
    protected $slots = array();

    /**
     * @var array
     */
    protected $types;

    /**
     * Creates a new instance. Optionally value types can be passed - class or
     * interface names, or native types, such as "array" or "boolean". This will
     * validate the types of the dispatch() arguments.
     *
     * @param mixed ... Value types
     * @throws InvalidTypeException
     */
    public function __construct() {
        $types = func_get_args();

        foreach ($types as $type) {
            if (!class_exists($type) && !interface_exists($type) && !isset(self::$nativeTypes[$type])) {
                throw new InvalidTypeException($type, array_keys(self::$nativeTypes));
            }
        }

        $this->types = $types;
    }

    /**
     * Add a listener to the signal, which will be removed once it has been
     * executed.
     *
     * @param callable $listener A valid callable
     *
     * @return SlotInterface The created Slot for this listener
     */
    public function addOnce($listener) {
        return $this->registerListener($listener, true);
    }

    /**
     * Execute the signal's listeners.
     *
     * @param mixed ... Values to send to listeners
     *
     * @return void
     */
    public function dispatch() {
        $args = func_get_args();
        $numArgs = count($args);
        $numTypes = count($this->types);

        if ($numArgs < $numTypes) {
            throw new MissingArgumentsException($numTypes, $numArgs);
        }

        $this->validateArgTypes($args, $numTypes);

        foreach ($this->slots as $slot) {
            $callback = $slot->getListener();

            if ($slot->getOnce()) {
                $this->remove($callback);
            }

            if (empty($args)) {
                call_user_func($callback);
            } else {
                call_user_func_array($callback, $args);
            }
        }
    }

    /**
     * Get the number of added listeners.
     *
     * @return integer The number of listeners
     */
    public function getNumListeners() {
        return count($this->slots);
    }

    /**
     * Remove a listener from the signal.
     *
     * @param callable $listener A previously added callable
     *
     * @return SlotInterface The removed Slot
     */
    public function remove($listener) {
        $slot = $this->getSlot($listener);

        if ($slot) {
            $index = array_search($slot, $this->slots, true);
            unset($this->slots[$index]);

            return $slot;
        }
    }

    /**
     * Remove all listeners from the signal.
     *
     * @return void
     */
    public function removeAll() {
        $this->slots = array();
    }

    /* HIDDEN METHODS */

    /**
     * Returns the slot that is associated with this listener.
     *
     * @param callable $listener
     *
     * @return Slot
     */
    protected function getSlot($listener) {
        foreach ($this->slots as $slot) {
            if ($slot->getListener() === $listener) {
                return $slot;
            }
        }
    }

    /**
     * Creates and adds a slot for this listener.
     *
     * @param callable $listener
     * @param boolean $once
     * @return Slot
     * @throws ListenerNotCallableException
     */
    protected function registerListener($listener, $once) {
        if (!is_callable($listener)) {
            throw new ListenerNotCallableException($listener);
        }

        if ($this->registrationPossible($listener, $once)) {
            return $this->slots[] = new Slot($listener, $once);
        }

        return $this->getSlot($listener);
    }

    /**
     * Checks whether this listener has already been registered.
     *
     * @param callable $listener
     * @param boolean $once
     * @return boolean
     * @throws CannotAddListenerException
     */
    protected function registrationPossible($listener, $once) {
        $existing = $this->getSlot($listener);

        if (!$existing) {
            return true;
        } else if ($existing->getOnce() !== $once) {
            throw new CannotAddListenerException('You cannot addOnce() then add() the same listener without removing the relationship first.');
        }

        return false;
    }

    /**
     * Validates the arguments types.
     *
     * @param array $args
     * @throws MissingArgumentsException
     * @throws ValueTypeMismatchException
     */
    protected function validateArgTypes(array $args, $numTypes) {
        for ($i = 0; $i < $numTypes; $i++) {
            $arg = $args[$i];
            $type = $this->types[$i];

            if (isset(self::$nativeTypes[$type])) {
                $is = 'is_' . self::$nativeTypes[$type];

                if (!$is($arg)) {
                    throw new ValueTypeMismatchException($type, $arg, true);
                }
            } else if (!$arg instanceof $type) {
                throw new ValueTypeMismatchException($type, $arg, false);
            }
        }
    }

}
