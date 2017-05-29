# -*- coding: utf-8 -*-
import os, glob
import traceback
import sys
import re
import threading
import unicodedata
try:
    import queue
except:
    import Queue as queue
from datetime import timedelta
import time
import json
import peewee
import tmdbdb
db = tmdbdb.database

from tmdbdb import Movies, Recommendations, AlternativeTitles, ReleaseDates
DIR = "TMDBDUMP"

normal_title_regex = re.compile(r"((?:\b|_)(?<!^)(a(?!$)|an|the|and|or|of)(?:\b|_))|\W|_", flags=re.IGNORECASE)

class Worker(threading.Thread):
    def __init__(self, q, *args, **kwargs):
        self.q = q
        super(Worker, self).__init__(*args, **kwargs)

    def run(self):
        while True:
            try:
                work = get_multiple_from_queue(self.q) #self.q.get(timeout=3)  # 3s timeout
                do_work(work)
            except queue.Empty:
                #self.q.task_done()
                return
            for w in work:
            	self.q.task_done()
            # do whatever work you have to do on work
            	#self.q.task_done()

class PopWorker(threading.Thread):
    def __init__(self, q, *args, **kwargs):
        self.q = q
        super(PopWorker, self).__init__(*args, **kwargs)

    def run(self):
        while True:
            try:
                work = get_multiple_from_queue(self.q, 4) #self.q.get(timeout=3)  # 3s timeout
                #do_work_pop(work)
            except queue.Empty:
                self.q.task_done()
                return
            for w in work:
            	self.q.task_done()
            # do whatever work you have to do on work
            	#self.q.task_done()

def get_multiple_from_queue(q, m = 100):
    work = []
    for x in range(0, m):
        try:
            work.append(q.get(timeout=1))
        except queue.Empty as e:
            if len(work) == 0:
                raise e
            else:
                return work

    return work

def do_work_pop(pops):
    global db
    with db.transaction():
        for tmdbid, pop in pops:
            Movies.update(popularity=pop).where(Movies.id == tmdbid).execute()
        print("Updated popularity for movies {0} - {1}".format(pops[0], pops[len(pops)-1]))

def clean_title(title):
    global normal_title_regex
    if title.isdigit():
        return title

    title = title.lower()
    title = title.replace("ä", "ae")
    title = title.replace("ö", "oe")
    title = title.replace("ü", "ue")
    title = title.replace("Ä", "Ae")
    title = title.replace("Ö", "Oe")
    title = title.replace("Ü", "Ue")
    title = title.replace("ß", "ss")
    title = unicodedata.normalize('NFD', unicode(title.decode("utf-8")))
    title = title.encode('ascii', 'ignore')
    title = title.decode("utf-8")
    title = normal_title_regex.sub(repl="", string=title)
    title = title.lower()

    return title




def do_work(files, tables = ["movies", "recommendations"]):
    insertion = []
    reco_insert = []
    alt_insert = []
    dates_insert = []
    ids = []
    for f in files:
        try:
            with open(f, "r") as res:
                try:
                    jres = json.loads(res.read())
                    if jres["id"] == None or jres["id"] == "" or jres["id"] == 0:
                        raise KeyError()

                    for reco in jres["recommendations"]["results"]:
                        reco_insert.append({"tmdbid" : jres["id"], "recommended" : reco["id"]})
                    for alt in jres["alternative_titles"]["titles"]:
                        alt_insert.append({"tmdbid" : jres["id"], "alternative_title" : alt["title"], "clean_alt_title" : clean_title(str(alt["title"].encode("utf-8"))), "iso_3166_1" : alt["iso_3166_1"]})
                    for iso_3166 in jres["release_dates"]["results"]:
                    	for date in iso_3166["release_dates"]:
                    		note = ""
                    		if "note" in date:
                    			note = date["note"]
                    		dates_insert.append({"tmdbid" : jres["id"], "iso_3166_1" : iso_3166["iso_3166_1"], "iso_639_1" : date["iso_639_1"], "certification" : date["certification"], "note" : note, "release_date" : date["release_date"], "release_type" : date["type"]})
                    #print(jres["id"])
                    ids.append(jres["id"])
                    release_date = jres["release_date"]
                    if release_date == "":
                        print("No release date for movie: {0}".format(jres["title"]))
                    trailer = (None, None)
                    for video in jres["videos"]["results"]:
                        if "trailer" in video["type"].lower() or "teaser" in video["type"].lower():
                            trailer = (video["key"], video["site"].lower())
                    genres = []
                    for genre in jres["genres"]:
                        genres.append(str(genre["id"]))
                    insertion.append({"id" : jres["id"], "title" : jres["title"], "imdb_id" : jres["imdb_id"],
                    "release_date" : release_date, "release_year" : release_date[0:4], "overview" : jres["overview"],
                    "vote_count" : jres["vote_count"], "vote_average" : jres["vote_average"], "tagline" : jres["tagline"],
                    "poster_path" : jres["poster_path"], "trailer_key" : trailer[0], "trailer_site" : trailer[1],
                    "backdrop_path" : jres["backdrop_path"], "homepage" : jres["homepage"], "popularity" : jres["popularity"],
                    "runtime" : jres["runtime"], "genres" : ",".join(genres), "clean_title" : clean_title(str(jres["title"].encode("utf-8")))})
                except Exception as e:
                    #print(traceback.format_exc())
                    #print("Invalid json for file: {0}".format(f))
                    with open("invalid_files.txt", "a") as invalid_files:
                        invalid_files.write(f + "\n")
        except Exception as e:
            #print(traceback.format_exc())
            ids.append(int(f.replace("TMDBDUMP/", "").replace(".json", "")))

    try:
        if len(insertion) > 0:
            Movies.delete().where(Movies.id << ids).execute()
            Movies.insert_many(insertion).execute()
        if len(reco_insert) > 0:
            Recommendations.delete().where(Recommendations.tmdbid << ids).execute()
            Recommendations.insert_many(reco_insert).execute()
        if len(alt_insert) > 0:
            AlternativeTitles.delete().where(AlternativeTitles.tmdbid << ids).execute()
            AlternativeTitles.insert_many(alt_insert).execute()
        if len(dates_insert) > 0:
            ReleaseDates.delete().where(ReleaseDates.tmdbid << ids).execute()
            ReleaseDates.insert_many(dates_insert).execute()
    except Exception as e:
        print(e)
    print("Pushed movies {0} to {1}".format(files[0], files[len(files)-1]))

def add_all_to_db(limit = 2861730):
    q = queue.Queue()
    for f in sorted(glob.glob(DIR+"/*.json"), key=lambda name: int(name.replace(DIR + "/", "").replace(".json", "")))[0:limit+1]:
        q.put_nowait(f)

    for _ in range(10):
        Worker(q).start()

    try:
        q.join()
    except Exception as e:
        with q.mutex:
            q.queue.clear()
        raise e

def add_selected_ids(ids):
    q = queue.Queue()
    for i in ids:
        q.put_nowait("TMDBDUMP/"+str(i) + ".json")

    for _ in range(10):
        Worker(q).start()

    try:
        q.join()
    except Exception as e:
        with q.mutex:
            q.queue.clear()
        raise e

def update_popularity(pops):
    q = queue.Queue()
    for pop in pops:
        q.put_nowait(pop)

    for _ in range(10):
        PopWorker(q).start()

    try:
        q.join()
    except Exception as e:
        with q.mutex:
            q.queue.clear()
        raise e




if __name__ == '__main__':
    add_all_to_db()
