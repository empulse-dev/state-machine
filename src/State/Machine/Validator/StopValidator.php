<?php

namespace Empulse\State\Machine\Validator;

use Empulse\State\Machine\ItemInterface;

/**
 * Bool Validator
 */
class StopValidator extends AbstractValidator
{

    protected static function _validate(ItemInterface $item, $field, $value):bool
    {
        return false;
    }
}
