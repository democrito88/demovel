<?php

namespace Demovel\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateProjectCommand extends Command
{
    protected static $defaultName = 'create:migration';

    protected function configure()
    {
        $this
            ->setName('crate:migrate')
            ->setDescription('Creates the migration main class');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();

        $projectDir = getcwd() . '/';

        //Create folders
        $filesystem->mkdir($projectDir . '/database');
        $filesystem->mkdir($projectDir . '/database/migration');

        // Create basic files
        $filesystem->dumpFile($projectDir . '/database/migration/CreateTable.php', file_get_contents('./../../templates/migrations/CreateTable.php.template'));
        $filesystem->dumpFile($projectDir . '/database/migration/CreateUsersTable.php', file_get_contents('./../../templates/migrations/CreateUsersTable.php.template'));
        $filesystem->dumpFile($projectDir . '/database/migration/runMigrations.php', file_get_contents('./../../templates/migrations/runMigrations.php.template'));

        $output->writeln('<info>Migration class created successfully.</info>');
        return Command::SUCCESS;
    }
}
