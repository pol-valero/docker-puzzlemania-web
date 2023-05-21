<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Model\Riddle;
use Salle\PuzzleMania\Repository\Riddles\RiddleRepository;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Slim\Views\Twig;

class RiddlesAPIController {
    private Twig $twig;
    private RiddleRepository $riddleRepository;
    private UserRepository $userRepository;

    public function __construct(Twig $twig, RiddleRepository $riddleRepository, UserRepository $userRepository) {
        $this->twig = $twig;
        $this->riddleRepository = $riddleRepository;
        $this->userRepository = $userRepository;

    }

    // GET /riddle
    public function showRiddles(Request $request, Response $response): Response
    {
        $riddles = $this->riddleRepository->getRiddles();
        $userStatus['logged'] = isset($_SESSION['user_id']);

        return $this->twig->render(
            $response,
            'riddles.twig',
            [
                'riddleslist' => true,
                'riddleExists' => true,
                'riddles' => $riddles,
                "userStatus" => $userStatus
            ]
        );
    }

    // GET /riddle/{id}
    public function showRiddle(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $riddle = $this->riddleRepository->getRiddleById($id);
        $userStatus['logged'] = isset($_SESSION['user_id']);

        if($riddle) {$riddle = [$riddle];} // Used to pass an array to twig and reuse the same template

        if(!$riddle){
            return $this->twig->render(
                $response,
                'riddles.twig',
                [
                    'riddleslist' => false,
                    'riddles' => false,
                    'riddleExists' => false,
                    "userStatus" => $userStatus
                ]
            );
        }else{
            return $this->twig->render(
                $response,
                'riddles.twig',

                [
                    'riddleslist' => false,
                    'riddleExists' => true,
                    'riddles' => $riddle,
                    "userStatus" => $userStatus
                ]
            );
        }
    }

    // GET /api/riddle
    public function getRiddles(Request $request, Response $response): Response{
        $riddles = $this->riddleRepository->getRiddles();
        $response->getBody()->write(json_encode($riddles));
        $response->withStatus(200);
        return $response->withHeader('Content-Type', 'application/json');
    }

    // POST /api/riddle
    public function postRiddle(Request $request, Response $response): Response{
        $data = $request->getParsedBody();
        $errors = $this->validatePostFields($data);
        $response->withHeader('Content-Type', 'application/json');

        if($errors['message'] == ''){
            // Create riddle
            $riddle = Riddle::create()
               ->setRiddle($data['riddle'])
               ->setAnswer($data['answer'])
               ->setUserId(intval($data['userId']));
           $id = $this->riddleRepository->createRiddle($riddle);
           $riddle->setId(intval($id)); // Return riddle with id set
           $response->getBody()->write(json_encode($riddle));
           return $response->withStatus(201);
        }else{
           // Error return
           $response->getBody()->write(json_encode($errors));
           return $response->withStatus(400);
       }
    }

    // GET /api/riddle/{id}
    public function getRiddle(Request $request, Response $response, array $args): Response{
        $id = intval($args['id']);
        $riddle = $this->riddleRepository->getRiddleById($id);
        if(!$riddle){
            $response->getBody()->write(json_encode(['message' => "Riddle with id $id does not exist"]));
            return $response->withStatus(404);
        }else{
            $response->getBody()->write(json_encode($riddle));
            return $response->withStatus(200);
        }
    }

    // PUT /api/riddle/{id}
    public function putRiddle(Request $request, Response $response, array $args): Response{
        $id = intval($args['id']);
        $data = $request->getParsedBody();

        if(!$this->riddleRepository->getRiddleById($id)){
            $response->getBody()->write(json_encode(['message' => "Riddle with id $id does not exist"]));
            return $response->withStatus(404);
        }

        $errors = $this->validatePutFields($data);

        if($errors['message'] == ''){
            // Update riddle
            $riddle = Riddle::create()
               ->setId($id)
               ->setRiddle($data['riddle'])
               ->setAnswer($data['answer']);
           $this->riddleRepository->updateRiddle($riddle);
           $response->getBody()->write(json_encode($riddle));
           return $response->withStatus(200);
        }else{
              // Error return
              $response->getBody()->write(json_encode($errors));
              return $response->withStatus(400);
        }
    }

    // DELETE /api/riddle/{id}
    public function deleteRiddle(Request $request, Response $response, array $args): Response{
        $id = intval($args['id']);
        if(!$this->riddleRepository->getRiddleById($id)){
            $response->getBody()->write(json_encode(['message' => "Riddle with id $id does not exist"]));
            return $response->withStatus(404);
        }else{
            $this->riddleRepository->deleteRiddle($id);
            return $response->withStatus(200);
        }
    }

    private function validatePutFields($data): array{
        // Check if riddle and answer exists
        $errors=['message' => ''];
        if(!isset($data['riddle']) || !isset($data['answer'])){
            $errors['message'] = "The riddle and/or answer cannot be empty";
        }
        return $errors;
    }

    private function validatePostFields($data): array{
        $errors=['message' => ''];

        // Check if userId exists
        if(!isset($data['userId'])){
            $errors['message'] .= " 'userId' key missing";
        }else{
            if(!$this->userRepository->getUserById(intval($data['userId']))){
                $errors['message'] .= "This 'userId' doesn't exist";
            }
        }

        // Check if $data['riddle'], $data['answer'] and $data['userId'] exists
        if(!isset($data['riddle'])){
            $errors['message'] .= " 'riddle' key missing";
        }
        if(!isset($data['answer'])){
            $errors['message'] .= " 'answer' key missing";
        }
        if(!isset($data['userId'])){
            $errors['message'] .= " 'userId' key missing";
        }

        return $errors;
    }
}