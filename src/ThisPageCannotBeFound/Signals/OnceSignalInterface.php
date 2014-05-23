<?php

namespace ThisPageCannotBeFound\Signals;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
interface OnceSignalInterface {

    /**
     * Add a listener to the signal, which will be removed once it has been
     * executed.
     *
     * @param callable $listener A valid callable
     *
     * @return SlotInterface The created Slot for this listener
     */
    public function addOnce($listener);

    /**
     * Execute the signal's listeners.
     *
     * @param mixed ... Values to send to listeners
     *
     * @return void
     */
    public function dispatch();

    /**
     * Get the number of added listeners.
     *
     * @return integer The number of listeners
     */
    public function getNumListeners();

    /**
     * Remove a listener from the signal.
     *
     * @param callable $listener A previously added callable
     *
     * @return SlotInterface The removed Slot
     */
    public function remove($listener);

    /**
     * Remove all listeners from the signal.
     *
     * @return void
     */
    public function removeAll();
}
