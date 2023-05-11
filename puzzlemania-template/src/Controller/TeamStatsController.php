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

        if (!isset($_SESSION['team_id'])) {
            //TODO: Flash message
            return $response->withHeader('Location', '/join');
        }

        $teamInfo = $this->teamRepository->getTeamById($_SESSION['team_id']);

        $teamMembers = $this->teamRepository->getTeamMembers($_SESSION['team_id']);

        $i = 0;

        foreach ($teamMembers as $member) {
            $name[$i] = explode("@", $member['email'])[0];
            $i++;
        }

        return $this->twig->render($response, 'team-stats.twig', ['teamInfo' => $teamInfo, 'teamMembers' => $name]);
    }

}