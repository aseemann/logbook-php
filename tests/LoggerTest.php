<?php

namespace AxelKummer\LogBook\Tests;

use AxelKummer\LogBook\Logger;
use AxelKummer\LogBook\Request\AbstractRequest;
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
}