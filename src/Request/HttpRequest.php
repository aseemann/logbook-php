<?php

namespace AxelKummer\LogBook\Request;

use AxelKummer\LogBook\Model\LogEntry;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Send the logs via the http protocol. This class uses guzzlehttp.
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class HttpRequest extends AbstractRequest
{
    /**
     * Send a given logentry to the
     *
     * @param LogEntry $logEntry
     *
     * @return void
     */
    public function sendLog(LogEntry $logEntry)
    {
        if (false === $this->hasCookie()) {
            return;
        }

        $client = new Client();

        $client->postAsync(
            $this->getUrl(),
            [
                RequestOptions::TIMEOUT => 1,
                RequestOptions::ALLOW_REDIRECTS => false,
                RequestOptions::HEADERS => $this->getHeaders($logEntry),
                RequestOptions::JSON => (string) $logEntry
            ]
        );
    }

    /**
     * Returns the array with request headers
     *
     * @param LogEntry $logEntry
     *
     * @return array
     */
    private function getHeaders(LogEntry $logEntry)
    {
        return [
            'LogBook-Logger-Name'    => $logEntry->getLoggerName(),
            'LogBook-Request-Uri'    => filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL),
            'LogBook-App-Identifier' => $this->appIdentifier
        ];
    }

    /**
     * Returns the request url
     *
     * @return string
     */
    public function getUrl()
    {
        return "http://$this->host" . ($this->port ? ":$this->port" : "") . '/logbook/' .$this->getLogBookId() . '/logs';
    }
}