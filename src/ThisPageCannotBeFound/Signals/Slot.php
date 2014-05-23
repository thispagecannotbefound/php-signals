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

}
