<?php

declare(strict_types=1);

namespace Laravel\AgentDetector;

class AgentResult
{
    public function __construct(
        public readonly bool $isAgent,
        public readonly ?string $name = null,
    ) {
        //
    }

    public static function forAgent(string $name): self
    {
        return new self(true, $name);
    }

    public static function noAgent(): self
    {
        return new self(false);
    }

    public function knownAgent(): ?KnownAgent
    {
        if ($this->name === null) {
            return null;
        }

        return KnownAgent::tryFrom($this->name);
    }
}
