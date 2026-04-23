<?php

declare(strict_types=1);

namespace AgentDetector;

use JsonSerializable;

final readonly class AgentResult implements JsonSerializable
{
    public function __construct(
        public bool $isAgent,
        public ?string $name = null,
        public ?string $sessionId = null,
    ) {
    }

    public function knownAgent(): ?KnownAgent
    {
        if ($this->name === null) {
            return null;
        }

        return KnownAgent::tryFrom($this->name);
    }

    /**
     * @return array{isAgent: bool, name: string|null}
     */
    public function jsonSerialize(): array
    {
        return [
            'isAgent' => $this->isAgent,
            'name' => $this->name,
        ];
    }

    /**
     * @return array{isAgent: bool, name: string|null, sessionId: string|null}
     */
    public function __serialize(): array
    {
        return [
            'isAgent' => $this->isAgent,
            'name' => $this->name,
            'sessionId' => $this->sessionId,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function __unserialize(array $data): void
    {
        $name = $data['name'] ?? null;
        $sessionId = $data['sessionId'] ?? null;

        $this->isAgent = (bool) ($data['isAgent'] ?? false);
        $this->name = is_string($name) ? $name : null;
        $this->sessionId = is_string($sessionId) ? $sessionId : null;
    }
}
