<?php

namespace App\Http\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Doctrine\ORM\EntityManager;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Routing\RouteContext;

class Controller
{

    /**
     * Controller constructor
     *
     * @param \Doctrine\ORM\EntityManager $em Injected EntityManager.
     */
    public function __construct(
        readonly protected EntityManager $em
    ) {
    }
}
