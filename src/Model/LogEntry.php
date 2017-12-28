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
    private $loggerName;

    /**
     * LogLevel
     *
     * @var string
     */
    private $severity;

    /**
     * LogMessage
     *
     * @var string
     */
    private $message;

    /**
     * LogContext
     *
     * @var array
     */
    private $context;

    /**
     * @var string
     */
    private $time;

    /**
     * LogEntry constructor.
     *
     * @param $severity
     * @param $message
     * @param $context
     */
    public function __construct($loggerName, $severity, $message, array $context = [])
    {
        $this->setLoggerName($loggerName)
            ->setSeverity($severity)
            ->setMessage($message)
            ->setContext($context)
            ->setTime(time());
    }

    /**
     * @return string
     */
    public function getLoggerName()
    {
        return $this->loggerName;
    }

    /**
     * @param string $loggerName
     *
     * @return $this
     */
    public function setLoggerName($loggerName)
    {
        $this->loggerName = str_replace('\\', '.', $loggerName);

        return $this;
    }

    /**
     * @return string
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * @param string $severity
     *
     * @return $this
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param array $context
     *
     * @return $this
     */
    public function setContext(array $context = [])
    {
        foreach ($context as $key => $data) {
            $context[$key] = $this->analyseContext($data);
        }

        $this->context = $context;

        return $this;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     *
     * @return LogEntry
     */
    public function setTime($time)
    {
        $this->time = $time;

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
        $logEntry['time'] = $this->getTime();
        $logEntry['message'] = $this->interpolate($this->getMessage(), $this->getContext());
        $logEntry['severity'] = $this->getSeverity();
        $logEntry['context'] = $this->getContext();

        return json_encode($logEntry);
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message the message with placeholders
     * @param array  $context the context
     *
     * @return string
     */
    private function interpolate($message, array $context = array())
    {
        if (!array_key_exists('data', $context)) {
            return $message;
        }

        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context['data'] as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                // check if the key already contains the braces
                $key = substr($key, 0, 1) === '{' ? $key : '{' . $key . '}';
                $replace[$key] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * Analyse context to get private and variables of debugged object
     *
     * @param mixed $context
     *
     * @return array
     */
    private function analyseContext($context)
    {
        if (is_object($context)) {
            $className = get_class($context);
            $obj = ['objectType' => $className];

            if ($className === "stdClass") {
                return $obj += (array) $context;
            }

            $reflection = new \ReflectionClass($className);
            foreach ($reflection->getProperties() as $property) {
                $property->setAccessible(true);
                $obj[$property->getName()] = $this->analyseContext($property->getValue($context));
            }

            return $obj;
        }

        return $context;
    }
}