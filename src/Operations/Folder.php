<?php

namespace LaravelUpgrade\Operations;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Folder extends Base
{
    public function exists()
    {
        $filesystem = new Filesystem();
        $existsInput = $filesystem->exists($this->input->getArgument("inputFolder"));
        $this->output->writeln("Input Directory: " . $this->input->getArgument("inputFolder") . ($existsInput ? "✓" : "×"));
        if (!$existsInput) {
            $this->output->writeln('Please entered correct input directory!');
            return 0;
        }
        $existsOutput = $filesystem->exists($this->input->getArgument("outputFolder"));
        $this->output->writeln("Output Directory: " . $this->input->getArgument("outputFolder") . ($existsOutput ? "✓" : "×"));
        if (!$existsOutput) {
            $this->output->writeln('Creating output directory...');
            $filesystem->mkdir($this->input->getArgument("outputFolder"), 0755);
        }
    }

    public function removeProject()
    {
        $filesystem = new Filesystem;
        $finder = new Finder();
        $filesystem->remove($finder->directories()->in($this->input->getArgument('outputFolder'))->exclude('.git'));
        $filesystem->remove($finder->files()->in($this->input->getArgument('outputFolder'))->exclude('.git'));
        $filesystem->remove([$this->input->getArgument('outputFolder') . '/.env.example', $this->input->getArgument('outputFolder') . '/.env', $this->input->getArgument('outputFolder') . '/.editorconfig', $this->input->getArgument('outputFolder') . '/.gitattributes', $this->input->getArgument('outputFolder') . '/.gitignore']);
    }
}
