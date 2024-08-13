<?php

namespace Demovel\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CreateProjectCommand extends Command
{
    protected static $defaultName = 'create:new';

    protected function configure()
    {
        $this
            ->setDescription('Creates a basic PHP project structure.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the project.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectName = $input->getArgument('name');
        $filesystem = new Filesystem();

        $projectDir = getcwd() . '/' . $projectName;

        if ($filesystem->exists($projectDir)) {
            $output->writeln('<error>Project already exists.</error>');
            return Command::FAILURE;
        }

        // Create project directories
        $filesystem->mkdir($projectDir . '/database');
        $filesystem->mkdir($projectDir . '/database/migration');
        $filesystem->mkdir($projectDir . '/database/seed');
        $filesystem->mkdir($projectDir . '/public');
        $filesystem->mkdir($projectDir . '/src');
        $filesystem->mkdir($projectDir . '/src/Entity');
        $filesystem->mkdir($projectDir . '/src/Controller');
        $filesystem->mkdir($projectDir . '/src/Provider');
        $filesystem->mkdir($projectDir . '/src/Mail');
        $filesystem->mkdir($projectDir . '/tests');

        // Create basic files
        $filesystem->dumpFile($projectDir . '/src/index.php', '<?php echo "Hello World!";' . PHP_EOL);
        $filesystem->dumpFile($projectDir . '/composer.json', json_encode([
            "name" => "your-vendor/" . strtolower($projectName),
            "require" => new \stdClass(),
            "autoload" => [
                "psr-4" => [
                    "App\\" => "src/"
                ]
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $output->writeln('<info>Project created successfully.</info>');
        return Command::SUCCESS;
    }
}
