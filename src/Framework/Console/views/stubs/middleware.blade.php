{!! '<' !!}?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class {{$name}} implements MiddlewareInterface
{
{{"\t"}}
{{"\t"}}/**
{{"\t"}}* Handle the middleware.
{{"\t"}}*
{{"\t"}}* @param \Psr\Http\Message\ServerRequestInterface $request
{{"\t"}}* @param \Psr\Http\Server\RequestHandlerInterface $handler
{{"\t"}}*
{{"\t"}}* @return \Psr\Http\Message\ResponseInterface
{{"\t"}}*/
{{"\t"}}public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
{{"\t"}}{
{{"\t"}}{{"\t"}}$response = $handler->handle($request);
{{"\t"}}{{"\t"}}$response->getBody()->write('Hello world');
{{"\t"}}{{"\t"}}return $response;
{{"\t"}}}
}
{{"\n"}}