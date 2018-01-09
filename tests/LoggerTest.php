<?php

namespace AxelKummer\LogBook\Tests;

use AxelKummer\LogBook\Exception;
use AxelKummer\LogBook\LogBook;
use AxelKummer\LogBook\Logger;
use AxelKummer\LogBook\LoggerUtility;
use AxelKummer\LogBook\Model\LogEntry;
use AxelKummer\LogBook\Request\AbstractRequest;
use AxelKummer\LogBook\Request\HttpRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * Tests for the logger.
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class LoggerTest extends TestCase
{
    /**
     * Test that a logger calls the log method
     *
     * @return void
     */
    public function testLogger()
    {
        $testMessage = "Test log message";

        $request = $this->getMockForAbstractClass(
            AbstractRequest::class,
            ['APP','localcost']
        );

        /**
         * @var \PHPUnit_Framework_MockObject_MockObject|Logger $logger
         */
        $logger = $this->getMockBuilder(Logger::class)
            ->setConstructorArgs([[__CLASS__], $request])
            ->setMethods(['log'])
            ->getMock();

        $logger->expects($this->at(0))
            ->method('log')
            ->with(LogLevel::EMERGENCY, $testMessage);

        $logger->expects($this->at(1))
               ->method('log')
               ->with(LogLevel::ALERT, $testMessage);

        $logger->expects($this->at(2))
               ->method('log')
               ->with(LogLevel::CRITICAL, $testMessage);

        $logger->expects($this->at(3))
               ->method('log')
               ->with(LogLevel::ERROR, $testMessage);

        $logger->expects($this->at(4))
               ->method('log')
               ->with(LogLevel::WARNING, $testMessage);

        $logger->expects($this->at(5))
               ->method('log')
               ->with(LogLevel::NOTICE, $testMessage);

        $logger->expects($this->at(6))
               ->method('log')
               ->with(LogLevel::INFO, $testMessage);

        $logger->expects($this->at(7))
               ->method('log')
               ->with(LogLevel::DEBUG, $testMessage);

        $logger->emergency($testMessage);
        $logger->alert($testMessage);
        $logger->critical($testMessage);
        $logger->error($testMessage);
        $logger->warning($testMessage);
        $logger->notice($testMessage);
        $logger->info($testMessage);
        $logger->debug($testMessage);
    }

    /**
     * Test that log calls sendLog of the request object
     *
     * @return void
     */
    public function testLog()
    {
        $_COOKIE[AbstractRequest::COOKIE_NAME] = "test-logbook-identifier";
        /**
         * @var AbstractRequest|\PHPUnit_Framework_MockObject_MockObject $request
         */
        $request = $this->getMockBuilder(HttpRequest::class)
            ->setConstructorArgs(['test', 'localhost', 9999])
            ->setMethods(['sendLog'])
            ->getMock();

        $logBook = LogBook::buildLogBookFromRequest($request);
        $logger = $logBook->getLogger('testSendLogs');

        $logEntry1 = new LogEntry(
            'testSendLogs',
            LogLevel::INFO,
            'test log'
        );

        $request->expects($this->at(0))
            ->method('sendLog')
            ->with($logEntry1);

        $logEntry2 = new LogEntry(
            'testSendLogs',
            LogLevel::ERROR,
            'test log 2'
        );

        $request->expects($this->at(1))
                ->method('sendLog')
                ->with($logEntry2);

        $logEntry3 = new LogEntry(
            'testSendLogs',
            LogLevel::WARNING,
            'test log 3'
        );

        $request->expects($this->at(2))
                ->method('sendLog')
                ->with($logEntry3);


        $logger->info('test log');
        $logger->error('test log 2');
        $logger->warning('test log 3');
    }


    /**
     * Test invalid logger config
     *
     * @expectedException Exception
     * @expectedExceptionMessage Please configure a request object by use the __construct() or setRequest() methods
     *
     * @return void
     */
    public function testInvalidLogger()
    {
        $logger = new Logger('Test', null);

        $logger->info('test');
    }
}