<?php

namespace AxelKummer\LogBook\Tests\Model;

use AxelKummer\LogBook\Model\LogEntry;
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
    public function testModel()
    {
        $logEntry = new LogEntry(
            __CLASS__,
            LogLevel::NOTICE,
            "LogEntry Test",
            $context = ["debug" => ['test' => 1, "test2" => 2]]
        );

        $this->assertSame('AxelKummer.LogBook.Tests.Model.LogEntryTest', $logEntry->getLogName());
        $this->assertSame(LogLevel::NOTICE, $logEntry->getLogLevel());
        $this->assertSame("LogEntry Test", $logEntry->getLogMessage());
        $this->assertSame($context, $logEntry->getLogContext());

        $strExpected = "{\"logName\":\"AxelKummer.LogBook.Tests.Model.LogEntryTest\",\"logLevel\":\"notice\",\"logMessage\":\"LogEntry Test\",\"logContext\":{\"debug\":{\"test\":1,\"test2\":2}}}";
        $strCurrent  = (string) $logEntry;

        $this->assertSame($strExpected, $strCurrent);
    }
}