<?php

namespace Empulse\State\Machine\Validator;

use Empulse\State\Machine\ItemInterface;

/**
 * Bool Validator
 */
class BoolValidator
{

    protected static function _validate(ItemInterface $item, $config):bool
    {
        return $item->get($field) === $value;
    }
}
