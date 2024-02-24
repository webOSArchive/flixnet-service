# Public Domain Movie API

- you probably want security on the utils folder
- `utils/import-movies.php` works best if it can write to a text file called resume.txt and a movie file called `../data/movies.json`
- movie data comes from: https://github.com/casbah-ma/cinedantan/ 
- extra metadata comes from TMDB
- movies come from Archive.org

## Create the Database

Use a mysql compatible server (I used mariadb)

- `CREATE DATABASE flixnet;`
- `USE flixnet;`
- `SOURCE /path/to/flixnet.sql`
- `CREATE USER 'dev'@'localhost' IDENTIFIED BY 'Str0ngP@ssword';`
- `GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER ON flixnet.* TO 'dev'@'localhost' IDENTIFIED BY 'Str0ngP@ssword';`
- `FLUSH PRIVILEGES`

## API

### /api/movies

- If no query string is set, returns 10 random movies
- If only `take` is set to a numeric value, returns that number of random movies (max 100)
- If `skip` is set to a numeric value, returns either 10 or `take` number of movies in order, starting at the position indicated by skip

### /api/movies/bygenre

- **Required** query string parameter is `genre` followed by *either* the name (string) or id (numeric) of the genre
- If only `take` is set to a numeric value, returns that number of random movies (max 100) in that specified genre
- If `skip` is set to a numeric value, returns either 10 or `take` number of movies in order, starting at the position indicated by skip

### /api/movies/bytitle

- **Required** query string parameter is `query` followed by the string to search for in movie titles
    - Search is performed with a wildcard following the search string
- If only `take` is set to a numeric value, returns that number of random movies (max 100) with a matching title
- If `skip` is set to a numeric value, returns either 10 or `take` number of movies in order, starting at the position indicated by skip

### /api/movies/byyear

- **Required** query string parameter is `year` and must be numeric
    - Search is performed with a wildcard following the search string
- If only `take` is set to a numeric value, returns that number of random movies (max 100) in the given year
- If `skip` is set to a numeric value, returns either 10 or `take` number of movies in order, starting at the position indicated by skip

### /api/genres

- Returns an array of the known genres in alphabetical order, including a count of movies in that genre

### /api/years

- Returns an array of the known years in numerical order, including a count of movies in that year