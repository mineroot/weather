<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi;

use Mineroot\WeatherApp\WeatherApi\Exception\ClientException;
use Mineroot\WeatherApp\WeatherApi\Request\RequestInterface;
use Mineroot\WeatherApp\WeatherApi\Response\ClientErrorResponse;
use Mineroot\WeatherApp\WeatherApi\Response\ResponseInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Client handles communication with a weather API using an injected HTTP client.
 * It serializes requests, appends the API key, and deserializes the responses into specified DTOs.
 * Errors are wrapped in custom exception with optional parsing of API error payloads.
 */
final readonly class Client implements ClientInterface
{
    public function __construct(
        private HttpClientInterface                     $weatherApiClient,
        private SerializerInterface&NormalizerInterface $serializer,
        #[Autowire(env: 'APP_WEATHER_API_KEY')]
        private string                                  $weatherApiKey,
    )
    {
    }

    public function request(RequestInterface $request, string $responseClass): ResponseInterface
    {
        try {
            /** @var array $query */
            $query = $this->serializer->normalize($request);
            $query['key'] = $this->weatherApiKey;
            $httpResponse = $this->weatherApiClient->request($request->method(), $request->uri(), ['query' => $query]);

            return $this->serializer->deserialize($httpResponse->getContent(), $responseClass, 'json');
        } catch (ClientExceptionInterface $e) {
            throw $this->wrapClientException($e);
        } catch (\Throwable $e) {
            throw new ClientException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    private function wrapClientException(ClientExceptionInterface $e): ClientException
    {
        try {
            $clientErrorResponse = $this->serializer->deserialize(
                $e->getResponse()->getContent(false),
                ClientErrorResponse::class,
                'json',
            );

            return new ClientException($clientErrorResponse->message, $clientErrorResponse->code, $e);
        } catch (\Throwable $e) {
            return new ClientException('Failed to parse error response: ' . $e->getMessage(), 0, $e);
        }
    }
}
