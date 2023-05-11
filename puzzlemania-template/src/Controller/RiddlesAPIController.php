<?php

namespace Salle\PuzzleMania\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Model\Riddle;
use Salle\PuzzleMania\Repository\Riddles\RiddleRepository;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Slim\Views\Twig;

class RiddlesAPIController
{
    private Twig $twig;
    private RiddleRepository $riddleRepository;
    private UserRepository $userRepository;

    public function __construct(Twig $twig, RiddleRepository $riddleRepository, UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->riddleRepository = $riddleRepository;
        $this->userRepository = $userRepository;

    }

    public function getRiddles(Request $request, Response $response): Response{
        $riddles = $this->riddleRepository->getRiddles();
        $response->getBody()->write(json_encode($riddles));
        $response->withStatus(200);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function postRiddle(Request $request, Response $response): Response{
        $data = $request->getParsedBody();
        $errors = $this->validateFields($data);
        $response->withHeader('Content-Type', 'application/json');

        if($errors['message'] == ''){
            // Create riddle
            $riddle[0] = Riddle::create()
               ->setRiddle($data['riddle'])
               ->setAnswer($data['answer'])
               ->setUserId($data['userId']);
           $id = $this->riddleRepository->createRiddle($riddle[0]);
           $riddle[0]->setId($id); // Return riddle with id set
           $response->getBody()->write(json_encode($riddle));
           return $response->withStatus(201);
        }else{
           // Error return
           $response->getBody()->write(json_encode($errors));
           return $response->withStatus(400);
       }
    }

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

    private function validateFields($data): array{
        $errors=['message' => ''];
        // TODO: Make more checks and errors possibilities

        // Check if userId exists
        if(!$this->userRepository->getUserById(intval($data['userId']))){
            $errors['message'] .= "This 'userId' doesn't exist";
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