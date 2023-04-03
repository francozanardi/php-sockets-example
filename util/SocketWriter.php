<?php

namespace util;

use Socket;
use Exception;

class SocketWriter {

    function write(Socket $socket, string $data): void {
        $result = socket_write($socket, $data, strlen($data));
        if ($result === false) {
            throw new Exception('Error writing in socket: ' . socket_strerror(socket_last_error()));
        }
    }
}