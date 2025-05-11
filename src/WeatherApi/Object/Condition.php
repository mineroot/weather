<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi\Object;

use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class Condition
{
    public function __construct(
        #[SerializedName('text')]
        public string $text,

        #[SerializedName('icon')]
        public string $icon,

        #[SerializedName('code')]
        public int    $code,
    )
    {
    }
}
