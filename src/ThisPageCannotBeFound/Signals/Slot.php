<?php

namespace ThisPageCannotBeFound\Signals;

/**
 * A Slot is a signal listener configuration object.
 *
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class Slot implements SlotInterface {

    /**
     * @var callable
     */
    protected $listener;

    /**
     * @var boolean
     */
    protected $once;

    /**
     * @var callable
     */
    protected $resolved;

    /**
     * @var callable
     */
    protected $resolver;

    /**
     * Creates the new Slot.
     *
     * @param callable $listener
     * @param boolean $once
     */
    function __construct($listener, $once) {
        $this->listener = $listener;
        $this->once = $once;
    }

    /**
     * Executes the listener, passing the arguments.
     *
     * @param array $args Values to pass to the listener
     *
     * @return mixed Return value of executed listener
     */
    public function execute(array $args) {
        $callback = $this->resolveListener();

        if (empty($args)) {
            return call_user_func($callback);
        } else {
            return call_user_func_array($callback, $args);
        }
    }

    /**
     * Returns the valid callable.
     *
     * @return callable
     */
    public function getListener() {
        return $this->listener;
    }

    /**
     * Returns whether this listener should be removed after it has been executed.
     *
     * @return boolean
     */
    public function getOnce() {
        return $this->once;
    }

    /**
     * If the listener is not a valid callback (e.g. a string like "Class:method"),
     * this method will use the resolver
     *
     * @return callable
     */
    public function resolveListener() {
        if (isset($this->resolved)) {
            return $this->resolved;
        }

        $listener = $this->listener;

        if (!isset($this->resolver) || is_object($listener)) {
            return $this->resolved = $listener;
        }

        if (is_array($listener)) {
            list($class, $method) = $listener;
        } else if (is_string($listener) && strpos($listener, '::')) {
            list($class, $method) = explode('::', $listener);
        }

        if (isset($class) && !is_object($class)) {
            $instance = call_user_func($this->resolver, $class);
            $listener = array($instance, $method);
        }

        return $this->resolved = $listener;
    }

    /**
     * Sets the listener resolver, a factory for uninitialized callables.
     *
     * @param callable $resolver
     */
    public function setResolver($resolver) {
        if (!is_callable($resolver)) {
            throw new Exception\ResolverNotCallableException($resolver);
        }

        $this->resolver = $resolver;
    }

}
