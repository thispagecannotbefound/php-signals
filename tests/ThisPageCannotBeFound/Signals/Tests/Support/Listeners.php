<?php

namespace ThisPageCannotBeFound\Signals\Tests\Support;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class Listeners {

    /**
     * @param integer $called
     * @return \Closure
     */
    public static function _closureIncrementsCalled(&$called = 0) {
        return function() use(&$called) {
            $called++;
        };
    }

    /**
     * @param object $result
     * @return \Closure
     */
    public static function _closureSetsArguments(&$result = null) {
        return function() use(&$result) {
            $result = func_get_args();
        };
    }

}
