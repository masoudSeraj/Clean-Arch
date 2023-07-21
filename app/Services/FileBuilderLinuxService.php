<?php

namespace App\Services;

use App\Contracts\FileBuilderInterface;
use App\Contracts\FileContentInterface;
use Illuminate\Support\Facades\Storage;

class FileBuilderLinuxService implements FileBuilderInterface
{
    public function __construct(public FileContentInterface $fileContentInterface)
    {
    }

    public function create($content)
    {
        if (! Storage::disk($this->fileContentInterface->getdisk())->exists($this->filename())) {
            Storage::disk($this->fileContentInterface->getdisk())->put($this->filename(), $content);

            return;
        }

        return $this->update();
    }

    public function update()
    {
        if (! Storage::disk($this->fileContentInterface->getdisk())->exists($this->filename())) {
            return;
        }
    }

    public function getFile()
    {
        return Storage::disk($this->fileContentInterface->getdisk())->get($this->filename());
    }

    protected function filename()
    {
        return $this->fileContentInterface->getFilename() ?? config('parspack.filename');
    }
}
