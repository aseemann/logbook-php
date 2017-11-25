<?php

namespace AxelKummer\LogBook\Tests;

use AxelKummer\LogBook\Exception;
use AxelKummer\LogBook\Logger;
use AxelKummer\LogBook\LoggerUtility;
use AxelKummer\LogBook\Request\AbstractRequest;
use AxelKummer\LogBook\Request\HttpRequest;
use AxelKummer\LogBook\Tests\Stub\Request;
use PHPUnit\Framework\TestCase;

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
        $request = LoggerUtility::makeRequestInstance(HttpRequest::class, "Test", "localhost");
        $request2 = LoggerUtility::makeRequestInstance(HttpRequest::class, "Test", "localhost");

        $this->assertSame($request, $request2);
    }

    /**
     * Test makeRequestInstance Throws exception if the instance does not extend the abstract request.
     *
     * @expectedException Exception
     * @expectedExceptionMessage The request object have to extend : AxelKummer\LogBook\Request\AbstractRequest
     *
     * @return void
     */
    public function testMakeRequestInstanceFails()
    {
       $request = LoggerUtility::makeRequestInstance(Request::class, "Test", "localhost");
       var_dump($request);
    }

    /**
     * test getting a logger instance.
     *
     * @return void
     */
    public function testGetLogger()
    {
        /**
         * @var AbstractRequest|\PHPUnit_Framework_MockObject_MockObject $request
         */
        $request = $this->getMockForAbstractClass(
            AbstractRequest::class, ['TestApp', 'localhost']
        );

        $logger1 = LoggerUtility::getLogger('Test', $request);
        $logger2 = LoggerUtility::getLogger('Test', $request);
        $logger3 = LoggerUtility::getLogger('Test3', $request);

        $this->assertSame($logger1, $logger2);
        $this->assertNotSame($logger2, $logger3);
    }
}
