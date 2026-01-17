<?php

namespace Davidany\Codegen;

use PDO;

class DB
{
    protected static $instances = array();

    protected function __construct($db_name = DB_NAME, $db_host = DB_HOST, $db_user = DB_USERNAME, $db_pass = DB_PASSWORD)
    {
        try {
            self::$instances["$db_name.$db_host.$db_user"] = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
            self::$instances["$db_name.$db_host.$db_user"]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public static function getInstance($db_name = DB_NAME, $db_host = DB_HOST, $db_user = DB_USERNAME, $db_pass = DB_PASSWORD)
    {
        $key = "$db_name.$db_host.$db_user";
        if (!isset(self::$instances[$key])) {
            new DB($db_name, $db_host, $db_user, $db_pass);
        }

        return self::$instances[$key];
    }

    public static function getForeignKeysForTable($table_name)
    {
        $stmt = self::$instances["$db_name.$db_host.$db_user"]->prepare("
            SELECT
                CONSTRAINT_NAME,
                COLUMN_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE
                TABLE_NAME = :table_name
                AND CONSTRAINT_NAME <> 'PRIMARY'
                AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        $stmt->bindParam(':table_name', $table_name);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
