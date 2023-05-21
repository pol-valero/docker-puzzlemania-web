<?php

declare(strict_types=1);

use DI\Container;
use Salle\PuzzleMania\Controller\FileController;
use Salle\PuzzleMania\Controller\RiddlesAPIController;
use Salle\PuzzleMania\Controller\GameController;
use Salle\PuzzleMania\Controller\JoinTeamController;
use Salle\PuzzleMania\Controller\SignUpController;
use Salle\PuzzleMania\Controller\SignInController;
use Salle\PuzzleMania\Controller\TeamStatsController;
use Salle\PuzzleMania\Middleware\AuthorizationMiddleware;
use Slim\App;

function addRoutes(App $app, Container $container): void
{
    /* HOME */
    $app->get(
        '/', SignInController::class . ':showHome'
    )->setName('showHome');

    /* LOGIN AND REGISTER */
    $app->get(
        '/sign-in', SignInController::class . ':showSignInForm'
    )->setName('signIn');
    $app->post(
        '/sign-in', SignInController::class . ':signIn'
    );
    $app->get(
        '/sign-up', SignUpController::class . ':showSignUpForm'
    )->setName('signUp');
    $app->post(
        '/sign-up', SignUpController::class . ':signUp'
    );

    /* GAME */
    $app->get(
        '/game', GameController::class . ':newGame'
    )->setName('newGame');

    $app->post(
        '/game', GameController::class . ':startGame'
    )->setName('startGame');

    $app->get(
        '/game/{gameId}/riddles/{riddleId}',
        GameController::class . ':nextRiddle'
    )->setName('nextRiddle');

    $app->post(
        '/game/{gameId}/riddles/{riddleId}',
        GameController::class . ':checkRiddleAnswer'
    )->setName('checkRiddleAnswer');

    /* TEAMS */
    $app->get(
        '/join',
        JoinTeamController::class . ':joinTeam'
    )->setName('joinTeam')->add(AuthorizationMiddleware::class);

    $app->get(
        '/team-stats',
        TeamStatsController::class . ':showStats'
    )->setName('teamStats')->add(AuthorizationMiddleware::class);

    $app->get(
        '/log-out',
        SignInController::class . ':logOut'
    )->setName('logOut');

    $app->post('/join', JoinTeamController::class . ':createTeam');

    $app->get(
        '/invite/join/{id:.*}',
        JoinTeamController::class . ':addUserToTeam'
    )->setName('addUserToTeam');

    /* RIDDLES */
    $app->get(
        '/riddles',
        RiddlesAPIController::class . ':showRiddles'
    )->setName('showRiddles');

    $app->get('/riddles/{id}', RiddlesAPIController::class . ':showRiddle');

    $app->get('/api/riddle', RiddlesAPIController::class . ':getRiddles');
    $app->post('/api/riddle', RiddlesAPIController::class . ':postRiddle');
    $app->get('/api/riddle/{id}', RiddlesAPIController::class . ':getRiddle');
    $app->put('/api/riddle/{id}', RiddlesAPIController::class . ':putRiddle');
    $app->delete('/api/riddle/{id}', RiddlesAPIController::class . ':deleteRiddle');

    /* PROFILE */
    $app->get(
        '/profile',
        FileController::class . ':showProfileFormAction'
    )->setName('profile');

    $app->post(
        '/profile',
        FileController::class . ':uploadFileAction'
    )->setName('upload');
}
