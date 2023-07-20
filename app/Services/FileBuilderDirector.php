<?php namespace App\Services;

use App\Services\FileContentService;
use App\Contracts\FileBuilderInterface;

class FileBuilderDirector
{
    public function __construct(
        public FileBuilderInterface $fileBuilderInterface    )
    {

    }

    public function createFileLogger($fileContent)
    {
        $this->fileBuilderInterface->create($fileContent);
    }
}