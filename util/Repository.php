<?php

namespace util;

interface Repository {

    function exists(string $key): bool;

    function getAll(string $key): array;
}