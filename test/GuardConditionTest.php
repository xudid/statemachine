<?php


use Xudid\StateMachine\GuardCondition;
use PHPUnit\Framework\TestCase;

class GuardConditionTest extends TestCase
{
    public function testEvaluateReturnEvaluationFunctionResult()
    {
        $evaluationFunction = function () {return true;};
        $expectedResult = $evaluationFunction();
        $guardCondition = new GuardCondition($evaluationFunction);
        $this->assertEquals($expectedResult, $guardCondition->evaluate());

        $evaluationFunction = function () {return false;};
        $expectedResult = $evaluationFunction();
        $guardCondition = new GuardCondition($evaluationFunction);
        $this->assertEquals($expectedResult, $guardCondition->evaluate());

        $evaluationFunction = function ($a, $b) {return $a & $b;};
        $expectedResult = $evaluationFunction(true, true);
        $guardCondition = new GuardCondition($evaluationFunction);
        $this->assertEquals($expectedResult, $guardCondition->evaluate(true, true));
    }
}
