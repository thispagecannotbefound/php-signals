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

        $slot = $this->signal->addOnce(Listeners::a());

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
    public function addOnceTwiceShouldReturnSameSlot() {
        $listener = Listeners::a();

        $first = $this->signal->addOnce($listener);
        $second = $this->signal->addOnce($listener);

        $this->assertSame($first, $second);
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
     * @expectedException \ThisPageCannotBeFound\Signals\Exception\MissingArgumentsException
     */
    public function dispatchNoTypeArgCountMatchShouldThrow() {
        $this->signal = new OnceSignal(array('stdClass'));
        $this->signal->addOnce(Listeners::a());
        $this->signal->dispatch();
    }

    /**
     * @test
     * @expectedException \ThisPageCannotBeFound\Signals\Exception\ValueTypeMismatchException
     */
    public function dispatchNativeTypeNoMatchShouldThrow() {
        $this->signal = new OnceSignal(array('boolean'));
        $this->signal->addOnce(Listeners::a());
        $this->signal->dispatch(123);
    }

    /**
     * @test
     * @expectedException \ThisPageCannotBeFound\Signals\Exception\ValueTypeMismatchException
     */
    public function dispatchClassTypeNoMatchShouldThrow() {
        $this->signal = new OnceSignal(array('Iterator'));
        $this->signal->addOnce(Listeners::a());
        $this->signal->dispatch(new \stdClass());
    }

    /**
     * @test
     */
    public function dispatchClassTypeMatchShouldBeSuccessul() {
        $this->signal = new OnceSignal(array('stdClass'));
        $this->signal->addOnce(Listeners::a());
        $this->signal->dispatch(new \stdClass());
    }

    /**
     * @test
     */
    public function getNumListenersShouldReturnListenerCount() {
        $this->assertEquals(0, $this->signal->getNumListeners());

        $this->signal->addOnce(Listeners::a());
        $this->signal->addOnce(Listeners::b());
        $this->signal->addOnce(Listeners::c());

        $this->assertEquals(3, $this->signal->getNumListeners());

        $this->signal->removeAll();

        $this->assertEquals(0, $this->signal->getNumListeners());
    }

    /**
     * @test
     */
    public function removeNoListenersShouldBeSilent() {
        $this->signal->remove(Listeners::a());
    }

    /**
     * @test
     */
    public function removeShouldReturnRemovedSlot() {
        $interface = 'ThisPageCannotBeFound\Signals\SlotInterface';
        $listener = Listeners::a();

        $added = $this->signal->addOnce($listener);
        $removed = $this->signal->remove($listener);

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

    /**
     * @test
     * @expectedException \ThisPageCannotBeFound\Signals\Exception\InvalidTypeException
     */
    public function __constructInvalidTypeShouldThrow() {
        new OnceSignal(array('foo-bar'));
    }

    /**
     * @test
     */
    public function __constructExistingClassesInterfacesShouldAccept() {
        new OnceSignal(array('ArrayIterator', 'Iterator'));
    }

    /**
     * @test
     */
    public function __constructNativeTypesShouldAccept() {
        new OnceSignal(array('array', 'boolean', 'float', 'integer', 'object', 'resource', 'string'));
    }

}
