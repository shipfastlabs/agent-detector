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

        $agentsWithEnvVars = [
            'cursor' => ['CURSOR_AGENT'],
            'gemini' => ['GEMINI_CLI'],
            'codex' => ['CODEX_SANDBOX', 'CODEX_THREAD_ID'],
            'augment-cli' => ['AUGMENT_AGENT'],
            'opencode' => ['OPENCODE_CLIENT', 'OPENCODE'],
            'amp' => ['AMP_CURRENT_THREAD_ID'],
            'claude' => ['CLAUDECODE', 'CLAUDE_CODE'],
            'replit' => ['REPL_ID'],
            'copilot' => ['COPILOT_CLI'],
            'antigravity' => ['ANTIGRAVITY_AGENT'],
            'pi' => ['PI_CODING_AGENT'],
        ];

        foreach ($agentsWithEnvVars as $agent => $envVars) {
            foreach ($envVars as $envVar) {
                if (getenv($envVar) !== false) {
                    $sessionId = match ($agent) {
                        'codex'  => (getenv('CODEX_THREAD_ID') ?: null),
                        'amp'    => (getenv('AMP_CURRENT_THREAD_ID') ?: null),
                        'claude' => (getenv('CLAUDE_CODE_SESSION_ID') ?: null),
                        default  => null,
                    };

                    return new AgentResult(true, $agent, $sessionId);
                }
            }
        }

        if (file_exists('/opt/.devin')) {
            return new AgentResult(true, 'devin');
        }

        return new AgentResult(false);
    }
}
