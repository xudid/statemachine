<?php


use Xudid\StateMachine\GuardCondition;
use Xudid\StateMachine\Transition;
use PHPUnit\Framework\TestCase;

class TransitionTest extends TestCase
{
    public function testTransitionCheckWithNoGuardConditionReturnTrue()
    {
        $transition = new Transition('initial', 'second');
        $this->assertTrue($transition->check());
    }

    public function testTransitionCheckWithGuardConditionReturnGuardConditionResult()
    {
        $transition = new Transition('initial', 'second');
        $condition = new GuardCondition(function(){return true;});
        $transition->addGuardCondition($condition);
        $this->assertTrue($transition->check());

        $transition = new Transition('initial', 'second');
        $condition = new GuardCondition(function(){return false;});
        $transition->addGuardCondition($condition);
        $this->assertFalse($transition->check());
    }
}
