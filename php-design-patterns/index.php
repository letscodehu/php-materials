<?php

interface LoggerInterface {
    function info($message);
    function warn($message);
    function error($message);
    function debug($message);
}

class MonologLogger implements LoggerInterface {
    function info($message) {
        echo '[INFO] '. $message.PHP_EOL;
    }
    function warn($message) {
        echo '[WARN] '. $message.PHP_EOL;
    }
    function error($message) {
        echo '[ERROR] '. $message.PHP_EOL;
    }
    function debug($message) {
        echo '[DEBUG] '. $message.PHP_EOL;
    }
}

class CheckoutController {

    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function checkout($userId) {
        $this->logger->info('User '. $userId. "# visited checkout page.");
    }

}

interface Log4PHPInterface {
    public function log($level, $message);
}

class ApacheLogger implements Log4PHPInterface {
    public function log($level, $message) {
        echo '['.$level.'] '. $message.PHP_EOL;
    }
}

class MonologLoggerAdapter implements LoggerInterface {

    private $logger;

    public function __construct(Log4PHPInterface $logger) {
        $this->logger = $logger;
    }

    function info($message) {
        $this->logger->log('INFO', $message);
    }
    function warn($message) {
        $this->logger->log('WARN', $message);
    }
    function error($message) {
        $this->logger->log('ERROR', $message);
    }
    function debug($message) {
        $this->logger->log('DEBUG', $message);
    }
}

$controller = new CheckoutController(new MonologLoggerAdapter(new ApacheLogger()));

$controller->checkout(5);