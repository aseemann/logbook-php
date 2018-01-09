<?php

namespace AxelKummer\LogBook;

use AxelKummer\LogBook\Request\AbstractRequest;
use AxelKummer\LogBook\Request\HttpRequest;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class LoggerUtility
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class LoggerUtility
{
    /**
     * Array with request objects
     *
     * @var AbstractRequest
     */
    private static $request;

    /**
     * Array with logger instances
     *
     * @var LogBook
     */
    private static $logBook;

    /**
     * @var string $requestId
     */
    private static $requestId;

    /**
     * @param string  $className
     * @param string  $appIdentifier
     * @param string  $host
     * @param integer $port
     *
     * @return AbstractRequest
     * @throws Exception
     */
    public static function setupRequest($className, $appIdentifier, $host, $port = null)
    {

        if (self::$request instanceof AbstractRequest) {
            return self::$request;
        }

        self::$request = new $className($appIdentifier, $host, $port);
        if (false === self::$request instanceof AbstractRequest) {
            self::$request = null;
            throw new Exception(
                'The request object must extend ' . AbstractRequest::class
            );
        }

        self::$request->setRequestId(self::getRequestId());
        self::$logBook = LogBook::buildLogBookFromRequest(self::$request);

        return self::$request;
    }

    /**
     * Returns a unique request identifier
     *
     * @param string $prefix Identifier prefix
     *
     * @return string
     */
    public static function getRequestId($prefix = '')
    {
        if (empty(static::$requestId)) {
            static::$requestId = md5(uniqid($prefix . rand(), true));
        }

        return static::$requestId;
    }

    /**
     * Returns a logger instance
     *
     * @param string $name
     *
     * @return LoggerInterface
     */
    public static function getLogger($name)
    {
        return self::$logBook->getLogger($name);
    }

}
