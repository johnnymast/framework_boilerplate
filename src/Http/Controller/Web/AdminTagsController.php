<?php
declare(strict_types=1);

namespace App\Http\Controller\Web;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Framework\Session\Facade\Session;
use App\Http\Controller\Controller;
use App\Factory\TagFactory;
use App\Model\Ascii;
use App\Model\Tag;

class AdminTagsController extends Controller
{
    /**
     * Show all existing Tags.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request object.
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $tags = $this->em->getRepository(Tag::class)
            ->findBy(['user' => $user], ['id' => 'DESC']);

        return view('web.admin.tags.index', ['tags' => $tags]);
    }

    /**
     * Show a form to edit an existing Tag.
     *
     * @param \App\Model\Tag $tag The Tag Object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function edit(Tag $tag): Response
    {
        return view('web.admin.tags.edit', ['tag' => $tag]);
    }

    /**
     * Update an existing Tag.
     *
     * @param \App\Model\Tag                           $tag     The Tag Object.
     * @param \Psr\Http\Message\ServerRequestInterface $request The request Object.
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Tag $tag, Request $request): Response
    {
        $flash = Session::getFlash();
        $params = $request->getParsedBody();

        if (!empty($params)) {
            $tag->setName($params['name']);
            $this->em->persist($tag);
            $this->em->flush();

            $flash->add('success', 'Tag successfully updated');
        } else {
            $flash->add('error', 'Unknown Tag');
        }

        return view('web.admin.tags.edit', ['tag' => $tag]);
    }

    /**
     * Show a new Tag form.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(): Response
    {
        return view('web.admin.tags.create', [
            'tag' => ['name']
        ]);
    }

    /**
     * Save a new Tag.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request Object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function store(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();
        $flash = Session::getFlash();

        if (isset($params['name'])) {
            $user = $request->getAttribute('user');
            $tag = TagFactory::create($params['name'], $user);

            $flash->add('success', 'Tag created');

            return $response->withStatus(302)->withHeader(
                'Location',
                '/admin/tags/' . $tag->getId(),
            );
        }

        $flash->add('error', 'Error creating tag');

        return view('web.admin.tags.create', [
            'tag' => $params,
        ]);
    }

    /**
     * Delete a Tag.
     *
     * @param \App\Model\Tag                           $tag      The Tag Object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     *
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(Tag $tag, Response $response): Response
    {
        $flash = Session::getFlash();

        /**
         * @var \App\Repository\AsciiRepository $repository
         */
        $repository = $this->em->getRepository(Ascii::class);
        $repository->deleteLinkedTagsWithId($tag->getId());

        $this->em->remove($tag);
        $this->em->flush();

        $flash->add('success', 'Tag successfully deleted');

        return $response->withStatus(302)->withHeader(
            'Location',
            app()->getRouteCollector()->getRouteParser()->urlFor('admin.tags.index')
        );
    }
}
