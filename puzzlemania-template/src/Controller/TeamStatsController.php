<?php

namespace Salle\PuzzleMania\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Repository\Teams\TeamRepository;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Slim\Views\Twig;

class TeamStatsController {

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository
    ) {
        //
    }

    public function showStats(Request $request, Response $response): Response {

        return $this->twig->render($response, 'team-stats.twig');

    }

}