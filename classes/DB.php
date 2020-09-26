<?php

namespace nullupload;

use PDO;

class DB
{
    private static $config = ['server' => 'localhost',
        'database' => 'nullupload',
        'username' => 'root',
        'password' => ''];

    private static $pdo;

    public static $configDisableUpload = "configDisableUpload";
    public static $configDisableUploadMessage = "configDisableUploadMessage";
    public static $histoTotalFileUpload = "histoTotalFileUpload";
    public static $histoTotalFileSize = "histoTotalFileSize";
    public static $histoTotalFileDownloads = "histoTotalFileDownloads";


    public static function init(array $config){
        self::$config = $config;

            self::$pdo = new \PDO('mysql:host='.self::$config['server'].';dbname='.self::$config['database'].';charset=UTF8', self::$config['username'], self::$config['password'], array(
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, //make the default fetch be an associative array
                //\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES utf8; SET time_zone = 'Europe/Madrid';"
            ));
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        //self::$pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT,1);

    }

    public static function getDB(): \PDO{
        return self::$pdo;
    }

    public static function setConfig(string $name, string $value):void{
        $stm = DB::getDB()->prepare("INSERT INTO config (name, value) VALUES(:name, :value) ON DUPLICATE KEY UPDATE value=:value");
        $stm->bindValue(":name",$name, PDO::PARAM_STR);
        $stm->bindValue(":value",$value, PDO::PARAM_STR);
        $stm->execute();
    }

    public static function getConfig(string $name):string{
        $stm = DB::getDB()->prepare("select value from config where name = :name limit 1");
        $stm->bindValue(":name",$name, PDO::PARAM_STR);
        $stm->execute();

        return $stm->fetch()['value'];
    }

}