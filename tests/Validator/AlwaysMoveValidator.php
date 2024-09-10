<?php

namespace Empulse\Tests\Validator;

use Empulse\State\Machine\Validator\AbstractValidator;

class AlwaysMoveValidator extends AbstractValidator{
    protected static function _validate($object, $args)
    {
        return true;
    }
}