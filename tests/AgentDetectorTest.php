<?php

declare(strict_types=1);

use AgentDetector\AgentDetector;
use AgentDetector\KnownAgent;

use function AgentDetector\detectAgent;

beforeEach(function (): void {
    foreach ([
        'AI_AGENT',
        'CURSOR_AGENT',
        'GEMINI_CLI',
        'CODEX_SANDBOX',
        'CODEX_THREAD_ID',
        'AUGMENT_AGENT',
        'OPENCODE_CLIENT',
        'OPENCODE',
        'AMP_CURRENT_THREAD_ID',
        'CLAUDECODE',
        'CLAUDE_CODE',
        'COPILOT_CLI',
        'REPL_ID',
        'ANTIGRAVITY_AGENT',
        'PI_CODING_AGENT',
        'CLAUDE_CODE_SESSION_ID',
    ] as $var) {
        putenv($var);
    }

    unset($GLOBALS['__mock_file_exists']);
});

afterEach(function (): void {
    foreach ([
        'AI_AGENT',
        'CURSOR_AGENT',
        'GEMINI_CLI',
        'CODEX_SANDBOX',
        'CODEX_THREAD_ID',
        'AUGMENT_AGENT',
        'OPENCODE_CLIENT',
        'OPENCODE',
        'AMP_CURRENT_THREAD_ID',
        'CLAUDECODE',
        'CLAUDE_CODE',
        'COPILOT_CLI',
        'REPL_ID',
        'ANTIGRAVITY_AGENT',
        'PI_CODING_AGENT',
        'CLAUDE_CODE_SESSION_ID',
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
it('detects cursor via CURSOR_AGENT', function (): void {
    putenv('CURSOR_AGENT=1');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('cursor')
        ->and($result->knownAgent())->toBe(KnownAgent::Cursor);
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

it('detects codex via CODEX_THREAD_ID', function (): void {
    putenv('CODEX_THREAD_ID=some-thread-id');

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

it('detects opencode via OPENCODE', function (): void {
    putenv('OPENCODE=true');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('opencode')
        ->and($result->knownAgent())->toBe(KnownAgent::Opencode);
});

it('detects amp via AMP_CURRENT_THREAD_ID', function (): void {
    putenv('AMP_CURRENT_THREAD_ID=some-thread-id');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('amp')
        ->and($result->knownAgent())->toBe(KnownAgent::Amp);
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

it('detects copilot via COPILOT_CLI', function (): void {
    putenv('COPILOT_CLI=1');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('copilot')
        ->and($result->knownAgent())->toBe(KnownAgent::Copilot);
});

it('detects replit via REPL_ID', function (): void {
    putenv('REPL_ID=some-repl-id');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('replit')
        ->and($result->knownAgent())->toBe(KnownAgent::Replit);
});

it('detects antigravity via ANTIGRAVITY_AGENT', function (): void {
    putenv('ANTIGRAVITY_AGENT=1');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('antigravity')
        ->and($result->knownAgent())->toBe(KnownAgent::Antigravity);
});

it('detects pi via PI_CODING_AGENT', function (): void {
    putenv('PI_CODING_AGENT=true');

    $result = AgentDetector::detect();

    expect($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('pi')
        ->and($result->knownAgent())->toBe(KnownAgent::Pi);
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

    expect($result->name)->toBe('cursor');
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
    'cursor' => ['CURSOR_AGENT', '1', KnownAgent::Cursor],
    'gemini' => ['GEMINI_CLI', 'true', KnownAgent::Gemini],
    'codex' => ['CODEX_SANDBOX', 'true', KnownAgent::Codex],
    'augment-cli' => ['AUGMENT_AGENT', 'true', KnownAgent::AugmentCli],
    'opencode' => ['OPENCODE_CLIENT', 'true', KnownAgent::Opencode],
    'amp' => ['AMP_CURRENT_THREAD_ID', 'thread-id', KnownAgent::Amp],
    'copilot' => ['COPILOT_CLI', '1', KnownAgent::Copilot],
    'claude' => ['CLAUDECODE', '1', KnownAgent::Claude],
    'replit' => ['REPL_ID', 'id', KnownAgent::Replit],
    'antigravity' => ['ANTIGRAVITY_AGENT', '1', KnownAgent::Antigravity],
    'pi' => ['PI_CODING_AGENT', 'true', KnownAgent::Pi],
]);

it('returns null knownAgent for custom agent', function (): void {
    putenv('AI_AGENT=unknown-agent');

    $result = AgentDetector::detect();

    expect($result->knownAgent())->toBeNull();
});

// Standalone function
it('works via standalone detectAgent function', function (): void {
    putenv('CURSOR_AGENT=1');

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

// Session ID extraction
it('extracts CODEX_THREAD_ID as sessionId when codex detected via CODEX_SANDBOX', function (): void {
    putenv('CODEX_SANDBOX=true');
    putenv('CODEX_THREAD_ID=thread-abc123');

    $result = AgentDetector::detect();

    expect($result->sessionId)->toBe('thread-abc123');
});

it('extracts CODEX_THREAD_ID as sessionId when codex detected via CODEX_THREAD_ID', function (): void {
    putenv('CODEX_THREAD_ID=thread-xyz');

    $result = AgentDetector::detect();

    expect($result->sessionId)->toBe('thread-xyz');
});

it('extracts AMP_CURRENT_THREAD_ID as sessionId when amp detected', function (): void {
    putenv('AMP_CURRENT_THREAD_ID=amp-session-456');

    $result = AgentDetector::detect();

    expect($result->sessionId)->toBe('amp-session-456');
});

it('returns null sessionId for agents without a session env var', function (): void {
    putenv('CURSOR_AGENT=1');

    $result = AgentDetector::detect();

    expect($result->sessionId)->toBeNull();
});

it('returns null sessionId for claude when CLAUDE_CODE_SESSION_ID is not set', function (): void {
    putenv('CLAUDECODE=1');

    $result = AgentDetector::detect();

    expect($result->sessionId)->toBeNull();
});

it('extracts CLAUDE_CODE_SESSION_ID as sessionId when set', function (): void {
    putenv('CLAUDECODE=1');
    putenv('CLAUDE_CODE_SESSION_ID=claude-sess-789');

    $result = AgentDetector::detect();

    expect($result->sessionId)->toBe('claude-sess-789');
});

it('sessionId is null when no agent detected', function (): void {
    $GLOBALS['__mock_file_exists'] = fn (string $path): bool => false;

    $result = AgentDetector::detect();

    expect($result->sessionId)->toBeNull();
});

// KnownAgent::displayName()
it('returns correct display names for all known agents', function (KnownAgent $agent, string $expected): void {
    expect($agent->displayName())->toBe($expected);
})->with([
    'cursor'      => [KnownAgent::Cursor,      'Cursor'],
    'claude'      => [KnownAgent::Claude,      'Claude Code'],
    'devin'       => [KnownAgent::Devin,       'Devin'],
    'replit'      => [KnownAgent::Replit,      'Replit'],
    'gemini'      => [KnownAgent::Gemini,      'Gemini CLI'],
    'codex'       => [KnownAgent::Codex,       'Codex'],
    'augment-cli' => [KnownAgent::AugmentCli,  'Augment CLI'],
    'opencode'    => [KnownAgent::Opencode,     'OpenCode'],
    'amp'         => [KnownAgent::Amp,          'Amp'],
    'copilot'     => [KnownAgent::Copilot,      'GitHub Copilot'],
    'antigravity' => [KnownAgent::Antigravity,  'Antigravity'],
    'pi'          => [KnownAgent::Pi,           'Pi'],
]);

// AgentResult serialization compatibility
it('unserializes a pre-sessionId payload without erroring', function (): void {
    // Serialized form of an AgentResult from before the sessionId field existed
    // (default property-based serialization with only isAgent + name).
    $legacyPayload = 'O:25:"AgentDetector\AgentResult":2:{s:7:"isAgent";b:1;s:4:"name";s:5:"codex";}';

    $result = unserialize($legacyPayload);

    expect($result)->toBeInstanceOf(\AgentDetector\AgentResult::class)
        ->and($result->isAgent)->toBeTrue()
        ->and($result->name)->toBe('codex')
        ->and($result->sessionId)->toBeNull();
});

it('round-trips serialize/unserialize with sessionId set', function (): void {
    $original = new \AgentDetector\AgentResult(true, 'codex', 'thread-abc');

    $restored = unserialize(serialize($original));

    expect($restored->isAgent)->toBeTrue()
        ->and($restored->name)->toBe('codex')
        ->and($restored->sessionId)->toBe('thread-abc');
});

it('excludes sessionId from default JSON encoding', function (): void {
    $result = new \AgentDetector\AgentResult(true, 'codex', 'thread-abc');

    expect(json_encode($result))->toBe('{"isAgent":true,"name":"codex"}');
});

it('json-encodes a null-agent result without sessionId', function (): void {
    $result = new \AgentDetector\AgentResult(false);

    expect(json_encode($result))->toBe('{"isAgent":false,"name":null}');
});
