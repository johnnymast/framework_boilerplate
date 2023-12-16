<?php

use App\Framework\Facade\Config;

/**
 * @param string $key
 *
 * @return mixed
 */
function config(string $key): mixed
{
    return Config::load($key);
}

/**
 * @param string $path
 *
 * @return string
 */
function url(string $path = ''): string
{
    $url = $_ENV['APP_URL'];
    if (!empty($path)) {
        $url = trim($url, '/') . '/' . $path;
    }
    return $url;
}

/**
 * @param string $path
 *
 * @return string
 */
function project_path(string $path = ''): string
{
    return realpath(PROJECT_PATH . '/' . $path);
}


/**
 * @param string $path
 *
 * @return string
 */
function source_path(string $path = ''): string
{
    if (empty($path)) {
        return realpath(PROJECT_PATH . '/src');
    }

    return realpath(PROJECT_PATH . '/src') . DIRECTORY_SEPARATOR . $path;
}


/**
 * @param string $file
 *
 * @return mixed
 */
function route_path(string $file): mixed
{
    if (empty($file)) {
        return realpath(PROJECT_PATH . '/routes/');
    }

    return realpath(PROJECT_PATH . '/routes/') . DIRECTORY_SEPARATOR . $file;
}

/**
 * @param string $path
 *
 * @return string
 */
function config_path(string $path = ''): string
{
    return realpath(PROJECT_PATH . '/config/' . $path);
}

/**
 * @param string $path
 *
 * @return string
 */
function view_path(string $path = ''): string
{
    if (empty($path)) {
        return realpath(PROJECT_PATH . '/resources/views');
    }

    return realpath(PROJECT_PATH . '/resources/views') . DIRECTORY_SEPARATOR . $path;
}

/**
 * @param string $path
 *
 * @return string
 */
function http_path(string $path = ''): string
{
    if (empty($path)) {
        return realpath(PROJECT_PATH . '/src/Http/');
    }

    return realpath(PROJECT_PATH . '/src/Http/') . DIRECTORY_SEPARATOR . $path;
}

/**
 * @param string $path
 *
 * @return string
 */
function cache_path(string $path = ''): string
{
    return realpath(PROJECT_PATH . '/var/cache/' . $path);
}

/**
 * @param string $path
 *
 * @return string
 */
function log_path(string $path = ''): string
{
    return realpath(PROJECT_PATH . '/var/logs/') . DIRECTORY_SEPARATOR . $path;
}
