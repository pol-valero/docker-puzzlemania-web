<?php

namespace Salle\PuzzleMania\Controller;

use http\Client;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Repository\Teams\TeamRepository;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Slim\Flash\Messages;
use Slim\Views\Twig;

class TeamStatsController {

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository,
        private Messages $flash
    ) {
        //
    }

    public function showStats(Request $request, Response $response): Response {

        if (!isset($_SESSION['team_id'])) {
            $this->flash->addMessage('errorTeamStats', 'Error: You have not joined a team yet!');
            return $response->withHeader('Location', '/join');
        }

        $messages = $this->flash->getMessages();
        $userStatus['logged'] = isset($_SESSION['user_id']);

        if (isset($messages['errorTeam'])) {
            $errorTeam = $messages['errorTeam'][0];
        } else {
            $errorTeam = '';
        }

        $teamInfo = $this->teamRepository->getTeamById($_SESSION['team_id']);

        $teamMembers = $this->teamRepository->getTeamMembers($_SESSION['team_id']);

        $i = 0;

        foreach ($teamMembers as $member) {
            $name[$i] = explode("@", $member['email'])[0];
            $i++;
        }

        return $this->twig->render($response, 'team-stats.twig', [
            'teamInfo' => $teamInfo,
            'teamMembers' => $name,
            'error' => $errorTeam,
            "userStatus" => $userStatus
        ]);
    }

    public function generateQr(Request $request, Response $response): Response {

       if(!isset($_SESSION['team_id'])) {
           return $response->withHeader('Location', '/');
       }

       //TODO: Generate QR code with API
        $data = array(
            'symbology' => 'QRCode',
            'code' => '12345'
        );

        $options = array(
            'http' => array(
                'method'  => 'POST',
                'content' => json_encode( $data ),
                'header' =>  "Content-Type: application/json\r\n" .
                    "Accept: image/png\r\n"
            )
        );

        $context  = stream_context_create( $options );
        $url = 'http://localhost:8020/BarcodeGenerator';
        $response2 = file_get_contents( $url, false, $context );

        //file_put_contents('label.png', $response2);

        return $response->withHeader('Location', '/team-stats');

    }

}