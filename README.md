# Demovel

![Version](https://img.shields.io/badge/v0.0.1-fed000?style=for-the-badge)
![License](https://img.shields.io/badge/MIT-0ac18e?style=for-the-badge)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/symfony-%23000000.svg?style=for-the-badge&logo=symfony&logoColor=white)
![Composer](https://img.shields.io/badge/composer-%23ff6347.svg?style=for-the-badge)
![Dependencies](https://img.shields.io/badge/dependencies-11-%23af0000.svg?style=for-the-badge)

Library that create a structure of a simple PHP project on MVC achitecture. It uses Symfony libraries as dependencies such as Doctrine.

### Usage

#### Create a new project

To create a new project, install this package and run the command:

```
composer install
./bin/console create:new my-new-project

```

It will create a project with the following structure:
```
root-folder
  ├──public
  |  └──index.php
  ├──src
  |  ├──Provider
  |  |  └──EntityServiceProvider.php
  |  ├──Controller
  |  |  ├──InterfaceController.php
  |  |  ├──Controller.php
  |  |  ├──UserController.php
  |  |  └──TokenController.php
  |  └──Entity
  |     ├──User.php
  |     └──Token.php
  ├──routes
  |  ├──router.php
  |  └──routes.php
  ├──.env
  └──.htaccess
```

#### Create migration basic structure

```
./bin/console create:migrate
```

This command will create the basic structure for create and run migration classes as it follows:

```
root-folder
  └──database
     └──migrations
        ├──CreateTable.php
        ├──CreateUsersTable.php
        └──runMigrations.php
```
All migration classes must extend CreateTable class and be mentioned in runMigrations.php script.

#### Create seeding basic structure
```
./bin/console create:seed
```
This command will create the basic structure for create and run seeding classes as it follows:

```
root-folder
  └──database
     └──seed
        ├──Seeder.php
        ├──UsersSeeder.php
        └──runSeeders.php
```
All seedeing classes must extend Seeder class and be mentioned in runSeeders.php script.

#### Create a mail class
```
./bin/console create:mail
```

It will create the Sender.php file inside a Email folder as it follows:
```
root-folder
  └──src
     └──Email
        └──Seeder.php
```
This package uses [PHPMailer](https://github.com/PHPMailer/PHPMailer) to send an email.

### Author
[Demócrito d'Anunciação](https://github.com/democrito88)