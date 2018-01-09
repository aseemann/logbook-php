<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 09.01.18
 * Time: 17:23
 */

namespace AxelKummer\LogBook\Tests;


use AxelKummer\LogBook\LogBook;
use AxelKummer\LogBook\Request\AbstractRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class LogBookTest extends TestCase
{
    public function testGetNewLogBook()
    {
        $logBook = LogBook::newLogBook();

        $this->assertInstanceOf(LogBook::class, $logBook);
    }

    public function testGetLoggerReturnsLogger()
    {
        $logBook = LogBook::newLogBook();

        $this->assertInstanceOf(LoggerInterface::class, $logBook->getLogger('loggerName'));
    }

    public function testGetLoggerReturnsNullLogger()
    {
        if (isset($_COOKIE[AbstractRequest::COOKIE_NAME])) {
            unset($_COOKIE[AbstractRequest::COOKIE_NAME]);
        }

        $logBook = LogBook::newLogBook();

        $this->assertInstanceOf(NullLogger::class, $logBook->getLogger('loggerName'));
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
        $_COOKIE[AbstractRequest::COOKIE_NAME] = "test-logbook-identifier";
        $logBook = LogBook::newLogBook('example-app');

        $logger1 = $logBook->getLogger('name');
        $logger2 = $logBook->getLogger('name');
        $logger3 = $logBook->getLogger('different-name');

        $this->assertSame($logger1, $logger2);
        $this->assertNotSame($logger2, $logger3);

        unset($_COOKIE[AbstractRequest::COOKIE_NAME]);
    }
}