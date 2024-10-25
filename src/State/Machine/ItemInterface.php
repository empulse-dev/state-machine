<?php

namespace Empulse\State\Machine;

/**
 * ItemInterface
 *
 */
interface ItemInterface
{
    /**
     * Summary of setData
     * @param mixed $data
     * @return \Empulse\State\Machine\ItemInterface
     */
    public function setData($data):self;

    /**
     * Summary of getState
     * @return mixed
     */
    public function getState():mixed;

    /**
     * Summary of setState
     * @return \Empulse\State\Machine\ItemInterface
     */
    public function setState($state):self;

    /**
     * Summary of __get
     * @param mixed $attr
     * @return mixed
     */

    static public function getBitMap():array;
}