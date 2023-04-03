<?php

namespace util;

require_once(realpath(dirname(__FILE__) . '/../logger/LoggerService.php'));
require_once('SocketReader.php');
require_once('SocketWriter.php');

use logger\LoggerService;
use Socket;

abstract class LocalServer {

    const LOCAL_HOST_IP = '127.0.0.1';

    protected LoggerService $loggerService;
    protected SocketReader $socketReader;
    protected SocketWriter $socketWriter;
    private Socket $socket;

    function __construct() {
        $this->loggerService = LoggerService::getInstance();
        $this->socketReader = new SocketReader();
        $this->socketWriter = new SocketWriter();
    }

    function __destruct() {
        socket_close($this->socket);
    }

    abstract protected function onMessageReceived(Socket $connectionSocket, string $buffer): void;

    abstract protected function getServerPort(): int;

    abstract protected function getServerName(): string;

    function run(): void {
        $this->socket = $this->setUpSocket();
        $this->listenConnections($this->socket);
    }

    private function setUpSocket(): Socket {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            $this->loggerService->log('Creating socket error: ' . socket_strerror(socket_last_error()));
            die();
        }
        if (!socket_bind($socket, self::LOCAL_HOST_IP, $this->getServerPort())) {
            $this->loggerService->log('Biding socket error: ' . socket_strerror(socket_last_error()));
            die();
        }
        if (!socket_listen($socket)) {
            $this->loggerService->log('Error when trying to listen for connections: ' . socket_strerror(socket_last_error()));
            die();
        }
        $this->loggerService->log('Server ' . $this->getServerName() . ' initated, listening on port ' . $this->getServerPort());
        return $socket;
    }

    private function listenConnections($socket): void {
        while (true) {
            $connectionSocket = socket_accept($socket);
            if (!$connectionSocket) {
                $this->loggerService->log('Error accepting connection: ' . socket_strerror(socket_last_error()));
                continue;
            }
            $this->loggerService->log('New connection with client');
            $buffer = $this->socketReader->read($connectionSocket);
            $this->onMessageReceived($connectionSocket, $buffer);
            socket_close($connectionSocket);
        }
    }
}