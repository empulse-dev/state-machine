<?php

namespace Empulse\StateMachine\Event;

use Symfony\Contracts\EventDispatcher\Event;

class TransitionItemEvent extends Event
{
    public function __construct(
        private object $subject
    ) {}

    public function getSubject(): object
    {
        return $this->subject;
    }
} 