<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\Logging\Contract;

/**
 * Provides structured context data for logging.
 */
interface LoggableInterface
{
    public function logContext(): array;
}
