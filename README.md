# RadarrAPI.TMDB
Backend Stuff for Radarr regarding TMDB (Discover, lists, Mappings, etc.)

**Manually deployed to https://api.radarr.video/**

## Setup Development Environment

- Python Scripts need Python 2.7, additionally the following python packages are required: peewee, python-mysql, requests
- PHP Scripts (and thus the whole api) need PHP 5.6
- Most of the API also needs a MySQL server with a copy of the TMDB database. To get a copy, see `TMDBDUMP.py`.

### Configuring the MySQL server to use with the API

After cloning the repo into the www folder of your webserver, create a file called ini.json in the following directory: `/path/to/www/../apps/radarr/ini/ini.json`.
Your directory structure should now look as follows:
```
/ (root)
    path
        to
-----------www
                RadarrApi.TMDB
                    ... (all of RadarrApi.TMDB source code)
-----------apps
                radarr
                    ini
                      ini.json

```

Copy the following into your ini.json file and adjust it according to your mysql credentials:
```
{
	"tmdb_mysql" : {
		"url" : "localhost",
		"user" : "myuser",
		"pass" : "mypass",
		"db" : "tmdb_copy_database"
	},
	"tmdbt_mysql" : {
		"url" : "localhost",
		"user" : "myuser",
		"pass" : "mypass",
		"db" : "tmdbt_copy_database"
	}
}
```
*Note: The second entry can safely point to the same database as the first one. It is only used for database maintanance on the main server*
