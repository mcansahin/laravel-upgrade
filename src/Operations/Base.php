<?php

namespace LaravelUpgrade\Operations;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LaravelUpgrade\Operations\Folder;
use LaravelUpgrade\Operations\Copy;
use LaravelUpgrade\Operations\Git;
use LaravelUpgrade\Operations\Version60;
use LaravelUpgrade\Project\Info;

class Base
{
    public $input;
    public $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function projectInfo()
    {
        return new Info($this->input->getArgument('inputFolder'));
    }

    public function folder()
    {
        return new Folder($this->input, $this->output);
    }

    public function copy()
    {
        return new Copy($this->input, $this->output);
    }

    public function git()
    {
        return new Git($this->input, $this->output);
    }

    public function version()
    {
        $className = 'Version' . str_replace('.', '', $this->projectInfo()->getLaravelNextVersion());
        return new $className();
    }
}
