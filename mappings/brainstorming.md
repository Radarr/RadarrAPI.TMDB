## Possible DB Layout

Master Table:

| id | tmdbid | imdbid | report_count | locked  |
|---|----|----|---|---|
| 1 | 11     | tt000011 |  13           | true  |
| 2 | 2      | tt000002 | -4           |  true |
| 3 | 11     | tt000011 |  -5           | true   |
| 4 | 2     | tt000002 | 4            | false  |

Title Mappings:

| master_id (Foreign Key) | aka_title           | 
|----------|---------------------|
| 1 | Star Wars: Whatevs  |
| 2 | Ariel: What a beach | 
| | |

Year Mappings:

| master_id (Foreign Key) | aka_year |
|----------|----------|
| 3 | 1978     |
| 4 | 1999     | 
|          |              |


## Possible API

### POST /mappings/add

Add new Mapping

#### Params
| name | type |
|--------|----------|
| type | string (either 'title' or 'year') |
| tmdbid | int |
| imdbid | string (must match: /tt\d{7}/) |
| aka_title | string (only present if type == title |
| aka_year | int (only present if type == year) |

#### Sample Request

`POST /mappings/add?type=title&tmdbid=11&imdbid=tt0000011&aka_title=Star%20Wars`

`POST /mappings/add?type=year&tmdbid=11&imdbid=tt0000011&aka_year=1978`

#### Sample Response

```
{
  "type" : "title",
  "tmdbid" : 11,
  "imdbid" : "tt0000011",
  "aka_title" : "Star Wars"
}
```

```
{
  "type" : "year",
  "tmdbid" : 11,
  "imdbid" : "tt0000011",
  "aka_year" : 1978
}
```

### GET /mappings/get

Retrieve mappings for movie

#### Params
| name | type | optional   | default value           |
|--------|----------|---------------------|------|
| tmdbid | int | only when imdbid is present | -  |
| imdbid | string (must match: /tt\d{7}/) | only when tmdbid is present | - |
| min_report | int |      yes    |       3              |

#### Sample Request

`GET /mappings/get?tmdbid=11&min_report=5`

#### Sample Response

```
[
  {
    "type" : "title",
    "tmdbid" : 11,
    "imdbid" : "tt0000011",
    "aka_title" : "Star Wars"
  },
  {
    "type" : "year",
    "tmdbid" : 11,
    "imdbid" : "tt0000011",
    "aka_year" : 1978
  }
]
```

## "Rate Limiting"

Each IP Address can only "vote" on a mapping once a day.

### Table for IP Addresses

| ip | masterid |
|--|--|
| 192.168.1.117 | 2 |

#### Sample "rate limited" response

```
{
  "error" : "Your IP already submitted this mapping today"
}
```
