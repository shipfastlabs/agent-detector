<?php

declare(strict_types=1);

namespace Laravel\AgentDetector;

final readonly class AgentResult
{
    public function __construct(
        public bool $isAgent,
        public ?string $name = null,
    ) {}

    public function knownAgent(): ?KnownAgent
    {
        if ($this->name === null) {
            return null;
        }

        return KnownAgent::tryFrom($this->name);
    }
}
