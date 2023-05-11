<?php

declare(strict_types=1);

use DI\Container;
use Salle\PuzzleMania\Controller\RiddlesAPIController;
use Salle\PuzzleMania\Controller\SignInController;
use Salle\PuzzleMania\Controller\SignUpController;
use Slim\App;

function addRoutes(App $app, Container $container): void
{
    $app->get('/', SignInController::class . ':showHome')->setName('showHome');
    $app->get('/sign-in', SignInController::class . ':showSignInForm')->setName('signIn');
    $app->post('/sign-in', SignInController::class . ':signIn');
    $app->get('/sign-up', SignUpController::class . ':showSignUpForm')->setName('signUp');
    $app->post('/sign-up', SignUpController::class . ':signUp');

    $app->get('/api/riddle', RiddlesAPIController::class . ':getRiddles');
    $app->post('/api/riddle', RiddlesAPIController::class . ':postRiddle');
    $app->get('/api/riddle/{id}', RiddlesAPIController::class . ':getRiddle');

}
