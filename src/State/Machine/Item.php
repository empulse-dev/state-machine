<?php

namespace Empulse\State\Machine;

/**
 * Item
 *
 */
trait Item
{

    private ?string $state = null;

    private int $flags = 0;

    protected $_data = [];

    protected $reparseAfterChange = true;

    protected $parsedFlags;

    /**
     * Summary of setData
     * 
     * @param mixed $data
     * @return mixed
     */
    public function setData($data): self{
        $this->_data = array_merge(
            $data,
            $this->_data
        );

        return $this;
    }

    /**
     * get current state
     * 
     * @return ?string
     */
    public function getState(): ?string{
        return $this->state;
    }

    /**
     * set current state
     * 
     * @return self
     */
    public function setState(?string $state): self{
        $this->state = $state;

        return $this;
    }

    /**
     * getter magic method
     * 
     * @param mixed $attr
     * @return mixed
     */
    public function get($attr):mixed
    {
        if (array_key_exists($attr, $this->_data)) {
            return $this->_data[$attr];
        }

        return null;
    }

    public function getFlags():int{
        return $this->flags;
    }

    public function setFlag($flag):self{
        $this->flags = $this->flags | static::getBitValue([$flag]);
        $this->reparseFlags();

        return $this;
    }
    
    public function removeFlag($flag)
    {
        $this->flags = $this->flags & ~static::getBitValue([$flag]);
        $this->reparseFlags();

        return $this;
    }

    public function getFlag($flag)
    {
        $position = static::getFlagPosition($flag);

        return $position === false ? false : ($this->flags & (1 << $position));
    }

    abstract static function getBitMap():array;

    public static function getBitValue(array $flags)
    {
        $value = 0;
        foreach ($flags as $flag) {
            $position = static::getFlagPosition($flag);
            if ($position === false) {
                throw new \Exception(sprintf('unknown flag %s', $flag));
            }
            $value |= pow(2, $position);
        }

        return $value;
    }

    public static function getFlagPosition($flag)
    {
        $position = array_search($flag, static::getBitMap());

        return $position === false ? false : $position;
    }

    public function parseFlags()
    {
        if (!$this->parsedFlags) {
            foreach ($this->getBitMap() as $position => $flag) {
                $this->parsedFlags['flag.'.$flag] = $this->getFlag($flag);
            }
        }

        return $this->parsedFlags;
    }

    public function reparseFlags()
    {
        if ($this->reparseAfterChange) {
            $this->parsedFlags = null;
            $this->parseFlags();
        }
    }
}