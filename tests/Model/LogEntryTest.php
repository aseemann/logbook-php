<?php

namespace AxelKummer\LogBook\Tests\Model;

use AxelKummer\LogBook\Model\LogEntry;
use AxelKummer\LogBook\Request\HttpRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class dummyMock
{
    public function __toString(): string
    {
        throw new \Exception("asd");
        return "asd";
    }
}

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

        $strExpected = "{\"time\":".time().",\"message\":\"LogEntry Test\",\"severity\":\"notice\",\"context\":{\"debug\":{\"test\":1,\"test2\":2}}}";
        $strCurrent  = (string) $logEntry;

        $this->assertSame($strExpected, $strCurrent);
    }

    /**
     * We want our logger to work in edge cases, e.g. when the context
     * cannot be rendered into valid JSON.
     *
     * @dataProvider erroneousLogEntryProvider
     *
     * @return void
     */
    public function testLoggerCatchesContextErrors(LogEntry $logEntry, string $partOfContent)
    {
        $this->assertContains($partOfContent, (string) $logEntry);
        $this->assertContains("PHP LogBook Error", (string) $logEntry);
    }

    /**
     * Provide test-data for edge cases
     *
     * @return array
     */
    public function erroneousLogEntryProvider()
    {
        return [
            [
                new LogEntry(
                    __CLASS__,
                    LogLevel::ERROR,
                    $message = "Error in context test",
                    ['data' => "\xB1\x31"]
                ),
                $message
            ],
            [
                new LogEntry(
                    __CLASS__,
                    "\xB1\x31",
                    $message = "Error in context test",
                    ['data' => "Test-data"]
                ),
                $message
            ],
            [
                new LogEntry(
                    __CLASS__,
                    LogLevel::ERROR,
                    $message = "\xB1\x31",
                    ['data' => "Test-data"]
                ),
                "Error: Malformed UTF-8 characters, possibly incorrectly encoded"
            ],
        ];
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

        $strExpected = "{\"time\":".time().",\"message\":\"LogEntry Test\",\"severity\":\"notice\",\"context\":{\"debug\":{\"objectType\":\"stdClass\",\"test\":1,\"test2\":2}}}";
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

        $strExpected = "{\"time\":".time().",\"message\":\"LogEntry Test\",\"severity\":\"notice\",\"context\":{\"debug\":{\"objectType\":\"AxelKummer\\\\LogBook\\\\Request\\\\HttpRequest\",\"appIdentifier\":\"test\",\"host\":\"test\",\"port\":\"test\",\"requestId\":null}}}";
        $strCurrent  = (string) $logEntry;

        $this->assertSame($strExpected, $strCurrent);
    }

    /**
     * Test that interpolation works
     *
     * @param string $message
     * @param array $context
     * @param string $expectedResult
     *
     * @dataProvider provideMessagesAndContextForInterpolation
     *
     * @return void
     */
    public function testInterpolationOfContext(string $message, array $context, string $expectedResult)
    {
        $logEntry = new LogEntry(
            __CLASS__,
            LogLevel::DEBUG,
            $message,
            $context
        );

        $this->assertContains($expectedResult, (string) $logEntry);
    }

    public function provideMessagesAndContextForInterpolation()
    {
        return [
            [
                "Variable: {var}",
                ['data' => ['var' => 'test']],
                "Variable: test",
            ],
            [
                "Variable: {var}",
                ['data' => ['{var}' => 'test']],
                "Variable: test",
            ],
            [
                "Variable: <{var}>",
                ['data' => ['{var}' => 'test']],
                "Variable: <test>",
            ],
            [
                "Variable: {var}",
                ['data' => ['var' => 123]],
                "Variable: 123",
            ],
            [
                "Variable: {var}",
                ['data' => ['var' => 0.123]],
                "Variable: 0.123",
            ],
            [
                "Variable: {var}",
                ['data' => ['var' => null]],
                "Variable: NULL",
            ],
            [
                "Variable: {var}",
                ['data' => ['var' => true]],
                "Variable: TRUE",
            ],
            [
                "Variable: {var}",
                ['data' => ['var' => false]],
                "Variable: FALSE",
            ],
            [
                "Variable: {var}",
                ['data' => ['var' => function() { return; }]],
                "Variable: CALLABLE",
            ],
        ];
    }
}