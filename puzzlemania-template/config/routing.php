<?php

declare(strict_types=1);

use DI\Container;
use Salle\PuzzleMania\Controller\API\RiddlesAPIController;
use Salle\PuzzleMania\Controller\API\UsersAPIController;
use Salle\PuzzleMania\Controller\GameController;
use Salle\PuzzleMania\Controller\JoinTeamController;
use Salle\PuzzleMania\Controller\SignUpController;
use Salle\PuzzleMania\Controller\SignInController;
use Salle\PuzzleMania\Middleware\AuthorizationMiddleware;
use Slim\App;

function addRoutes(App $app, Container $container): void
{
    /* HOME */
    $app->get('/', SignInController::class . ':showHome')->setName('showHome');
    /* LOGIN AND REGISTER */
    $app->get('/sign-in', SignInController::class . ':showSignInForm')->setName('signIn');
    $app->post('/sign-in', SignInController::class . ':signIn');
    $app->get('/sign-up', SignUpController::class . ':showSignUpForm')->setName('signUp');
    $app->post('/sign-up', SignUpController::class . ':signUp');
    /* GAME */
    $app->get('/game', GameController::class . ':newGame')->setName('newGame');

    $app->get('/join', JoinTeamController::class . ':joinTeam')->setName('joinTeam')->add(AuthorizationMiddleware::class);

}
