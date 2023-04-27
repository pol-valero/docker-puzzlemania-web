<?php

namespace Salle\PuzzleMania\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Repository\Riddles\RiddleRepository;
use Slim\Views\Twig;

class RiddlesAPIController
{
    private Twig $twig;
    private RiddleRepository $riddleRepository;

    public function __construct(Twig $twig, RiddleRepository $riddleRepository)
    {
        $this->twig = $twig;
        $this->riddleRepository = $riddleRepository;
    }


    public function getRiddles(Request $request, Response $response): Response{
        $riddles = $this->riddleRepository->getRiddles();
        $response->getBody()->write(json_encode($riddles));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function postRiddle(Request $request, Response $response): Response{
        // TODO Implement posting riddles
        return $response;
    }
}