<?php

namespace ThisPageCannotBeFound\Signals;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class Signal extends OnceSignal implements SignalInterface {

    public function add($listener) {
        return $this->addSlot($listener, false);
    }

}
