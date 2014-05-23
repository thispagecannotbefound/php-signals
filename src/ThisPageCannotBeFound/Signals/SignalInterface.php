<?php

namespace ThisPageCannotBeFound\Signals;

/**
 * A Signal executes callbacks, passing optional values.
 *
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
interface SignalInterface {

    /**
     * Add a listener to the signal.
     *
     * @param callable $listener A valid callable
     *
     * @return SlotInterface The created Slot for this listener
     */
    public function add($listener);
}
