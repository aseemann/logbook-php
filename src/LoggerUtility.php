<?php

namespace AxelKummer\LogBook;

use AxelKummer\LogBook\Request\AbstractRequest;

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
     * @var Logger[]
     */
    private static $logger = [];

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
                'The request object have to extend : ' . AbstractRequest::class
            );
        }

        self::$request->setRequestId(self::getRequestId());

        return self::$request;
    }

    /**
     * Returns a unique request identifier
     *
     * @return string
     */
    public static function getRequestId()
    {
        if (empty(static::$requestId)) {
            static::$requestId = uniqid('', true);
        }

        return static::$requestId;
    }

    /**
     * Returns a logger instance
     *
     * @param string $name
     *
     * @return Logger
     */
    public static function getLogger($name)
    {
        if (array_key_exists($name, self::$logger)) {
            return self::$logger[$name];
        }

        self::$logger[$name] = new Logger($name, self::$request);
        return self::$logger[$name];
    }

}
