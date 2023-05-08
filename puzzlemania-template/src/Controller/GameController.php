<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GameController {
    //

    public function __construct(private Twig $twig) {
        //
    }

    public function newGame(Request $request, Response $response): Response {
        $data = [];
        // check if logged
        /*if (!isset($_SESSION['user_id'])) {               //TODO: uncomment block
            // redirect to home
            return $response->withHeader('Location', '/');
        }*/

        // check has team
        //

        return $this->twig->render($response, 'start-game.twig', [
            "data" => $data
        ]);
    }
}