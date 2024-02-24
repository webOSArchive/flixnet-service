<?php
include "../../secrets.php";

$conn;
try {
    $conn = new PDO("mysql:host=$dbserver;dbname=flixnet", $dbuser, $dbpasswd);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "<br>Database Connection Error: " . $e->getMessage();
    die();
}
header('Content-Type: application/json');
//TODO: count of occurence?
$sql = "SELECT UNIQUE year FROM tbl_movies ORDER BY year";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
echo json_encode($result);
?>