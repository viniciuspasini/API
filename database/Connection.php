<?php

namespace database;

use PDO;

class Connection
{
    public static function Connection(): PDO
    {
        $connection =  new PDO(
            "mysql:host=".env('HOST').";dbname=".env('DB_NAME'),
            env('USERNAME'),
            env('PASSWORD')
        );
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $connection;
    }
}