<?php

namespace logger;

interface Logger {
    
    function log(string $message): void;
}