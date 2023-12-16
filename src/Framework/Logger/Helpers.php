<?php


use App\Framework\Logger\Interfaces\LoggerInterface;


/**
 * Log a info message.
 *
 * @param string        $message The message to log.
 * @param array<string> $context Some extra information.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return void
 */
function log_info(string $message, array $context = []): void
{
    /***
     * @var \Monolog\Logger $logger
     */
    $logger = app()->resolve(LoggerInterface::class);
    $logger->info($message, $context);
}

/**
 * Log a debug message.
 *
 * @param string        $message The message to log.
 * @param array<string> $context Some extra information.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return void
 */
function log_debug(string $message, array $context = []): void
{
    /***
     * @var \Monolog\Logger $logger
     */
    $logger = app()->resolve(LoggerInterface::class);
    $logger->debug($message, $context);
}

/**
 * Log a warning message.
 *
 * @param string        $message The message to log.
 * @param array<string> $context Some extra information.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return void
 */
function log_warn(string $message, array $context = []): void
{
    /***
     * @var \Monolog\Logger $logger
     */
    $logger = app()->resolve(LoggerInterface::class);
    $logger->warning($message, $context);
}

/**
 * Log a error message.
 *
 * @param string        $message The message to log.
 * @param array<string> $context Some extra information.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return void
 */
function log_error(string $message, array $context = []): void
{
    /***
     * @var \Monolog\Logger $logger
     */
    $logger = app()->resolve(LoggerInterface::class);
    $logger->error($message, $context);
}

/**
 * Log a emergency message.
 *
 * @param string        $message The message to log.
 * @param array<string> $context Some extra information.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return void
 */
function log_emergency(string $message, array $context = []): void
{
    /***
     * @var \Monolog\Logger $logger
     */
    $logger = app()->resolve(LoggerInterface::class);
    $logger->emergency($message, $context);
}

/**
 * Log a critical message.
 *
 * @param string        $message The message to log.
 * @param array<string> $context Some extra information.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return void
 */
function log_critical(string $message, array $context = []): void
{
    /***
     * @var \Monolog\Logger $logger
     */
    $logger = app()->resolve(LoggerInterface::class);
    $logger->critical($message, $context);
}