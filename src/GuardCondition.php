<?php

namespace Xudid\StateMachine;

class GuardCondition
{
    private $evaluationFunction;

    public function __construct(Callable $evaluationFunction)
    {
        $this->evaluationFunction = $evaluationFunction;
    }

    public function evaluate(...$args)
    {
        return call_user_func($this->evaluationFunction, ...$args);
    }
}