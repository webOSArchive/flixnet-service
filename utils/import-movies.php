<h2>Movie Importer</h2>
<form method="get">
<?php
include "../secrets.php";
echo "Checking environment...";
$resumefile = '../data/resume.txt';
$resume;
if (isset($_GET["resume"]))
    $resume = $_GET["resume"];
// Open the file to get existing content
if (file_exists($resumefile)) {
    $resume = file_get_contents($resumefile);
    echo "Read OK!";
} else {
    try {
        file_put_contents($resumefile, 0);
        if (!file_exists($resumefile))
            throw new Exception ("Could not create file");
        echo "Create OK!";
    } catch(Exception $ex) {
        echo ("<li>Warning, could not create resume file, import will not survive browser window close. To resolve, provide write access to: " . $resumefile);
    }    
}
$rd = "";
if (isset($_GET["resume"]))
    $rd = "disabled";
eol();

//Determine config
echo "Resume:   <input type='text' name='resume' value='" . $resume ."' " . $rd .">" . eol();

$md = "";
$max = 25;  //Number of movies to load each pass
if (isset($_GET["max"])) {
    $max = $_GET["max"];
    $md = "disabled";
}
echo "Max/Pass:  <input type='text' name='max' value='" . $max ."' " . $md .">" . eol();

$cooldown = 2;  //Delay between reloading page to start another pass
$dd = "";
if (isset($_GET["cooldown"])) {
    $cooldown = $_GET["cooldown"];
    $dd = "disabled";
}
echo "Cooldown: <input type='text' name='cooldown' value='" . $cooldown ."' " . $dd .">" . eol();

$go = false;    //User confirms ready
if (isset($_GET["go"]))
    $go = $_GET["go"];
echo "<input type='hidden' name='go' value='true'>" . eol();

//Check for API key
echo "Checking credentials...";
if (isset($apiKey)) {
    echo "OK";
} else {
    die("TMDB API Key missing or not set");
}
eol();

//Make sure database connections works
echo "Checking Database...";
$conn;
try {
    $conn = new PDO("mysql:host=$dbserver;dbname=flixnet", $dbuser, $dbpasswd);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "OK";
} catch(PDOException $e) {
    echo "<br>Error: " . $e->getMessage();
    die();
}
eol();

//Make sure we can get the data file
echo "Checking Data file...";
if (file_exists("../data/movies.json")) {
    $data = file_get_contents ("../data/movies.json");
    echo "OK";
} else {
    $data = getData("https://raw.githubusercontent.com/casbah-ma/cinedantan/master/public/database/movies.json");
    try {
        file_put_contents("../data/movies.json", $movies);
        if (!file_exists("../data/movies.json"))
            throw new Exception("Could not create movie file");
        echo "OK";
    } catch (Exception $ex) {
        echo "<li>Warning: could not load or save movie data, it will be fetched from the remote server repeatedly. You may need to set a lower max to avoid timeouts. Make data/movies.json writeable to avoid this condition.";
    }
}
eol();

if (!$go)
    echo "<input type='Submit' value='Ready!'>" . PHP_EOL;
?>
</form>
<?php
$lookupURL = "https://api.themoviedb.org/3/find/%?api_key=" . $apiKey . "&external_source=imdb_id";
//Documentation on image paths: https://www.themoviedb.org/talk/5aeaaf56c3a3682ddf0010de
$backdropURL = "https://image.tmdb.org/t/p/w300/"; //or w154
$posterURL = "https://image.tmdb.org/t/p/w154/"; //or w92

if (!$go) {
//    echo "<a href='"
}
if ($go) {
    echo "<hr>Importing Movies...";

    $movies = json_decode($data, true);
    $count = 0;
    $total = count($movies);
    echo "Parsing " . $max . " of " . $total . " movies starting at " . $resume . " (" . round(($resume / $total)*100) . "% complete)" . eol();
    echo "<hr>";
    $useMax = $max + $resume;
    foreach ($movies as $movie) {
        if ($count >= $resume) {
            echo "Processing movie #" . $count . ": " . $movie['title'] . eol();
            $imdbid = $movie['imdb'];
            if (isset($imdbid) && $imdbid != "") {
        
                $sql = "SELECT * FROM tbl_movies WHERE imdb_id=:i";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':i', $imdbid);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            
                if ($result) {
                    echo " - Movie already exists, skipping!" . eol();
                } else {
                    $tmdbURL = str_replace("%", $imdbid, $lookupURL);
                    $tmdbInfo = getData($tmdbURL);            
                    if (isset($tmdbInfo)) {
                        $tmdbData = json_decode($tmdbInfo);
                        echo " - backdrop: " . $backdropURL . $tmdbData->movie_results[0]->backdrop_path . eol();
                        echo " - poster: " . $posterURL . $tmdbData->movie_results[0]->poster_path . eol();
                    }
                }
                eol();
            }
        }
        $count++;
        if ($count >= $useMax) {
            $resume = $count;
            break;
        }
    }
    echo "<hr>";
    if ($resume >= $total || $count >= $total) {
        echo "All done processing movies!" . eol();
        file_put_contents($resumefile, 0);
    } else {
        echo "Continuing processing movies at " . $resume . " in " . $cooldown . " seconds..." . eol();
        try {
            file_put_contents($resumefile, $resume);
            if (!file_exists($resumefile))
                throw new Exception("Could not update resume file");
            echo PHP_EOL . "<script>window.setTimeout('document.location.reload()', " . ($cooldown*1000) . ")</script>"; 
        } catch(Exception $ex) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
            $newUrl = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $newUrl = explode("?", $newUrl)[0];
            $newUrl = $newUrl . "?go=true&resume=" . $resume . "&max=" . $max . "&cooldown=" . $cooldown;
            echo PHP_EOL . "<script>window.setTimeout('document.location=\"" . $newUrl . "\"', " . ($cooldown*1000) . ")</script>"; 
        }
    }
    echo "<p></p>";
}

function getData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if(curl_error($ch)) {
        echo curl_error($ch);
    }
    curl_close($ch);
    return $response;
}

function eol() {
    echo "<br>" . PHP_EOL;
}
?>
