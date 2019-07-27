<?php
// DIC configuration

use nullupload\DB;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
//    die($settings['path'] . $settings['level']);
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    /*Propel LOGGER*/
    /*$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
    $serviceContainer->setLogger('defaultLogger', $logger);*/
    return $logger;
};

$c = $container;


try{
    DB::init($c->get('settings')['database']);
}catch (PDOException $e){
    $c->logger->addError($e->getMessage());
    die("Database connection error");
}


// Register component on container
$container['view'] = function ($container) {
    $settings = $container->get('settings')['twigSettings'];
    
    $view = new \Slim\Views\Twig($settings['twigTemplatesPath'], [
        'cache' => $settings['twigCacheTemplatesPath'],
        'debug' => $settings['enableTwigDebug']
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    
    $view->getEnvironment()->getExtension("Twig_Extension_Core")->setTimezone('Europe/Madrid');

    return $view;
};

SessionHelper::init();
SessionHelper::session_start();
