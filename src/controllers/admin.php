<?php

use nullupload\DB;
use Slim\Http\Request;
use Slim\Http\Response;

/*Change this to change the admin access route*/
$app->group('/'.SessionHelper::$adminDirectory, function () {

    $this->map(['GET', 'POST'], "", function ($request, $response, $args) {

        if (SessionHelper::isAdminSession()) {

            $stm = DB::getDB()->prepare("select * from files order by uploadDate desc");
            $stm->execute();

            $files = $stm->fetchAll();

            $nfiles = $stm->rowCount();

            $usedSpace = (int) DB::getConfig(DB::$histoTotalFileSize) / (1000 * 1000);
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

            $stm = DB::getDB()->prepare("select * from files where id = ? limit 1");
            $stm->bindParam(1,$fileid, PDO::PARAM_STR);
            $stm->execute();

            $file = $stm->fetch();

            IOHelper::download($file, __DIR__ . '/../' . '../uploads/', $this, true);
        }
    })->setName("adminDownload");

    $this->get('/delete-{id:[a-z0-9]{8,10}}', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {
            $fileid = $args['id'];

            $stm = DB::getDB()->prepare("select * from files where id = ? limit 1");
            $stm->bindParam(1,$fileid, PDO::PARAM_STR);
            $stm->execute();

            $file = $stm->fetch();

            $path = __DIR__ . '/../' . '../uploads/';

            IOHelper::delete($file, $path);
        }

        return $response->withRedirect($this->router->pathFor("admin"));
    })->setName("adminDelete");

    $this->get('/deleteblock-{id:[a-z0-9]{8,10}}', function ($request, $response, $args) {
        if (SessionHelper::isAdminSession()) {
            $fileid = $args['id'];

            $stm = DB::getDB()->prepare("select * from files where id = ? limit 1");
            $stm->bindParam(1,$fileid, PDO::PARAM_STR);
            $stm->execute();

            $file = $stm->fetch();

            $path = __DIR__ . '/../' . '../uploads/';

            IOHelper::delete($file, $path);

            $fileHash = $file['integrity'];

            $stm = DB::getDB()->prepare("insert into bannedFiles(fileHash) values(?) on duplicate key update fileHash=fileHash");
            $stm->bindParam(1,$fileHash, PDO::PARAM_STR);
            $stm->execute();
        }

        return $response->withRedirect($this->router->pathFor("admin"));
    })->setName("adminDeleteAndBlock");

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

            $stm = DB::getDB()->prepare("select * from news order by dateCreation desc");
            $stm->execute();

            $news = $stm->fetchAll();

            return $this->view->render($response, 'admin/news.html', [
                        'page' => 'news',
                        'news' => $news
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

                $stm = DB::getDB()->prepare("insert into news(title, newText, dateCreation) values(?, ?, NOW())");
                $stm->bindParam(1,$title, PDO::PARAM_STR);
                $stm->bindParam(2,$text, PDO::PARAM_STR);
                $stm->execute();

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

            $stm = DB::getDB()->prepare("delete from news where id = ? limit 1");
            $stm->bindParam(1,$args['id'], PDO::PARAM_INT);
            $stm->execute();

            return $response->withRedirect($this->router->pathFor("adminNews"));
        } else {
            return $response->withRedirect($this->router->pathFor("admin"));
        }
    })->setName("adminDeleteNew");

    $this->get('/blockedfiles', function (Request $request, Response $response, $args) {
        if (SessionHelper::isAdminSession()) {

            $stm = DB::getDB()->prepare("select fileHash from bannedFiles");
            $stm->execute();

            $blockedFiles = $stm->fetchAll();

            return $this->view->render($response, 'admin/blockedfiles.html', [
                'blockedFiles' => $blockedFiles
            ]);
        }
    })->setName("adminBlockedFiles");

    $this->get('/blockedfiles/delete-{filehash}', function (Request $request, Response $response, $args) {
        if (SessionHelper::isAdminSession()) {

            $fileHash = $request->getAttribute("filehash", null);

            if ($fileHash !== null){
                $stm = DB::getDB()->prepare("delete from bannedFiles where fileHash = ?");
                $stm->bindValue(1, $fileHash, PDO::PARAM_STR);
                $stm->execute();
            }

            return $response->withRedirect($this->router->pathFor("adminBlockedFiles"));

        }
    })->setName("adminBlockedFilesDelete");

    $this->map(['GET', 'POST'], '/config', function (Request $request, Response $response, $args) {
        if (SessionHelper::isAdminSession()) {
            if ($request->isPost()){
                //TODO: add or delete
            }

            return $this->view->render($response, 'admin/config.html', [

            ]);
        }
    })->setName("adminConfig");
});
