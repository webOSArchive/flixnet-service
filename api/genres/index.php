<?php
include "../../database.php";
$conn = connectDB();
header('Content-Type: application/json');
//TODO: count of occurence?
$sql = "SELECT * FROM tbl_genres ORDER BY genre";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
echo json_encode($result);
?>