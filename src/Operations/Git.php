<?php

namespace LaravelUpgrade\Operations;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Git extends Base
{
    public function init()
    {
        $process = new Process(['git', 'init'], $this->input->getArgument('outputFolder'));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $this->output->writeln($process->getOutput());
        $this->addAndCommit();
    }

    public function checkoutNextVersion($version)
    {
        $process = new Process(['git', 'checkout', '-b', $version], $this->input->getArgument('outputFolder'));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $this->output->writeln($process->getOutput());
    }

    public function addAndCommit($message = "init project")
    {
        $process = new Process(['git', 'add', '.'], $this->input->getArgument('outputFolder'));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $this->output->writeln($process->getOutput());
        if (!empty($process->getOutput())) {
            $process = new Process(['git', 'commit', '-m', '"' . $message . '"'], $this->input->getArgument('outputFolder'));
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $this->output->writeln($process->getOutput());
        }
    }
}
