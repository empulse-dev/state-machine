<?php

namespace Empulse\State;

use Empulse\Exception\Map\LoopDetectedException;
use Empulse\Exception\MapException;
use Empulse\State\Machine\Item;
use Empulse\State\Machine\ItemInterface;
use Empulse\State\Machine\Tracker;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Empulse\StateMachine\Event\TransitionItemEvent;
class Machine {

    /**
     * The item
     *
     * @var Item
     */
    protected $item;

    /**
     * The states.
     *
     * @var array
     */
    protected $states = [];

    /**
     * The transitions.
     *
     * @var array
     */
    protected $transitions = [];

    protected $appliedTransitions = [];

    protected $code;

    protected $trackerCollection = [];

    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public function getCode(){
        return (empty($this->code))
            ? self::class
            : $this->code;
    }

    public function setCode($code){
        $this->code = $code;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ItemInterface $item, array $map):self
    {
        $this->item = $item;
        $this->transitions = $map[Map::MAP_TRANSITIONS];
        $this->states = $map[Map::MAP_STATES];
        $this->appliedTransitions = [];

        //@todo validate item has interface implemented
        $initialState = $this->item->getState();

        if (null === $initialState) {
            $initialState = $this->findInitialState();
            $this->item->setState($initialState);
        }

        return $this;
    }

    public function findInitialState(){
        return $this->states[Map::STATUS_INITIAL];
    }

    public function push(){
        $this
            ->initCustomTransitions()
            ->movemovemove()
        ;
    }

    protected function initCustomTransitions(){
        /** 
         * @todo add custom events for prioritized transitions
         * */
        return $this;
    }

    protected function movemovemove(){
        // Validate if has current state
        if ($this->item->getState()) {
            $transitions = $this->transitions;
            //$parameters = $this->getParametersForCurrentTransition();
            $parameters = [];
            
            $transitionItem = new TransitionItemEvent($this->item);

            foreach ($transitions as $transitionCode => $transition) {
                $this->eventDispatcher->dispatch($transitionItem, 'before_transition');
                $this->eventDispatcher->dispatch($transitionItem, 'before_transition.'.$transitionCode);
                if ($this->_can($transition, $parameters)) {
                    $this->_apply($transition[Map::MAP_TO], $parameters);

                    if(isset($transition[Map::MAP_FLAGS])){
                        $this->applyFlags($transition[Map::MAP_FLAGS], $this->item);
                    }

                    $this->eventDispatcher->dispatch($transitionItem, 'after_transition.'.$transitionCode);
                    $this->eventDispatcher->dispatch($transitionItem, 'after_transition');

                    $continue = true;

                    if(isset($transition[Map::PROPERTIES])){
                        if(in_array(Map::STOP_AFTER_APPLY, $transition[Map::PROPERTIES])){
                            $continue = false;
                        }    
                    }
                    
                    if ($continue) {
                        $this->movemovemove();
                    }

                    break;
                }
            }
        }

        return $this;
    }

    protected function _can($transition, $parameters){
        $result = false;
        if(in_array($this->item->getState(), $transition[Map::MAP_FROM])){
            if (!isset($transition[Map::VALIDATORS]) || empty($transition[Map::VALIDATORS])) {
                throw new MapException('Every transition needs at least one validator');
            }

            $result = $this->_evaluateValidators($transition[Map::VALIDATORS], $parameters);
            // var_dump('from: '.$transition['from'][0]. ' to: '.$transition['to'] . ' res: '.(int)$result);
        }
        
        return $result;
    }

    protected function applyFlags($flagsConfig, ItemInterface $item){
        foreach($flagsConfig as $group => $flags){
            switch($group){
                case Map::MAP_FLAGS_ACTIVE:
                    foreach($flags as $flag){
                        $item->setFlag($flag);
                    }
                    break;
                case Map::MAP_FLAGS_DISABLE:
                    foreach($flags as $flag){
                        $item->removeFlag($flag);
                    }
                    break;
            }
        }
    }

    static function getGroups():array{
        return [
            Map::VALID_ALL,
            Map::VALID_FIRST
        ];
    }

    protected function _evaluateValidators($validators, $parameters, $type = Map::VALID_ALL){
        foreach ($validators as $key => $validator) {
            $result = (in_array($key, self::getGroups()))
                ? $this->_evaluateValidators($validator, $parameters, $key)
                : $this->_executeValidation(
                    $validator[Map::VALIDATOR],
                    $validator[Map::VALIDATOR_CONFIG],
                    $this->item
                );
            // var_dump($validator, $result);
            if (Map::VALID_FIRST === $type && $result) {
                return true;
            }
        
            if (Map::VALID_ALL === $type && !$result) {
                return false;
            }
        }
        
        return (Map::VALID_ALL === $type);
    }

    protected function _executeValidation($validator, $config, $item):bool{
        $result = call_user_func(
                [$validator, 'validate'], 
                $this->item, 
                $config
        );

        $this->trackerCollection[] = new Tracker($validator, $config, $result);

        return $result;
    }

    protected function _apply($transitionTo, $parameters = null): void{
        $previousState = $this->item->getState();
        $this->item->setState($transitionTo);
        $fromTo = $previousState.'-'.$transitionTo;
        if(!isset($this->appliedTransitions[$fromTo])){
            $this->appliedTransitions[$fromTo] = 1;
        } else {
            $this->appliedTransitions[$fromTo]+= 1;
            
            if($this->appliedTransitions[$fromTo] > 1){
                throw new LoopDetectedException('Loop detected in transition: '.$fromTo);
            }    
        }
    }

    public function getTrackerCollection(){
        return $this->trackerCollection;
    }
}
