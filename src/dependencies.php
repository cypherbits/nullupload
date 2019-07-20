<?php
// DIC configuration

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

//Propel config
if ($_SERVER['SERVER_NAME'] == "localhost") {
    //Test config database
    require("propelconfig.php");
}else{
    //Production config database
    require("propelconfig_pro.php");
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
