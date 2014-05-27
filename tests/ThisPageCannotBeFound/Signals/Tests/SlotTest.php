<?php

namespace ThisPageCannotBeFound\Signals\Tests;

use PHPUnit_Framework_TestCase;
use ThisPageCannotBeFound\Signals\Slot;
use ThisPageCannotBeFound\Signals\Tests\Support\Listeners;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class SlotTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function listenerIsObjectShouldReturnListener() {
        $listener = Listeners::_closureIncrementsCalled();

        $resolved = $this->resolveListener($listener);

        $this->assertSame($listener, $resolved);
    }

    /**
     * @test
     */
    public function noResolverShouldReturnUnresolvedListener() {
        $listener = Listeners::_arrayStringCallback();

        $resolved = $this->resolveListener($listener);

        $this->assertSame($listener, $resolved);
    }

    /**
     * @test
     */
    public function noResolverObjectArrayShouldReturnListener() {
        $listener = Listeners::_arrayObjectCallback();

        $resolved = $this->resolveListener($listener);

        $this->assertSame($listener, $resolved);
    }

    /**
     * @test
     */
    public function hasResolverStringArrayShouldReturnResolved() {
        $listener = Listeners::_arrayStringCallback();

        $resolved = $this->resolveListener($listener, $this->getBasicResolver());

        $this->assertNotSame($listener, $resolved);
        $this->assertInstanceOf(Listeners::__CLASS, $resolved[0]);
    }

    /**
     * @test
     */
    public function hasResolverStringShouldReturnResolved() {
        $listener = Listeners::_stringCallback();

        $resolved = $this->resolveListener($listener, $this->getBasicResolver());

        $this->assertNotSame($listener, $resolved);
        $this->assertInstanceOf(Listeners::__CLASS, $resolved[0]);
    }

    /**
     * @test
     * @expectedException \ThisPageCannotBeFound\Signals\Exception\ResolverNotCallableException
     */
    public function setResolverInvalidCallableShouldThrow() {
        $this->resolveListener(Listeners::_stringCallback(), 'foo-bar-baz');
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
