<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\WeatherApi\Request;

interface RequestInterface
{
    public function method(): string;

    public function uri(): string;
}
