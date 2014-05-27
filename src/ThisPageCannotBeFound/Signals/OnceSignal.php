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
     * @var SlotInterface[]
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
     * @param array $types Value types
     * @throws InvalidTypeException
     */
    public function __construct(array $types = array()) {
        foreach ($types as $type) {
            if (!isset(self::$nativeTypes[$type]) &&
                    !class_exists($type) &&
                    !interface_exists($type)) {
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
        if (empty($this->slots)) {
            return;
        }

        $args = func_get_args();
        $this->validateArgTypes($args);
        $this->executeListeners($args);
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
        foreach ($this->slots as $index => $slot) {
            if ($slot->getListener() === $listener) {
                unset($this->slots[$index]);

                return $slot;
            }
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
     * Execute the listeners for each slot.
     *
     * @param array $args
     */
    protected function executeListeners(array $args) {
        foreach ($this->slots as $index => $slot) {
            if ($slot->getOnce()) {
                unset($this->slots[$index]);
            }

            $slot->execute($args);
        }
    }

    /**
     * Returns the slot that is associated with this listener.
     *
     * @param callable $listener
     *
     * @return SlotInterface
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
     * @return SlotInterface
     */
    protected function registerListener($listener, $once) {
        $existing = $this->registrationPossible($listener, $once);

        if ($existing) {
            return $existing;
        }

        return $this->slots[] = new Slot($listener, $once);
    }

    /**
     * Checks whether this listener has already been registered.
     *
     * @param callable $listener
     * @param boolean $once
     * @return SlotInterface Existing slot
     * @throws CannotAddListenerException
     * @throws ListenerNotCallableException
     */
    protected function registrationPossible($listener, $once) {
        if (!is_callable($listener)) {
            throw new ListenerNotCallableException($listener);
        }

        $existing = $this->getSlot($listener);

        if ($existing && $existing->getOnce() !== $once) {
            throw new CannotAddListenerException('You cannot addOnce() then add() the same listener without removing the relationship first.');
        }

        return $existing;
    }

    /**
     * Validates the arguments types.
     *
     * @param array $args
     * @throws MissingArgumentsException
     * @throws ValueTypeMismatchException
     */
    protected function validateArgTypes(array $args) {
        if (empty($this->types)) {
            return;
        }

        $numTypes = count($this->types);

        if (count($args) < $numTypes) {
            throw new MissingArgumentsException($numTypes, count($args));
        }

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
