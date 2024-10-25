<?php

namespace Empulse\State\Machine\Validator;

use Empulse\State\Machine\ItemInterface;

/**
 * Empty Validator
 */
class NotEmptyValidator extends AbstractValidator
{

    protected static function _validate(ItemInterface $item, $config):mixed
    {
        $result = true;
        foreach($config as $key => $f){
            $result &= !empty($item->get($f));
        }
        return $result;
    }
}
