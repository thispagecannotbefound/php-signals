<?php

namespace ThisPageCannotBeFound\Signals;

/**
 * A Signal executes callbacks, passing optional values.
 *
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class Signal extends OnceSignal implements SignalInterface {

    /**
     * Add a listener to the signal.
     *
     * @param callable $listener A valid callable
     *
     * @return SlotInterface The created Slot for this listener
     */
    public function add($listener) {
        return $this->registerListener($listener, false);
    }

}
