<?php

namespace App\Http\Controller\Web;

use App\Framework\Session\Facade\Session;
use App\Http\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminAccountController extends Controller
{
    /**
     * Show a form to edit a user account
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function edit(): Response
    {
        return view('web.admin.account.edit');
    }

    /**
     * Update an existing Tag.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  The request Object.
     * @param \Psr\Http\Message\ResponseInterface      $response The response object.
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Request $request, Response $response): Response
    {
        $flash = Session::getFlash();
        $user = $request->getAttribute('user');
        $params = $request->getParsedBody();

        if (!empty($params)) {
            $user->setName($params['name']);
            $this->em->persist($user);
            $this->em->flush();

            Session::set('user', $user);

            $flash->add('success', 'Account successfully updated');
        } else {
            $flash->add('error', 'Error updating account');
        }

        return $response->withStatus(302)->withHeader(
            'Location',
            app()->getRouteCollector()
                ->getRouteParser()
                ->urlFor('admin.account.edit')
        );
    }
}
