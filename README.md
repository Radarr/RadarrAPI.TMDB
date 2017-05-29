# RadarrAPI.TMDB
Backend Stuff for Radarr regarding TMDB (Discover, lists, Mappings, etc.)

**Manually deployed to https://api.radarr.video/**

## Setup Development Environment

- Python Scripts need Python 2.7, additionally the following python packages are required: peewee, python-mysql, requests
- PHP Scripts (and thus the whole api) need PHP 5.6
- Most of the API also needs a MySQL server with a copy of the TMDB database. To get a copy, see `TMDBDUMP.py`.

### Configuring the MySQL server to use with the API

After cloning the repo into the www folder of your webserver, rename ini.sample.json to ini.json and edit it. There you can specify the mysql server connections to use.
*Note: The second entry can safely point to the same database as the first one. It is only used for database maintanance on the main server*
