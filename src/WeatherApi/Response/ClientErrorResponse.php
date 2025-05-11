<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi\Response;

use Symfony\Component\Serializer\Attribute\SerializedPath;

final readonly class ClientErrorResponse implements ResponseInterface
{
    public function __construct(
        #[SerializedPath('[error][code]')]
        public int    $code,

        #[SerializedPath('[error][message]')]
        public string $message,
    )
    {

    }
}
