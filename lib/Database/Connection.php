<?php

    namespace Database;

    abstract class Connection
    {
        private static $conn;

        public static function getConn()
        {
            if (!self::$conn) {
                self::$conn = new \PDO('mysql:host=127.0.0.1:8889; dbname=qsw', 'root','root');
            }

            return self::$conn;
        }
    }