<?php

namespace Empulse\State\Machine;

/**
 * Item
 *
 */
class Item
{

    CONST STATE_FIELD = 'emp_state';

    protected $_data = [];

    public function __construct($data){
        $this->_data = $data;
    }

    /**
     * get current state
     * 
     * @return string|null
     */
    public function getItemState(): string|null{
        if(!isset($this->_data[self::STATE_FIELD])){
            return null;
        }
        
        return $this->_data[self::STATE_FIELD];
    }

    /**
     * Set the state of the entity
     *
     * @param string $value
     *
     * @throws \Exception
     */
    public function setItemState(string $state): self{
        $this->_data[self::STATE_FIELD] = $state;

        return $this;
    }

    public function __get($attr)
    {
        if (array_key_exists($attr, $this->_data)) {
            return $this->_data[$attr];
        }

        return null;
    }
}