<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Controller;

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
    private array $riddles;
    private int $currentRiddle;

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository,
        private GameRepository $gameRepository,
        private RiddleRepository $riddleRepository
    ) {
        $this->currentPoints = 10;
        $this->riddles = [];
        $this->currentRiddle = 0;
    }

    public function newGame(Request $request, Response $response): Response {
        $data = [];
        // check if logged
        if (!isset($_SESSION['user_id'])) {
            // redirect to home
            return $response->withHeader('Location', '/');
        }

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
            "data" => $data
        ]);
    }

    public function startGame(Request $request, Response $response): Response {
        // get riddles ids
        $nRiddles = count($this->riddleRepository->getRiddles());

        for ($i = 0; $i < 3; $i++) {
            $random = rand(0,$nRiddles);
            $this->riddles[$i] = $random;
        }

        // generate game ID
        $lastGame = $this->gameRepository->getLastGame();

        if (null == $lastGame) {
            $this->id = 0;
        } else {
            $this->id = $lastGame->getId() + 1;
        }

        $gameId = $this->id;
        $riddleId = $this->riddles[0];

        return $response->withHeader('Location', '/game/' . $gameId . '/riddles/' . $riddleId);
        /*$routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $response->withHeader('Location', $routeParser->urlFor('nextRiddle', [
            "gameId" => $gameId,
            "riddleId" => $riddleId
        ]));*/
    }

    public function nextRiddle(Request $request, Response $response): Response {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $this->id = (int) $request->getAttribute("gameId");
        $data = [];
        $data['riddleNum'] = $this->currentRiddle + 1;
        $data['riddle'] = 'What is your favorite colour?';

        return $this->twig->render($response, 'game-riddle.twig', [
            "formAction" => $routeParser->urlFor('checkRiddleAnswer', [
                "gameId" => $this->id,
                "riddleId" => $this->riddles[$this->currentRiddle]
            ]),
            "data" => $data
        ]);
    }

    public function checkRiddleAnswer(Request $request, Response $response): Response {
        $formData = $request->getParsedBody();
        $answer = $formData['answer'];
        $riddle = $this->riddleRepository->getRiddleById($this->riddles[$this->currentRiddle]);

        if ($riddle->getAnswer() == $answer) {
            $this->currentPoints += 10;
        } else {
            $this->currentPoints -= 10;
            // TODO: show error
        }

        if ($this->currentPoints <= 0) {
            // game over
        }

        return $response->withHeader('Location', '/game/' . $this->id . '/riddles/' . $this->riddles[1]);
    }
}