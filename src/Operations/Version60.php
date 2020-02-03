<?php

namespace LaravelUpgrade\Operations;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class Version60 extends Base
{
    public function files()
    {
        $filesystem = new Filesystem();
        $files = [
            'copy' => [
                '/.env',
                '/package.json',
                '/webpack.mix.js',
            ],
            'mirror' => [
                '/app',
                '/database',
                '/config',
                '/resources',
                '/routes',
                '/public',
            ],
        ];
        foreach ($files as $key => $values) {
            foreach ($values as $value) {
                if ($filesystem->exists($this->input->getArgument("inputFolder") . '' . $value)) {
                    if ($key == "copy") {
                        $filesystem->copy($this->input->getArgument("inputFolder") . '' . $value, $this->input->getArgument("outputFolder") . '' . $value, true);
                    } else if ($key == "mirror") {
                        $filesystem->mirror($this->input->getArgument("inputFolder") . '' . $value, $this->input->getArgument("outputFolder") . '' . $value, null, array('ovverride' => true));
                    }
                }
            }
        }
        $this->git()->addAndCommit("copy all files");
        $exceptLaravel6 = array(
            'php',
            'fideloper/proxy',
            'laravel/framework',
            'laravel/tinker'
        );
        $composer = file_get_contents($this->input->getArgument("inputFolder") . "/composer.json");
        foreach (json_decode($composer)->require as $index => $value) {
            if (!in_array($index, $exceptLaravel6)) {
                $process = new Process(['composer', 'require', $index, '-d', $this->input->getArgument("outputFolder")]);
                $process->run(function ($type, $buffer) {
                    if (Process::ERR === $type) {
                        echo 'INFO > ' . $buffer . "\n";
                    } else {
                        echo 'OUT > ' . $buffer . "\n";
                    }
                });
            }
        }
        $this->git()->addAndCommit("your php packages load");
    }
}
