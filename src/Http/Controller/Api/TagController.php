<?php

namespace App\Http\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Http\Controller\Controller;
use App\Model\Tag;

class TagController extends Controller
{
    /**
     * Return a list of tags.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Request $request, Response $response): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $user = $request->getAttribute('user');
        $tags = $this->em->getRepository(Tag::class)
            ->findBy(['user' => $user]);


        $response->getBody()->write(json_encode($tags));
        return $response->withHeader('Content-type', 'application/json');
    }
}