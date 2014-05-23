<?php

namespace ThisPageCannotBeFound\Signals;

/**
 * A Slot is a signal listener configuration object.
 *
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
interface SlotInterface {

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
