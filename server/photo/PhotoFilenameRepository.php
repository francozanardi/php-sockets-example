<?php

namespace server\photo;

require_once(realpath(dirname(__FILE__) . '/../../util/Repository.php'));

use util\Repository;

class PhotoFilenameRepository implements Repository {

    private array $filenames = [
        'Juan' => [
            'foto1.png',
            'foto2.png',
            'foto3.png'
        ],
        'Pedro' => [
            'foto4.png',
            'foto5.png'
        ]
    ];

    function exists(string $key): bool {
        return array_key_exists($key, $this->filenames);
    }

    function getAll(string $key): array {
        return $this->filenames[$key] ?? [];
    }
}