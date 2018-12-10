DELETE FROM TerrainsOnTrails;
DELETE FROM TrailUses;
DELETE FROM ParksOnTrails;
DELETE FROM AmenitiesAtPark;

-- 3rd level tables
DELETE FROM Trailheads;
DELETE FROM Scores;

--2nd level tables
DELETE FROM Trails;
DELETE FROM Parks;
DELETE FROM Uses;
DELETE FROM Terrains;
DELETE FROM Amenities;

--1st level table
DELETE FROM Difficulties;



LOAD DATA LOCAL INFILE '/home/fall2018/jmo2525/projectworking/data/Difficulties.txt'
INTO TABLE Difficulties
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(difficulty_id, difficulty_rank)
;

LOAD DATA LOCAL INFILE '/home/fall2018/jmo2525/projectworking/data/Trails.txt'
INTO TABLE Trails
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(trail_id, name, address, city, zip, ada, length, difficulty_id)
;

LOAD DATA LOCAL INFILE '/home/fall2018/jmo2525/projectworking/data/Parks.txt'
INTO TABLE Parks
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(park_id, park_name, address)
;

LOAD DATA LOCAL INFILE '/home/fall2018/jmo2525/projectworking/data/Uses.txt'
INTO TABLE Uses
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(use_id, activity_name)
;

LOAD DATA LOCAL INFILE '/home/fall2018/jmo2525/projectworking/data/Terrains.txt'
INTO TABLE Terrains
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(terrain_id, terrain_type)
;

LOAD DATA LOCAL INFILE '/home/fall2018/jmo2525/projectworking/data/Trailheads.txt'
INTO TABLE Trailheads
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(trail_id, gps_lat, gps_long)
;

LOAD DATA LOCAL INFILE '/home/fall2018/jmo2525/projectworking/data/Scores.txt'
INTO TABLE Scores
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(trail_id, score_id, score, score_date, score_link)
;

LOAD DATA LOCAL INFILE '/home/fall2018/jmo2525/projectworking/data/TerrainsOnTrails.txt'
INTO TABLE TerrainsOnTrails
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(tot_id, trail_id, terrain_id)
;

LOAD DATA LOCAL INFILE '/home/fall2018/jmo2525/projectworking/data/TrailUses.txt'
INTO TABLE TrailUses
FIELDS TERMINATED BY '|'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(trailuse_id, trail_id, use_id)
;
