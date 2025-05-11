<?php

declare(strict_types=1);

namespace Mineroot\WeatherApp\Controller;

use Mineroot\WeatherApp\WeatherApi\CachedClient;
use Mineroot\WeatherApp\WeatherApi\ClientInterface;
use Mineroot\WeatherApp\WeatherApi\Exception\ClientException;
use Mineroot\WeatherApp\WeatherApi\Request\GetCurrentForecastRequest;
use Mineroot\WeatherApp\WeatherApi\Response\GetCurrentForecastResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

#[AsController]
#[Route('/{query}', name: 'home', methods: ['GET'])]
final readonly class HomeAction
{
    public function __construct(
        #[Autowire(service: CachedClient::class)]
        private ClientInterface       $weatherApiClient,
        private Environment           $twig,
        private UrlGeneratorInterface $router,
    )
    {
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function __invoke(string $query = ''): Response
    {
        if ('' === $query) {
            return new RedirectResponse(
                $this->router->generate('home', ['query' => 'odesa']),
                Response::HTTP_TEMPORARY_REDIRECT,
            );
        }

        $request = new GetCurrentForecastRequest($query);
        try {
            $errorMessage = null;
            $statusCode = Response::HTTP_OK;
            $response = $this->weatherApiClient->request($request, GetCurrentForecastResponse::class);
        } catch (ClientException $e) {
            if (!$e->is4xxError()) {
                throw $e;
            }
            $errorMessage = $e->getMessage();
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response = null;
        }

        return new Response($this->twig->render('home.html.twig', [
            'weather' => $response,
            'error' => $errorMessage,
        ]), $statusCode);
    }
}
