<?php

namespace App\Http\Controller\Web;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Framework\Session\Facade\Session;
use App\Http\Controller\Controller;
use App\Factory\CategoryFactory;
use App\Model\Category;
use App\Model\Ascii;

class AdminCategoryController extends Controller
{
    /**
     * Show all existing categories.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $categories = $this->em->getRepository(Category::class)
            ->findBy(['user' => $user], ['id' => 'DESC']);


        return view('web.admin.categories.index', ['categories' => $categories]);
    }

    /**
     * Show a form to edit an existing category.
     *
     * @param \App\Model\Category $category The category object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function edit(Category $category): Response
    {
        return view('web.admin.categories.edit', ['category' => $category]);
    }

    /**
     * Update an existing category.
     *
     * @param \App\Model\Category                      $category The Category Object.
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request Object.
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Category $category, Request $request): Response
    {
        $flash = Session::getFlash();
        $params = $request->getParsedBody();

        if (!empty($params)) {
            $category->setName($params['name']);
            $this->em->persist($category);
            $this->em->flush();

            $flash->add('success', 'Category successfully updated');
        } else {
            $flash->add('error', 'Unknown Category');
        }

        return view('web.admin.categories.edit', ['category' => $category]);
    }

    /**
     * Ajax handler for updating the category order.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request Object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateorder(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();
        $data = ["message" => "Categories could not be updated"];
        if (isset($params['items']) && is_array($params['items'])) {
            foreach ($params['items'] as $weight => $id) {
                $cat = $this->em->getRepository(Category::class)->find($id);
                $cat->setWeight($weight);
                $this->em->persist($cat);
                $this->em->flush();
            }

            $data["message"] = "Categories updated";
        }


        $response->getBody()->write(json_encode($data));
        return $response;
    }

    /**
     * Show a new category form.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(): Response
    {
        return view('web.admin.categories.create', [
            'category' => ['name']
        ]);
    }

    /**
     * Save a new Category.
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
            $category = CategoryFactory::create($params['name'], $user);

            $flash->add('success', 'Category created');

            return $response->withStatus(302)->withHeader(
                'Location',
                '/admin/category/' . $category->getId(),
            );
        }

        $flash->add('error', 'Error creating Category');

        return view('web.admin.categories.create', [
            'category' => ['name'],
        ]);
    }

    /**
     * Delete a category.
     *
     * @param \App\Model\Category                 $category The Category Object,
     * @param \Psr\Http\Message\ResponseInterface $response The response object.
     *
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(Category $category, Response $response): Response
    {
        $flash = Session::getFlash();

        /**
         * @var \App\Repository\AsciiRepository $repository ;
         */
        $repository = $this->em->getRepository(Ascii::class);
        $repository->deleteLinkedCategoriesWithId($category->getId());

        $this->em->remove($category);
        $this->em->flush();

        $flash->add('success', 'Category successfully deleted');

        return $response->withStatus(302)->withHeader(
            'Location',
            app()->getRouteCollector()->getRouteParser()->urlFor('admin.categories.index')
        );
    }
}