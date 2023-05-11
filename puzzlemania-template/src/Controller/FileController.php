<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Controller;
use Cassandra\Uuid;
use Psr\Http\Message\UploadedFileInterface;
use Salle\PuzzleMania\Repository\UserRepository;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class FileController
{
    private const UPLOADS_DIR = __DIR__ . '/../../public/uploads';

    private const UNEXPECTED_ERROR = "An unexpected error occurred uploading the file '%s'...";

    private const INVALID_EXTENSION_ERROR = "The received file extension '%s' is not valid";

    // We use this const to define the extensions that we are going to allow
    private const ALLOWED_EXTENSIONS = ['jpg', 'png', 'pdf'];

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository
    )
    {
    }


    public function showProfileFormAction(Request $request, Response $response): Response
    {
        if(!isset($_SESSION['user_id'])){
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location',
                $routeParser->urlFor("signIn"))
                ->withStatus(302);
        }else{
            return $this->twig->render(
                $response,
                'upload.twig',
                [
                    'email' => $this->userRepository->getUserById(intval($_SESSION['user_id']))->email,
                    'profile_picture' => "/uploads/AAA645cba3c9188b8.70400452.jpg"
                ]
            );
        }
    }


    public function uploadFileAction(Request $request, Response $response): Response
    {
        if(!isset($_SESSION['user_id'])){
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location',
                $routeParser->urlFor("signIn"))
                ->withStatus(302);
        }

        $uploadedFiles = $request->getUploadedFiles();
        // TODO: Check only for 1 file
        $errors = [];

        /** @var UploadedFileInterface $uploadedFile */
        foreach ($uploadedFiles['files'] as $uploadedFile) {
            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(
                    self::UNEXPECTED_ERROR,
                    $uploadedFile->getClientFilename()
                );
                continue;
            }

            $name = $uploadedFile->getClientFilename();

            $fileInfo = pathinfo($name);

            $format = $fileInfo['extension'];

            if (!$this->isValidFormat($format)) {
                $errors[] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
                continue;
            }

            // We should generate a custom name here instead of using the one coming form the form
            $uniqueName = uniqid('AAA', true) . '.' . $format;

            $uploadedFile->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $uniqueName );
        }

        return $this->twig->render(
            $response,
            'upload.twig',
            [
                'email' => $this->userRepository->getUserById(intval($_SESSION['user_id']))->email,
                'profile_picture' => "/uploads/AAA645cba3c9188b8.70400452.jpg"
            ]
        );
    }

    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }
}