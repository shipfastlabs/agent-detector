<p align="center">
    <img src="docs/og.png" height="300" alt="Agent Detector" />
    <p align="center">
        <a href="https://github.com/shipfastlabs/agent-detector/actions"><img alt="GitHub Workflow Status (master)" src="https://github.com/shipfastlabs/agent-detector/actions/workflows/tests.yml/badge.svg"></a>
        <a href="https://packagist.org/packages/shipfastlabs/agent-detector"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/shipfastlabs/agent-detector"></a>
        <a href="https://packagist.org/packages/shipfastlabs/agent-detector"><img alt="Latest Version" src="https://img.shields.io/packagist/v/shipfastlabs/agent-detector"></a>
        <a href="https://packagist.org/packages/shipfastlabs/agent-detector"><img alt="License" src="https://img.shields.io/packagist/l/shipfastlabs/agent-detector"></a>
    </p>
</p>

------

A lightweight PHP utility to detect if your code is running inside an AI agent or automated development environment.

> **Requires [PHP 8.2+](https://php.net/releases/)**

## Installation

```bash
composer require shipfastlabs/agent-detector
```

## Usage

```php
use AgentDetector\AgentDetector;

$result = AgentDetector::detect();

if ($result->isAgent) {
    echo "Running inside: {$result->name}";
    // e.g. "claude", "cursor", "codex"
}

// Check for a specific known agent
if ($result->knownAgent() === \AgentDetector\KnownAgent::Claude) {
    echo "Hello from Claude!";
}

// Get a human-readable display name
if ($agent = $result->knownAgent()) {
    echo $agent->displayName(); // "Claude Code", "GitHub Copilot", "Gemini CLI", ...
}

// Get the session ID (where available)
if ($result->sessionId !== null) {
    echo "Session: {$result->sessionId}"; // e.g. CODEX_THREAD_ID or AMP_CURRENT_THREAD_ID value
}
```

Or use the standalone function:

```php
use function AgentDetector\detectAgent;

$result = detectAgent();
```

## Supported Agents

| Agent | Display Name | Detection Method | Session ID |
|-------|-------------|-----------------|------------|
| Custom | _(raw value)_ | `AI_AGENT` env var | — |
| Cursor | Cursor | `CURSOR_AGENT` env var | — |
| Gemini | Gemini CLI | `GEMINI_CLI` env var | — |
| Codex | Codex | `CODEX_SANDBOX` or `CODEX_THREAD_ID` env var | `CODEX_THREAD_ID` |
| Augment CLI | Augment CLI | `AUGMENT_AGENT` env var | — |
| Amp | Amp | `AMP_CURRENT_THREAD_ID` env var | `AMP_CURRENT_THREAD_ID` |
| Opencode | OpenCode | `OPENCODE_CLIENT` or `OPENCODE` env var | — |
| Claude | Claude Code | `CLAUDECODE` or `CLAUDE_CODE` env var | `CLAUDE_CODE_SESSION_ID` _(if set)_ |
| Copilot | GitHub Copilot | `COPILOT_CLI` env var | — |
| Replit | Replit | `REPL_ID` env var | — |
| Devin | Devin | `/opt/.devin` file exists | — |
| Antigravity | Antigravity | `ANTIGRAVITY_AGENT` env var | — |
| Pi | Pi | `PI_CODING_AGENT` env var | — |

### Custom Agent

Set the `AI_AGENT` environment variable to any value to identify your custom agent:

```bash
AI_AGENT=my-custom-agent php your-script.php
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details on how to contribute, including adding support for new agents.

## Testing

```bash
composer test
```

**Agent Detector** was created by **[Pushpak Chhajed](https://github.com/pushpak1300)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
