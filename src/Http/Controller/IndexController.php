<?php

namespace App\Http\Controller;

use App\Http\Middleware\Testmiddleware;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController extends Controller
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(Request $request, Response $response): Response
    {
        return view('welcome');
    }
}
