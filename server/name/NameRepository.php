<?php

namespace server\name;

require_once(realpath(dirname(__FILE__) . '/../../util/Repository.php'));

use util\Repository;

class NameRepository implements Repository {

    private array $names = [
        'Juan',
        'Pedro',
        'José'
    ];

    function exists(string $key): bool {
        return in_array($key, $this->names);
    }

    function getAll(string $key): array {
        return array_filter($this->names, fn($name) => $name === $key);
    }
}