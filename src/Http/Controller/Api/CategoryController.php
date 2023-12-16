<?php

namespace App\Http\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Http\Controller\Controller;
use App\Model\Category;

class CategoryController extends Controller
{

    /**
     * Return a list of categories.
     *
     * @param \Slim\Psr7\Request                  $request  The request Object.
     * @param \Psr\Http\Message\ResponseInterface $response The response object.
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Request $request, Response $response): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $user = $request->getAttribute('user');
        $categories = $this->em->getRepository(Category::class)
            ->findBy(['user' => $user], ['weight' => 'ASC']);


        $response->getBody()->write(json_encode($categories));
        return $response->withHeader('Content-type', 'application/json');
    }
}