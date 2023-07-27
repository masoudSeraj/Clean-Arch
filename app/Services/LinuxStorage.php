<?php

namespace App\Services;

use App\Contracts\StorageInterface;

class LinuxStorage implements StorageInterface
{
    protected $path;

    protected $filename;

    protected $disk = '/opt/myprogram/product_comments/';

    public function store($filename, $path)
    {

    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFilename()
    {

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

    public function exists($file): bool
    {
        return shell_exec("[ -f $this->disk"."$file ] && echo 1 || echo 0");
    }

    public function missing($file): bool
    {
        return shell_exec("[ -f $this->disk"."$file ] && echo 1 || echo 0");
    }

    public function write($content, $path)
    {
        shell_exec("echo $content > $this->disk"."$path");
    }

    public function append($content, $path)
    {
        shell_exec("echo $content >> ".$this->disk."$path");
    }

    public function getContent($path)
    {
        return shell_exec('cat '.$this->disk.$path);
    }

    public function getFile($path)
    {
        return $this->disk.$path;
    }

    public function delete($path)
    {
        shell_exec("rm -f $this->disk".$path);
    }
}
