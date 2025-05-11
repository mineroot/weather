<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi\Object;

use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class CurrentForecast
{
    public function __construct(
        #[SerializedName('last_updated_epoch')]
        public int       $lastUpdatedEpoch,

        #[SerializedName('last_updated')]
        public string    $lastUpdated,

        #[SerializedName('temp_c')]
        public float     $tempC,

        #[SerializedName('temp_f')]
        public float     $tempF,

        #[SerializedName('is_day')]
        public int       $isDay,

        #[SerializedName('condition')]
        public Condition $condition,

        #[SerializedName('wind_mph')]
        public float     $windMph,

        #[SerializedName('wind_kph')]
        public float     $windKph,

        #[SerializedName('wind_degree')]
        public float     $windDegree,

        #[SerializedName('wind_dir')]
        public string    $windDirection,

        #[SerializedName('pressure_mb')]
        public float     $pressureMb,

        #[SerializedName('pressure_in')]
        public float     $pressureIn,

        #[SerializedName('precip_mm')]
        public float     $precipitationMm,

        #[SerializedName('precip_in')]
        public float     $precipitationIn,

        #[SerializedName('humidity')]
        public float     $humidity,

        #[SerializedName('cloud')]
        public float     $cloud,

        #[SerializedName('feelslike_c')]
        public float     $feelsLikeC,

        #[SerializedName('feelslike_f')]
        public float     $feelsLikeF,

        #[SerializedName('vis_km')]
        public float     $visibilityKm,

        #[SerializedName('vis_miles')]
        public float     $visibilityMiles,

        #[SerializedName('uv')]
        public float     $ultraviolet,

        #[SerializedName('gust_mph')]
        public float     $gustMph,

        #[SerializedName('gust_kph')]
        public float     $gustKph,
    )
    {
    }
}
