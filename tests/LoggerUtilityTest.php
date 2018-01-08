<?php

namespace AxelKummer\LogBook\Tests;

use AxelKummer\LogBook\Exception;
use AxelKummer\LogBook\LoggerUtility;
use AxelKummer\LogBook\Request\AbstractRequest;
use AxelKummer\LogBook\Request\HttpRequest;
use AxelKummer\LogBook\Tests\Stub\Request;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Class LoggerUtilityTest
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class LoggerUtilityTest extends TestCase
{

    /**
     * Test makeRequestInstance;
     *
     * @return void
     */
    public function testMakeRequestInstance()
    {
        $request = LoggerUtility::setupRequest(HttpRequest::class, "Test", "localhost");
        $request2 = LoggerUtility::setupRequest(HttpRequest::class, "Test", "localhost");

        $this->assertSame($request, $request2);
    }

    /**
     * Test makeRequestInstance Throws exception if the instance does not extend the abstract request.
     *
     * @expectedException Exception
     * @expectedExceptionMessage The request object must extend AxelKummer\LogBook\Request\AbstractRequest
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testMakeRequestInstanceFails()
    {
       LoggerUtility::setupRequest(Request::class, "Test", "localhost");
    }

    /**
     * test getting a logger instance.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testGetLogger()
    {
        $_COOKIE[HttpRequest::COOKIE_NAME] = "test-logbook-identifier";
        LoggerUtility::setupRequest(HttpRequest::class, "Test", "localhost");

        $logger1 = LoggerUtility::getLogger('Test');
        $logger2 = LoggerUtility::getLogger('Test');
        $logger3 = LoggerUtility::getLogger('Test3');

        $this->assertSame($logger1, $logger2);
        $this->assertNotSame($logger2, $logger3);
    }

    /**
     * If there is no cookie, then we don't need to process any data. Rather
     * we should do nothing with it.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testGetLoggerReturnsNullLoggerIfNoCookieSet()
    {
        if (isset($_COOKIE[AbstractRequest::COOKIE_NAME])) {
            unset($_COOKIE[AbstractRequest::COOKIE_NAME]);
        }

        LoggerUtility::setupRequest(HttpRequest::class, "Test", "localhost");

        $logger1 = LoggerUtility::getLogger('Test');

        $this->assertInstanceOf(NullLogger::class, $logger1);
    }
}
