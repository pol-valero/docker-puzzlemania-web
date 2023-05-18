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
    private int $id;
    private int $currentPoints;
    private int $currentRiddle;

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository,
        private GameRepository $gameRepository,
        private RiddleRepository $riddleRepository
    ) {
        $this->currentPoints = 10;
        $this->currentRiddle = 0;
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
            return $response->withHeader('Location', '/'); //TODO: change location
        }

        // get team name
        $team = $this->teamRepository->getTeamById($user->team);
        $data['team'] = $team->name;

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
        $formData = $request->getParsedBody();
        $answer = $formData['answer'];
        $gameId = intval($request->getAttribute("gameId"));
        $riddleId = intval($request->getAttribute("riddleId"));
        $riddle = $this->riddleRepository->getRiddleById($riddleId);
        $game = $this->gameRepository->getGameById($gameId);
        $data = [];
        $errors = [];

        if ($riddle->getAnswer() == $answer) {
            $_SESSION['gamePoints'] += 10;
        } else {
            $_SESSION['gamePoints'] -= 10;
            // TODO: show correct answer
        }

        if ($_SESSION['gamePoints'] <= 0) {
            // game over
        }

        $riddles = $game->riddles();
        $_SESSION['currentRiddle']++;

        if (intval($_SESSION['currentRiddle']) < 3) {
            $nextRiddle = $riddles[intval($_SESSION['currentRiddle'])];
        } else {
            // gameOver
            $_SESSION['currentRiddle'] = 0;
            $nextRiddle = $riddles[intval($_SESSION['currentRiddle'])]; //TODO: remove this, only for testing purposes now
        }

        return $response->withHeader('Location', '/game/' . $_SESSION['gameId'] . '/riddles/' . $nextRiddle);
    }
}