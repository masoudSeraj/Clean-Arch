<?php

namespace App\Contracts;

/**
 * FileContentInterface
 *
 * This could be a class too!
 */
interface FileContentInterface
{
    public function setContent($content);

    public function getContent();

    public function setCount($count);

    public function setDisk($disk);

    public function getDisk();

    public function setFilename($filename);

    public function getFilename();

    public function getProductWithCount();
}
