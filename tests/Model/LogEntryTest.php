<?php

namespace AxelKummer\LogBook\Tests\Model;

use AxelKummer\LogBook\Model\LogEntry;
use AxelKummer\LogBook\Request\HttpRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * Test for the logBook entry
 *
 * @category Library
 * @package  axel-kummer/logbook-php
 * @author   Axel Kummer <kummeraxel@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @link     https://github.com/axel-kummer/logbook-php
 */
class LogEntryTest extends TestCase
{
    /**
     * Simple test of the model
     *
     * @return void
     */
    public function testModel()
    {
        $logEntry = new LogEntry(
            __CLASS__,
            LogLevel::NOTICE,
            "LogEntry Test",
            $context = ["debug" => ['test' => 1, "test2" => 2]]
        );

        $this->assertSame('AxelKummer.LogBook.Tests.Model.LogEntryTest', $logEntry->getLoggerName());
        $this->assertSame(LogLevel::NOTICE, $logEntry->getSeverity());
        $this->assertSame("LogEntry Test", $logEntry->getMessage());
        $this->assertSame($context, $logEntry->getContext());

        $strExpected = "{\"timestamp\":".time().",\"message\":\"LogEntry Test\",\"severity\":\"notice\",\"context\":{\"debug\":{\"test\":1,\"test2\":2}}}";
        $strCurrent  = (string) $logEntry;

        $this->assertSame($strExpected, $strCurrent);
    }

    /**
     * Test LogEntry with objects in context
     *
     * @return void
     */
    public function testModelObjContext()
    {
        $obj = new \stdClass();
        $obj->test = 1;
        $obj->test2 = 2;

        $logEntry = new LogEntry(
            __CLASS__,
            LogLevel::NOTICE,
            "LogEntry Test",
            $context = ["debug" => $obj]
        );

        $this->assertSame('AxelKummer.LogBook.Tests.Model.LogEntryTest', $logEntry->getLoggerName());
        $this->assertSame(LogLevel::NOTICE, $logEntry->getSeverity());
        $this->assertSame("LogEntry Test", $logEntry->getMessage());

        $strExpected = "{\"timestamp\":".time().",\"message\":\"LogEntry Test\",\"severity\":\"notice\",\"context\":{\"debug\":{\"objectType\":\"stdClass\",\"test\":1,\"test2\":2}}}";
        $strCurrent  = (string) $logEntry;

        $this->assertSame($strExpected, $strCurrent);


        $obj2 = new HttpRequest('test', 'test', 'test');

        $logEntry = new LogEntry(
            __CLASS__,
            LogLevel::NOTICE,
            "LogEntry Test",
            $context = ["debug" => $obj2]
        );

        $this->assertSame('AxelKummer.LogBook.Tests.Model.LogEntryTest', $logEntry->getLoggerName());
        $this->assertSame(LogLevel::NOTICE, $logEntry->getSeverity());
        $this->assertSame("LogEntry Test", $logEntry->getMessage());

        $strExpected = "{\"timestamp\":".time().",\"message\":\"LogEntry Test\",\"severity\":\"notice\",\"context\":{\"debug\":{\"objectType\":\"AxelKummer\\\\LogBook\\\\Request\\\\HttpRequest\",\"appIdentifier\":\"test\",\"host\":\"test\",\"port\":\"test\"}}}";
        $strCurrent  = (string) $logEntry;

        $this->assertSame($strExpected, $strCurrent);
    }
}