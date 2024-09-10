<?php

namespace Empulse\State\Machine\Validator;

/**
 * Abstract Validator
 */
abstract class AbstractValidator
{

    /**
     * @param \Empulse\State\Machine\Item $item
     * @param mixed $args
     * @return bool
     */
    public static function validate($item, $args = null)
    {
        return static::_validate($item, $args);
    }

    /**
     * @param \Empulse\State\Machine\Item $item
     *
     * @return bool
     * @throws \Exception
     */
    protected static function _validate($object, $args)
    {
        throw new \Exception(__METHOD__.' Not implemented');
    }

    protected function _saveHistory($orderItem, $history)
    {
        //$asset = $object->getAssetValidator()->saveAsset(ShipConfirmType::class);
        //$history->setFkGaiaTransitionAssetShipConfirm($asset);
    }
}
