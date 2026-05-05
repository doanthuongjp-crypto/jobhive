<?php
class DbInfo {
    public static function getServer() {
        return 'localhost';
    }

    public static function getDbName() {
        return 'jobhive';
    }

    public static function getUserName() {
        return 'root';
    }

    public static function getPassword() {
        return '';
    }

    public static function getPort() {
        return '3306';
    }

    public static function connectDB() {
        try {
            $dsn = 'mysql:host=' . DbInfo::getServer() . ';dbname=' . DbInfo::getDbName() . ';port=' . DbInfo::getPort() . ';charset=utf8mb4';
            $option = array(
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );
            $conn = new PDO($dsn, DbInfo::getUserName(), DbInfo::getPassword(), $option);
            return $conn;
        } catch (PDOException $e) {
            die("Could not connect to the database.");
        }
    }
}
?>
