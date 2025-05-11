<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi\Exception;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

final class ClientException extends \RuntimeException
{
    public function is4xxError(): bool
    {
        return $this->getPrevious() instanceof ClientExceptionInterface;
    }
}
