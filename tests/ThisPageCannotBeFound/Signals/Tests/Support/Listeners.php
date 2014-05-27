<?php

namespace ThisPageCannotBeFound\Signals\Tests\Support;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class Listeners {

    const __CLASS = __CLASS__;

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

    /**
     * @return array
     */
    public static function _arrayStringCallback() {
        return array(__CLASS__, 'exampleListener');
    }

    /**
     * @return array
     */
    public static function _arrayObjectCallback() {
        return array(new self(), 'exampleListener');
    }

    /**
     * @return string
     */
    public static function _stringCallback() {
        return __CLASS__ . '::exampleListener';
    }

    /**
     * @return string Fully qualified method name
     */
    public function exampleListener() {
        return __METHOD__;
    }

    /* DUMMY LISTENERS */

    /**
     * @return string Callable as fully qualified name
     */
    public static function a() {
        return __METHOD__;
    }

    /**
     * @return string Callable as fully qualified name
     */
    public static function b() {
        return __METHOD__;
    }

    /**
     * @return string Callable as fully qualified name
     */
    public static function c() {
        return __METHOD__;
    }

}
