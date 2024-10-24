<?php

namespace Empulse\State\Machine;

/**
 * Item
 *
 */
trait Item
{

    public $state = null;

    protected $_data = [];

    /**
     * Summary of setData
     * 
     * @param mixed $data
     * @return mixed
     */
    public function setData($data): self{
        $this->_data = $data;

        return $this;
    }

    /**
     * get current state
     * 
     * @return mixed
     */
    public function getState(): mixed{
        return $this->state;
    }

    /**
     * set current state
     * 
     * @return int|null
     */
    public function setState($state): self{
        $this->state = $state;

        return $this;
    }

    /**
     * getter magic method
     * 
     * @param mixed $attr
     * @return mixed
     */
    public function __get($attr):mixed
    {
        if (array_key_exists($attr, $this->_data)) {
            return $this->_data[$attr];
        }

        return null;
    }
}