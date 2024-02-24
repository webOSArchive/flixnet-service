<?php
include "../../database.php";
$conn = connectDB();

header('Content-Type: application/json');
$sql = "SELECT UNIQUE year FROM tbl_movies ORDER BY year";
$stmt = $conn->prepare($sql);
$stmt->execute();
$years = $stmt->fetchAll(PDO::FETCH_OBJ);

foreach ($years as $year) {
    $sql = "SELECT COUNT(DISTINCT title) as total FROM tbl_movies where year=:year";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':year', $year->year);
    $stmt->execute();
    $counts = $stmt->fetchAll(PDO::FETCH_OBJ);
    $year->count = $counts[0]->total;
}
echo json_encode($years);
?>