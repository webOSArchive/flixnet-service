<?php
include_once "secrets.php";

function connectDB() {
    global $dbserver, $dbuser, $dbpasswd;
    $conn;
    try {
        if (!isset($dbserver))
            die("Database server not set.");
        if (!isset($dbuser))
            die("Database username not set.");
        if (!isset($dbpasswd))
            die("Database password not set.");
        $conn = new PDO("mysql:host=$dbserver;dbname=flixnet", $dbuser, $dbpasswd);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "<br>Database Connection Error: " . $e->getMessage();
        die();
    }
}
?>