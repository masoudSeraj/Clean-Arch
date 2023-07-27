<?php

namespace App\Services;

use App\Contracts\FileBuilderInterface;
use App\Contracts\FileContentInterface;
use App\Contracts\StorageInterface;
use Illuminate\Support\Facades\Storage;

class FileBuilderLinuxService implements FileBuilderInterface
{
    public function __construct(
        public FileContentInterface $fileContentInterface,
        public StorageInterface $storageInterface
    ) {
    }

    /**
     * Method create
     *
     * @param $content $content
     *
     * When a product is created, the product will be added to the Log file
     * asocciated with its count. If there's not log file available for it,
     * a new log file will be created in the desired path.
     * @return void
     */
    public function create($content)
    {
        // dd($this->fileContentInterface->getContent());

        // if there is no file, create file and add product inside
        if (! $this->storageInterface->exists($this->filename())) {
            // Storage::disk($this->fileContentInterface->getdisk())->put($this->filename(), $content);
            $this->storageInterface->write($content, $this->filename());
            shell_exec('chmod 777 -R '.$this->storageInterface->getPath());

            return;
        }

        // product already exists in file
        if (! is_null(shell_exec('grep '.$this->fileContentInterface->getContent().' '.$this->getPath().' | head -n 1 | cut -d: -f1'))) {
            return;
        }

        // if file is already exists, but the product is not duplicate, append it!
        // Storage::disk($this->fileContentInterface->getdisk())->append($this->filename(), $content);
        $this->storageInterface->append($content, $this->filename());
        // shell_exec("chmod 777 ". $this->getPath());
    }

    /**
     * Method update
     *
     * @return void
     *
     * This method is used to update the log file which is already created.
     * It will be trigerred when a new comment is added to the product.
     * First it checks if the product is already in the log file.
     * If yes it will update the product count (It will add the product with its count and then removes the old one)
     */
    public function update()
    {
        if ($this->storageInterface->exists($this->filename())) {
            $line = (int) shell_exec('grep -n '.$this->fileContentInterface->getContent().' '.$this->getPath().' | head -n 1 | cut -d: -f1');
        }

        // dd(is_null($line) );
        if ($line !== 0) {
            $this->addLine($line);
            $this->removeLine($line + 1);

            return;
        }
        // $this->storageInterface->append($this->fileContentInterface->getProductWithCount(), $this->filename());

    }

    public function getFileContent()
    {
        // return Storage::disk($this->fileContentInterface->getdisk())->get($this->filename());
        return $this->storageInterface->getContent($this->filename());
    }

    protected function filename()
    {
        return $this->fileContentInterface->getFilename() ?? config('parspack.filename');
    }

    protected function removeLine($line)
    {
        shell_exec("sed -i '".$line."d' ".$this->getPath());
    }

    protected function addLine($line)
    {
        shell_exec("sed -i '".$line."i {$this->fileContentInterface->getProductWithCount()}' ".$this->getPath());
    }

    public function getPath()
    {
        // return Storage::disk($this->fileContentInterface->getdisk())->path($this->filename());
        return $this->storageInterface->getFile($this->filename());
    }
}
