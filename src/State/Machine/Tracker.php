<?php

namespace Empulse\State\Machine;
use Empulse\State\Machine\ItemInterface;
use Empulse\State\Machine\Validator\AbstractValidator;

class Tracker
{

    protected $validator = null;

    protected $result = null;

    protected $config = null;

    public function __construct($validator, $config, bool $result){
        $this->validator = $validator;
        $this->config = $config;
        $this->result = $result;
    }

    public function __tostring():string{
        return sprintf(
            '%s => %s', 
            is_string($this->validator) 
                ? $this->validator 
                : get_class($this->validator), 
            (bool) $this->result ? 'true' : 'false');
    }

    public function getValidator():string{
        return $this->validator;
    }

    public function getResult():bool{
        return $this->result;
    }
}
