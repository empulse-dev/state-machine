<?php

namespace Empulse\State\Machine\Validator;

use Empulse\State\Machine\ItemInterface;

/**
 * Abstract Validator
 */
abstract class AbstractValidator
{
    const VALIDATION_TYPE_BOOL = 'bool';
    const VALIDATION_TYPE_NUMERIC = 'numeric';
    const VALIDATION_TYPE_STRING = 'string';
    const VALIDATION_TYPE_EMPTY = 'empty';

    /**
     * @param \Empulse\State\Machine\Item $item
     * @param mixed $args
     * @return bool
     */
    public static function validate(ItemInterface $item, $config)
    {
        return static::_validate($item, $config);
    }

    /**
     * @param \Empulse\State\Machine\Item $item
     *
     * @return bool
     * @throws \Exception
     */
    protected static function _validate(ItemInterface $item, $config):mixed
    {
        throw new \Exception(__METHOD__.' Not implemented');
    }

    protected function _saveHistory($orderItem, $history)
    {
        
    }
}
