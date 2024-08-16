<?php

namespace Demovel\Command;

require_once "./src/config.php";

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateEntityCommand extends Command
{
    protected static $defaultName = 'new:entity';

    protected function configure()
    {
        $this
            ->setName('new:entity')
            ->setDescription('Creates a new entity class.')
            ->addArgument('name', InputArgument::REQUIRED, 'The Entity class name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectDir = ROOT_PATH;
        $filesystem = NEW Filesystem();
        $entityName = $input->getArgument('name');

        $basicEntity = file_get_contents(__DIR__.'/../../templates/src/Entity/BasicEntity.php.template');
        $basicEntity = str_replace('{{CLASS_NAME}}', ucfirst($entityName), $basicEntity);
        $basicEntity = str_replace('{{TABLE_NAME}}', strtolower($entityName).'s', $basicEntity);
        $filesystem->dumpFile($projectDir . '/src/Entity/'.ucfirst($entityName).'.php', $basicEntity);

        $output->writeln('<info>Entity created successfully.</info>');
        return Command::SUCCESS;
    }
}
