<?php

namespace Salle\PuzzleMania\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Model\Team;
use Salle\PuzzleMania\Model\User;
use Salle\PuzzleMania\Repository\Teams\TeamRepository;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Slim\Flash\Messages;
use Slim\Views\Twig;

class JoinTeamController {

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository,
        private Messages $flash
    ) {
        //
    }

    public function joinTeam(Request $request, Response $response): Response {


        if (isset($_SESSION['team_id'])) {
            $this->flash->addMessage('errorTeam', 'Error: You are already in a team!');
           return $response->withHeader('Location', '/team-stats');
        }

        $messages = $this->flash->getMessages();

        if (isset($messages['errorTeamStats'])) {
            $errorTeamStats = $messages['errorTeamStats'][0];
        } else {
            $errorTeamStats = '';
        }

        $inclompleteTeams = $this->teamRepository->getIncompleteTeams();


        if($inclompleteTeams == null) {
            $showIncompleteTeams = false;
        } else {
            $showIncompleteTeams = true;
        }

        return $this->twig->render($response, 'join-team.twig', ['incompleteTeams' => $inclompleteTeams, 'showIncompleteTeams' => $showIncompleteTeams, 'error' => $errorTeamStats]);

    }

    public function createTeam(Request $request, Response $response): Response {

        $data = $request->getParsedBody();
        $teamName = $data['teamName'];

        $teamId = $this->teamRepository->createTeam($teamName);

        $userInfo = $this->userRepository->getUserById($_SESSION['user_id']);

        $createdAt = date_create_from_format('Y-m-d H:i:s', $userInfo->createdAt);

        $user = User::create();
        $user->setId($userInfo->id);
        $user->setEmail($userInfo->email);
        $user->setPassword($userInfo->password);
        $user->setTeam($teamId);
        $user->setCreatedAt($createdAt);
        $user->setUpdatedAt(new DateTime());

        //$user = new User ($userInfo->id, $userInfo->email, $userInfo->password, $teamId, $createdAt, new DateTime());
        //$user->setTeam($teamId);
        //$user->setUpdatedAt(new DateTime());

        $this->userRepository->updateUser($user);

        return $response->withHeader('Location', '/team-stats');

    }

    public function addUserToTeam(Request $request, Response $response): Response{

        $teamId = $request->getAttribute('id');

        $teamId = (int)$teamId;

        $_SESSION['team_id'] = $teamId;

        $userInfo = $this->userRepository->getUserById($_SESSION['user_id']);

        $createdAt = date_create_from_format('Y-m-d H:i:s', $userInfo->createdAt);

        $user = User::create();
        $user->setId($userInfo->id);
        $user->setEmail($userInfo->email);
        $user->setPassword($userInfo->password);
        $user->setTeam($teamId);
        $user->setCreatedAt($createdAt);
        $user->setUpdatedAt(new DateTime());

        $this->userRepository->updateUser($user);

        $teamInfo = $this->teamRepository->getTeamById($teamId);

        $updatedNumMembers = $teamInfo->numMembers + 1;

        $this->teamRepository->updateTeam($teamId, $updatedNumMembers, new DateTime());

        return $response->withHeader('Location', '/team-stats');
    }

}