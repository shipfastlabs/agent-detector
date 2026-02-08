<?php

declare(strict_types=1);

namespace AgentDetector;

function detectAgent(): AgentResult
{
    return AgentDetector::detect();
}
