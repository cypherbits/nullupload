<?php

use nullupload\DB;

echo "\n==NULLUPLOAD CRON START ".date("Y-m-d H:i:s")." ==";

require 'vendor/autoload.php';

$stm = DB::getDB()->prepare("select * from files");
$stm->execute();

$files = $stm->fetchAll();

foreach ($files as $file) {
    if ($file['deleteDate'] < time() ||
        (strtotime('+2 day', $file['uploadDate']) < time() && (int) $file['nDownloads'] === 0) ||
    (!empty($file['lastDownload']) && strtotime('+100 day', $file['lastDownload']) < time())) {

        echo "\n->Deleting file id: " . $file['id'] . '... ';

        $path = "uploads/" . $file['filename'];

        if (file_exists($path)) {
            unlink($path);
            echo " deleted...";
        } else {
            echo "Warning: file <" . $path . '> does not exist';
        }
        $stm = DB::getDB()->prepare("delete from files where id = ? limit 1");
        $stm->bindParam(1,$file['id'], PDO::PARAM_INT);
        $stm->execute();
        echo " done.";
    }
}

$totalsize = IOHelper::get_total_size("uploads");
DB::setConfig(DB::$histoTotalFileSize, $totalsize);

echo "\n==NULLUPLOAD CRON END== \n";
