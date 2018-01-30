SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `movies`;
DROP TABLE IF EXISTS `genres`;
DROP TABLE IF EXISTS `keywords`;
DROP TABLE IF EXISTS `collections`;
DROP TABLE IF EXISTS `persons`;
DROP TABLE IF EXISTS `credits`;
DROP TABLE IF EXISTS `ratings`;
DROP TABLE IF EXISTS `companies`;
DROP TABLE IF EXISTS `alternative_titles`;
DROP TABLE IF EXISTS `release_dates`;
DROP TABLE IF EXISTS `videos`;
DROP TABLE IF EXISTS `recommendations`;
DROP TABLE IF EXISTS `similar`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `movies` (
    `id` INTEGER NOT NULL,
    `adult` BOOLEAN NOT NULL,
    `backdrop_path` TEXT NOT NULL,
    `budget` INTEGER NOT NULL,
    `collection_id` INTEGER NOT NULL,
    `homepage` TEXT NOT NULL,
    `imdb_id` VARCHAR(50) NOT NULL,
    `original_language` VARCHAR(50) NOT NULL,
    `original_title` TEXT NOT NULL,
    `overview` TEXT NOT NULL,
    `popularity` FLOAT NOT NULL,
    `poster_path` TEXT NOT NULL,
    `release_date` DATE NOT NULL,
    `revenue` INTEGER NOT NULL,
    `runtime` INTEGER NOT NULL,
    `tagline` TEXT NOT NULL,
    `title` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`id`)
);

CREATE TABLE `genres` (
    `id` INTEGER NOT NULL,
    `name` VARCHAR(180) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`id`)
);

CREATE TABLE `keywords` (
    `id` Integer NOT NULL,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`id`)
);

CREATE TABLE `collections` (
    `id` INTEGER NOT NULL,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`id`)
);

CREATE TABLE `persons` (
    `id` INTEGER NOT NULL,
    `name` TEXT NOT NULL,
    `gender` INTEGER NOT NULL,
    `profile_path` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`id`)
);

CREATE TABLE `credits` (
    `movie_id` INTEGER NOT NULL,
    `person_id` INTEGER NOT NULL,
    `credit_id` VARCHAR(180) NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `character` TEXT,
    `order` INTEGER,
    `job` TEXT,
    `departement` TEXT,
    PRIMARY KEY (`credit_id`)
);

CREATE TABLE `ratings` (
    `movie_id` INTEGER NOT NULL,
    `voting_average` FLOAT NOT NULL,
    `voting_count` INTEGER NOT NULL,
    `origin` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`movie_id`, `origin`)
);

CREATE TABLE `companies` (
    `id` INTEGER NOT NULL,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`id`)
);

CREATE TABLE `alternative_titles` (
    `movie_id` INTEGER NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `iso_3166_1` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`movie_id`, `title`, `iso_3166_1`)
);

CREATE TABLE `release_dates` (
    `movie_id` INTEGER NOT NULL,
    `type` INTEGER NOT NULL,
    `certification` VARCHAR(50) NOT NULL,
    `date` DATE NOT NULL,
    `note` TINYTEXT NOT NULL,
    `iso_3166_1` VARCHAR(50) NOT NULL,
    `iso_639_1` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`movie_id`, `type`, `date`, `iso_3166_1`)
);

CREATE TABLE `videos` (
    `id` VARCHAR(180) NOT NULL,
    `iso_639_1` VARCHAR(50) NOT NULL,
    `iso_3166_1` VARCHAR(50) NOT NULL,
    `key` TINYTEXT NOT NULL,
    `name` TINYTEXT NOT NULL,
    `site` TINYTEXT NOT NULL,
    `size` INTEGER NOT NULL,
    `type` VARCHAR(180) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `recommendations` (
    `movie_id` INTEGER NOT NULL,
    `recommended_id` INTEGER NOT NULL,
    PRIMARY KEY (`movie_id`, `recommended_id`)
);

CREATE TABLE `similar` (
    `movie_id` INTEGER NOT NULL,
    `similar_id` INTEGER NOT NULL,
    PRIMARY KEY (`movie_id`, `similar_id`)
);

ALTER TABLE `movies` ADD FOREIGN KEY (`collection_id`) REFERENCES `collections`(`id`);
ALTER TABLE `credits` ADD FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`);
ALTER TABLE `credits` ADD FOREIGN KEY (`person_id`) REFERENCES `persons`(`id`);
ALTER TABLE `ratings` ADD FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`);
ALTER TABLE `alternative_titles` ADD FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`);
ALTER TABLE `release_dates` ADD FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`);
ALTER TABLE `recommendations` ADD FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`);
ALTER TABLE `recommendations` ADD FOREIGN KEY (`recommended_id`) REFERENCES `movies`(`id`);
ALTER TABLE `similar` ADD FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`);
ALTER TABLE `similar` ADD FOREIGN KEY (`similar_id`) REFERENCES `movies`(`id`);