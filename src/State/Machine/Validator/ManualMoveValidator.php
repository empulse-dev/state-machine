<?php

namespace Empulse\State\Machine\Validator;

use Empulse\State\Machine\ItemInterface;

/**
 * Manual Validator for demo purposes
 */
class ManualMoveValidator extends AbstractValidator
{

    protected static function _validate(ItemInterface $item, $config):mixed
    {
        return (bool)array_pop($config);
    }
}
