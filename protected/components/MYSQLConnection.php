<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.2016
 * Time: 10:35
 */
class MYSQLConnection {
    private static $_connections = [];
    public static function getConnection($config) {
        if (!self::$_connections[$config['dbName']]) {
            $conn = self::createConnectionFromConfig($config);
            if ($conn) {
                self::$_connections[$config['dbName']] = $conn;
            } else {
                throw new Exception('Could not establish connection to '.$config['dbName']);
            }
        }
        return self::$_connections[$config['dbName']];
    }
    private static function createConnectionFromConfig($config) {
        return mysqli_connect('localhost', $config['userName'], $config['pss'], $config['dbName']);
    }
    private function __construct()
    {
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    private function __sleep()
    {
        // TODO: Implement __sleep() method.
    }
    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }
}