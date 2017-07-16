<?php

namespace DataSource;
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 08.07.17
 * Time: 15:22
 */

class Database {

    /**
     * @var \PDO
     */
    private $_connection;

    /**
     * @var Database|null
     */
    private static $_instance = NULL;

    private function __construct()
    {
        $dsn = "pgsql:host=localhost;dbname=vagrant";
        $this->_connection = new \PDO($dsn, 'vagrant', 'vagrant');
        $this->_connection->exec("set names utf8");
    }

    /**
     * @return Database|null
     */
    public static function getInstance()
    {
        if(!self::$_instance)  self::$_instance = new self();
        return self::$_instance;
    }

    private function __clone() {}

    private function __wakeup() {}

    public function getConnection()
    {
        return $this->_connection;
    }
}