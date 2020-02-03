<?php

namespace LaravelUpgrade\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use LaravelUpgrade\Project\Info;
use LaravelUpgrade\Operations\Base;

class Upgrade extends Command
{
    protected static $defaultName = 'upgrade';

    protected function configure()
    {
        $this
            ->setDescription('Upgrade your project')
            ->setHelp('This command upgrade the project')
            ->addArgument('inputFolder', InputArgument::REQUIRED, 'Input Folder')
            ->addArgument('outputFolder', InputArgument::REQUIRED, 'Output Folder');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('max_execution_time', 0);

        while (true) {
            $base = new Base($input, $output);
            $base->folder()->exists();
            $projectInfo = $base->projectInfo();
            $nextVersion = $projectInfo->getLaravelNextVersion();
            if ($nextVersion == null) {
                $output->writeln("done.");
                break;
            }
            $output->writeln("Project Current Version: " . $projectInfo->getLaravelVersion());
            $output->writeln("Project Next Version: " . $nextVersion);
            if ($input->getArgument('inputFolder') != $input->getArgument('outputFolder')) {
                $base->copy()->inputToOutput();
                $base->git()->init();
            }
            $base->git()->checkoutNextVersion($nextVersion);
            $base->folder()->removeProject();
            $base->copy()->laravelProject($nextVersion);
            $base->git()->addAndCommit("Laravel " . $nextVersion . " init project");
            $base->copy()->files();
            $input->setArgument('inputFolder', $input->getArgument('outputFolder'));
        }

        return 0;
    }
}
