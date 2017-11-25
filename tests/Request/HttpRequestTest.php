<?php

namespace AxelKummer\LogBook\Tests\Request;

use AxelKummer\LogBook\LoggerUtility;
use AxelKummer\LogBook\Model\LogEntry;
use AxelKummer\LogBook\Request\AbstractRequest;
use AxelKummer\LogBook\Request\HttpRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * Class HttpRequestTest
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class HttpRequestTest extends TestCase
{
    private $hostName = "localhost";
    private $port     = 58000;
    private $output   = "";

    public function setUp()
    {
        $path = realpath(__DIR__ . '/../stub/server');

        //$this->output = shell_exec("php -S {$this->hostName}:{$this->port} -t $path");

        parent::setUp();
    }

    public function testSendLog()
    {
        $request = LoggerUtility::makeRequestInstance(HttpRequest::class, "Test", $this->hostName, $this->port);

        $entry = new LogEntry('TestLogger',LogLevel::INFO,'Test log entry',['array' => 1]);

        $request->sendLog($entry);

        $_COOKIE[AbstractRequest::COOKIE_NAME] = "1234";
        $_SERVER['REQUEST_URI'] = "test.html";

        $request->sendLog($entry);
    }
}
