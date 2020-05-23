<?php

// Routes

use nullupload\DB;

$app->get('/', function ($request, $response, $args) {

    // CSRF token name and value
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $name = $request->getAttribute($nameKey);
    $value = $request->getAttribute($valueKey);

    $settings = $this->get('settings')['nullupload'];

    $totalsize = (int) DB::getConfig(DB::$histoTotalFileSize);

    $stm = DB::getDB()->prepare("select * from news order by dateCreation desc limit 1");
    $stm->execute();

    $latestNew = $stm->fetch();

    if ((int) $settings['maxLimit'] >= $totalsize) {
        return $this->view->render($response, 'index.html', [
                    'page' => 'home',
                    'nameKey' => $nameKey,
                    'name' => $name,
                    'valueKey' => $valueKey,
                    'value' => $value,
            'latestNew' => $latestNew
        ]);
    } else {
        return $this->view->render($response, 'index-limited.html', [
                    'page' => 'home'
        ]);
    }
})->setName('home');


require("controllers/do-upload.php");

require("controllers/download.php");

require("controllers/delete.php");

require("controllers/admin.php");

$app->get('/news', function ($request, $response, $args) {

    $stm = DB::getDB()->prepare("select * from news order by dateCreation desc");
    $stm->execute();

    $news = $stm->fetchAll();

    return $this->view->render($response, 'news.html', [
                'page' => 'news',
                'news' => $news
    ]);
})->setName('news');

$app->get('/statistics', function ($request, $response, $args) {

    $stm = DB::getDB()->prepare("select count(*) as nfiles, sum(nDownloads) as ndownloads from files");
    $stm->execute();

    $nums = $stm->fetch();
    
    return $this->view->render($response, 'statistics.html', [
                'page' => 'statistics',
                'nfiles' => $nums['nfiles'],
                'ndownloads' => $nums['ndownloads'],
                'totalFileSize' => DB::getConfig(DB::$histoTotalFileSize),
                'totalFilesUploaded' => DB::getConfig(DB::$histoTotalFileUpload),
                'totalFilesDownloaded' => DB::getConfig(DB::$histoTotalFileDownloads)
    ]);
})->setName('statistics');

$app->get('/report', function ($request, $response, $args) {
    return $this->view->render($response, 'report.html', [
                'page' => 'report'
    ]);
})->setName('report');

$app->get('/terms', function ($request, $response, $args) {
    return $this->view->render($response, 'terms.html', [
                'page' => 'terms'
    ]);
})->setName('terms');

$app->get('/privacy', function ($request, $response, $args) {
    return $this->view->render($response, 'privacy.html', [
                'page' => 'privacy'
    ]);
})->setName('privacy');



$app->get('/news/{id:[0-9]+}', function ($request, $response, $args) {


    return $this->view->render($response, 'news-item.html', [
                'page' => 'news'
    ]);
});
