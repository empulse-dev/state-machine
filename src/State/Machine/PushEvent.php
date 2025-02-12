<?php

namespace Empulse\State\Machine;

use Symfony\Contracts\EventDispatcher\Event;

class PushEvent extends Event {

    public function __construct(
        protected string $from,
        protected string $to,
        protected array $parameters = []
    ) {
    }
}