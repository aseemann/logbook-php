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
    const COOKIE_NAME = 'LOGBOOK';

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
     * @return array
     */
    protected function getLogBookId()
    {
        if (false === $this->hasCookie()) {
            return [];
        }

        return $_COOKIE[self::COOKIE_NAME];
    }

    /**
     * Send the logEntry to the LogBook server
     *
     * @param LogEntry $logEntry LogEntry to ship
     *
     * @return void
     */
    abstract public function sendLog(LogEntry $logEntry);

    /**
     * Returns the request url
     *
     * @return string
     */
    abstract public function getUrl();
}