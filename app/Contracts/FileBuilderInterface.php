<?php

namespace App\Contracts;

interface FileBuilderInterface
{
    public function __construct(FileContentInterface $fileContentInterface, StorageInterface $storageInterface);

    public function create($fileContent);

    public function update();
}
