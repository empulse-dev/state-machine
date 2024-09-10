<?php

namespace Empulse\State\Machine\Queue;

/**
 * Queue Item
 */
class Item
{

    /** @var string */
    protected $flux;

    /** @var array|callable */
    protected $items;

    /**
     * Item constructor.
     *
     * @param string         $flux
     * @param array|callable $items
     */
    public function __construct($flux, $items)
    {
        $this->flux = $flux;
        $this->items = $items;
    }

    /**
     * Get flux
     *
     * @return string
     */
    public function getFlux()
    {
        return $this->flux;
    }

    /**
     * Get items
     *
     * @return array|callable
     */
    public function getItems()
    {
        return $this->items;
    }
}
