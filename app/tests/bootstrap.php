<?php

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

// Create and boot 'test' kernel
$kernel = new Kernel('test', true);
$kernel->boot();

// Create new application
$application = new Application($kernel);
$application->setAutoExit(false);

$application->run(new ArrayInput([
    'command' => 'doctrine:database:drop',
    '--force' => '1',
    '--env' => 'test',
]));

$application->run(new ArrayInput([
    'command' => 'doctrine:database:create',
    '--no-interaction' => '1',
    '--env' => 'test',
]));

$application->run(new ArrayInput([
    'command' => 'doctrine:schema:create',
    '--no-interaction' => '1',
    '--env' => 'test',
]));
