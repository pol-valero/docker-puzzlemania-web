<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PuzzleMania\Model\User;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Salle\PuzzleMania\Service\ValidatorService;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

final class SignUpController
{
    private ValidatorService $validator;

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository
    )
    {
        $this->validator = new ValidatorService();
    }

    /**
     * Renders the form
     */
    public function showSignUpForm(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render(
            $response,
            'sign-up.twig',
            [
                'formAction' => $routeParser->urlFor('signUp')
            ]
        );
    }

    public function signUp(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $errors = [];

        $errors['email'] = $this->validator->validateEmail($data['email']);
        $errors['password'] = $this->validator->validatePassword($data['password']);
        //$errors['birthday'] = $this->validator->validateBirthday($data['birthday']);
        if ($data['password'] != $data['repeatPassword']) {
            $errors['password'] = "Passwords do not match.";
        }

        // Unset variables if there are no errors
        if ($errors['email'] == '') {
            unset($errors['email']);
        }
        if ($errors['password'] == '') {
            unset($errors['password']);
        }

        $savedUser = $this->userRepository->getUserByEmail($data['email']);
        if ($savedUser != null) {
            $errors['email'] = "User already exists!";
        }
        if (count($errors) == 0) {
            $user = User::create()
                ->setEmail($data['email'])
                ->setPassword(md5($data['password']))
                ->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime());
            $this->userRepository->createUser($user);

            //If the team_id is set in the session, it means that the user has scanned a QR code and therefore
            //we add him to the team automatically and redirect him to the team stats page
            if (isset($_SESSION['team_id'])) {
                $_SESSION['user_id'] = $this->userRepository->getUserByEmail($data['email'])->id;
                $inviteUrl = '/invite/join/'.$_SESSION['team_id'];
                return $response->withHeader('Location', $inviteUrl);
            }

            return $response->withHeader('Location', '/sign-in')->withStatus(302);
        }
        return $this->twig->render(
            $response,
            'sign-up.twig',
            [
                'formErrors' => $errors,
                'formData' => $data,
                'formAction' => $routeParser->urlFor('signUp')
            ]
        );
    }
}
