<?php

namespace ThisPageCannotBeFound\Signals\Tests;

use PHPUnit_Framework_TestCase;
use ThisPageCannotBeFound\Signals\OnceSignal;
use ThisPageCannotBeFound\Signals\Tests\Support\Listeners;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class OnceSignalTest extends PHPUnit_Framework_TestCase {

    /**
     * @var OnceSignal
     */
    private $signal;

    protected function setUp() {
        $this->signal = new OnceSignal();
    }

    /**
     * @test
     * @expectedException \ThisPageCannotBeFound\Signals\Exception\ListenerNotCallableException
     */
    public function addOnceListenerNotCallableShouldThrow() {
        $this->signal->addOnce('foo-bar-baz');
    }

    /**
     * @test
     */
    public function addOnceShouldReturnSlot() {
        $interface = 'ThisPageCannotBeFound\Signals\SlotInterface';

        $slot = $this->signal->addOnce('sprintf');

        $this->assertInstanceOf($interface, $slot);
    }

    /**
     * @test
     */
    public function addOnceDispatchMultipleShouldExecuteListenerOnce() {
        $called = 0;

        $this->signal->addOnce(Listeners::_closureIncrementsCalled($called));

        $this->signal->dispatch();
        $this->signal->dispatch();
        $this->signal->dispatch();

        $this->assertEquals(1, $called);
    }

    /**
     * @test
     */
    public function dispatchNoListenersShouldBeSilent() {
        $this->signal->dispatch();
    }

    /**
     * @test
     */
    public function dispatchArgumentsShouldPassArgumentsToListeners() {
        $args = array(
            'foo',
            123,
            array('bar', 456),
            (object) array('baz' => 789),
        );

        $result = null;

        $this->signal->addOnce(Listeners::_closureSetsArguments($result));
        $this->signal->dispatch($args[0], $args[1], $args[2], $args[3]);

        $this->assertSame($args, $result);
    }

    /**
     * @test
     */
    public function getNumListenersShouldReturnListenerCount() {
        $this->assertEquals(0, $this->signal->getNumListeners());

        $this->signal->addOnce('sprintf');

        $this->assertEquals(1, $this->signal->getNumListeners());

        $this->signal->addOnce('sprintf');

        $this->assertEquals(2, $this->signal->getNumListeners());

        $this->signal->addOnce('sprintf');
        $this->signal->addOnce('sprintf');
        $this->signal->addOnce('sprintf');

        $this->assertEquals(5, $this->signal->getNumListeners());

        $this->signal->removeAll();

        $this->assertEquals(0, $this->signal->getNumListeners());
    }

    /**
     * @test
     */
    public function removeNoListenersShouldBeSilent() {
        $this->signal->remove('sprintf');
    }

    /**
     * @test
     */
    public function removeShouldReturnRemovedSlot() {
        $interface = 'ThisPageCannotBeFound\Signals\SlotInterface';

        $added = $this->signal->addOnce('sprintf');
        $removed = $this->signal->remove('sprintf');

        $this->assertInstanceOf($interface, $removed);
        $this->assertSame($added, $removed);
    }

    /**
     * @test
     */
    public function removeShouldRemoveStrictMatch() {
        $calledA = 0;
        $calledB = 0;

        $listenerA = Listeners::_closureIncrementsCalled($calledA);
        $listenerB = Listeners::_closureIncrementsCalled($calledB);

        $this->signal->addOnce($listenerA);
        $this->signal->addOnce($listenerB);
        $this->signal->remove($listenerA);
        $this->signal->dispatch();

        $this->assertEquals(0, $calledA);
        $this->assertEquals(1, $calledB);
    }

    /**
     * @test
     */
    public function removeAllShouldRemoveAllListeners() {
        $calledA = 0;
        $calledB = 0;

        $listenerA = Listeners::_closureIncrementsCalled($calledA);
        $listenerB = Listeners::_closureIncrementsCalled($calledB);

        $this->signal->addOnce($listenerA);
        $this->signal->addOnce($listenerB);
        $this->signal->removeAll();
        $this->signal->dispatch();

        $this->assertEquals(0, $calledA);
        $this->assertEquals(0, $calledB);
    }

}
