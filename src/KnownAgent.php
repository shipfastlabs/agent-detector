<?php

declare(strict_types=1);

namespace AgentDetector;

enum KnownAgent: string
{
    case Cursor = 'cursor';
    case Claude = 'claude';
    case Devin = 'devin';
    case Replit = 'replit';
    case Gemini = 'gemini';
    case Codex = 'codex';
    case AugmentCli = 'augment-cli';
    case Opencode = 'opencode';
    case Amp = 'amp';
    case Copilot = 'copilot';
    case Antigravity = 'antigravity';
    case Pi = 'pi';

    public function displayName(): string
    {
        return match ($this) {
            self::Cursor      => 'Cursor',
            self::Claude      => 'Claude Code',
            self::Devin       => 'Devin',
            self::Replit      => 'Replit',
            self::Gemini      => 'Gemini CLI',
            self::Codex       => 'Codex',
            self::AugmentCli  => 'Augment CLI',
            self::Opencode    => 'OpenCode',
            self::Amp         => 'Amp',
            self::Copilot     => 'GitHub Copilot',
            self::Antigravity => 'Antigravity',
            self::Pi          => 'Pi',
        };
    }
}
