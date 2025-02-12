<?php

namespace Empulse\State\Machine\Validator;

use Empulse\State\Machine\ItemInterface;

/**
 * Regex Validator
 * Validates fields against regular expressions
 */
class RegexValidator extends AbstractValidator
{
    protected static function _validate(ItemInterface $item, $config): mixed
    {
        $result = true;
        foreach ($config as $field => $pattern) {
            $value = $item->get($field);
            if (!preg_match($pattern, $value)) {
                $result = false;
                break;
            }
        }
        return $result;
    }
} 