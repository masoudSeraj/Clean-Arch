<?php namespace App\Contracts;

interface FileBuilderInterface
{
    public function __construct(FileContentInterface $fileContentInterface);
    public function create($fileContent);
}