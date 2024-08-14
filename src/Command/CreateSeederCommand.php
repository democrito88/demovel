<?php

namespace Demovel\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateProjectCommand extends Command
{
    protected static $defaultName = 'migrate';

    protected function configure()
    {
        $this
            ->setDescription('Migrates the migration classes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();

        $projectDir = getcwd() . '/';

        //Create folders
        $filesystem->mkdir($projectDir . '/database/seed');

        // Create basic files
        $filesystem->dumpFile($projectDir . '/database/seeder/Seeder.php', file_get_contents('./../../templates/seeder/Seeder.php.template'));
        $filesystem->dumpFile($projectDir . '/database/seeder/UsersSeeder.php', file_get_contents('./../../templates/seeder/UsersSeeder.php.template'));
        $filesystem->dumpFile($projectDir . '/database/seeder/runSeeder.php', file_get_contents('./../../templates/seeder/runSeeder.php.template'));

        $output->writeln('<info>Migration executed successfully.</info>');
        return Command::SUCCESS;
    }
}
