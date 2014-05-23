<?php

namespace ThisPageCannotBeFound\Signals;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class OnceSignal implements OnceSignalInterface {

    /**
     * @var Slot[]
     */
    protected $slots = array();

    public function addOnce($listener) {
        return $this->addSlot($listener, true);
    }

    public function dispatch() {
        $args = func_get_args();

        foreach ($this->slots as $slot) {
            $callback = $slot->getListener();

            if ($slot->getOnce()) {
                $this->remove($callback);
            }

            if (empty($args)) {
                call_user_func($callback);
            } else {
                call_user_func_array($callback, $args);
            }
        }
    }

    public function getNumListeners() {
        return count($this->slots);
    }

    public function remove($listener) {
        foreach ($this->slots as $key => $slot) {
            if ($slot->getListener() === $listener) {
                unset($this->slots[$key]);
                return $slot;
            }
        }
    }

    public function removeAll() {
        $this->slots = array();
    }

    /* HIDDEN METHODS */

    /**
     * @param callable $listener
     * @param boolean $once
     * @return Slot
     * @throws Exception\ListenerNotCallableException
     * @todo Check if listener has already been added
     */
    protected function addSlot($listener, $once) {
        if (!is_callable($listener)) {
            throw new Exception\ListenerNotCallableException($listener);
        }

        return $this->slots[] = new Slot($listener, $once);
    }

}
