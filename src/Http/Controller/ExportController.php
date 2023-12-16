<?php

namespace App\Http\Controller;

use App\Importer\Facade\Import;
use App\Model\Ascii;
use App\Model\User;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RuntimeException;
use Slim\Psr7\Stream;
use Slim\Psr7\UploadedFile;

class ExportController extends Controller
{

    /**
     * Export Ascii Art.
     *
     * @param \Psr\Http\Message\ResponseInterface $response The response object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function export(Response $response): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        /**
         * @var \App\Repository\AsciiRepository $repository
         */
        $repository = app()->resolve(EntityManager::class)->getRepository(Ascii::class);
        $all = $repository->findAll();;
        $list = [];

        foreach ($all as $item) {
            $allCats = $item->getCategories();
            $allTags = $item->getTags();

            $cats = $tags = [];

            foreach ($allCats as $cat) {
                $cats[] = $cat->getName();
            }

            foreach ($allTags as $tag) {
                $tags[] = $tag->getName();
            }

            $list[] = [
                'text' => $item->getText(),
                'cats' => implode(',', $cats),
                'tags' => implode(',', $tags),
            ];
        }

        $stream = fopen('php://memory', 'w+');

        foreach ($list as $fields) {
            fputcsv($stream, $fields);
        }

        rewind($stream);

        $response = $response->withHeader('Content-Type', 'text/csv');
        $response = $response->withHeader('Content-Disposition', 'attachment; filename="ascii.csv"');

        return $response->withBody(new Stream($stream));
    }

    /**
     * Import an upload file.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function import(Request $request, Response $response): Response
    {
        try {
            $mimes = array(
                'text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt',
            );

            $em = app()->resolve(EntityManager::class);

            $upload = app()->request()->getUploadedFiles();
            $uploadedFile = $upload['import'];

            $settings = config('app.uploads');
            $path = $settings['path'];

            if (!in_array($uploadedFile->getClientMediaType(), $mimes)) {
                throw new \Exception("Invalid file format.");
            }

            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $file = $this->moveUploadedFile($path, $uploadedFile);

                /**
                 * User comes from the session its not a real instance
                 * of a user model so we need to load it.
                 */
                $user = $request->getAttribute('user');
                $user = $em->getRepository(User::class)->find($user->getId());

                $result = Import::import($file, $user);

                if ($result) {
                    $response->withHeader('Content-Type', 'application/json')
                        ->getBody()
                        ->write(
                            json_encode([
                                'status' => 'success',
                                'message' => $result['imported'] . ' records imported.'
                            ])
                        );
                } else {
                    throw new \Exception("Error while importing ascii art.");
                }
            }
        } catch (RuntimeException $e) {
            $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200)
                ->getBody()
                ->write(
                    json_encode([
                        'status' => 'error',
                        "message" => "Error processing file upload"
                    ])
                );
        } catch (Exception $e) {
            $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200)
                ->getBody()
                ->write(
                    json_encode([
                        'status' => 'error',
                        "message" => $e->getMessage()
                    ])
                );
        }

        return $response;
    }

    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param string       $directory    directory to which the file is moved
     * @param UploadedFile $uploadedFile file uploaded file to move
     *
     * @throws \Exception
     * @return string filename of moved file
     */
    private function moveUploadedFile(string $directory, UploadedFile $uploadedFile): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $directory . DIRECTORY_SEPARATOR . $filename;
    }
}
