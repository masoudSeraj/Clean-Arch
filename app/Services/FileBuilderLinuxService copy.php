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
        // if (! Storage::disk($this->fileContentInterface->getdisk())->exists($this->filename())) {
        //     Storage::disk($this->fileContentInterface->getdisk())->put($this->filename(), $content);
        //     return;
        // }
        if(!file_exists(public_path($this->filename())))
        {
            file_put_contents(public_path($this->filename()), $content);
        }
    }

    public function update()
    {
        // if (Storage::disk($this->fileContentInterface->getdisk())->exists($this->filename())) {
        //     $line = $this->getLine();
        // }
        if (file_exists(public_path($this->filename()))) {
            $line = $this->getLine();
            dd($line);
        }

        if(!is_null($line) || $line != 0){
            $this->removeLine($line);
            $this->updateLine($line);
        }
    }

    public function getFile()
    {
        // return Storage::disk($this->fileContentInterface->getdisk())->get($this->filename());
        return base_path('parspack/public/'.$this->filename());
    }

    protected function filename()
    {
        return $this->fileContentInterface->getFilename() ?? config('parspack.filename');
    }

    protected function getLine()
    {
        // dd(Storage::disk('public')->exists( $this->getPath()));
        // $text = dd("grep -n " . $this->fileContentInterface->getContent() . " " . $this->getPath() . " | head -n 1 | cut -d: -f1");
        // sleep(2)
        $line = shell_exec("grep \"-n\" \"a\" \"/var/www/html/parspack/storage/logs/laravel.log\"");
        dd($line);
    }
    protected function removeLine($line)
    {
        dd($line);
        shell_exec("sed -i '". $line."d' " . $this->getPath());
    }

    protected function updateLine($line)
    {
        shell_exec("sed -i '".$line."i' " . $this->getPath());
    }

    protected function getPath()
    {
        // dd(storage_path());
        // dd( Storage::disk($this->fileContentInterface->getdisk())->path($this->filename()));
        return( base_path('parspack/public/'.$this->filename()));
    }
}
