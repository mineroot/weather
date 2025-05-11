<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi;

use Mineroot\WeatherApp\WeatherApi\Exception\ClientException;
use Mineroot\WeatherApp\WeatherApi\Request\RequestInterface;
use Mineroot\WeatherApp\WeatherApi\Response\ResponseInterface;

interface ClientInterface
{
    /**
     * @template T of ResponseInterface
     * @psalm-param RequestInterface $request
     * @psalm-param class-string<T> $responseClass
     * @psalm-return T|ResponseInterface
     *
     * @throws ClientException If an error occurs during the request or response handling.
     */
    public function request(RequestInterface $request, string $responseClass): ResponseInterface;
}
