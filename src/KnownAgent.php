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
    case Kiro = 'kiro';
    case KiroCli = 'kiro-cli';
}
