<?php

namespace LaravelUpgrade\Project;

class Base
{
    public $folder;

    public function __construct($folder)
    {
        $this->folder = $folder;
    }
}
