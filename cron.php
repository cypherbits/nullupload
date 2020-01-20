<?php

use nullupload\DB;

echo "\n==NULLUPLOAD CRON START ".date("Y-m-d H:i:s")." ==";

require 'vendor/autoload.php';
$settings = require __DIR__ . '/src/settings.php';

try{
    DB::init($settings['settings']['database']);
}catch (PDOException $e){
    $c->logger->addError($e->getMessage());
    die("Database connection error");
}

$stm = DB::getDB()->prepare("select * from files");
$stm->execute();
//TODO: select already files to be deleted, do not check it in PHP

$files = $stm->fetchAll();

foreach ($files as $file) {
    if (strtotime($file['deleteDate']) < time() ||
        (strtotime('+1 day', strtotime($file['uploadDate'])) < time() && (int) $file['nDownloads'] < 2) ||
    (!empty($file['lastDownload']) && strtotime('+100 day', strtotime($file['lastDownload'])) < time())) {

        echo "\n->Deleting file id: " . $file['id'];

        $path = "uploads/" . $file['filename'];

        if (file_exists($path)) {
            unlink($path);
            echo " -> Deleted.";
        } else {
            echo " -> Warning: file <" . $path . '> does not exist';
        }
        $stm = DB::getDB()->prepare("delete from files where id = ? limit 1");
        $stm->bindParam(1,$file['id'], PDO::PARAM_INT);
        $stm->execute();
        echo " -> Done.";
    }
}

$totalsize = IOHelper::get_total_size("uploads");
DB::setConfig(DB::$histoTotalFileSize, $totalsize);

echo "\n==NULLUPLOAD CRON END== \n";
