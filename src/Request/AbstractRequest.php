<?php

namespace AxelKummer\LogBook\Request;

use AxelKummer\LogBook\Model\LogEntry;

/**
 * Abstract request class.
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
abstract class AbstractRequest
{
    /**
     * Name of logbook session cookie
     */
    const COOKIE_NAME = 'logbook';

    /**
     * @var string $appIdentifier
     */
    protected $appIdentifier;

    /**
     * @var string LogBook hostname
     */
    protected $host;

    /**
     * @var integer LogBook port
     */
    protected $port;

    /**
     * @var string request identifier
     */
    protected $requestId;

    /**
     * AbstractRequest constructor.
     *
     * @param string $appIdentifier AppIdentifier
     * @param string $host          LogBook Host
     * @param integer $port         LooBook Port
     */
    public function __construct($appIdentifier, $host, $port = null)
    {
        $this->appIdentifier = $appIdentifier;
        $this->host          = $host;
        $this->port          = $port;
    }

    /**
     * Returns true if the cookie is set
     *
     * @return bool
     */
    protected function hasCookie()
    {
        return isset($_COOKIE[self::COOKIE_NAME]);
    }

    /**
     * Returns the cookie data
     *
     * @return string
     */
    protected function getLogBookId()
    {
        return (string) $_COOKIE[self::COOKIE_NAME];
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param string $requestId
     *
     * @return AbstractRequest
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;

        return $this;
    }

    /**
     * Send the logEntry to the LogBook server
     *
     * @param LogEntry $logEntry LogEntry to ship
     *
     * @return bool
     */
    abstract public function sendLog(LogEntry $logEntry);

    /**
     * Returns the request url
     *
     * @return string
     */
    abstract public function getUrl();
}