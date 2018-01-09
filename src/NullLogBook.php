<?php

namespace AxelKummer\LogBook;

use Psr\Log\NullLogger;

class NullLogBook implements LogBookInterface
{
    /**
     * @var NullLogger
     */
    private $nullLogger;

    /**
     * NullLogBook constructor.
     */
    public function __construct()
    {
        $this->nullLogger = new NullLogger();
    }

    /**
     * Get the logger
     *
     * @param string $loggerName
     *
     * @return NullLogger
     */
    public function getLogger($loggerName)
    {
        return $this->nullLogger;
    }
}