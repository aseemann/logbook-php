<?php

namespace AxelKummer\LogBook;

use AxelKummer\LogBook\Model\LogEntry;
use AxelKummer\LogBook\Request\AbstractRequest;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * LogBook PHP Logger.
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class Logger implements LoggerInterface
{
    /**
     * Name of the logger.
     *
     * @var string
     */
    private $loggerName;

    /**
     * Request object
     *
     * @var AbstractRequest
     */
    private $request;

    /**
     * Logger constructor.
     *
     * @param string          $name    Name of the logger should have (e.g. the class name)
     * @param AbstractRequest $request Request object
     */
    public function __construct($name, AbstractRequest $request = null)
    {
        $this->loggerName = $name;
        $this->request = $request;
    }

    /**
     * Set the request object
     *
     * @param AbstractRequest $request
     *
     * @return void
     */
    public function setRequest(AbstractRequest $request)
    {
        $this->request = $request;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @throws Exception if no request object was passed
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if (false === $this->request instanceof AbstractRequest) {
            throw new Exception(
                "Please configure a request object by use the __construct() or setRequest() methods"
            );
        }

        $logEntry = new LogEntry($this->loggerName, $level, $message, $context);

        $this->request->sendLog($logEntry);
    }
}
