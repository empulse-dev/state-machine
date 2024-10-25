<?php

namespace Empulse\State\Machine\Validator;

use Empulse\State\Machine\ItemInterface;

/**
 * Flag On Validator
 */
class FlagOnValidator extends AbstractValidator
{

    protected static function _validate(ItemInterface $item, $config):mixed
    {
        $flag = array_pop($config);
        return (bool)$item->getFlag($flag) === true;
    }
}
