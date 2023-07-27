<?php

namespace App\Contracts;

interface StorageInterface
{
    public function store($filename, $path);

    public function setPath($path);

    public function getPath();

    public function setFilename($filename);

    public function getFilename();

    public function exists($filename);

    public function missing($filename);

    public function setDisk($disk);

    public function getDisk();

    public function write($content, $path);

    public function append($content, $path);

    public function getContent($path);

    public function getFile($path);

    public function delete($path);
}
