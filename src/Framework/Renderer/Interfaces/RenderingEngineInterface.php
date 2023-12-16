<?php

namespace App\Framework\Renderer\Interfaces;

interface RenderingEngineInterface
{

    /**
     * Return the rendering engine.
     *
     * @return mixed
     */
    public function getRenderer(): mixed;

    /**
     * Add a path to load views from.
     *
     * @param string $path The view path to add.
     *
     * @return void
     */
    public function addViewPath(string $path): void;

    /**
     * @param string               $view       The view.
     * @param array<string, mixed> $data       The data a controller can pass to a view.
     * @param array<string, mixed> $sharedData The shared data passed by the framework.
     *
     * @return string
     */
    public function build(string $view, array $data = [], array $sharedData = []): string;
}