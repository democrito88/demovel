<?php

namespace Demovel\Command;

require_once './src/config.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class CreateProjectCommand extends Command
{
    protected static $defaultName = 'create:new';

    protected function configure()
    {
        $this
            ->setName('create:new')
            ->setDescription('Creates a basic PHP project structure.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the project.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectName = $input->getArgument('name');
        $filesystem = new Filesystem();

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question('Would you like to define a database? [y/n]');
        $databaseYN = $helper->ask($input, $output, $question);

        if($databaseYN === 'y'){
            $question = new Question('Where is this database located? [localhost]');
            $databaseHost = $helper->ask($input, $output, $question);
            if($databaseHost == ""){
                $databaseHost = "localhost";
            }
            
            $question = new Question('What is the database driver? [sqlite/mysql]');
            $databaseDriver = $helper->ask($input, $output, $question);

            if($databaseDriver == "sqlite"){
                $databaseDriver = "php_sqlite";

                $connContent = "'driver' => \$_ENV['DB_DRIVE'],
            'path' => __DIR__ . '/../../'.\$_ENV['DB_PATH'],";

                $databaseUserName = "";
                $databasePassword = "";
            }else{
                $databaseDriver = "pdo_mysql";

                $connContent = "'driver' => \$_ENV['DB_DRIVE'],
            'host' => \$_ENV['DB_HOST'],
            'dbname' => \$_ENV['DB_NAME'],
            'user' => \$_ENV['DB_USER'],
            'password' => \$_ENV['DB_PASSWORD'],
            'charset' => 'utf8mb4',";

                $question = new Question('What is the username of the database? [root]');
                $databaseUserName = $helper->ask($input, $output, $question);
                if($databaseUserName == ""){
                    $databaseUserName = "root";
                }

                $question = new Question('What is the username password? (<ENTER> for blank password)');
                $databasePassword = $helper->ask($input, $output, $question);
            }

            $question = new Question('What is the name of the database?');
            $databaseName = $helper->ask($input, $output, $question);
        }else{
            $databaseName = "";
            $databaseHost = "";
            $databaseDriver = "";
            $databaseUserName = "";
            $databasePassword = "";
            $connContent = "";
        }

        $projectDir = ROOT_PARENT_PATH.'/' . $projectName.'/'; //getcwd() . '/' . $projectName.'/';

        if ($filesystem->exists($projectDir)) {
            $output->writeln('<error>Project already exists.</error>');
            return Command::FAILURE;
        }

        // Create project directories
        $filesystem->mkdir($projectDir . 'public');
        $filesystem->mkdir($projectDir . 'src');
        $filesystem->mkdir($projectDir . 'src/Entity');
        $filesystem->mkdir($projectDir . 'src/Controller');
        $filesystem->mkdir($projectDir . 'src/Provider');
        $filesystem->mkdir($projectDir . 'src/Mail');
        $filesystem->mkdir($projectDir . 'routes');
        $filesystem->mkdir($projectDir . 'tests');

        if($databaseName != ""){
            $filesystem->mkdir($projectDir . 'database');
            $filesystem->dumpFile($projectDir . 'database/'.$databaseName.'.sqlite', "");
        }

        // Create basic files
        $env = file_get_contents(__DIR__.'/../../templates/.env.template');
        $env = str_replace('{{APP_NAME}}', $projectName, $env);
        $env = str_replace('{{DB_NAME}}', strtolower($databaseName), $env);
        $env = str_replace('{{DB_HOST}}', strtolower($databaseHost), $env);
        $env = str_replace('{{DB_DRIVE}}', strtolower($databaseDriver), $env);
        $env = str_replace('{{DB_USER}}', $databaseUserName, $env);
        $env = str_replace('{{DB_PASSWORD}}', $databasePassword, $env);
        $filesystem->dumpFile($projectDir . '.env', $env);

        $filesystem->dumpFile($projectDir . '.htaccess', file_get_contents(__DIR__.'/../../templates/.htaccess.template'));
        $filesystem->dumpFile($projectDir . 'public/index.php', file_get_contents(__DIR__.'/../../templates/public/index.php.template'));
        $filesystem->dumpFile($projectDir . 'composer.json', json_encode([
            "name" => "your-vendor/" . strtolower($projectName),
            "require" => new \stdClass(),
            "autoload" => [
                "psr-4" => [
                    "App\\" => "src/"
                ]
            ],
            "require" => [
                "doctrine/orm"=> "^2.16",
                "doctrine/dbal"=> "^3.2",
                "doctrine/annotations"=> "^1.0",
                "symfony/yaml"=> "*",
                "symfony/cache"=> "^6.3",
                "firebase/php-jwt"=> "^6.8",
                "phpmailer/phpmailer"=> "^6.8",
                "doctrine/migrations"=> "^3.6",
                "symfony/flex"=> "^2.4",
                "symfony/filesystem"=> "7.2.x-dev"
            ],
            "config" => [
                "allow-plugins" => [
                    "symfony/flex"=> true
                ]
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        //set the entity manager provider
        $entityManagerProvider = file_get_contents(__DIR__.'/../../templates/src/Provider/EntityServiceProvider.php.template');
        $entityManagerProvider = str_replace('{{CONN_CONTENT}}', $connContent, $entityManagerProvider);
        $filesystem->dumpFile($projectDir . 'src/Provider/EntityServiceProvider.php', $entityManagerProvider);

        $filesystem->dumpFile($projectDir . 'src/Entity/Entity.php', file_get_contents(__DIR__.'/../../templates/src/Entity/Entity.php.template'));
        $filesystem->dumpFile($projectDir . 'src/Entity/User.php', file_get_contents(__DIR__.'/../../templates/src/Entity/User.php.template'));
        $filesystem->dumpFile($projectDir . 'src/Entity/Token.php', file_get_contents(__DIR__.'/../../templates/src/Entity/Token.php.template'));
        $filesystem->dumpFile($projectDir . 'src/Controllers/InterfaceController.php', file_get_contents(__DIR__.'/../../templates/src/Controllers/InterfaceController.php.template'));
        $filesystem->dumpFile($projectDir . 'src/Controllers/Controller.php', file_get_contents(__DIR__.'/../../templates/src/Controllers/Controller.php.template'));
        $filesystem->dumpFile($projectDir . 'src/Controllers/UserController.php', file_get_contents(__DIR__.'/../../templates/src/Controllers/UserController.php.template'));
        $filesystem->dumpFile($projectDir . 'routes/routes.php', file_get_contents(__DIR__.'/../../templates/routes/routes.php.template'));
        $filesystem->dumpFile($projectDir . 'routes/router.php', file_get_contents(__DIR__.'/../../templates/routes/router.php.template'));

        $output->writeln('<info>Project created successfully.</info>');
        return Command::SUCCESS;
    }
}
