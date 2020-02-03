<?php

namespace LaravelUpgrade\Operations;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Filesystem\Filesystem;

class Copy extends Base
{
    public function inputToOutput()
    {
        $filesystem = new Filesystem();
        $filesystem->mirror($this->input->getArgument('inputFolder'), $this->input->getArgument('outputFolder'));
        $filesystem->remove($this->input->getArgument('outputFolder') . "/.git");
    }

    public function laravelProject($version)
    {
        $filesystem = new Filesystem;
        $tmp_laravel = '/tmp/laravel_' . $version;
        $process = new Process(['composer', 'create-project', '--prefer-dist', 'laravel/laravel=' . $version, $tmp_laravel], $this->input->getArgument('outputFolder'));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $this->output->writeln($process->getOutput());
        $filesystem->mirror($tmp_laravel, $this->input->getArgument('outputFolder'));
        $filesystem->remove($tmp_laravel);
    }

    public function files()
    {
        $this->version()->files();
    }
}
