<?php

namespace App\Http\Controller\Api;

use App\Factory\AsciiFactory;
use App\Http\Controller\Controller;
use App\Model\Ascii;
use App\Model\Category;
use App\Model\Tag;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AsciiController extends Controller
{
    /**
     * Return all art or art from a given category.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     * @param array<string, mixed>                     $args     The arguments for this route.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Request $request, Response $response, array $args = []): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $repo = app()->resolve(EntityManager::class)->getRepository(Ascii::class);
        $catsRepo = app()->resolve(EntityManager::class)->getRepository(Category::class);

        $user = $request->getAttribute('user');
        $ascii = $repo->findBy(['user' => $user]);

        $ascii = array_filter($ascii, fn($a) => $a->getCategories()->count() == 0);
        $ascii = array_slice($ascii, 0, count($ascii));

        if (isset($args['category_id'])) {
            $cat = $catsRepo->find($args['category_id']);
            $ascii = $cat->getAsciis()->toArray();
        }

        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($ascii));

        return $response;
    }

    /**
     * Search for art.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function search(Request $request, Response $response): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $repo = app()->resolve(EntityManager::class)->getRepository(Ascii::class);

        $user = $request->getAttribute('user');
        $ascii = $repo->findBy(['user' => $user]);

        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($ascii));

        return $response;
    }

    /**
     * Create new art.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(Request $request, Response $response): Response
    {
        $csrf = app()->resolve('csrf');
        $csrf->generateToken();

        try {

            $params = $request->getParsedBody();
            $text = $params['text'];
            $cats = $params['categories'];
            $tags = $params['tags'];

            $user = $request->getAttribute('user');

            $ascii = AsciiFactory::create($text, $user);

            if (count($cats)) {
                $asciiCats = $ascii->getCategories();
                $asciiCats->clear();
                foreach ($cats as $cat_id) {
                    $cat = $this->em->getRepository(Category::class)->find($cat_id);
                    if ($cat && !$asciiCats->contains($cat_id)) {
                        $ascii->addCategory($cat);
                    }
                }
            }

            if (count($tags)) {
                $asciiTags = $ascii->getTags();
                $asciiTags->clear();
                foreach ($tags as $tag_id) {
                    $tag = $this->em->getRepository(Tag::class)->find($tag_id);
                    if ($tag && !$asciiTags->contains($tag_id)) {
                        $ascii->addTag($tag);
                    }
                }
            }

            $this->em->persist($ascii);
            $this->em->flush();

            $json = [
                'status' => 'success',
                'message' => __('Your ascii art has been created'),
                'token' => csrf_token(),
            ];
        } catch (\Exception $e) {
            $json = [
                'status' => 'error',
                'message' => __('There are a problem creating a new ascii for you.'),
                'token' => csrf_token(),
            ];
        }

        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($json));

        return $response;
    }

    /**
     * Update art.
     *
     * @param \App\Model\Ascii                         $ascii    The Ascii Object.
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Ascii $ascii, Request $request, Response $response): Response
    {
        $csrf = app()->resolve('csrf');
        $csrf->generateToken();


        try {

            $params = $request->getParsedBody();
            $text = $params['text'];
            $cats = $params['categories'];
            $tags = $params['tags'];

            $asciiCats = $ascii->getCategories();
            $asciiCats->clear();

            /**
             * @var \App\Repository\TagRepository $repository
             */
            $repository = $this->em->getRepository(Category::class);

            foreach ($cats as $cat_id) {
                $cat = $repository->find($cat_id);
                if ($cat && !$asciiCats->contains($cat_id)) {
                    $ascii->addCategory($cat);
                }
            }

            if (count($tags)) {
                $asciiTags = $ascii->getTags();
                $asciiTags->clear();

                /**
                 * @var \App\Repository\TagRepository $repository
                 */
                $repository = $this->em->getRepository(Tag::class);

                foreach ($tags as $tag_id) {
                    $tag = $repository->find($tag_id);
                    if ($tag && !$asciiTags->contains($tag_id)) {
                        $ascii->addTag($tag);
                    }
                }
            }

            $ascii->setText($text);
            $this->em->persist($ascii);
            $this->em->flush();


            $json = [
                'status' => 'success',
                'message' => __('Your ascii art has been updated'),
                'token' => csrf_token(),
            ];
        } catch (\Exception $e) {
            $json = [
                'status' => 'error',
                'message' => __('There are a problem updating your ascii art.'),
                'token' => csrf_token(),
            ];
        }
        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($json));

        return $response;
    }

    /**
     * Delete art.
     *
     * @param \App\Model\Ascii                    $ascii    The Ascii Object.
     * @param \Psr\Http\Message\ResponseInterface $response The response object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(Ascii $ascii, Response $response): Response
    {
        $em = app()->resolve(EntityManager::class);
        $csrf = app()->resolve('csrf');
        $csrf->generateToken();

        try {

            $em->remove($ascii);
            $em->flush();

            $json = [
                'status' => 'success',
                'message' => __('Your ascii art has been deleted'),
                'token' => csrf_token(),
            ];
        } catch (\Exception $e) {
            $json = [
                'status' => 'error',
                'message' => __('There are a problem deleting your ascii art.'),
                'token' => csrf_token(),
            ];
        }


        $response = $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($json));

        return $response;
    }
}
