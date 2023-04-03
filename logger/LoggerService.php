<?php

namespace logger;

require_once('EchoLogger.php');

class LoggerService {

    private static $instance;
    private Logger $logger;

    private function __construct() {
        $this->logger = new EchoLogger();
    }

    static function getInstance(): LoggerService {
        if (!self::$instance) {
            self::$instance = new LoggerService();
        }
        return self::$instance;
    }

    function log(string $message): void {
        $this->logger->log($message);
    }
}