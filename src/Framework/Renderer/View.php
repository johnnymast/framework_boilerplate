<?php

namespace App\Framework\Renderer;

use Psr\Http\Message\ResponseInterface;

class View
{
    /**
     * Storage for the response object.
     *
     * @var \Psr\Http\Message\ResponseInterface|null
     */
    protected ?ResponseInterface $response = null;

    /**
     * View Constructor.
     *
     * @param string               $view The name of the view.
     * @param array<string, mixed> $data The data for the view.
     */
    public function __construct(
        protected readonly string $view,
        protected readonly array $data = [],

    ) {
        $this->response = app()
            ->getResponseFactory()
            ->createResponse();
    }

    /**
     * Return the name of the view.
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * Return the data of the view.
     *
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Write the view to the Response instance.
     *
     * @param string $content The content for the view.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function render(string $content): ResponseInterface
    {
        $this->response->getBody()->write($content);
        return $this->response;
    }
}