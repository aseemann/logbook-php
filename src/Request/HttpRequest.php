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
class HttpRequest
    extends AbstractRequest
{
    /**
     * Header constants
     */
    const HEADER_PREFIX         = "LogBook-";
    const HEADER_LOGGER_NAME    = self::HEADER_PREFIX . "Logger-Name";
    const HEADER_APP_IDENTIFIER = self::HEADER_PREFIX . "App-Identifier";
    const HEADER_REQUEST_URI    = self::HEADER_PREFIX . "Request-Uri";
    const HEADER_REQUEST_ID     = self::HEADER_PREFIX . "Request-Id";

    /**
     * @var string api root path
     */
    const API_ROOT_PATH         = "/api/v1/logbooks/";

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

        $client = new Client();
        try {
            $client->request(
                'POST',
                $this->getUrl(),
                [
                    RequestOptions::TIMEOUT         => 0.01,
                    RequestOptions::ALLOW_REDIRECTS => false,
                    RequestOptions::HEADERS         => $this->getHeaders($logEntry),
                    RequestOptions::BODY            => (string) $logEntry,
                ]
            );
        } catch (\Exception $exception) {
        }
        unset($client);

        return true;
    }

    /**
     * Returns the request url
     *
     * @return string
     */
    public function getUrl()
    {
        if (false === $this->hasCookie()) {
            return "";
        }

        return "http://$this->host" . ($this->port ? ":$this->port" : "")
            . $this->getApiRootPath()
            . $this->getLogBookId()
            . '/logs';
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
            "Content-Type"              => "application/json",
            self::HEADER_LOGGER_NAME    => $logEntry->getLoggerName(),
            self::HEADER_REQUEST_URI    => filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL),
            self::HEADER_APP_IDENTIFIER => $this->appIdentifier,
            self::HEADER_REQUEST_ID     => $this->getRequestId(),
        ];
    }

    /**
     * Returns the api root path
     *
     * @return string
     */
    private function getApiRootPath()
    {
        return self::API_ROOT_PATH;
    }
}