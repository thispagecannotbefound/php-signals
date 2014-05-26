<?php

namespace ThisPageCannotBeFound\Signals\Tests;

use PHPUnit_Framework_TestCase;
use ThisPageCannotBeFound\Signals\Signal;
use ThisPageCannotBeFound\Signals\Tests\Support\Listeners;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class SignalTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Signal
     */
    private $signal;

    protected function setUp() {
        $this->signal = new Signal();
    }

    /**
     * @test
     */
    public function addShouldReturnSlot() {
        $interface = 'ThisPageCannotBeFound\Signals\SlotInterface';

        $slot = $this->signal->add('sprintf');

        $this->assertInstanceOf($interface, $slot);
    }

    /**
     * @test
     */
    public function addMultipleShouldExecuteMultiple() {
        $called = 0;

        $this->signal->add(Listeners::_closureIncrementsCalled($called));
        $this->signal->dispatch();
        $this->signal->dispatch();
        $this->signal->dispatch();

        $this->assertEquals(3, $called);
    }

    /**
     * @test
     * @expectedException \ThisPageCannotBeFound\Signals\Exception\CannotAddListenerException
     */
    public function addThenAddOnceSameShouldThrow() {
        $listener = Listeners::_closureIncrementsCalled();

        $this->signal->add($listener);
        $this->signal->addOnce($listener);
    }

}
