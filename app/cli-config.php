<?php
require __DIR__ . '/bootstrap.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup as DoctrineSetup;
use Doctrine\ORM\EntityManager as DoctrineEm;

// replace with mechanism to retrieve EntityManager in your app
$isDevMode = true;
$config = DoctrineSetup::createAnnotationMetadataConfiguration(
	[APP_BASE_DIR . "/entities"],
	$isDevMode
);

$conn = $dbParams = [
    'driver'   => $_ENV['DOCTRINE_DRIVER'],
    'host' => $_ENV['DB_HOST'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'dbname'   => $_ENV['DB_NAME'],
    'charset' => 'utf8'
];

$entityManager = DoctrineEm::create($conn, $config);

return ConsoleRunner::createHelperSet($entityManager);
