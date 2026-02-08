<?php

declare(strict_types=1);

namespace AgentDetector;

final class AgentDetector
{
    public static function detect(): AgentResult
    {
        $aiAgent = getenv('AI_AGENT');
        if (is_string($aiAgent) && trim($aiAgent) !== '') {
            return new AgentResult(true, trim($aiAgent));
        }

        if (getenv('CURSOR_TRACE_ID') !== false) {
            return new AgentResult(true, 'cursor');
        }

        if (getenv('CURSOR_AGENT') !== false) {
            return new AgentResult(true, 'cursor-cli');
        }

        if (getenv('GEMINI_CLI') !== false) {
            return new AgentResult(true, 'gemini');
        }

        if (getenv('CODEX_SANDBOX') !== false) {
            return new AgentResult(true, 'codex');
        }

        if (getenv('AUGMENT_AGENT') !== false) {
            return new AgentResult(true, 'augment-cli');
        }

        if (getenv('OPENCODE_CLIENT') !== false) {
            return new AgentResult(true, 'opencode');
        }

        if (getenv('CLAUDECODE') !== false || getenv('CLAUDE_CODE') !== false) {
            return new AgentResult(true, 'claude');
        }

        if (getenv('REPL_ID') !== false) {
            return new AgentResult(true, 'replit');
        }

        if (file_exists('/opt/.devin')) {
            return new AgentResult(true, 'devin');
        }

        return new AgentResult(false);
    }
}
