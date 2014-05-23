<?php

namespace ThisPageCannotBeFound\Signals;

/**
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

    function __construct($listener, $once) {
        $this->listener = $listener;
        $this->once = $once;
    }

    public function getListener() {
        return $this->listener;
    }

    public function getOnce() {
        return $this->once;
    }

    public function execute($args) {

    }

}
