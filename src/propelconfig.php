<?php

$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();

    $manager->setConfiguration(array(
        'dsn' => 'mysql:host=localhost;port=3306;dbname=nullupload',
        'user' => 'root',
        'password' => '',
        'settings' =>
        array(
            'charset' => 'utf8',
            'queries' =>
            array(
            ),
        ),
        'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
        'model_paths' =>
        array(
            0 => 'src',
            1 => 'vendor',
        ),
    ));


$manager->setName('default');
$serviceContainer->setConnectionManager('default', $manager);
$serviceContainer->setDefaultDatasource('default');

/*$propelLogger = new Monolog\Logger('propelLogger');
$propelLogger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/../logs/propel.log', Monolog\Logger::DEBUG));
$serviceContainer->setLogger('propelLogger', $propelLogger);*/
