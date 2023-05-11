<?php

namespace Salle\PuzzleMania\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Model\Team;
use Salle\PuzzleMania\Model\User;
use Salle\PuzzleMania\Repository\Teams\TeamRepository;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Slim\Views\Twig;

class JoinTeamController {

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository
    ) {
        //
    }

    public function joinTeam(Request $request, Response $response): Response {

        return $this->twig->render($response, 'join-team.twig');

    }

    public function createTeam(Request $request, Response $response): Response {

        $data = $request->getParsedBody();
        $teamName = $data['teamName'];

        $teamId = $this->teamRepository->createTeam($teamName);

        $userInfo = $this->userRepository->getUserById($_SESSION['user_id']);

        $createdAt = date_create_from_format('Y-m-d H:i:s', $userInfo->createdAt);

        $user = new User ($userInfo->id, $userInfo->email, $userInfo->password, $teamId, $createdAt, new DateTime());
        //$user->setTeam($teamId);
        //$user->setUpdatedAt(new DateTime());

        $this->userRepository->updateUser($user);

        return $response->withHeader('Location', '/team-stats');

    }
}