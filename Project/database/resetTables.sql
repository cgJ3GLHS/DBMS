-- junction tables
DROP TABLE IF EXISTS TerrainsOnTrails;
DROP TABLE IF EXISTS TrailUses;
DROP TABLE IF EXISTS ParksOnTrails;
DROP TABLE IF EXISTS AmenitiesAtPark;

-- 3rd level tables
DROP TABLE IF EXISTS Trailheads;
DROP TABLE IF EXISTS Scores;

--2nd level tables
DROP TABLE IF EXISTS Trails;
DROP TABLE IF EXISTS Parks;
DROP TABLE IF EXISTS Uses;
DROP TABLE IF EXISTS Terrains;
DROP TABLE IF EXISTS Amenities;

--1st level table
DROP TABLE IF EXISTS Difficulties;


-- 1st level table
CREATE TABLE Difficulties
  (
    difficulty_id   INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    difficulty_rank VARCHAR(255) NOT NULL
  )
;

-- 2nd level tables
CREATE TABLE Trails 
  ( 
    trail_id      INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name          CHAR(240) NOT NULL,
    address       VARCHAR(255),
    city          CHAR(240),
    zip           INT,
    ada           VARCHAR(255),
    length        FLOAT,
    lengthUnits   CHAR(15),
    price         INT,         --not using
    difficulty_id INT,
    dog_friendly  BOOLEAN,     --not using
    off_leash     BOOLEAN,     --not using
    bag_stations  BOOLEAN,     --not using
    
    CONSTRAINT fk_trail_diff FOREIGN KEY (difficulty_id) REFERENCES Difficulties (difficulty_id),
    CONSTRAINT trails_unique UNIQUE (name(100), address(100))
  )
;

CREATE TABLE Parks 
  ( 
    park_id      INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    park_name    CHAR(240) NOT NULL,
    address      VARCHAR(255),
    
    CONSTRAINT parks_unique UNIQUE (park_name(100), address(100))
  )
;

CREATE TABLE Uses
  ( 
    use_id        INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    activity_name VARCHAR(255) NOT NULL,
    
    CONSTRAINT uses_unique UNIQUE (activity_name(100))
  )
;

CREATE TABLE Terrains
  ( 
    terrain_id   INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    terrain_type VARCHAR(255) NOT NULL,
    
    CONSTRAINT terrains_unique UNIQUE (terrain_type(100))
  )
;

CREATE TABLE Amenities
  ( 
    amenity_id   INT NOT NULL AUTO_INCREMENT PRIMARY KEY,  --not using
    amenity_name VARCHAR(255) NOT NULL,
    
    CONSTRAINT amenities_unique UNIQUE (amenity_name(100))
  )
;

-- 3rd level tables
CREATE TABLE Trailheads
  ( 
    trailhead_id   INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    gps_lat        FLOAT NOT NULL,
    gps_long       FLOAT NOT NULL,
    trail_id       INT NOT NULL,
    
    CONSTRAINT fk_trailhead_trail FOREIGN KEY (trail_id) REFERENCES Trails (trail_id)
  )
;

CREATE TABLE Scores 
  ( 
    score_id      INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    score         INT NOT NULL,
    score_date    DATE,
    score_link    VARCHAR(255),
    trail_id      INT NOT NULL,
    
    CONSTRAINT fk_score_trail FOREIGN KEY (trail_id) REFERENCES Trails (trail_id)
  )
;

-- junction tables
CREATE TABLE TerrainsOnTrails
  ( 
    tot_id     INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    trail_id   INT NOT NULL,
    terrain_id INT NOT NULL,
    
    CONSTRAINT fk_tot_trail   FOREIGN KEY (trail_id) REFERENCES Trails (trail_id),
    CONSTRAINT fk_tot_terrain FOREIGN KEY (terrain_id) REFERENCES Terrains (terrain_id)
  )
;

CREATE TABLE TrailUses
  ( 
    trailuse_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    trail_id    INT NOT NULL,
    use_id      INT NOT NULL,
    
    CONSTRAINT fk_tu_trail FOREIGN KEY (trail_id) REFERENCES Trails (trail_id),
    CONSTRAINT fk_tu_use   FOREIGN KEY (use_id)   REFERENCES Uses (use_id)
  )
;

CREATE TABLE ParksOnTrails
  ( 
    parkontrail_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    trail_id       INT NOT NULL,
    park_id        INT NOT NULL,
    
    CONSTRAINT fk_tp_trail FOREIGN KEY (trail_id) REFERENCES Trails (trail_id),
    CONSTRAINT fk_tp_park  FOREIGN KEY (park_id)  REFERENCES Parks (park_id)
  )
;

CREATE TABLE AmenitiesAtPark
  ( 
    amenitiesatpark_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    amenity_id         INT NOT NULL,
    park_id            INT NOT NULL,
    
    CONSTRAINT fk_aap_amenity FOREIGN KEY (amenity_id) REFERENCES Amenities (amenity_id),
    CONSTRAINT fk_aap_park    FOREIGN KEY (park_id)    REFERENCES Parks (park_id)
  )
;

-- print a list of the existing tables, so we can check that everything is there
--SHOW TABLES;

