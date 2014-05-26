<?php

namespace ThisPageCannotBeFound\Signals\Tests;

use PHPUnit_Framework_TestCase;
use ThisPageCannotBeFound\Signals\Slot;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class SlotTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function listenerIsObjectShouldReturnListener() {
        $listener = Support\Listeners::_closureIncrementsCalled();

        $resolved = $this->resolveListener($listener);

        $this->assertSame($listener, $resolved);
    }

    /**
     * @test
     */
    public function noResolverShouldReturnUnresolvedListener() {
        $listener = Support\Listeners::_arrayStringCallback();

        $resolved = $this->resolveListener($listener);

        $this->assertSame($listener, $resolved);
    }

    /**
     * @test
     */
    public function noResolverObjectArrayShouldReturnListener() {
        $listener = Support\Listeners::_arrayObjectCallback();

        $resolved = $this->resolveListener($listener);

        $this->assertSame($listener, $resolved);
    }

    /**
     * @test
     */
    public function hasResolverStringArrayShouldReturnResolved() {
        $listener = Support\Listeners::_arrayStringCallback();

        $resolved = $this->resolveListener($listener, $this->getBasicResolver());

        $this->assertNotSame($listener, $resolved);
        $this->assertInstanceOf(Support\Listeners::__CLASS, $resolved[0]);
    }

    /**
     * @test
     */
    public function hasResolverStringShouldReturnResolved() {
        $listener = Support\Listeners::_stringCallback();

        $resolved = $this->resolveListener($listener, $this->getBasicResolver());

        $this->assertNotSame($listener, $resolved);
        $this->assertInstanceOf(Support\Listeners::__CLASS, $resolved[0]);
    }

    /* HELPER METHODS */

    /**
     * Returns a basic closure resolver.
     *
     * @return \Closure
     */
    private function getBasicResolver() {
        return function($class) {
            return new $class;
        };
    }

    /**
     * Create a new slot and call its resolve method.
     *
     * @param callable $listener
     * @return callable
     */
    private function resolveListener($listener, $resolver = null) {
        $slot = new Slot($listener, true);

        if ($resolver) {
            $slot->setResolver($resolver);
        }

        return $slot->resolveListener();
    }

}
