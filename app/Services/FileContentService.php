<?php namespace App\Services;

class FileContentService
{
    protected $content = 'unknown';
    protected $count = '1';    
    /**
     * Method simpleContent
     *
     * @return string
     */
    public function simpleContent() :string
    {
        return $this->content .': '. $this->count;
    
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }
}