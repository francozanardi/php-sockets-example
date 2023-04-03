<?php

namespace server\message;

require_once(realpath(dirname(__FILE__) . '/../../util/LocalServer.php'));
require_once(realpath(dirname(__FILE__) . '/../../util/Repository.php'));
require_once('MessageRepository.php');

use util\LocalServer;
use util\Repository;
use Socket;

class MessageServer extends LocalServer {

    const PORT = 9002;

    private Repository $repository;

    function __construct() {
        parent::__construct();
        $this->repository = new MessageRepository();
    }
    
    protected function getServerName(): string {
        return 'MessageServer';
    }

    protected function getServerPort(): int {
        return self::PORT;
    }

    protected function onMessageReceived(Socket $connectionSocket, string $buffer): void {
        $messages = $this->repository->getAll($buffer);
        $response = json_encode($messages);
        $this->socketWriter->write($connectionSocket, $response);
    }
}

$server = new MessageServer();
$server->run();