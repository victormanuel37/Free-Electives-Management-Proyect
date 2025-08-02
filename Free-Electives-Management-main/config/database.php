<?php
class DataBase {
    private static $dsn = "mysql:host=localhost;dbname=ccom4019;charset=utf8mb4";
    private static $username = "root";
    private static $password = "";
    private static $db; 

    private function __construct() {}

    public static function getDB() {
        if (!self::$db) { 
            try {
                self::$db = new PDO(
                    self::$dsn,
                    self::$username,
                    self::$password
                );
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("<p>Error en la conexión: " . $e->getMessage() . "</p>");
            }
        }
        return self::$db; 
    }
}
?>
