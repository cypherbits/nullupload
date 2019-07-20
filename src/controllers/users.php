<?php

$app->get('/login', function ($request, $response, $args) {

    if (SessionHelper::isActiveSession()) {
        return $response->withRedirect($this->router->pathFor("home"));
    }

    // CSRF token name and value
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $name = $request->getAttribute($nameKey);
    $value = $request->getAttribute($valueKey);


    return $this->view->render($response, 'login.html', [
                'page' => 'login',
                'nameKey' => $nameKey,
                'name' => $name,
                'valueKey' => $valueKey,
                'value' => $value
    ]);
})->setName('userLogin');


$app->post('/login', function ($request, $response, $args) {

//    if (SessionHelper::isActiveSession()) {
//        return $response->withRedirect($this->router->pathFor("home"));
//    }
//
//    $txtuser = $request->getParam("txtUser");
//    $password = $request->getParam("txtPassword");
//    
//    $user = UsersQuery::create()
//            ->findOneByUsername(hash("sha256", $txtuser));
//
//    if ($user != null) {
//        if (password_verify($password, $user->getPassword())) {
//
//            SessionHelper::newAuthSession($user->getId());
//
//            return $response->withRedirect($this->router->pathFor("home"));
//        }
//    }else{
//        die("no encontrado");
//    }

    return $response->withRedirect($this->router->pathFor("userLogin"));
});

$app->get('/join', function ($request, $response, $args) {

    if (SessionHelper::isActiveSession()) {
        return $response->withRedirect($this->router->pathFor("home"));
    }

    // CSRF token name and value
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $name = $request->getAttribute($nameKey);
    $value = $request->getAttribute($valueKey);

    return $this->view->render($response, 'join.html', [
                'page' => 'join',
                'nameKey' => $nameKey,
                'name' => $name,
                'valueKey' => $valueKey,
                'value' => $value
    ]);
})->setName('userJoin');

$app->post('/join', function ($request, $response, $args) {

//    if (SessionHelper::isActiveSession()) {
//        return $response->withRedirect($this->router->pathFor("home"));
//    }
//
//    $txtuser = $request->getParam("txtUser");
//    $password = $request->getParam("txtPassword");
//    $password2 = $request->getParam("txtPassword2");
//    $checkTerms = $request->getParam("checkTerms");
//
//    if ($txtuser != null && $password != null && $password2 != null && $checkTerms != null && $checkTerms == "yes" && $password == $password2) {
//
//        $user = new Users();
//        $user->setUsername(hash("sha256", $txtuser));
//        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
//        $user->save();
//
//        return $response->withRedirect($this->router->pathFor("userLogin"));
//    } else {
//        return $response->withRedirect($this->router->pathFor("userJoin"));
//    }
    
    return $response->withRedirect($this->router->pathFor("userJoin"));
});
