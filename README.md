# Statemachine
A base state machine library

***Create and initialize states***

    $stateMachine = new StateMachine();
    
    $stateMachine->addState('initial');
    
    $stateMachine->addState('final');

***Add a transition*** 

    $stateMachine->transition('initial', 'final');

***Guard a transition with logic***

    $stateMachine->transition('initial', 'final')
        ->guard(function(){
            return ....;
        });
