<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\Logging;

use Mineroot\WeatherApp\Logging\Contract\LoggableInterface;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

/**
 * Replaces @see LoggableInterface values in log context with their logContext() output.
 */
final readonly class LoggableProcessor implements ProcessorInterface
{
    public function __invoke(LogRecord $record): LogRecord
    {
        $context = $record->context;
        $updated = false;
        foreach ($context as $key => $value) {
            if ($value instanceof LoggableInterface) {
                $context[$key] = $value->logContext();
                $updated = true;
            }
        }

        return $updated ? $record->with(context: $context) : $record;
    }
}
