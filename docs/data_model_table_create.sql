CREATE TABLE public.movie (
    id integer NOT NULL,
    adult boolean NOT NULL,
    backdrop_path text NOT NULL,
    budget integer NOT NULL,
    collection_id integer NOT NULL,
    homepage text NOT NULL,
    imdb_id varchar(0) NOT NULL,
    original_language varchar(0) NOT NULL,
    original_title text NOT NULL,
    overview text NOT NULL,
    popularity real NOT NULL,
    poster_path text NOT NULL,
    release_date date NOT NULL,
    revenue integer NOT NULL,
    runtime integer NOT NULL,
    tagline text NOT NULL,
    title text NOT NULL,
    PRIMARY KEY (id)
);

CREATE INDEX ON public.movie
    (collection_id);


CREATE TABLE public.genre (
    id integer NOT NULL,
    name varchar(0) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE public.keyword (
    id Integer NOT NULL,
    name varchar(0) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE public.collection (
    id integer NOT NULL,
    name varchar(0) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE public.person (
    id integer NOT NULL,
    name varchar(0) NOT NULL,
    gender integer NOT NULL,
    profile_path varchar(0) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE public.credits (
    movie_id integer NOT NULL,
    person_id integer NOT NULL,
    credit_id varchar(0) NOT NULL,
    type varchar(0) NOT NULL,
    character varchar(0),
    job varchar(0),
    departement varchar(0),
    PRIMARY KEY (credit_id)
);

CREATE INDEX ON public.credits
    (movie_id);
CREATE INDEX ON public.credits
    (person_id);


COMMENT ON COLUMN public.credits.type
    IS 'Either `cast` or `crew`.';

CREATE TABLE public.rating (
    movie_id integer NOT NULL,
    voting_average real NOT NULL,
    voting_count integer NOT NULL,
    origin varchar(0) NOT NULL,
    PRIMARY KEY (movie_id, origin)
);


COMMENT ON COLUMN public.rating.origin
    IS 'Can be one of `tmdb` or `imdb` at the moment. Planned are: `metacritic`, `rt_critic`and `rt_user`.';

CREATE TABLE public.company (
    id integer NOT NULL,
    name varchar(0) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE public.alternative_title (
    movie_id integer NOT NULL,
    title text NOT NULL,
    iso_3166_1 varchar(0) NOT NULL,
    PRIMARY KEY (movie_id, title, iso_3166_1)
);


CREATE TABLE public.release_date (
    movie_id integer NOT NULL,
    type integer NOT NULL,
    certification varchar(0) NOT NULL,
    date date NOT NULL,
    note TINYTEXT NOT NULL,
    iso_3166_1 varchar(0) NOT NULL,
    iso_639_1 varchar(0) NOT NULL,
    PRIMARY KEY (movie_id, type, date, iso_3166_1)
);


CREATE TABLE public.video (
    id varchar(0) NOT NULL,
    iso_639_1 varchar(0) NOT NULL,
    iso_3166_1 varchar(0) NOT NULL,
    key TINYTEXT NOT NULL,
    name TINYTEXT NOT NULL,
    site TINYTEXT NOT NULL,
    size integer NOT NULL,
    type varchar(0) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE public.recommendation (
    movie_id integer NOT NULL,
    recommended_id integer NOT NULL,
    PRIMARY KEY (movie_id, recommended_id)
);


CREATE TABLE public.similar (
    movie_id integer NOT NULL,
    similar_id integer NOT NULL,
    PRIMARY KEY (movie_id, similar_id)
);

