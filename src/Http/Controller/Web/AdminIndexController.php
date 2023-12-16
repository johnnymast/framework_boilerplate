<?php

namespace App\Http\Controller\Web;

use Psr\Http\Message\ResponseInterface as Response;
use App\Http\Controller\Controller;

class AdminIndexController extends Controller
{
    /**
     * Redirect to the tag list as default page for the admin.
     *
     * @param \Psr\Http\Message\ResponseInterface $response The response Object.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Response $response): Response
    {
        return $response->withHeader(
            'Location',
            app()->getRouteCollector()->getRouteParser()->urlFor('admin.tags.index')
        )->withStatus(302);
    }
}