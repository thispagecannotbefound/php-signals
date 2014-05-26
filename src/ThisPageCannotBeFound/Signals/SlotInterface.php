<?php

namespace ThisPageCannotBeFound\Signals;

/**
 * A Slot is a signal listener configuration object.
 *
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
interface SlotInterface {

    /**
     * Executes the listener, passing the arguments.
     *
     * @param array $args Values to pass to the listener
     *
     * @return mixed Return value of executed listener
     */
    public function execute(array $args);

    /**
     * Returns the valid callable.
     *
     * @return callable
     */
    public function getListener();

    /**
     * Returns whether this listener should be removed after it has been executed.
     *
     * @return boolean
     */
    public function getOnce();
}
