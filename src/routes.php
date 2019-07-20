<?php

// Routes

$app->get('/', function ($request, $response, $args) {

    // CSRF token name and value
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $name = $request->getAttribute($nameKey);
    $value = $request->getAttribute($valueKey);

    $settings = $this->get('settings')['nullupload'];

    $totalsize = (int) file_get_contents(__DIR__ . "/../usedSpace");

    $news = NewsQuery::create()->find();
    $latestNew = $news->getLast();

    if ((int) $settings['maxLimit'] > $totalsize) {
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

//require("controllers/users.php");

//$app->get('/test', function ($request, $response, $args) {
//
//    $deleteDate = strtotime('+30 days', time());
//
//    $rem = $deleteDate - time();
//    $day = floor($rem / 86400);
//    $hr = floor(($rem % 86400) / 3600);
//    $min = floor(($rem % 3600) / 60);
//    $sec = ($rem % 60);
//    if ($day)
//        echo "$day Days ";
//    if ($hr)
//        echo "$hr Hours ";
//    if ($min)
//        echo "$min Minutes ";
//    if ($sec)
//        echo "$sec Seconds ";
//    echo "Remaining...";
//    
//    
//
//
//    exit;
//});

$app->get('/news', function ($request, $response, $args) {

    $news = NewsQuery::create()->orderByDatecreation(\Propel\Runtime\ActiveQuery\Criteria::DESC)->find();

    return $this->view->render($response, 'news.html', [
                'page' => 'news',
                'news' => $news->toArray()
    ]);
})->setName('news');

$app->get('/statistics', function ($request, $response, $args) {

    $files = FilesQuery::create()
            ->find();
    
    $nfiles = $files->count();
    
    $ndownloads = 0;
    
    //TODO with a SUM on a select
    foreach($files as $file){
        $ndownloads += $file->getNdownloads();
    }
    
    return $this->view->render($response, 'statistics.html', [
                'page' => 'statistics',
                'nfiles' => $nfiles,
                'ndownloads' => $ndownloads
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
