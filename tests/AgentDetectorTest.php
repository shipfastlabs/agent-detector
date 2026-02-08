<?php

declare(strict_types=1);

use AgentDetector\AgentDetector;
use AgentDetector\KnownAgent;

use function AgentDetector\detectAgent;

beforeEach(function (): void {
    foreach ([
        'AI_AGENT',
        'CURSOR_TRACE_ID',
        'CURSOR_AGENT',
        'GEMINI_CLI',
        'CODEX_SANDBOX',
        'AUGMENT_AGENT',
        'OPENCODE_CLIENT',
        'CLAUDECODE',
        'CLAUDE_CODE',
        'REPL_ID',
    ] as $var) {
        putenv($var);
    }

    unset($GLOBALS['__mock_file_exists']);
});

afterEach(function (): void {
    foreach ([
        'AI_AGENT',
        'CURSOR_TRACE_ID',
        'CURSOR_AGENT',
        'GEMINI_CLI',
        'CODEX_SANDBOX',
        'AUGMENT_AGENT',
        'OPENCODE_CLIENT',
        'CLAUDECODE',
        'CLAUDE_CODE',
        'REPL_ID',
    ] as $var) {
        putenv($var);
    }

    unset($GLOBALS['__mock_file_exists']);
});

// Custom agent detection
it('detects a custom agent via AI_AGENT', function (): void {
    putenv('AI_AGENT=my-custom-agent');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('my-custom-agent')
        ->and($result->knownAgent())->toBeNull();
});

it('does not detect an agent when AI_AGENT is not set', function (): void {
    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeFalse()
        ->and($result->name)->toBeNull();
});

// Known agent env var detection
it('detects cursor via CURSOR_TRACE_ID', function (): void {
    putenv('CURSOR_TRACE_ID=some-trace-id');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('cursor')
        ->and($result->knownAgent())->toBe(KnownAgent::Cursor);
});

it('detects cursor-cli via CURSOR_AGENT', function (): void {
    putenv('CURSOR_AGENT=true');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('cursor-cli')
        ->and($result->knownAgent())->toBe(KnownAgent::CursorCli);
});

it('detects gemini via GEMINI_CLI', function (): void {
    putenv('GEMINI_CLI=true');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('gemini')
        ->and($result->knownAgent())->toBe(KnownAgent::Gemini);
});

it('detects codex via CODEX_SANDBOX', function (): void {
    putenv('CODEX_SANDBOX=true');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('codex')
        ->and($result->knownAgent())->toBe(KnownAgent::Codex);
});

it('detects augment-cli via AUGMENT_AGENT', function (): void {
    putenv('AUGMENT_AGENT=true');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('augment-cli')
        ->and($result->knownAgent())->toBe(KnownAgent::AugmentCli);
});

it('detects opencode via OPENCODE_CLIENT', function (): void {
    putenv('OPENCODE_CLIENT=true');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('opencode')
        ->and($result->knownAgent())->toBe(KnownAgent::Opencode);
});

it('detects claude via CLAUDECODE', function (): void {
    putenv('CLAUDECODE=1');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('claude')
        ->and($result->knownAgent())->toBe(KnownAgent::Claude);
});

it('detects claude via CLAUDE_CODE', function (): void {
    putenv('CLAUDE_CODE=1');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('claude')
        ->and($result->knownAgent())->toBe(KnownAgent::Claude);
});

it('detects replit via REPL_ID', function (): void {
    putenv('REPL_ID=some-repl-id');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('replit')
        ->and($result->knownAgent())->toBe(KnownAgent::Replit);
});

// Devin detection via file_exists mock
it('detects devin via /opt/.devin file', function (): void {
    $GLOBALS['__mock_file_exists'] = fn (string $path): bool => $path === '/opt/.devin';

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('devin')
        ->and($result->knownAgent())->toBe(KnownAgent::Devin);
});

it('does not detect devin when /opt/.devin does not exist', function (): void {
    $GLOBALS['__mock_file_exists'] = fn (string $path): bool => false;

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeFalse()
        ->and($result->name)->toBeNull();
});

// Priority order
it('prioritizes AI_AGENT over CURSOR_TRACE_ID', function (): void {
    putenv('AI_AGENT=custom');
    putenv('CURSOR_TRACE_ID=trace');

    $result = AgentDetector::detect();

    expect($result->name)->toBe('custom');
});

it('prioritizes CURSOR_TRACE_ID over CURSOR_AGENT', function (): void {
    putenv('CURSOR_TRACE_ID=trace');
    putenv('CURSOR_AGENT=true');

    $result = AgentDetector::detect();

    expect($result->name)->toBe('cursor');
});

it('prioritizes CURSOR_AGENT over CLAUDECODE', function (): void {
    putenv('CURSOR_AGENT=true');
    putenv('CLAUDECODE=1');

    $result = AgentDetector::detect();

    expect($result->name)->toBe('cursor-cli');
});

it('prioritizes CLAUDECODE over REPL_ID', function (): void {
    putenv('CLAUDECODE=1');
    putenv('REPL_ID=some-id');

    $result = AgentDetector::detect();

    expect($result->name)->toBe('claude');
});

// Edge cases
it('ignores empty AI_AGENT', function (): void {
    putenv('AI_AGENT=');

    $GLOBALS['__mock_file_exists'] = fn (string $path): bool => false;

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeFalse()
        ->and($result->name)->toBeNull();
});

it('ignores whitespace-only AI_AGENT', function (): void {
    putenv('AI_AGENT=   ');

    $GLOBALS['__mock_file_exists'] = fn (string $path): bool => false;

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeFalse()
        ->and($result->name)->toBeNull();
});

it('trims AI_AGENT value', function (): void {
    putenv('AI_AGENT=  my-agent  ');

    $result = AgentDetector::detect();

    expect($result->name)->toBe('my-agent');
});

it('handles AI_AGENT with special characters', function (): void {
    putenv('AI_AGENT=my-agent/v2.0 (beta)');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('my-agent/v2.0 (beta)');
});

// knownAgent() return values
it('returns correct enum for known agents', function (string $envVar, string $envValue, KnownAgent $expected): void {
    putenv("{$envVar}={$envValue}");

    $result = AgentDetector::detect();

    expect($result->knownAgent())->toBe($expected);
})->with([
    'cursor' => ['CURSOR_TRACE_ID', 'trace', KnownAgent::Cursor],
    'cursor-cli' => ['CURSOR_AGENT', 'true', KnownAgent::CursorCli],
    'gemini' => ['GEMINI_CLI', 'true', KnownAgent::Gemini],
    'codex' => ['CODEX_SANDBOX', 'true', KnownAgent::Codex],
    'augment-cli' => ['AUGMENT_AGENT', 'true', KnownAgent::AugmentCli],
    'opencode' => ['OPENCODE_CLIENT', 'true', KnownAgent::Opencode],
    'claude' => ['CLAUDECODE', '1', KnownAgent::Claude],
    'replit' => ['REPL_ID', 'id', KnownAgent::Replit],
]);

it('returns null knownAgent for custom agent', function (): void {
    putenv('AI_AGENT=unknown-agent');

    $result = AgentDetector::detect();

    expect($result->knownAgent())->toBeNull();
});

// Standalone function
it('works via standalone detectAgent function', function (): void {
    putenv('CURSOR_TRACE_ID=trace');

    $result = detectAgent();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('cursor');
});

// Convenience boolean
it('returns false isAgent when no agent detected', function (): void {
    $GLOBALS['__mock_file_exists'] = fn (string $path): bool => false;

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeFalse()
        ->and($result->knownAgent())->toBeNull();
});
