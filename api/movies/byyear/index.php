<?php
include "../../../database.php";
$conn = connectDB();

$year = 0;
if (isset($_GET["year"]) && is_numeric($_GET["year"]))
    $year = $_GET["year"];
if ($year == 0) {
    die ("specify year in querystring");
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
if (!isset($_GET["skip"]))
    $sql = "SELECT * FROM tbl_movies WHERE year=:year ORDER BY RAND() LIMIT " . $take;
else
    $sql = "SELECT * FROM tbl_movies WHERE year=:year ORDER BY title LIMIT " . $take . " OFFSET " . $skip;

$stmt = $conn->prepare($sql);
$stmt->bindValue(':year', $year);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
echo json_encode($result);
?>