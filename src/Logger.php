<?php

namespace AxelKummer\LogBook;

use AxelKummer\LogBook\Model\LogEntry;
use AxelKummer\LogBook\Request\AbstractRequest;
use Psr\Log\AbstractLogger;

/**
 * LogBook PHP Logger.
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class Logger extends AbstractLogger
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
