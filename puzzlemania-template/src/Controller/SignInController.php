<?php
declare(strict_types=1);

namespace Salle\PuzzleMania\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Salle\PuzzleMania\Service\ValidatorService;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class SignInController {
    private ValidatorService $validator;

    public function __construct(
        private Twig           $twig,
        private UserRepository $userRepository,
        private Messages       $flash
    )
    {
        $this->validator = new ValidatorService();
    }

    public function showSignInForm(Request $request, Response $response): Response {
        $messages = $this->flash->getMessages();

        $notifications = $messages['notifications'] ?? [];

        return $this->twig->render($response, 'sign-in.twig', ["notifs" => $notifications]);
    }

    public function showHome(Request $request, Response $response): Response {
        $userStatus = [];

        $messages = $this->flash->getMessages();

        if (isset($messages['notifications'])) {
            $error = $messages['notifications'][0];
        } else {
            $error = '';
        }

        if (!isset($_SESSION['user_id'])) {
            $username = "stranger";
        } else {
            $userStatus['logged'] = true;
            $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
            $username = explode('@', $user->email)[0];
        }
            return $this->twig->render($response, 'home.twig', [
                "username" => $username,
                "userStatus" => $userStatus,
                "error" => $error
            ]);
    }

    public function signIn(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $errors = [];

        $errors['email'] = $this->validator->validateEmail($data['email']);
        $errors['password'] = $this->validator->validatePassword($data['password']);

        if ($errors['email'] == '') {
            unset($errors['email']);
        }

        if ($errors['password'] == '') {
            unset($errors['password']);
        }

        if (count($errors) == 0) {
            // Check if the credentials match the user information saved in the database
            $user = $this->userRepository->getUserByEmail($data['email']);
            if ($user == null) {
                $errors['email'] = 'User with this email address does not exist.';
            } else if ($user->password != md5($data['password'])) {
                $errors['password'] = 'Your email and/or password are incorrect.';
            } else {
                $_SESSION['user_id'] = $user->id;

                if($user->team != null) {
                    $_SESSION['team_id'] = $user->team;
                }

                return $response->withHeader('Location', '/')->withStatus(302);
            }
        }

        return $this->twig->render(
            $response,
            'sign-in.twig',
            [
                'formErrors' => $errors,
                'formData' => $data,
                'formAction' => $routeParser->urlFor('signIn')
            ]
        );
    }

    public function logOut(Request $request, Response $response): Response {
        session_destroy();
        return $response->withHeader('Location', '/sign-in');
    }
}
