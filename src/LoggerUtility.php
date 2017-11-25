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
     * @var AbstractRequest[]
     */
    private static $requestInstances = [];

    /**
     * Array with logger instances
     *
     * @var Logger[]
     */
    private static $logger = [];

    /**
     * @param string  $className
     * @param string  $appIdentifier
     * @param string  $host
     * @param integer $port
     *
     * @return AbstractRequest
     * @throws Exception
     */
    public static function makeRequestInstance($className, $appIdentifier, $host, $port = null)
    {
        $hash = md5($className.$appIdentifier.$host.$port);

        if (array_key_exists($hash, self::$requestInstances)) {
            return self::$requestInstances[$hash];
        }

        self::$requestInstances[$hash] = new $className($appIdentifier, $host, $port);

        if (false === self::$requestInstances[$hash] instanceof AbstractRequest) {
            unset(self::$requestInstances[$hash]);
            throw new Exception(
                'The request object have to extend : ' . AbstractRequest::class
            );
        }

        return self::$requestInstances[$hash];
    }

    /**
     * Returns a logger instance
     *
     * @param                 $name
     * @param AbstractRequest $request
     *
     * @return Logger
     */
    public static function getLogger($name, AbstractRequest $request)
    {
        if (array_key_exists($name, self::$logger)) {
            return self::$logger[$name];
        }

        self::$logger[$name] = new Logger($name, $request);
        return self::$logger[$name];
    }

}
