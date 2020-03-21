<?php

namespace AxelKummer\LogBook\Request;

use AxelKummer\LogBook\Model\LogEntry;

/**
 * Send the logs via the http protocol. This class uses guzzlehttp.
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class FileRequest
    extends AbstractRequest
{

    /**
     * Send a given logentry to the
     *
     * @param LogEntry $logEntry
     *
     * @return bool
     */
    public function sendLog(LogEntry $logEntry)
    {
        if (false === $this->hasCookie()) {
            return false;
        }

        $entry = json_decode((string) $logEntry, true);
        $entry['logger'] = $logEntry->getLoggerName();
        $entry['application'] = $this->appIdentifier;
        $entry['request_uri'] =  filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $entry['request_id'] = $this->getRequestId();

        file_put_contents($this->getUrl(), json_encode($entry) . PHP_EOL,FILE_APPEND);
        return true;
    }

    public function getUrl()
    {
        if (false === $this->hasCookie()) {
            return null;
        }

        return "/tmp/logbook-" . $_COOKIE['logbook'] . ".log";
    }
}