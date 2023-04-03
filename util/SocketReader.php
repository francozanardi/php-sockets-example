<?php

namespace util;

use Socket;
use Exception;

class SocketReader {

    const READER_BUFFER_SIZE = 1024;

    function read(Socket $socket): string {
        $buffer = socket_read($socket, self::READER_BUFFER_SIZE);
        if ($buffer === false) {
            throw new Exception('Error reading socket: ' . socket_strerror(socket_last_error()));
        }
        return $buffer;
    }
}