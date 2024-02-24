# Public Domain Movie API

- you probably want security on the utils folder
- `utils/import-movies.php` works best if it can write to a text file called resume.txt and a movie file called `../data/movies.json`

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