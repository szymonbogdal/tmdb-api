# TMDB API
A Laravel-based API that provides movie, series and genre data from TMDB.

## Start
To scrape data from TMDB and save it to database run command: `tmdb:import`

## API Endpoints

All endpoints supports language parameter. To select specific language add lang parameter: `?lang=en`<br>
Supported languages: `[ en, pl, de ]`

### Movies

#### Get All Movies
```http
GET /api/movies
```

#### Get Single Movie
```http
GET /api/movies/{id}
```
#### Movie Structure
```json
    {
      "id": 6,
      "title": "Warfare",
      "overview": "A platoon of Navy SEALs embarks on a dangerous mission in Ramadi, Iraq, with the chaos and brotherhood of war retold through their memories of the event.",
      "genres": [
        {
          "id": 1,
          "name": "Action"
        },
        {
          "id": 18,
          "name": "War"
        }
      ]
    }
```

### Series

#### Get All Series
```http
GET /api/series
```

#### Get Single Series
```http
GET /api/series/{id}
```
#### Serie Structure
```json
{
      "id": 4,
      "name": "Watch What Happens Live with Andy Cohen",
      "overview": "Bravo network executive Andy Cohen discusses pop culture topics with celebrities and reality show personalities.",
      "genres": [
        {
          "id": 4,
          "name": "Comedy"
        }
      ]
    }
```
### Genres

#### Get All Genres
```http
GET /api/genres
```

#### Get Single Genre
```http
GET /api/genres/{id}
```
#### Genre Structure
```json
    {
      "id": 1,
      "name": "Action"
    }
```