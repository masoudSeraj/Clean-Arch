<?php namespace App\Contracts;

use App\Services\FileContentService;

interface FileBuilderInterface
{
    public function create($fileContent);
}