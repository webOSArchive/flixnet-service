<?php
include "../../../database.php";
$conn = connectDB();

$query = "";
if (isset($_GET["query"]))
    $query = $_GET["query"];
if ($query == "") {
    die ("specify query in querystring");
}
$take = 10;
if (isset($_GET["take"]) && is_numeric($_GET["take"]))
    $take = $_GET["take"];
if ($take > 100)
    $take = 100;
$skip = 0;
if (isset($_GET["skip"]) && is_numeric($_GET["skip"]))
    $skip = $_GET["skip"];

header('Content-Type: application/json');
$sql = "SELECT * FROM tbl_movies WHERE title LIKE :query ORDER BY title LIMIT " . $take . " OFFSET " . $skip;

$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', $query . "%");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
echo json_encode($result);
?>