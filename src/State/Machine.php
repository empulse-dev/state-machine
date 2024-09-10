<?php

namespace Empulse\State;

use Empulse\Exception\MapException;
use Empulse\State\Machine\Item;

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

    protected $code;

    public function getCode(){
        if(empty($this->code)){
            return self::class;
        } else {
            return $this->code;
        }
    }

    public function setCode($code){
        $this->code = $code;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(Item $item, array $map)
    {
        $this->item = $item;
        $this->transitions = $map[Map::MAP_TRANSITIONS];
        $this->states = $map[Map::MAP_STATES];

        //@todo validate item has interface implemented
        $initialState = $this->item->getItemState();
        

        if (null === $initialState) {
            $initialState = $this->findInitialState();
            $this->item->setItemState($initialState);
            // @todo set dispatcher
            // $this->dispatcher->dispatch(FiniteEvents::SET_INITIAL_STATE, new StateMachineEvent($this));
        }
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
        if ($this->item->getItemState()) {
            $transitions = $this->transitions;
            //$parameters = $this->getParametersForCurrentTransition();
            $parameters = [];
            foreach ($transitions as $transitionCode => $transition) {
                if ($this->_can($transition, $parameters)) {
                    $this->_apply($transition[Map::MAP_TO], $parameters);
                    $properties = $transition['properties'] ?? [];

                    if (!isset($properties['stop_after_apply'])
                        //&& !isset($properties[Transition::TRANSITION_TYPE_COMPLEMENT])
                    ) {
                        $this->movemovemove();
                    }

                    break;
                }
            }
        }

        return $this;
    }

    protected function _can($transition, $parameters){
        if(in_array($this->item->getItemState(), $transition[Map::MAP_FROM])){
            if (!isset($transition[Map::VALIDATORS]) || empty($transition[Map::VALIDATORS])) {
                throw new MapException('Every transition needs at least one validator');
            }

            return $this->_evaluateValidators($transition[Map::VALIDATORS], $parameters);
        } 
        
        return false;
    }

    protected function _evaluateValidators($validators, $parameters, $type = Map::VALID_ALL){
        foreach ($validators as $key => $validator) {
            $denied = false;
            $isInt = is_int($key);
            if($isInt && isset($validator[0])){
                if($denied = $validator[0] === Map::DENIAL_CHAR){
                    $validator = str_replace(Map::DENIAL_CHAR,'', $validator);
                }
            }
        
            /**
             * @todo replace validator::validate for a service when symfony came up
             * $this->container->get($validator)->validate($object, $parameters)
             */
            $result = $isInt
                ? call_user_func([$validator, 'validate'], $this->item)
                : $this->_evaluateValidators($validator, $parameters, $key);
        
            if($denied){
                $result = !$result;
            }
        
            if (Map::VALID_FIRST === $type && $result) {
                return true;
            }
        
            if (Map::VALID_ALL === $type && !$result) {
                return false;
            }
        }
        
        return (Map::VALID_ALL === $type);
    }

    protected function _apply($transitionTo, $parameters = null){
        $this->item->setItemState($transitionTo);
    }
}
