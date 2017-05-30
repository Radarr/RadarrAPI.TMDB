## Possible DB Layout

### Master Table:

| id | tmdbid | imdbid | report_count | total_reports | locked  |
|---|----|----|---|---|---|
| 1 | 11     | tt000011 |  13         | 15  | true  |
| 2 | 2      | tt000002 | -4          | 6 |  true |
| 3 | 11     | tt000011 |  -5         | 10  | true   |
| 4 | 2     | tt000002 | 4            | 50 | false  |

### Title Mappings:

| mappingsid (Foreign Key) | aka_title | aka_clean_title |
|----------|---------------------|---------|
| 1 | Star Wars: Whatevs  | starwarswhatevs |
| 2 | Ariel: What a beach | arielwhatbeach |
| | | |

**Note:** Clean title is used to ensure that only one mapping per aka_title exists (should be unique). It also should help mapping with e.g. files.

### Year Mappings:

| mappingsid (Foreign Key) | aka_year |
|----------|----------|
| 3 | 1978     |
| 4 | 1999     | 
|          |              |

### Meta Table:

| mappingsid (Foreign Key) | Event Type | Date |
|----|----|----|
| 1 | 0 | 2017-04-05:15:10:46 |
| 2 | 1 | 2017-04-07:15:12:33 |

**Event Types:**
- Add Mapping: 0
- Approve mapping: 1
- Disapprove mappinng: 2
- Lock mapping: 3

### Create Syntaxes:
```
-- Create syntax for TABLE 'ip_adresses'
CREATE TABLE `ip_adresses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL DEFAULT '',
  `mappingsid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_mappingsid_ip` (`mappingsid`),
  CONSTRAINT `FK_mappingsid_ip` FOREIGN KEY (`mappingsid`) REFERENCES `mappings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'mappings'
CREATE TABLE `mappings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tmdbid` int(11) NOT NULL,
  `imdbid` varchar(9) NOT NULL DEFAULT '',
  `report_count` int(11) NOT NULL,
  `locked` tinyint(11) NOT NULL DEFAULT '0',
  `total_reports` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'events'
CREATE TABLE `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) unsigned NOT NULL,
  `mappingsid` int(11) unsigned NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_mappings_events` (`mappingsid`),
  CONSTRAINT `FK_mappings_events` FOREIGN KEY (`mappingsid`) REFERENCES `mappings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'title_mappings'
CREATE TABLE `title_mappings` (
  `mappingsid` int(11) unsigned NOT NULL,
  `aka_title` text NOT NULL,
  PRIMARY KEY (`mappingsid`),
  CONSTRAINT `FK_mappingsid` FOREIGN KEY (`mappingsid`) REFERENCES `mappings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'year_mappings'
CREATE TABLE `year_mappings` (
  `mappingsid` int(11) unsigned NOT NULL,
  `aka_year` smallint(4) NOT NULL,
  PRIMARY KEY (`mappingsid`),
  CONSTRAINT `year_mappings_ibfk_1` FOREIGN KEY (`mappingsid`) REFERENCES `mappings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```
## Possible API

### POST /mappings/add

Add new Mapping. If mapping already exists (determined via clean_title) it is "upvoted".

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
  "id" : 1,
  "type" : "title",
  "tmdbid" : 11,
  "imdbid" : "tt0000011",
  "aka_title" : "Star Wars",
  "report_count" : 1,
  "total_reports" : 1
}
```

```
{
  "id" : 2,
  "type" : "year",
  "tmdbid" : 11,
  "imdbid" : "tt0000011",
  "aka_year" : 1978,
  "report_count" : 1,
  "total_reports" : 1
}
```

### PUT /mappings/vote

"Vote" on existing mapping

#### Params
| name | type |
|--------|----------|
| id | int |
| direction | int (either 1 or -1, default 1)|

#### Sample Request

`PUT /mappings/vote?id=1&direction=1`

`PUT /mappings/vote?id=2&direction=-1`

#### Sample Response

```
{
  "id" : 1,
  "type" : "title",
  "tmdbid" : 11,
  "imdbid" : "tt0000011",
  "aka_title" : "Star Wars",
  "report_count" : 2,
  "total_reports" : 2
}
```

```
{
  "id" : 2,
  "type" : "year",
  "tmdbid" : 11,
  "imdbid" : "tt0000011",
  "aka_year" : 1978,
  "report_count" : 0,
  "total_reports" : 2
}
```

### GET /mappings/get

Retrieve mappings for movie

#### Params
| name | type | optional   | default value           |
|--------|----------|---------------------|------|
| tmdbid | int | only when imdbid is present | -  |
| imdbid | string (must match: /tt\d{7}/) | only when tmdbid is present | - |
| type | string (either "title", "year" or "all" | yes | all |
| min_report | int |      yes    |       3              |

#### Sample Request

`GET /mappings/get?tmdbid=11&min_report=-50`
`GET /mappings/get?tmdbid=11&min_report=5&type=title`

#### Sample Response

```
{
  "tmdbid" : 11,
  "imdbid" : "tt0000011",
  "titles" : [
    {
      "id" : 1,
      "type" : "title",
      "aka_title" : "Star Wars",
      "report_count" : 15,
      "total_reports" : 20,
      "locked" : false
    }
  ],
  "years" : [
    {
      "id" : 2,
      "type" : "year",
      "aka_year" : 1978,
      "report_count" : -4,
      "total_reports" : 6,
      "locked" : true
    }
  ]
}
```

```
{
  "tmdbid" : 11,
  "imdbid" : "tt0000011",
  "titles" : [
    {
      "id" : 1,
      "type" : "title",
      "aka_title" : "Star Wars",
      "report_count" : 15,
      "total_reports" : 20,
      "locked" : false
    }
  ]
}
```

## "Rate Limiting"

Each IP Address can only "vote" on a mapping once a day.

### Table for IP Addresses

| ip | mappingsid |
|--|--|
| 192.168.1.117 | 2 |

#### Sample "rate limited" response

```
{
  "error" : "Your IP already submitted this mapping today"
}
```
