<?php namespace App\Services;

use Illuminate\Support\Facades\Storage;
      use App\Contracts\FileBuilderInterface;

class FileBuilderLinuxService implements FileBuilderInterface
{
    protected $disk = 'public';
    protected $filename = 'products.txt';


    public function create($content)
    {
        if(!Storage::disk($this->disk)->exists($this->filename())){
            Storage::disk($this->disk)->put($this->filename(), $content);
            return;
        }
        return $this->update();
    }

    public function update()
    {
        if(!Storage::disk($this->disk)->exists($this->filename())){
            return;
        }
    }

    public function getFile()
    {
        return Storage::disk($this->disk)->get($this->filename());
    }

    public function setDisk($disk)
    {
        $this->disk = $disk;
        return $this;
    }

    public function getDisk()
    {
        return $this->disk;
    }

    protected function filename()
    {
        return config('parspack.filename') ?? $this->filename;
    }
}