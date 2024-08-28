<?php

namespace Demovel\Command;

require_once "./src/config.php";

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateControllerCommand extends Command
{
    protected static $defaultName = 'new:controller';

    protected function configure()
    {
        $this
            ->setName('new:controller')
            ->setDescription('Creates a new controller class.')
            ->addArgument('name', InputArgument::REQUIRED, 'The Controller class name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectDir = ROOT_PATH;
        $filesystem = NEW Filesystem();
        $controllerName = $input->getArgument('name');

        $basicController = file_get_contents(__DIR__.'/../../templates/src/Controllers/BasicController.php.template');
        $filesystem->dumpFile($projectDir . '/src/Controller/'.$controllerName.'.php', str_replace('{{CLASS_NAME}}', $controllerName, $basicController));

        $output->writeln('<info>Controller created successfully.</info>');
        return Command::SUCCESS;
    }
}
