<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Model\Game;
use Salle\PuzzleMania\Repository\Games\GameRepository;
use Salle\PuzzleMania\Repository\Riddles\RiddleRepository;
use Salle\PuzzleMania\Repository\Teams\TeamRepository;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class GameController {
    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository,
        private GameRepository $gameRepository,
        private RiddleRepository $riddleRepository
    ) {
    }

    public function newGame(Request $request, Response $response): Response {
        $data = [];
        // check if logged
        if (!isset($_SESSION['user_id'])) {
            // redirect to home
            return $response->withHeader('Location', '/');
        }

        $userStatus['logged'] = isset($_SESSION['user_id']);

        // check has team
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        if (!isset($user->team)) {
            // redirect to join team page
            return $response->withHeader('Location', '/join');
        }

        // get team name
        $team = $this->teamRepository->getTeamById($user->team);
        $_SESSION['teamId'] = $team->getId();
        $data['team'] = $team->name();

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render($response, 'start-game.twig', [
            "formAction" => $routeParser->urlFor('startGame'),
            "data" => $data,
            "userStatus" => $userStatus
        ]);
    }

    public function startGame(Request $request, Response $response): Response {
        $_SESSION['currentRiddle'] = 0;
        $_SESSION['gamePoints'] = 10;

        // get random riddles
        $riddles = $this->riddleRepository->getRandomRiddles();
        $_SESSION['riddle1_id'] = $riddles[0]->getId();
        $_SESSION['riddle2_id'] = $riddles[1]->getId();
        $_SESSION['riddle3_id'] = $riddles[2]->getId();

        $_SESSION['riddle1'] = $riddles[0]->getRiddle();
        $_SESSION['riddle2'] = $riddles[1]->getRiddle();
        $_SESSION['riddle3'] = $riddles[2]->getRiddle();

        $_SESSION['riddle1_answer'] = $riddles[0]->getAnswer();
        $_SESSION['riddle2_answer'] = $riddles[1]->getAnswer();
        $_SESSION['riddle3_answer'] = $riddles[2]->getAnswer();

        $game = Game::create()
            ->setUserId(intval($_SESSION['user_id']))
            ->setRiddle1Id(intval($_SESSION['riddle1_id']))
            ->setRiddle2Id(intval($_SESSION['riddle2_id']))
            ->setRiddle3Id(intval($_SESSION['riddle3_id']))
            ->setScore(10)
            ->setCreatedAt(new DateTime());
        $_SESSION['gameId'] = $this->gameRepository->createGame($game)->getId();

        return $response->withHeader('Location', '/game/' . $_SESSION['gameId'] . '/riddles/' . $_SESSION['riddle1_id']);
    }

    public function nextRiddle(Request $request, Response $response): Response {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $data = [];

        $data['riddleNum'] = intval($_SESSION['currentRiddle']) + 1;

        if ($_SESSION['currentRiddle'] == 0) {
            $data['riddle'] = $_SESSION['riddle1'];
            $riddleId = $_SESSION['riddle1_id'];
        } else if ($_SESSION['currentRiddle'] == 1) {
            $data['riddle'] = $_SESSION['riddle2'];
            $riddleId = $_SESSION['riddle2_id'];
        } else {
            $data['riddle'] = $_SESSION['riddle3'];
            $riddleId = $_SESSION['riddle3_id'];
        }

        $userStatus['logged'] = isset($_SESSION['user_id']);

        return $this->twig->render($response, 'game-riddle.twig', [
            "formAction" => $routeParser->urlFor('checkRiddleAnswer', [
                "gameId" => $_SESSION['gameId'],
                "riddleId" => $riddleId
            ]),
            "data" => $data,
            "userStatus" => $userStatus
        ]);
    }

    public function checkRiddleAnswer(Request $request, Response $response): Response {
        $data = [];
        $formData = $request->getParsedBody();
        $userAnswer = $formData['answer'];
        $data['riddleNum'] = $_SESSION['currentRiddle'] + 1;

        if ($_SESSION['currentRiddle'] == 0) {
            $riddleAnswer = $_SESSION['riddle1_answer'];
            $data['riddle'] = $_SESSION['riddle1'];
        } else if ($_SESSION['currentRiddle'] == 1) {
            $riddleAnswer = $_SESSION['riddle2_answer'];
            $data['riddle'] = $_SESSION['riddle2'];
        } else {
            $riddleAnswer = $_SESSION['riddle3_answer'];
            $data['riddle'] = $_SESSION['riddle3'];
        }

        if (strtolower($riddleAnswer) == strtolower($userAnswer)) {
            $_SESSION['gamePoints'] += 10;
            $data['answerStatus'] = 'Correct';
        } else {
            $_SESSION['gamePoints'] -= 10;
            $data['answerStatus'] = 'Wrong';
            $data['answer'] = $riddleAnswer;
        }

        $_SESSION['currentRiddle']++;
        $userStatus['logged'] = isset($_SESSION['user_id']);

        if (($_SESSION['gamePoints'] <= 0) || (intval($_SESSION['currentRiddle']) >= 3)) { // game over
            // update game score
            $this->gameRepository->updateGameScore($_SESSION['gameId'], $_SESSION['gamePoints']);
            // update team score (since if the user loses all its points here we will have 0 points
            // so we can always add it to the team score
            $this->teamRepository->increaseTeamScore($_SESSION['teamId'], $_SESSION['gamePoints']);
            // redirect

            return $this->twig->render($response, 'game-summary.twig', [
                "totalScore" => $_SESSION['gamePoints'],
                "buttonHref" => '/team-stats',
                "buttonValue" => 'Finish',
                "gameOver" => true,
                "userStatus" => $userStatus
            ]);
        } else {
            if ($_SESSION['currentRiddle'] == 1) {
                $nextRiddle = $_SESSION['riddle2_id'];
            } else {
                $nextRiddle = $_SESSION['riddle3_id'];
            }
        }

        $route = '/game/' . $_SESSION['gameId'] . '/riddles/' . $nextRiddle;

        return $this->twig->render($response, 'game-summary.twig', [
            "totalScore" => $_SESSION['gamePoints'],
            "data" => $data,
            "buttonHref" => $route,
            "buttonValue" => 'Next',
            "gameOver" => false,
            "userStatus" => $userStatus
        ]);
    }
}