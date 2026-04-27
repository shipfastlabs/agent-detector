<?php

declare(strict_types=1);

namespace Laravel\AgentDetector;

class AgentResult
{
    public readonly bool $isAgent;

    public function __construct(public readonly ?string $name = null)
    {
        $this->isAgent = $name !== null;
    }

    public static function forAgent(KnownAgent|string $name): self
    {
        return new self($name instanceof KnownAgent ? $name->value : $name);
    }

    public static function noAgent(): self
    {
        return new self();
    }

    public function knownAgent(): ?KnownAgent
    {
        return KnownAgent::tryFrom($this->name ?? '');
    }
}
