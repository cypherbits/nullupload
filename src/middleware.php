<?php

// Application middleware
$c = $app->getContainer();

$guard = new \Slim\Csrf\Guard();
$guard->setFailureCallable(function ($request, $response, $next) {
    //$request = $request->withAttribute("csrf_status", false);
    //$response =  $response->withRedirect($_SERVER['HTTP_REFERER']);
    //return $next($request, $response);
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
        return $response->withRedirect($_SERVER['HTTP_REFERER']);
    }else{
        return $response->withRedirect("https://nullupload.com");
    }
});

$app->add($guard);
//$c['csrf'] = $guard;
$c['csrf'] = function ($c) {
    $guard = new \Slim\Csrf\Guard();
//    $guard->setFailureCallable(function ($request, $response, $next) {
//        //$request = $request->withAttribute("csrf_status", false);
//        //$response =  $response->withRedirect($_SERVER['HTTP_REFERER']);
//        //return $next($request, $response);
//        return $response->withRedirect($_SERVER['HTTP_REFERER']);
//    });
    return $guard;
};

$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {

        $c->logger->addError($exception->getMessage() . ' on ' . $exception->getFile() . ' at ' . $exception->getLine());

        /*do{
            $c->logger->addError($exception->getMessage() . ' on ' . $exception->getFile() . ' at ' . $exception->getLine());
        }while($e = $exception->getPrevious());*/



        return $c['response']->withStatus(500)
                        ->withHeader('Content-Type', 'text/html')
                        ->write('Something went wrong!');
    };
};

$c['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {

        $c->logger->addError($exception->getMessage() . ' on ' . $exception->getFile() . ' at ' . $exception->getLine());

        /*do{
            $c->logger->addError($exception->getMessage() . ' on ' . $exception->getFile() . ' at ' . $exception->getLine());
        }while($e = $exception->getPrevious());*/

        return $c['response']->withStatus(500)
                        ->withHeader('Content-Type', 'text/html')
                        ->write('Something went wrong!');
    };
};

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {

        return $c->view->render($response, 'notfound.html', [
        ]);
    };
};

$app->add(function ($request, $response, $next) use ($c) {
    //$response->getBody()->write('BEFORE');
    $c->view->getEnvironment()->addGlobal("urlHome", str_replace("index.php", "", $this->router->pathFor("home")));
    $c->view->getEnvironment()->addGlobal("urlStatistics", $this->router->pathFor("statistics"));
    $c->view->getEnvironment()->addGlobal("urlNews", $this->router->pathFor("news"));
    $c->view->getEnvironment()->addGlobal("urlReport", $this->router->pathFor("report"));
    $c->view->getEnvironment()->addGlobal("urlTerms", $this->router->pathFor("terms"));
    $c->view->getEnvironment()->addGlobal("urlPrivacy", $this->router->pathFor("privacy"));
    $c->view->getEnvironment()->addGlobal("urlAdminHome", $this->router->pathFor("admin"));
    $c->view->getEnvironment()->addGlobal("urlAdminNews", $this->router->pathFor("adminNews"));
   // $c->view->getEnvironment()->addGlobal("urlAdminUsers", "#");
    $c->view->getEnvironment()->addGlobal("urlAdminLogout", $this->router->pathFor("adminLogout"));
    $c->view->getEnvironment()->addGlobal("urlAdminDownload", $this->router->pathFor("adminDownload" , ["id" => '']));
    $c->view->getEnvironment()->addGlobal("urlAdminDelete", $this->router->pathFor("adminDelete", ["id" => '']));
    $c->view->getEnvironment()->addGlobal("urlAdminCreateNews", $this->router->pathFor("adminCreateNews"));
    /*$c->view->getEnvironment()->addGlobal("urlAdminOpcache", $this->router->pathFor("adminOpcache"));*/
    $c->view->getEnvironment()->addGlobal("urlAdminDeleteNew", $this->router->pathFor("adminDeleteNew", ["id" => '']));
    $c->view->getEnvironment()->addGlobal("urlAdminPhpinfo", $this->router->pathFor("adminPhpinfo"));
    $c->view->getEnvironment()->addGlobal("urlAdminConfig", $this->router->pathFor("adminConfig"));
    //$c->view->getEnvironment()->addGlobal("urlUserLogin", $this->router->pathFor("userLogin"));
    //$c->view->getEnvironment()->addGlobal("urlUserJoin", $this->router->pathFor("userJoin"));

    $response = $next($request, $response);
    //$response->getBody()->write('AFTER');

    return $response;
});
