<?php


/**
 * Output the csrf token.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return void
 */
function csrf_meta(): void
{
    $csrf = app()->resolve('csrf');
    $token = $csrf->getTokenValue();

    echo "<meta content=\"{$token}\" name=\"csrf-token\" />";
}

/**
 * Echo the csrf token hidden input.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return void
 */
function csrf(): void
{
    $csrf = app()->resolve('csrf');
    $token = $csrf->getTokenValue();

    echo "<input type=\"hidden\" name=\"_token\" value=\"{$token}\" />";
}


/**
 * Return the current csrf token value.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return string
 */
function csrf_token(): string
{
    $csrf = app()->resolve('csrf');
    return $csrf->getTokenValue();
}