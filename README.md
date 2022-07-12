## Installation

### Requirements

* XAMPP (PHP, MySQL)
* IDE  (PhpStorm)
* COMPOSER

### Laravel installation

Execute the following command to install laravel globally on your pc

```bash
composer global require laravel/installer
```

Clone the repository on your pc

```bash
git clone https://github.com/REPOSITORY-USERNAME
```

### Installation of dependencies

Execute this command to install the dependencies corresponding to the file composer.json

```bash
composer install
```

### Create a database that supports Laravel

Create a MySQL database in XAMPP which will allow to connect to the Laravel project.

### Create the .env file

Duplicate the .env.example file, rename it to .env and include the database connection data we indicated in the previous step.

### Migrations

Execute the following command to run the migrations and seeder of the project

```bash
php artisan migrate:fresh --seed
```

### That's all! Now the tables have been generated in your database.
