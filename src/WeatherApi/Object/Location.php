<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi\Object;

use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class Location
{
    public function __construct(
        #[SerializedName('name')]
        public string $name,

        #[SerializedName('region')]
        public string $region,

        #[SerializedName('country')]
        public string $country,

        #[SerializedName('lat')]
        public float  $latitude,

        #[SerializedName('lon')]
        public float  $longitude,

        #[SerializedName('tz_id')]
        public string $tzId,

        #[SerializedName('localtime_epoch')]
        public int    $localtimeEpoch,

        #[SerializedName('localtime')]
        public string $localTime,
    )
    {
    }
}
