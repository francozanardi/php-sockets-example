<?php

namespace server\message;

require_once(realpath(dirname(__FILE__) . '/../../util/Repository.php'));

use util\Repository;

class MessageRepository implements Repository {

    private array $messages = [
        [
            'sender' => 'Pedro',
            'receiver' => 'Juan',
            'message' => 'Hola'
        ],
        [
            'sender' => 'Pedro',
            'receiver' => 'Juan',
            'message' => '¿Cómo va?'
        ],
        [
            'sender' => 'Juan',
            'receiver' => 'Pedro',
            'message' => 'Hola'
        ],
        [
            'sender' => 'Juan',
            'receiver' => 'Pedro',
            'message' => 'Todo bien'
        ]
    ];

    function exists(string $key): bool {
        foreach ($this->messages as $message) {
            if ($this->isMessageRelatedToKey($message, $key)) {
                return true;
            }
        }
        return false;
    }

    function getAll(string $key): array {
        return array_filter($this->messages, fn($message) => $this->isMessageRelatedToKey($message, $key));
    }

    private function isMessageRelatedToKey(array $message, string $key): bool {
        return $message['sender'] === $key || $message['receiver'] === $key;
    }
}