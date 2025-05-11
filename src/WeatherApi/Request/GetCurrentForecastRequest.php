<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi\Request;

use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class GetCurrentForecastRequest implements RequestInterface
{
    public function __construct(
        #[SerializedName('q')]
        public string $query,
    )
    {
    }

    public function method(): string
    {
        return 'GET';
    }

    public function uri(): string
    {
        return '/v1/current.json';
    }
}
