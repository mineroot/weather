<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi;

use Mineroot\WeatherApp\WeatherApi\Exception\ClientException;
use Mineroot\WeatherApp\WeatherApi\Request\RequestInterface;
use Mineroot\WeatherApp\WeatherApi\Response\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * CachedClient wraps another ClientInterface implementation and adds caching functionality.
 * Requests are normalized into cache keys, and responses are cached for a configurable TTL to reduce redundant API calls.
 * It also logs request handling outcomes including cache hits.
 */
final readonly class CachedClient implements ClientInterface
{
    private const array CACHE_KEY_RESERVED = ['{', '}', '(', ')', '/', '\\', '@', ':'];

    public function __construct(
        private ClientInterface     $client,
        private CacheInterface      $cache,
        private NormalizerInterface $normalizer,
        private LoggerInterface     $logger,
        private int                 $ttl = 60,
    )
    {
        if ($this->ttl <= 0) {
            throw new \InvalidArgumentException('TTL must be positive.');
        }
    }

    public function request(RequestInterface $request, string $responseClass): ResponseInterface
    {
        try {
            $isHit = true;
            $response = $this->cache->get($this->cacheKey($request), function (ItemInterface $item) use ($request, $responseClass, &$isHit): ResponseInterface {
                $isHit = false;
                $item->expiresAfter($this->ttl);
                return $this->client->request($request, $responseClass);
            });
            $this->logger->info('Weather API request handled.', ['response' => $response, 'cacheHit' => $isHit]);

            return $response;
        } catch (ClientException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ClientException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    private function cacheKey(RequestInterface $request): string
    {
        /** @var array $query */
        $query = $this->normalizer->normalize($request);
        $key = strtolower(sprintf('%s %s %s', $request->method(), $request->uri(), http_build_query($query)));

        // remove reserved characters
        return str_replace(self::CACHE_KEY_RESERVED, '', $key);
    }
}
