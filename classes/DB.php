<?php

namespace nullupload;

class DB
{
    private static $config = ['server' => 'localhost',
        'database' => 'nullupload',
        'username' => 'root',
        'password' => ''];

    private static $pdo;


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

}