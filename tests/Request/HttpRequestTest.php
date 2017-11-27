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
    /**
     * Test send http request
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testSendLog()
    {
        $request = LoggerUtility::setupRequest(HttpRequest::class, "Test", 'localhost', 9999);

        $entry = new LogEntry('TestLogger',LogLevel::INFO,'Test log entry',['array' => 1]);

        $this->assertEmpty($request->getUrl());
        $this->assertFalse($request->sendLog($entry));

        $_COOKIE[AbstractRequest::COOKIE_NAME] = "1234";

        $_SERVER['REQUEST_URI'] = "test.html";

        $this->assertSame('http://localhost:9999/logbook/1234/logs', $request->getUrl());

        $this->assertTrue($request->sendLog($entry));
    }
}
