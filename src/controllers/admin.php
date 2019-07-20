<?php

/*Change this to change the admin access route*/
$app->group('/_superadmin', function () {

    $this->map(['GET', 'POST'], "", function ($request, $response, $args) {

        if (SessionHelper::isAdminSession()) {
            $files = FilesQuery::create()->orderByUploaddate(\Propel\Runtime\ActiveQuery\Criteria::DESC)->find();
            $nfiles = $files->count();
            $files = $files->toArray();

            $usedSpace = (int) file_get_contents(__DIR__ . "/../../usedSpace") / (1000 * 1000);
            $maxSize = (int) $this->get('settings')['nullupload']['maxLimit'] / (1000 * 1000);

            return $this->view->render($response, 'admin/files-list.html', [
                        'page' => 'files',
                        'files' => $files,
                        'nfiles' => $nfiles,
                        'usedSpace' => $usedSpace,
                        'maxSize' => $maxSize
            ]);
        } else {

            if ($request->getParam("login") == "yes") {
                $user = $request->getParam("txtUsername");
                $password = $request->getParam("txtPassword");

                if (hash_equals(SessionHelper::$adminUser, $user) && password_verify($password, SessionHelper::$adminPassword)) {

                    SessionHelper::newAuthSession(0);
                    SessionHelper::setAdminSession(true);

                    return $response->withRedirect($this->router->pathFor("admin"));
                } else {

// CSRF token name and value
                    $nameKey = $this->csrf->getTokenNameKey();
                    $valueKey = $this->csrf->getTokenValueKey();
                    $name = $request->getAttribute($nameKey);
                    $value = $request->getAttribute($valueKey);

                    return $this->view->render($response, 'admin/admin-login.html', [
                                'msg' => "Bad user or password",
                                'nameKey' => $nameKey,
                                'name' => $name,
                                'valueKey' => $valueKey,
                                'value' => $value
                    ]);
                }
            } else {
// CSRF token name and value
                $nameKey = $this->csrf->getTokenNameKey();
                $valueKey = $this->csrf->getTokenValueKey();
                $name = $request->getAttribute($nameKey);
                $value = $request->getAttribute($valueKey);

                return $this->view->render($response, 'admin/admin-login.html', [
                            'nameKey' => $nameKey,
                            'name' => $name,
                            'valueKey' => $valueKey,
                            'value' => $value
                ]);
            }
        }
    })->setName("admin");


    $this->get('/download-{id:[a-z0-9]{8,10}}', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {
            $fileid = $args['id'];
            $file = FilesQuery::create()->filterById($fileid)->findOne();

            IOHelper::download($file, __DIR__ . '/../' . '../uploads/', $this, true);
        }
    })->setName("adminDownload");

    $this->get('/delete-{id:[a-z0-9]{8,10}}', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {
            $fileid = $args['id'];
            $file = FilesQuery::create()->filterById($fileid)->findOne();

            $path = __DIR__ . '/../' . '../uploads/';

            IOHelper::delete($file, $path);
        }

        return $response->withRedirect($this->router->pathFor("admin"));
    })->setName("adminDelete");

    $this->get('/logout', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {
            SessionHelper::setAdminSession(false);
            SessionHelper::deleteSession();
        }

        return $response->withRedirect($this->router->pathFor("admin"));
    })->setName("adminLogout");

    $this->get('/phpinfo', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {
            phpinfo();
        } else {
            return $response->withRedirect($this->router->pathFor("admin"));
        }
    })->setName("adminPhpinfo");

    $this->get('/news', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {

            $news = NewsQuery::create()->orderByDatecreation()->find();

            return $this->view->render($response, 'admin/news.html', [
                        'page' => 'news',
                        'news' => $news->toArray()
            ]);
        } else {
            return $response->withRedirect($this->router->pathFor("admin"));
        }
    })->setName("adminNews");

    $this->get('/createNews', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {

            $nameKey = $this->csrf->getTokenNameKey();
            $valueKey = $this->csrf->getTokenValueKey();
            $name = $request->getAttribute($nameKey);
            $value = $request->getAttribute($valueKey);

            return $this->view->render($response, 'admin/create-news.html', [
                        'page' => 'news',
                        'nameKey' => $nameKey,
                        'name' => $name,
                        'valueKey' => $valueKey,
                        'value' => $value
            ]);
        } else {
            return $response->withRedirect($this->router->pathFor("admin"));
        }
    })->setName("adminCreateNews");

    $this->post('/createNews', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {

            $title = $request->getParam("txtTitle");
            $text = $request->getParam("txtNews");

            if (!empty($title) && !empty($text)) {
                $news = new News();
                $news->setTitle($title);
                $news->setNewtext($text);
                $news->save();
            }

            return $this->view->render($response, 'admin/create-news.html', [
                        'page' => 'news'
            ]);
        } else {
            return $response->withRedirect($this->router->pathFor("admin"));
        }
    });

    $this->get('/deleteNew-{id}', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {

            $new = NewsQuery::create()
                    ->findById($args['id']);

            $new->delete();


            return $response->withRedirect($this->router->pathFor("adminNews"));
        } else {
            return $response->withRedirect($this->router->pathFor("admin"));
        }
    })->setName("adminDeleteNew");
});
