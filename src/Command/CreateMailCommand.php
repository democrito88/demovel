<?php

namespace Demovel\Command;

require_once "./src/config.php";

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateMailCommand extends Command
{
    protected static $defaultName = 'attach:mail';

    protected function configure()
    {
        $this
            ->setName('attach:mail')
            ->setDescription('Creates a mailing class.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();

        $projectDir = ROOT_PATH;

        if($filesystem->exists($projectDir.'/src/Email')){
            $output->writeln('<error>Mailer already exists in src/Email directory.</error>');
            return Command::FAILURE;
        }

        // Create project directories
        $filesystem->mkdir($projectDir . '/src/Email');

        // Create basic files
        $filesystem->dumpFile($projectDir . '/src/Email/Sender.php', file_get_contents(__DIR__.'/../../templates/src/Email/Sender.php.template'));

        $output->writeln('<info>Mailer created successfully.</info>');
        return Command::SUCCESS;
    }
}
