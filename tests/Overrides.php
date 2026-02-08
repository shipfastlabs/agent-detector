<?php

declare(strict_types=1);

namespace AgentDetector;

function file_exists(string $filename): bool
{
    if (isset($GLOBALS['__mock_file_exists'])) {
        return ($GLOBALS['__mock_file_exists'])($filename);
    }

    return \file_exists($filename);
}
