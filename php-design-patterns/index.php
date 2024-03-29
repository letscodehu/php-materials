<?php

interface LoggerInterface
{
    public function info($message);
    public function warn($message);
    public function error($message);
    public function debug($message);
}

class MonologLogger implements LoggerInterface
{
    public function info($message)
    {
        echo '[INFO] '. $message.PHP_EOL;
    }
    public function warn($message)
    {
        echo '[WARN] '. $message.PHP_EOL;
    }
    public function error($message)
    {
        echo '[ERROR] '. $message.PHP_EOL;
    }
    public function debug($message)
    {
        echo '[DEBUG] '. $message.PHP_EOL;
    }
}
class CheckoutController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function checkout($userId)
    {
        $this->logger->info('User '. $userId. "# visited checkout page.");
    }
}

interface Log4PHPInterface
{
    public function log($level, $message);
}

class ApacheLogger implements Log4PHPInterface
{
    public function log($level, $message)
    {
        echo '['.$level.'] '. $message.PHP_EOL;
    }
}

class MonologLoggerAdapter implements LoggerInterface
{
    private $logger;

    public function __construct(Log4PHPInterface $logger)
    {
        $this->logger = $logger;
    }

    public function info($message)
    {
        $this->logger->log('INFO', $message);
    }
    public function warn($message)
    {
        $this->logger->log('WARN', $message);
    }
    public function error($message)
    {
        $this->logger->log('ERROR', $message);
    }
    public function debug($message)
    {
        $this->logger->log('DEBUG', $message);
    }
}

$controller = new CheckoutController(new MonologLoggerAdapter(new ApacheLogger()));

$controller->checkout(5);
