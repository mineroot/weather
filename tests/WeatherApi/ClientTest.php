<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\Tests\WeatherApi;

use Mineroot\WeatherApp\WeatherApi\Client;
use Mineroot\WeatherApp\WeatherApi\Object\Condition;
use Mineroot\WeatherApp\WeatherApi\Object\CurrentForecast;
use Mineroot\WeatherApp\WeatherApi\Object\Location;
use Mineroot\WeatherApp\WeatherApi\Request\GetCurrentForecastRequest;
use Mineroot\WeatherApp\WeatherApi\Response\GetCurrentForecastResponse;
use Mineroot\WeatherApp\WeatherApi\Response\ResponseInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ClientTest extends KernelTestCase
{
    /**
     * @return list<array{
     *     client: MockHttpClient,
     *     expectedResponse: ?ResponseInterface,
     *     expectedException: ?string
     * }>
     */
    public static function requestProvider(): array
    {
        $fixturesDir = dirname(__DIR__) . '/fixtures';
        return [
            [
                'client' => new MockHttpClient(
                    new MockResponse(
                        file_get_contents($fixturesDir . '/weather_responses/current_forecast_success.json') ?: '',
                    ),
                ),
                'expectedResponse' => new GetCurrentForecastResponse(
                    location: new Location(
                        name: 'Odesa',
                        region: "Odes'ka Oblast'",
                        country: "Ukraine",
                        latitude: 46.4667,
                        longitude: 30.7333,
                        tzId: 'Europe/Kiev',
                        localtimeEpoch: 1746877558,
                        localTime: '2025-05-10 14:45',
                    ),
                    currentForecast: new CurrentForecast(
                        lastUpdatedEpoch: 1746877500,
                        lastUpdated: '2025-05-10 14:45',
                        tempC: 15.1,
                        tempF: 59.1,
                        isDay: 1,
                        condition: new Condition(
                            text: 'Partly Cloudy',
                            icon: '//cdn.weatherapi.com/weather/64x64/day/116.png',
                            code: 1003,
                        ),
                        windMph: 3.4,
                        windKph: 5.4,
                        windDegree: 277,
                        windDirection: 'W',
                        pressureMb: 1017,
                        pressureIn: 30.04,
                        precipitationMm: 0,
                        precipitationIn: 0,
                        humidity: 39,
                        cloud: 34,
                        feelsLikeC: 15.1,
                        feelsLikeF: 59.1,
                        visibilityKm: 10,
                        visibilityMiles: 6,
                        ultraviolet: 5.1,
                        gustMph: 4,
                        gustKph: 6.4,
                    ),
                ),
                'expectedException' => null,
            ],
            [
                'client' => new MockHttpClient(
                    new MockResponse(
                        file_get_contents($fixturesDir . '/weather_responses/current_forecast_failure.json') ?: '',
                        ['http_code' => 400],
                    ),
                ),
                'expectedResponse' => null,
                'expectedException' => 'No matching location found.',
            ],
        ];
    }

    #[DataProvider('requestProvider')]
    public function testRequest(HttpClientInterface $client, ?ResponseInterface $expectedResponse, ?string $expectedException): void
    {
        /** @var SerializerInterface&NormalizerInterface $serializer */
        $serializer = self::getContainer()->get(SerializerInterface::class);

        if ($expectedException !== null) {
            $this->expectExceptionMessage($expectedException);
        }

        $weatherHttpClient = new Client($client, $serializer, '');
        $response = $weatherHttpClient->request(new GetCurrentForecastRequest('Odesa'), GetCurrentForecastResponse::class);

        if ($expectedResponse !== null) {
            $this->assertEquals($expectedResponse, $response);
        }
    }
}
