import requests
import time
import json
import sys
import os, glob
import threading
try:
	import queue
except:
	import Queue as queue
from datetime import timedelta
import TMDB2SQL
import zlib

from tmdbdb import StevenLu

API_KEY = "1a7373301961d03f97f853a876dd1212"
append_to_response = "images,alternative_titles,videos,credits,keywords,release_dates,similar_movies,recommendations"
BASE = "https://api.themoviedb.org/3"
DIR = "TMDBDUMP"

class Worker(threading.Thread):
	def __init__(self, q, *args, **kwargs):
		self.q = q
		super(Worker, self).__init__(*args, **kwargs)

	def run(self):
		while True:
			try:
				work = self.q.get(timeout=3)  # 3s timeout
				do_work(work)
			except queue.Empty:
				return
			# do whatever work you have to do on work
			self.q.task_done()

class TMDBNotfoundException(Exception):
	"""docstring for TMDBNotfoundException."""
	def __init__(self):
		super(TMDBNotfoundException, self).__init__()


def get_from_tmdb(path, query, timeout_pause = 5):
	global API_KEY, append_to_response, BASE
	url = BASE + path
	query["api_key"] = API_KEY
	r = requests.get(url, params=query, allow_redirects=True)
	if r.status_code == 404:
		raise TMDBNotfoundException()

	if "X-RateLimit-Remaining" in r.headers:
		rate_limit = r.headers["X-RateLimit-Remaining"]
		if int(rate_limit) <= 2:
			print("Request limit almost reached. Sleeping.")
			time.sleep(timeout_pause)
	else:
		time.sleep(timeout_pause)

	return r.text

def get_movie(tmdbid):
	global append_to_response, LATEST_ID
	return get_from_tmdb("/movie/{0}".format(tmdbid), {"append_to_response" : append_to_response, "language" : "en-US", "include_image_language" : "en"})

def get_latest_id():
	res = get_from_tmdb("/movie/latest", {})
	jres = json.loads(res)
	if not "id" in jres:
		time.sleep(1)
		return get_latest_id()
	return jres["id"]

LATEST_ID = get_latest_id()

def do_update_latest_id():
	global LATEST_ID, q
	while not q.empty():
		lid = get_latest_id()
		if lid != LATEST_ID:
			print("Updating latest id from {0} to {1}".format(LATEST_ID, lid))
			for newId in range(LATEST_ID+1, lid+1):
				q.put_nowait(newId)

			LATEST_ID = lid
		time.sleep(200)


def do_work(tmdbid):
	global LATEST_ID, start, fromId, current, total, changed_ids
	try:
		res = get_movie(tmdbid).encode('utf-8')
		jres = json.loads(res)
		if "id" not in jres:
			time.sleep(1)
			do_work(tmdbid)
			return
		with open("TMDBDUMP/{0}.json".format(tmdbid), "w+") as f:
			f.write(res)
		current += 1
		c = time.time()
		difference = float(c - start) / float(current)
		eta = (total - current) * difference
		print("Downloaded data for movie {0} ({1}).".format(jres["original_title"].encode('utf-8'), tmdbid))
		print("\t  {0}/{1} (Total: {2}/{3}), {4} left.".format(current, total, tmdbid, LATEST_ID, str(timedelta(seconds=eta))))
	except TMDBNotfoundException as e:
		print("No movie with TMDBID {0} found".format(tmdbid))
		try:
			os.remove("TMDBDUMP/{0}.json".format(tmdbid))
		except Exception as e:
			pass

def download_all_json():
	global LATEST_ID, start, fromId, q
	tmdbid = fromId
	start = time.time()
	q = queue.Queue()
	for tmdbid in range(fromId, LATEST_ID+1):
		q.put_nowait(tmdbid)

	for _ in range(10):
		Worker(q).start()
	try:
		t = threading.Thread(target=do_update_latest_id)
		t.start()
		q.join()
	except Exception as e:
		with q.mutex:
			q.queue.clear()
		raise e
		
def download_necessary_json():
	global LATEST_ID, start, q, fromId, changed_ids, total, current
	lines = download_daily_ids()
	print("Downloaded latest ids")
	all_ids = [json.loads(line)["id"] for line in lines if "{" in line]
	already_downloaded = get_already_downloaded_ids()
	already_set = set(already_downloaded)
	necessary_ids = [id for id in all_ids if id not in already_set]
	print("Gathered necessary ids to download")
	fromId = 1
	total = 0
	current = 0
	start = time.time()
	q = queue.Queue()
	for i in necessary_ids:
		q.put_nowait(int(i))
		LATEST_ID = i
		total += 1
	try:
		for _ in range(10):
			Worker(q).start()
		q.join()
	except Exception as e:
		with q.mutex:
			q.queue.clear()
		raise e


def download_invalid_files():
	global LATEST_ID, start, q, fromId
	fromId = 1
	start = time.time()
	with open("invalid_files.txt", "r") as f:
		files = f.readlines()
		q = queue.Queue()
		for j in files:
			tmdbid = j.replace("TMDBDUMP/", "").replace(".json", "")
			try:
				tmdbid = int(tmdbid)
				q.put_nowait(tmdbid)
				LATEST_ID = tmdbid
			except Exception as e:
				print(e)
	try:
		for _ in range(10):
			Worker(q).start()
		q.join()
	except Exception as e:
		with q.mutex:
			q.queue.clear()
		raise e

def get_changed_ids(days = 1, page = 1):
	global changed_ids
	start = time.time() - days*60*60*24
	startStr = time.strftime("%Y-%m-%d", time.localtime(start))
	print("Getting changed ids on page: " + str(page))
	res = get_from_tmdb("/movie/changes", {"start_date" : startStr, "page" : page})
	jres = json.loads(res)
	total_pages = jres["total_pages"]
	for i in jres["results"]:
		if i["id"] not in changed_ids:
			changed_ids.append(int(i["id"]))
	if total_pages == page:
		return (True, page)
	else:
		return (False, page)


def download_and_dump_latest_changes(days = 1, skip_downloading = False):
	global LATEST_ID, start, q, fromId, changed_ids, total, current
	changed_ids = []
	fromId = 1
	total = 0
	current = 0
	start = time.time()
	ch = get_changed_ids(days)
	while not ch[0]:
		ch = get_changed_ids(days, ch[1]+1)
	if not skip_downloading:
		q = queue.Queue()
		for i in changed_ids:
			q.put_nowait(int(i))
			LATEST_ID = i
			total += 1
		try:
			for _ in range(10):
				Worker(q).start()
			q.join()
		except Exception as e:
			with q.mutex:
				q.queue.clear()
			raise e

	TMDB2SQL.add_selected_ids(changed_ids)

def download_daily_ids():
	r=requests.get("http://files.tmdb.org/p/exports/"+time.strftime("movie_ids_%m_%d_%Y.json.gz"))
	data = zlib.decompress(r.content, zlib.MAX_WBITS|32)
	res = data.decode("utf-8")
	lines = res.split("\n")
	return lines


def download_daily_ids_and_update_popularity():
	lines = download_daily_ids()
	pops = []
	for line in lines:
		try:
			js = json.loads(line)
			pops.append((js["id"], js["popularity"]))
		except Exception as e:
			print("Failed to load json for line {0}, {1}".format(line, e))

	#print(pops[0])
	TMDB2SQL.update_popularity(pops)

	# try:
	#	  while True:
	#
	#		  tmdbid += 1
	#		  if tmdbid >= LATEST_ID-2:
	#			  LATEST_ID = get_latest_id()
	# except Exception as e:
	#	  print(e)
	#	  print("Last TMDBID was: {0}".format(tmdbid))
	
def update_popular_movies():
	r = requests.get("https://s3.amazonaws.com/popular-movies/movies.json")
	data = r.json()

	insertions = []

	for movie in data:
		insertions.append({"imdb_id" : movie["imdb_id"]})

	print(insertions)
	StevenLu.delete().execute()
	StevenLu.insert_many(insertions).execute()
	
	
def get_already_downloaded_jsons():
	return sorted(glob.glob("TMDBDUMP/*.json"), key=lambda name: int(name.replace(DIR + "/", "").replace(".json", "")))
	
def get_already_downloaded_ids():
	jsons = get_already_downloaded_jsons()
	return [id.replace("TMDBUMP/", "").replace(".json", "") for id in jsons]


def list_jsons():
	ids = get_already_downloaded_jsons()
	print("Already downloaded info for {0} movies.".format(len(ids)))
	idStr = ", ".join(ids)
	print(idStr)
		
		 
	

if __name__ == "__main__":
	if len(sys.argv) < 2:
		fromId = 1
		download_all_json()
	else:
		if sys.argv[1] == "invalid":
			download_invalid_files()
		elif sys.argv[1] == "daily":
			download_daily_ids_and_update_popularity()
		elif sys.argv[1] == "changes":
			days = 1
			if len(sys.argv) > 2:
				days = int(sys.argv[2])
			skip_downloading = False
			if len(sys.argv) > 3:
				skip_downloading = sys.argv[3] == "no_download"
			download_and_dump_latest_changes(days, skip_downloading)
			
		elif sys.argv[1] == "list":
			list_jsons()
		elif sys.argv[1] == "stevenlu":
			update_popular_movies()
		elif sys.argv[1] == "necessary":
			download_necessary_json()
		else:
			fromId = int(sys.argv[1])
			download_all_json()
