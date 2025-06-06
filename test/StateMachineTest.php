<?php

use PHPUnit\Framework\TestCase;
use Xudid\StateMachine\GuardCondition;
use Xudid\StateMachine\StateMachine;
use Xudid\StateMachine\Transition;

class StateMachineTest extends TestCase
{
    public function testGetCurrentStateWithNewlyCreatedStateMachineThrowsException()
    {
        $stateMachine = new StateMachine();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('State machine has no state');
        $stateMachine->getCurrentState();
    }

    public function testGetCurrentStateWithStateMachineThatHasOneStateNotThrowsException()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->getCurrentState();
        $this->expectNotToPerformAssertions();
    }

    public function testSetInitialStateWithAnEmptyStateMachineThrowsException()
    {
        $stateMachine = new StateMachine();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('State machine has no state');
        $stateMachine->initial('initial');
    }

    public function testSetInitialStateWithStateMachineThatHasOneStateNotThrowsException()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->initial('initial');
        $this->expectNotToPerformAssertions();
    }

    public function testSetInitialStateWithUnknownStateThrowsException()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $this->expectException(Exception::class);
        $stateMachine->initial('second');
    }

    public function testCurrentStateOfStateMachineWithOneStepIsTheState()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $this->assertEquals('initial', $stateMachine->getCurrentState());
    }

    public function testInitialStateOfStateMachineWithOneStepIsTheState()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $this->assertTrue($stateMachine->isInitialState('initial'));
    }

    public function testUnknownStateIsNotInitialState()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $this->assertFalse($stateMachine->isInitialState('second'));
    }

    public function testInitialStateIsFirstAddedStateIfNotSet()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $this->assertTrue($stateMachine->isInitialState('initial'));
    }

    public function testInitialStateIsDesignedState()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $stateMachine->initial('second');
        $this->assertTrue($stateMachine->isInitialState('second'));
    }
    public function testIsCurrentStateReturnTrueWhenItsArgIsEqualToGetCurrentStateResult()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $arg = 'initial';
        $this->assertEquals($arg, $stateMachine->getCurrentState());
        $this->assertTrue($stateMachine->isCurrentState($arg));
    }

    public function testIsCurrentStateReturnFalseWhenItsArgIsDifferentToGetCurrentStateResult()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $arg = 'second';
        $this->assertNotEquals($arg, $stateMachine->getCurrentState());
        $this->assertFalse($stateMachine->isCurrentState($arg));
    }
    public function testCurrentStateIsInitialTestWhenStateMachineIsInitialState()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $stateMachine->initial('second');
        $this->assertTrue($stateMachine->isCurrentState('second'));
    }

    public function testSetToUnkownStateThrowsException()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
		$stateMachine->transition('initial', 'second');
        $stateMachine->setState('second');
        $this->expectException(Exception::class);
        $stateMachine->setState('third');
    }

    public function testGetCurrentStateWhenSetToKnownStateIsGood()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $stateMachine->addTransition(new Transition('initial', 'second'));
        $stateMachine->setState('second');
        $this->assertTrue($stateMachine->isCurrentState('second'));
    }

    public function testAddTransitionWithAnUnknownStateThrowsException()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $stateMachine->addTransition(new Transition('initial', 'second'));
        $this->expectException(Exception::class);
        $stateMachine->addTransition(new Transition('initial', 'third'));
    }

    public function testHasTransitionReturnFalseIfThereIsNoTransition()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $this->assertFalse($stateMachine->hasTransition('initial', 'second'));
    }

    public function testHasTransitionReturnFalseIfOneStateIsUnknown()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $stateMachine->addTransition(new Transition('initial', 'second'));
        $this->assertFalse($stateMachine->hasTransition('initial', 'third'));
    }

    public function testHasTransitionReturnFalseIfNotExists()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $stateMachine->addState('third');
        $stateMachine->addTransition(new Transition('initial', 'second'));
        $this->assertFalse($stateMachine->hasTransition('initial', 'third'));
    }

    public function testHasTransitionReturnTrueIfExists()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $stateMachine->addTransition(new Transition('initial', 'second'));
        $this->assertTrue($stateMachine->hasTransition('initial', 'second'));
    }

    public function testHasTransitionReturnTrueIfExistsWithSeveralTransitionFromOneState()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $stateMachine->addState('third');
        $transition1 = new Transition('initial', 'second');
        $stateMachine->addTransition($transition1);
        $transition2 = new Transition('initial', 'third');
        $stateMachine->addTransition($transition2);
        $this->assertTrue($stateMachine->hasTransition('initial', 'second'));
        $this->assertTrue($stateMachine->hasTransition('initial', 'third'));
    }

    public function testCurrentStateDoesNotChangeIfTransitionDoesNotExist()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
		$this->expectException(Exception::class);
        $stateMachine->setState('second');
    }

    public function testCurrentStateChangeIfTransitionHasNotGuardCondition()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $transition = new Transition('initial', 'second');
        $stateMachine->addTransition($transition);
        $stateMachine->setState('second');
        $this->assertTrue($stateMachine->isCurrentState('second'));
    }

    public function testCurrentStateChangeIfTransitionFulfillGuardCondition()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $transition = new Transition('initial', 'second');
        $transition->addGuardCondition(new GuardCondition(function (){return true;}));
        $stateMachine->addTransition($transition);
        $stateMachine->setState('second');
        $this->assertTrue($stateMachine->isCurrentState('second'));
    }

    public function testCurrentStateNotChangeIfTransitionNotFulfillGuardCondition()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $transition = new Transition('initial', 'second');
        $transition->addGuardCondition(new GuardCondition(function (){return false;}));
        $stateMachine->addTransition($transition);
        $stateMachine->setState('second');
        $this->assertTrue($stateMachine->isCurrentState('initial'));
    }

    public function testStateMachineExecuteCallableWhenEnterState()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $transition = new Transition('initial', 'second');
        $stateMachine->addTransition($transition);
        $stateMachine->onEnterState('second', function () {echo 'aze';});
        $stateMachine->onEnterState('second', function () {echo 'qsd';});
        $stateMachine->setState('second');
        $this->expectOutputString('azeqsd');
    }

    public function testStateMachineExecuteCallableWhenLeaveState()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $stateMachine->addState('second');
        $transition = new Transition('initial', 'second');
        $stateMachine->addTransition($transition);
        $stateMachine->onLeaveState('initial', function () {echo 'aze';});
        $stateMachine->onLeaveState('initial', function () {echo 'qsd';});
        $stateMachine->setState('second');
        $this->expectOutputString('azeqsd');
    }

	public function testStateMachineExecuteCallableWhenLeaveStateBeforeWhenEnterState()
	{
		$stateMachine = new StateMachine();
		$stateMachine->addState('initial');
		$stateMachine->addState('second');
		$transition = new Transition('initial', 'second');
		$stateMachine->addTransition($transition);
		$stateMachine->onLeaveState('initial', function () {echo 'aze';});
		$stateMachine->onEnterState('second', function () {echo 'qsd';});
		$stateMachine->setState('second');
		$this->expectOutputString('azeqsd');
	}

    public function testAutoTransitionWorks()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial');
        $transition = new Transition('initial', 'initial');
        $stateMachine->addTransition($transition);
        $stateMachine->onEnterState('initial', function () {echo 'aze';});
        $stateMachine->onLeaveState('initial', function () {echo 'qsd';});
        $stateMachine->setState('initial');
        $this->expectOutputString('qsdaze');
    }
    public function testAddStateIsFluent()
    {
        $stateMachine = new StateMachine();
        $stateMachine->addState('initial')
            ->addState('second');
        $this->expectNotToPerformAssertions();
    }

    public function testSetStateIsFluent()
    {
		$stateMachine  = (new StateMachine())
			->addState('initial')
			->addState('second');
		$stateMachine->transition('initial', 'second');
		$stateMachine->setState('second')
			->addState('third');

        $this->expectNotToPerformAssertions();
    }
}
