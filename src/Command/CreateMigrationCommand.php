<?php

namespace Demovel\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateMigrationCommand extends Command
{
    protected static $defaultName = 'attach:migration';

    protected function configure()
    {
        $this
            ->setName('attach:migration')
            ->setDescription('Creates the migration main class');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();

        $projectDir = ROOT_PATH; //getcwd() . '/' . $projectName.'/';

        if($filesystem->exists($projectDir.'/database/migration')){
            $output->writeln('<error>Migration structure already exists.</error>');
            return Command::FAILURE;
        }

        //Create folders
        $filesystem->mkdir($projectDir . '/database');
        $filesystem->mkdir($projectDir . '/database/migration');

        // Create basic files
        $filesystem->dumpFile($projectDir . '/database/migration/CreateTable.php', file_get_contents(__DIR__.'/../../templates/migrations/CreateTable.php.template'));
        $filesystem->dumpFile($projectDir . '/database/migration/CreateUsersTable.php', file_get_contents(__DIR__.'/../../templates/migrations/CreateUsersTable.php.template'));
        $filesystem->dumpFile($projectDir . '/database/migration/runMigrations.php', file_get_contents(__DIR__.'/../../templates/migrations/runMigrations.php.template'));

        $output->writeln('<info>Migration structure attached successfully.</info>');
        return Command::SUCCESS;
    }
}
