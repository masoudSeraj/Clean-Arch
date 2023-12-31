<?php

namespace App\Services;

use App\Contracts\FileContentInterface;

/**
 * FileContentService
 * This class will not likely to be changed so that no interface is implemented
 */
class FileContentService implements FileContentInterface
{
    protected $content;

    protected $count = '1';

    protected $disk = 'public';

    protected $filename;

    protected $productWithCount;

    public function __construct()
    {

    }

    /**
     * Method simpleContent
     */
    public function simpleContent(): string
    {
        return $this->productWithCount = $this->content.': '.$this->count;
    }

    public function getProductWithCount(): string
    {
        return $this->productWithCount;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }

    public function setDisk($disk)
    {
        $this->disk = $disk;
    }

    public function getDisk()
    {
        return $this->disk;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }
}
