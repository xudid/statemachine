<?php

namespace Xudid\StateMachine;

class Transition
{
    private string $sourceState;
    private string $targetState;
    private GuardCondition $guardTransition;

    public function __construct(string $sourceState, string $targetState)
    {
        $this->sourceState = $sourceState;
        $this->targetState = $targetState;
    }

    public function getSourceState(): string
    {
        return $this->sourceState;
    }

    public function getTargetState():string
    {
        return $this->targetState;
    }

    public function check(): bool
    {
        if (!isset($this->guardTransition)) {
            return true;
        }

        return $this->guardTransition->evaluate();
    }

    public function addGuardCondition(GuardCondition $condition)
    {
        $this->guardTransition = $condition;
    }
}