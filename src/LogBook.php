<?php

namespace AxelKummer\LogBook;

use AxelKummer\LogBook\Request\AbstractRequest;
use AxelKummer\LogBook\Request\HttpRequest;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class LogBook
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Alexander Gunkel <alexandergunkel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class LogBook
{
    /**
     * @var AbstractRequest
     */
    private $request;

    /**
     * @var LoggerInterface[]
     */
    private $loggers = array();

    /**
     * LogBook constructor.
     *
     * @param AbstractRequest $request
     */
    private function __construct(AbstractRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get a new LogBook instance
     *
     * @param string $appIdentifier
     * @param string $host
     * @param int    $port
     *
     * @return LogBook
     */
    final public static function newLogBook($appIdentifier = '', $host = 'localhost', $port = null)
    {
        return new LogBook(
            new HttpRequest(
                $appIdentifier,
                $host,
                $port
            )
        );
    }

    /**
     * Build a LogBook based on a request object
     *
     * @param AbstractRequest $request given request object
     *
     * @return LogBook
     */
    final public static function buildLogBookFromRequest(AbstractRequest $request)
    {
        return new LogBook($request);
    }

    /***
     * Get a logger
     *
     * @param string $loggerName
     *
     * @return LoggerInterface
     */
    public function getLogger($loggerName)
    {
        if (!isset($_COOKIE[AbstractRequest::COOKIE_NAME])) {
            return new NullLogger();
        }

        if (array_key_exists($loggerName, $this->loggers)) {
            return $this->loggers[$loggerName];
        }

        return $this->loggers[$loggerName] = new Logger($loggerName, $this->request);
    }
}