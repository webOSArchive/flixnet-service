<?php
include "../../../secrets.php";
$genre = "";
if (isset($_GET["genre"]) && is_numeric($_GET["genre"]))
    $genre = $_GET["genre"];
if ($genre == "") {
    die ("specify genre in querystring");
}
$take = 10;
if (isset($_GET["take"]) && is_numeric($_GET["take"]))
    $take = $_GET["take"];
if ($take > 100)
    $take = 100;
$skip = 0;
if (isset($_GET["skip"]) && is_numeric($_GET["skip"]))
    $skip = $_GET["skip"];
    
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
//TODO: JOIN query with genre
if (!isset($_GET["skip"]))
    $sql = "SELECT * FROM tbl_movies ORDER BY RAND() LIMIT " . $take;
else
    $sql = "SELECT * FROM tbl_movies LIMIT " . $take . " OFFSET " . $skip;

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
echo json_encode($result);
?>