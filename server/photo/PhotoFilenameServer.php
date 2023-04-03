<?php

namespace server\photo;

require_once(realpath(dirname(__FILE__) . '/../../util/LocalServer.php'));
require_once(realpath(dirname(__FILE__) . '/../../util/Repository.php'));
require_once('PhotoFilenameRepository.php');

use util\LocalServer;
use util\Repository;
use Socket;

class PhotoFilenameServer extends LocalServer {

    const PORT = 9001;

    private Repository $repository;

    function __construct() {
        parent::__construct();
        $this->repository = new PhotoFilenameRepository();
    }

    protected function getServerName(): string {
        return 'PhotoFilenameServer';
    }
    
    protected function getServerPort(): int {
        return self::PORT;
    }

    protected function onMessageReceived(Socket $connectionSocket, string $buffer): void {
        $filenames = $this->repository->getAll($buffer);
        $response = json_encode($filenames);
        $this->socketWriter->write($connectionSocket, $response);
    }
}

$server = new PhotoFilenameServer();
$server->run();