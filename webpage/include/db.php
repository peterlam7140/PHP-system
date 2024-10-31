<?php
function connectDBWithPDO()
{
    try {
        global $dbhost;
        global $dbuser;
        global $dbpwd;
        global $dbname;
        $database = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASSWORD);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $database->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
        return $database;
    } catch (Exception $e) {
        echo 'Exception -> ';
        var_dump($e);
        throw $e;
    }
}

$conn = connectDBWithPDO();

function connectDB() {
    global $conn;
    return $conn;
}