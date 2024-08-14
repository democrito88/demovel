<?php

namespace Demovel\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateProjectCommand extends Command
{
    protected static $defaultName = 'create:mail';

    protected function configure()
    {
        $this
            ->setName('create:mail')
            ->setDescription('Creates a mailing class.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectName = __DIR__;
        $filesystem = new Filesystem();

        $projectDir = getcwd() . '/' . $projectName.'/';

        // Create project directories
        $filesystem->mkdir($projectDir . 'src/Email');

        // Create basic files
        $filesystem->dumpFile($projectDir . 'src/Email/', file_get_contents(__DIR__.'/../../templates/src/Email/Sender.php.template'));

        $output->writeln('<info>Mailer created successfully.</info>');
        return Command::SUCCESS;
    }
}
