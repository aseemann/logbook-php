<?php
namespace AxelKummer\LogBook\Model;

/**
 * LogBook PHP. Model of an logentry which should be sent to the LogBookServer
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class LogEntry
{
    /**
     * Name of logger
     *
     * @var string
     */
    private $logName;

    /**
     * LogLevel
     *
     * @var string
     */
    private $logLevel;

    /**
     * LogMessage
     *
     * @var string
     */
    private $logMessage;

    /**
     * LogContext
     *
     * @var array
     */
    private $logContext;

    /**
     * LogEntry constructor.
     *
     * @param $logLevel
     * @param $logMessage
     * @param $logContext
     */
    public function __construct($logName, $logLevel, $logMessage, array $logContext = [])
    {
        $this->setLogName($logName)
            ->setLogLevel($logLevel)
            ->setLogMessage($logMessage)
            ->setLogContext($logContext);
    }

    /**
     * @return string
     */
    public function getLogName() : string
    {
        return $this->logName;
    }

    /**
     * @param string $logName
     *
     * @return $this
     */
    public function setLogName(string $logName) : LogEntry
    {
        $this->logName = str_replace('\\', '.', $logName);

        return $this;
    }

    /**
     * @return string
     */
    public function getLogLevel() : string
    {
        return $this->logLevel;
    }

    /**
     * @param string $logLevel
     *
     * @return $this
     */
    public function setLogLevel(string $logLevel)
    {
        $this->logLevel = $logLevel;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogMessage() : string
    {
        return $this->logMessage;
    }

    /**
     * @param string $logMessage
     *
     * @return $this
     */
    public function setLogMessage(string $logMessage)
    {
        $this->logMessage = $logMessage;

        return $this;
    }

    /**
     * @return array
     */
    public function getLogContext() : array
    {
        return $this->logContext;
    }

    /**
     * @param array $logContext
     *
     * @return $this
     */
    public function setLogContext(array $logContext)
    {
        $this->logContext = $logContext;

        return $this;
    }

    /**
     * Converts the model to a json encoded string
     *
     * @return string
     */
    public function __toString()
    {
        $logEntry = [];
        foreach ($this as $property => $value) {
            $logEntry[$property] = $value;
        }

        return json_encode($logEntry);
    }
}