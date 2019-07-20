<?php

echo "\n==NULLUPLOAD CRON START ".date("Y-m-d H:i:s")." ==";

require 'vendor/autoload.php';
//$_SERVER['SERVER_NAME'] = "localhost";
require 'src/propelconfig.php';

$files = FilesQuery::create()->find();

foreach ($files as $file) {
    if ($file->getDeletedate()->getTimestamp() < time() ||
        (strtotime('+2 day', $file->getUploaddate()->getTimestamp()) < time() && (int) $file->getNdownloads() === 0) ||
    (!empty($file->getLastdownload()) && strtotime('+100 day', $file->getLastdownload()->getTimestamp()) < time())) {

        echo "\n->Deleting file id: " . $file->getId() . '... ';

        $path = "uploads/" . $file->getFilename();

        if (file_exists($path)) {
            unlink($path);
            echo " deleted...";
        } else {
            echo "Warning: file <" . $path . '> does not exist';
        }
        $file->delete();
        echo " done.";
    }
}

$totalsize = IOHelper::get_total_size("uploads");
file_put_contents("usedSpace", $totalsize);

echo "\n==NULLUPLOAD CRON END== \n";
