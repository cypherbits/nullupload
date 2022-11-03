<?php // Clean and remove uploaded files that do not match any valid download

use nullupload\DB;

echo "\n==NULLUPLOAD CLEAN START ".date("Y-m-d H:i:s")." ==";

require 'vendor/autoload.php';
$settings = require __DIR__ . '/src/settings.php';

try{
    DB::init($settings['settings']['database']);
}catch (PDOException $e){
    $c->logger->addError($e->getMessage());
    die("Database connection error");
}

$stm = DB::getDB()->prepare("select filename from files");
$stm->execute();

$dbfiles = $stm->fetchAll();
$usedFiles = [];
foreach($dbfiles as $dbfile){
    $usedFiles[]=$dbfile['filename'];
}

$realfiles = array_values(array_diff(scandir("uploads"), array('..', '.')));

$usedFiles = array_values($usedFiles);

//var_dump($realfiles);
//var_dump($usedFiles);


foreach($realfiles as $realfile){
    $realfilepath = "uploads/" . $realfile;

    if (!in_array($realfile, $usedFiles)){
        echo 'Deleting file ' . $realfile;
        unlink($realfilepath);
        echo " -> Deleted.";
    }
}

$totalsize = IOHelper::get_total_size("uploads");
DB::setConfig(DB::$histoTotalFileSize, $totalsize);

echo "\n==NULLUPLOAD CLEAN END== \n";
