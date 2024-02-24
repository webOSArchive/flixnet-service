<?php
include "../../database.php";
$conn = connectDB();
header('Content-Type: application/json');

$sql = "SELECT * FROM tbl_genres ORDER BY genre";
$stmt = $conn->prepare($sql);
$stmt->execute();
$genres = $stmt->fetchAll(PDO::FETCH_OBJ);

foreach($genres as $genre) {
    $sql = "SELECT count(distinct tbl_movies.title) as total, tbl_movie_genres.genre_id FROM tbl_movies 
        INNER JOIN tbl_movie_genres ON tbl_movie_genres.movie_id = tbl_movies.id
        WHERE genre_id=:genreid";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':genreid', $genre->id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    $genre->count = $result[0]->total;
}
echo json_encode($genres);

?>