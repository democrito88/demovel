<?php

namespace Demovel\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateSeederCommand extends Command
{
    protected static $defaultName = 'attach:seeding';

    protected function configure()
    {
        $this
            ->setName('attach:seeding')
            ->setDescription('Seed the database tables');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();

        $projectDir = ROOT_PATH; //getcwd() . '/' . $projectName.'/';

        if($filesystem->exists($projectDir.'/database/seeder')){
            $output->writeln('<error>Seeding structure already exists.</error>');
            return Command::FAILURE;
        }

        //Create folders
        $filesystem->mkdir($projectDir . '/database/seeder');

        // Create basic files
        $filesystem->dumpFile($projectDir . '/database/seeder/Seeder.php', file_get_contents(__DIR__.'/../../templates/seeder/Seeder.php.template'));
        $filesystem->dumpFile($projectDir . '/database/seeder/UsersSeeder.php', file_get_contents(__DIR__.'/../../templates/seeder/UsersSeeder.php.template'));
        $filesystem->dumpFile($projectDir . '/database/seeder/runSeeders.php', file_get_contents(__DIR__.'/../../templates/seeder/runSeeders.php.template'));

        $output->writeln('<info>Seeding structure attached successfully.</info>');
        return Command::SUCCESS;
    }
}
