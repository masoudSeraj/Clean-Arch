<?php

namespace App\Services;

use App\Contracts\FileBuilderInterface;

class FileBuilderDirector
{
    public function __construct(
        public FileBuilderInterface $fileBuilderInterface)
    {

    }

    public function createFileLogger($fileContent)
    {
        $this->fileBuilderInterface->create($fileContent);
    }

    public function updateFileLogger()
    {
        $this->fileBuilderInterface->update();
    }
}
