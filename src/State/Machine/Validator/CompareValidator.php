<?php

namespace Empulse\State\Machine\Validator;

use Empulse\State\Machine\ItemInterface;

/**
 * Compare Validator
 */
class CompareValidator extends AbstractValidator
{
    protected static function _validate(ItemInterface $item, $config): bool
    {
        $op1 = $item->get($config['op1']);
        $op2 = $item->get($config['op2']);

        switch($config['type']) {
            case 'eq':
                return $op1 === $op2;
            case 'neq':
                return $op1 !== $op2;
            case 'gt':
                return $op1 > $op2;
            case 'gte':
                return $op1 >= $op2;
            case 'lt':
                return $op1 < $op2;
            case 'lte':
                return $op1 <= $op2;
            default:
                throw new \Exception(
                    sprintf(
                        "invalid compare type: %s on %s and %s", 
                        $config['type'],
                        $op1,
                        $op2
                    )
                );
        }
    }
}
