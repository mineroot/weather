<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi\Response;

use Mineroot\WeatherApp\Logging\Contract\LoggableInterface;
use Mineroot\WeatherApp\WeatherApi\Object\CurrentForecast;
use Mineroot\WeatherApp\WeatherApi\Object\Location;
use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class GetCurrentForecastResponse implements ResponseInterface, LoggableInterface
{
    public function __construct(
        #[SerializedName('location')]
        public Location        $location,

        #[SerializedName('current')]
        public CurrentForecast $currentForecast,
    )
    {
    }

    public function logContext(): array
    {
        return [
            'location' => $this->location->name,
            'temperature' => $this->currentForecast->tempC,
            'condition' => $this->currentForecast->condition->text,
        ];
    }
}
