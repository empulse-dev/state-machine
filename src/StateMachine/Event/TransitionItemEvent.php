<?php

namespace Empulse\StateMachine\Event;

use Symfony\Contracts\EventDispatcher\Event;

class TransitionItemEvent extends Event
{
    public function __construct(
        private object $subject,
        private string $transitionCode,
        private array $parameters = []
    ) {}

    public function getSubject(): object
    {
        return $this->subject;
    }

    public function getTransitionCode(): string
    {
        return $this->transitionCode;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
} 
