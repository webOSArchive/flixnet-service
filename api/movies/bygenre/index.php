<?php
include "../../../database.php";
$conn = connectDB();

$genre = "";
if (isset($_GET["genre"]))
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

header('Content-Type: application/json');
if (is_numeric($genre))
    $sql = "SELECT * FROM tbl_genres WHERE id=:genre";
else
    $sql = "SELECT * FROM tbl_genres WHERE genre LIKE :genre";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':genre', $genre);
$stmt->execute();
$genres = $stmt->fetchAll(PDO::FETCH_OBJ);
if (count($genres) > 0) {
    $genreid = $genres[0]->id;

    $sql = "SELECT tbl_movies.*, tbl_movie_genres.genre_id FROM tbl_movies 
        INNER JOIN tbl_movie_genres ON tbl_movie_genres.movie_id = tbl_movies.id 
        WHERE genre_id=:genreid ";
    if (!isset($_GET["skip"]))
        $sql .= " ORDER BY RAND() ";
    else
        $sql .= " ORDER BY title ";
    $sql .= " LIMIT " . $take;
    if (isset($_GET["skip"]))
        $sql .= " OFFSET " . $skip;
    
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':genreid', $genreid);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($result);
} else {
    echo "[]";
}
?>