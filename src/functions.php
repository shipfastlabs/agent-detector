<?php

declare(strict_types=1);

namespace Laravel\AgentDetector;

function detectAgent(): AgentResult
{
    return AgentDetector::detect();
}
