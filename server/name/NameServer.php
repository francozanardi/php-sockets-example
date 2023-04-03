<?php

namespace server\name;

require_once(realpath(dirname(__FILE__) . '/../../util/LocalServer.php'));
require_once(realpath(dirname(__FILE__) . '/../../util/Repository.php'));
require_once('NameRepository.php');

use util\Repository;
use util\LocalServer;
use Socket;

class NameServer extends LocalServer {

    const NAME_NOT_FOUND_MESSAGE = 'NOT_FOUND';
    const UNEXPECTED_ERROR_MESSAGE = 'UNEXPECTED_ERROR';
    const PORT = 9000;
    private const MESSAGE_SERVER_IP = '127.0.0.1';
    private const MESSAGE_SERVER_PORT = 9002;
    private const PHOTO_SERVER_IP = '127.0.0.1';
    private const PHOTO_SERVER_PORT = 9001;

    private Repository $repository;
    private Socket $messageServerSocket;
    private Socket $photoServerSocket;

    function __construct() {
        parent::__construct();
        $this->repository = new NameRepository();
    }

    protected function getServerName(): string {
        return 'NameServer';
    }
    
    protected function getServerPort(): int {
        return self::PORT;
    }

    protected function onMessageReceived(Socket $connectionSocket, string $buffer): void {
        if (!$this->repository->exists($buffer)) {
            $this->socketWriter->write($connectionSocket, self::NAME_NOT_FOUND_MESSAGE);
            return;
        }
        $messageServerResponse = $this->sendRequestToMessageServer($buffer);
        $photoServerResponse = $this->sendRequestToPhotoServer($buffer);
        $response = $this->buildServerResponse($messageServerResponse, $photoServerResponse);
        $this->socketWriter->write($connectionSocket, $response);
    }

    private function buildServerResponse(string $serializedMessages, string $serializedPhotoFilenames): string {
        $messages = json_decode($serializedMessages, true);
        $photoFilenames = json_decode($serializedPhotoFilenames, true);
        return json_encode([
            'messages' => $messages,
            'photos' => $photoFilenames
        ]);
    }

    private function sendRequestToMessageServer(string $name): string {
        $messageServerSocket = $this->createSocketAsClient(self::MESSAGE_SERVER_IP, self::MESSAGE_SERVER_PORT);
        $this->socketWriter->write($messageServerSocket, $name);
        return $this->socketReader->read($messageServerSocket);
    }

    private function sendRequestToPhotoServer(string $name): string {
        $photoServer = $this->createSocketAsClient(self::PHOTO_SERVER_IP, self::PHOTO_SERVER_PORT);
        $this->socketWriter->write($photoServer, $name);
        return $this->socketReader->read($photoServer);
    }

    private function createSocketAsClient(string $ip, int $port): Socket {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            $this->loggerService->log('Error creating socket as client: ' . socket_strerror(socket_last_error()));
            die();
        }
        $socketConnected = socket_connect($socket, $ip, $port);
        if (!$socketConnected) {
            $this->loggerService->log('Error connecting socket as client: ' . socket_strerror(socket_last_error()));
            die();
        }
        return $socket;
    }
}

$server = new NameServer();
$server->run();