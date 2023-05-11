<?php

namespace Salle\PuzzleMania\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Repository\Teams\TeamRepository;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Slim\Views\Twig;

class JoinTeamController {

    //constructor
    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository
    ) {
        //
    }

    public function joinTeam(Request $request, Response $response): Response {
        /*$data = [];
        // check if logged
        if (!isset($_SESSION['user_id'])) {               //TODO: uncomment block
            // redirect to home
            return $response->withHeader('Location', '/');
        }

        // check has team
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        if (isset($user->team)) {
            // redirect to join team page
            return $response->withHeader('Location', '/'); //TODO: change location
        }

        // get team name
        $team = $this->teamRepository->getTeamById($user->team);
        $data['team'] = $team->name;

        return $this->twig->render($response, 'start-game.twig', [
            "data" => $data
        ]);*/

        return $this->twig->render($response, 'join-team.twig');

    }
}