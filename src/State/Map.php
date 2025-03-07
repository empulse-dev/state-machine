<?php

namespace Empulse\State;

/**
 * Workflow State Map
 *
 */
class Map
{

    
    const MAP_FROM = 'from';
    const MAP_TO = 'to';
    const MAP_CODE = 'code';
    const MAP_STATES = 'states';
    const MAP_TRANSITIONS = 'transitions';

    const MAP_VALIDATORS = 'validators';
    const STATUS_INITIAL = 'initial';
    const STATUS_MIDDLE = 'middle';
    const STATUS_FINAL = 'final';
    const VALID_FIRST = 'first_one_of_this';
    const VALID_ALL = 'all_valids_of_this';

    const VALIDATORS = 'validators';

    const VALIDATOR = 'v';

    const VALIDATOR_CONFIG = 'vc';

    const PROPERTIES = 'properties';

    const STOP_AFTER_APPLY = 'stop_after_apply';

    const DENIAL_CHAR = '!';

    const MAP_FLAGS = 'flags';

    const MAP_FLAGS_ACTIVE = 'active';

    const MAP_FLAGS_DISABLE = 'disable';

    const MAP_ELSE = 'else';

    protected $_map = [];

    /**
     * Set map
     * @param array $map
     */
    public function setMap($map){
        $this->_map = $map;
    }
    /**
     * Get map
     *
     * @return array
     */
    public function getMap(){
        return $this->_map;
    }

    private function getData($section){
        return $this->_map[$section];
    }
    public function getStates(){
        return $this->getData(self::MAP_STATES);
    }

    public function getTransitions(){
        return $this->getData(self::MAP_TRANSITIONS);
    }
}
