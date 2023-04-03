<?php

namespace logger;

require_once('Logger.php');

class EchoLogger implements Logger {

    function log(string $message): void {
        echo $message . PHP_EOL;
    }
}