<?php

declare(strict_types=1);

namespace Salle\PuzzleMania\Controller;
use Salle\PuzzleMania\Repository\Users\UserRepository;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class FileController
{
    private const UPLOADS_DIR = __DIR__ . '/../../public/uploads';

    private const UNEXPECTED_ERROR = "An unexpected error occurred uploading the file '%s'...";

    private const INVALID_EXTENSION_ERROR = "The received file extension '%s' is not valid";

    private const INVALID_SIZE_ERROR = "The received file size is not valid, it must be less than 1MB";

    // We use this const to define the extensions that we are going to allow
    private const ALLOWED_EXTENSIONS = ['jpg', 'png'];

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository
    )
    {
    }


    public function showProfileFormAction(Request $request, Response $response): Response
    {
        $userStatus['logged'] = true;
        return $this->twig->render(
            $response,
            'upload.twig',
            [
                'email' => $this->userRepository->getUserById(intval($_SESSION['user_id']))->email,
                'profile_picture' => "/uploads/" . $this->userRepository->getUserById(intval($_SESSION['user_id']))->profile_picture,
                'userStatus' => $userStatus
            ]
        );
    }


    public function uploadFileAction(Request $request, Response $response): Response
    {
        if(!isset($_SESSION['user_id'])){
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withHeader('Location',
                $routeParser->urlFor("signIn"))
                ->withStatus(302);
        }

        $uploadedFile = $request->getUploadedFiles()['files'];

        $errors = [];

        // Check if the file exists
        if($uploadedFile->getSize() == 0){
            $errors[] = "You must select a file";
        }else{
            // Check if there is no error with the upload
            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(
                    $errors[] = self::UNEXPECTED_ERROR,
                    $uploadedFile->getClientFilename()
                );
            }

            // Check if the file size is less than 1MB
            // echo $uploadedFile->getSize();

            $name = $uploadedFile->getClientFilename();
            $fileInfo = pathinfo($name);
            $format = $fileInfo['extension'];

            $mime = mime_content_type($uploadedFile->getStream()->getMetadata('uri'));

            // Check if the image format is valid
            if (!$this->isValidFormat($format) && !$this->isValidFormat(substr($mime, 6))) {
                $errors[] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
            }else{
                // Check if the image dimensions are less than 400x400
                $imageInfo = getimagesize($uploadedFile->getStream()->getMetadata('uri'));
                if ($imageInfo[0] > 400 || $imageInfo[1] > 400) {
                    $errors[] = "The image dimensions are not valid";
                }
            }

            // If there are no errors, upload the file, otherwise, show the errors
            if (empty($errors)) {
                $uniqueName = uniqid('', true) . '.' . $format;
                $uploadedFile->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $uniqueName );
                $this->userRepository->setProfilePicture($_SESSION['user_id'], $uniqueName);
            }
        }

        $userStatus['logged'] = true;
        return $this->twig->render(
            $response,
            'upload.twig',
            [
                'errors' => $errors,
                'email' => $this->userRepository->getUserById(intval($_SESSION['user_id']))->email,
                'profile_picture' => "/uploads/" . $this->userRepository->getUserById(intval($_SESSION['user_id']))->profile_picture,
                'userStatus' => $userStatus
            ]
        );
    }


    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }
}