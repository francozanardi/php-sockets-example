<?php

namespace logger;

require_once('Logger.php');

class DummyLogger implements Logger {
    
    function log(string $message): void { }
}