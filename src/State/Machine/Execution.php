<?php

namespace Empulse\State\Machine;

class Execution
{
    protected $key;
    protected $init;
    protected $end;
    protected $elapsed;
    protected $flux;
    protected $trackerCollection = [];

    protected $itemStart = null;
    protected $itemEnd = null;

    public function __construct($fluxCode, ItemInterface $item) {
        $this->init = time();
        $this->flux = $fluxCode;
        $this->key = sprintf('%s-%s-%d', $fluxCode, $item->getTraceableId(), $this->init);
        $this->itemStart = clone $item;
    }

    public function finalize(array $trackerCollection, ItemInterface $item) {
        $this->end = time();
        $this->elapsed = $this->end - $this->init;
        $this->trackerCollection = $trackerCollection;
        $this->itemEnd = clone $item;
    }

    public function getKey():string{
        return $this->key;
    }

    public function getTrackerCollection():array{
        return $this->trackerCollection;
    }
}
