<?php

namespace Xudid\StateMachine;

use Exception;

class StateMachine
{
    private string $initialState = '';
    private string $currentState = '';
    private array $states = [];
    private array $transitions = [];

    /**
     * @throws Exception
     */
    public function getCurrentState(): string
    {
        $this->testInitialization();
        return $this->currentState;
    }

    public function isCurrentState($state): bool
    {
        return $this->currentState === $state;
    }

    public function addState(string $state)
    {
        $this->states[] = $state;
        if ($this->initialState && $this->currentState) {
            return $this;
        }

        $this->initialState = $state;
        $this->currentState = $state;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function initial(string $state)
    {
        $this->testInitialization();
        if (!$this->hasState($state)) {
            throw new Exception('Initial state must be added first');
        }

        $this->initialState = $state;
        $this->currentState = $state;

    }

    private function testInitialization(): void
    {
        if (!$this->currentState) {
            throw new Exception('State machine has no state');
        }
    }

    public function isInitialState(string $state):bool
    {
        return $this->initialState === $state;
    }

    public function setState(string $state)
    {
        if (!$this->hasState($state)) {
            throw new Exception('State machine has no state');
        }

        if (!$this->hasTransition($this->currentState, $state)) {
            return $this;
        }

        $possibleTransitions = $this->getStateTransitions($this->currentState);
        foreach ($possibleTransitions as $possibleTransition) {
            if ($possibleTransition->getTargetState() === $state) {
                $transition = $possibleTransition;
                break;
            }
        }

        if ($transition->check()) {
            $this->currentState = $state;
        }

        return $this;
    }

    public function addTransition(Transition $transition)
    {
        if (!$this->hasState($transition->getSourceState()) || !$this->hasState($transition->getTargetState())) {
            throw new Exception('Before add Transition ensure that source state and target state exist');
        }

        $this->transitions[$transition->getSourceState()][] = $transition;
    }

    public function hasState($state)
    {
        return in_array($state, $this->states);
    }

    public function hasTransition(string $sourceState, string $targetState): bool
    {
        if (!$this->hasState($sourceState) || !$this->hasState($targetState)) {
            return false;
        }

        if (empty($this->transitions)) {
            return false;
        }

        if (!$this->hasTransitionsFromState($sourceState)) {
            return false;
        }

        $possibleTransitions = $this->getStateTransitions($sourceState);
        foreach ($possibleTransitions as $possibleTransition) {
            if ($possibleTransition->getTargetState() === $targetState) {
                return true;
            }
        }

       return false;
    }


    public function hasTransitionsFromState(string $state): bool
    {
        return $this->getStateTransitions($state) !== null;
    }

    public function getStateTransitions(string $state): mixed
    {
        return $this->transitions[$state];
    }
}
