<?php

declare(strict_types=1);

namespace Laravel\AgentDetector;

class AgentDetector
{
    protected const AGENT_ENV_VARS = [
        'cursor' => ['CURSOR_AGENT'],
        'gemini' => ['GEMINI_CLI'],
        'codex' => ['CODEX_SANDBOX', 'CODEX_CI', 'CODEX_THREAD_ID'],
        'augment-cli' => ['AUGMENT_AGENT'],
        'opencode' => ['OPENCODE_CLIENT', 'OPENCODE'],
        'amp' => ['AMP_CURRENT_THREAD_ID'],
        'claude' => ['CLAUDECODE', 'CLAUDE_CODE'],
        'replit' => ['REPL_ID'],
        'copilot' => ['COPILOT_MODEL', 'COPILOT_ALLOW_ALL', 'COPILOT_GITHUB_TOKEN', 'COPILOT_CLI'],
        'antigravity' => ['ANTIGRAVITY_AGENT'],
        'pi' => ['PI_CODING_AGENT'],
        'kiro-cli' => ['KIRO_AGENT_PATH'],
    ];

    public static function detect(): AgentResult
    {
        return self::fromAiAgentEnvVar()
            ?? self::fromKnownEnvVars()
            ?? self::fromFileSystem()
            ?? AgentResult::noAgent();
    }

    protected static function fromAiAgentEnvVar(): ?AgentResult
    {
        $aiAgent = getenv('AI_AGENT');

        if ($aiAgent === false) {
            return null;
        }

        $aiAgent = trim($aiAgent);

        if ($aiAgent === '') {
            return null;
        }

        $agentName = match ($aiAgent) {
            'github-copilot', 'github-copilot-cli' => 'copilot',
            default => $aiAgent,
        };

        return AgentResult::forAgent($agentName);
    }

    protected static function fromKnownEnvVars(): ?AgentResult
    {
        foreach (self::AGENT_ENV_VARS as $agent => $envVars) {
            foreach ($envVars as $envVar) {
                if (getenv($envVar) === false) {
                    continue;
                }

                $agentName = match ($agent) {
                    'claude' => getenv('CLAUDE_CODE_IS_COWORK') !== false ? 'cowork' : 'claude',
                    default => $agent,
                };

                return AgentResult::forAgent($agentName);
            }
        }

        return null;
    }

    protected static function fromFileSystem(): ?AgentResult
    {
        if (file_exists('/opt/.devin')) {
            return AgentResult::forAgent('devin');
        }

        return null;
    }
}
