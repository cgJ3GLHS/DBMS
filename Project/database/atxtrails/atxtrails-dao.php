<!-- ################ BEGIN atxtrails-dao.php ################ -->
<?php

#------------------------------------------------------------------------------
# Function ConnectDB
#
# Creates a connection to the database
#
# IN: 
#
# OUT: 
#------------------------------------------------------------------------------
function ConnectDB()
{
  $host     = "localhost";
  $username = "atxtrails";
  $password = "";  #redacted :)
  $database = "atxtrails";
  
  if ($link = mysqli_connect($host, $username, $password, $database))
  {
    LogMessage("Connected to database.");
    return $link;
  }
  else
  {
    $errormsg="Could not connect to database:" . mysqli_connect_error();
    LogMessage($errormsg);
  }
}

#------------------------------------------------------------------------------
# Function DisconnectDB
#
# Creates a connection to the database
#
# IN: 
#
# OUT: 
#------------------------------------------------------------------------------
function DisconnectDB($link)
{
  LogMessage("Closing DB Connection");
  mysqli_close($link);
}

#------------------------------------------------------------------------------
# Function RunQuerySelect
#
# Creates a connection to the database
#
# IN: Query to run on the database
#
# OUT: array of query results
#------------------------------------------------------------------------------
function RunQuerySelect($link, $query)
{
  $result = mysqli_query($link, $query);
  
  $arrindex = 0;
  while ($row = mysqli_fetch_array($result))
  {
    $resultArray[$arrindex] = $row;
    $arrindex++;
  }
  
  if ($arrindex == 0)
  {
    return false;
  }
  else
  {
    return $resultArray;
  }
}

#------------------------------------------------------------------------------
# Function GetRangesFromData
#
# Checks a numberical database field and breaks it into 3 ranges with their
# top end rounded to the nearest int.
#
# IN: table name, field name
#
# OUT: Array of ranges.
#------------------------------------------------------------------------------
function GetRangesFromData($table, $field)
{
  $query = <<<"HEREQUERY"
SELECT  min($field) AS tier0,
       (min($field) + ( 1 * FLOOR((max($field) - min($field))/4)) ) AS tier1,
       (min($field) + ( 2 * FLOOR((max($field) - min($field))/4)) ) AS tier2,
       (min($field) + ( 3 * FLOOR((max($field) - min($field))/4)) ) AS tier3,
        max($field) AS tier4
  FROM $table
  
HEREQUERY;

  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, $query);
  DisconnectDB($dblink);
  
  return $res;
}

#------------------------------------------------------------------------------
# Function GetRangeStringsFromData
#
# Checks a numberical database field and breaks it into 3 ranges with their
# top end rounded to the nearest int.
#
# IN: table name, field name
#
# OUT: Array of ranges.
#------------------------------------------------------------------------------
function GetRangeStringsFromData($table, $field)
{
  $query = <<<"HEREQUERY"
SELECT CONCAT( min($field),
               ' - <',
               (min($field) + ( 1 * FLOOR((max($field) - min($field))/4)))
             ) AS tier
  FROM $table

UNION ALL

SELECT CONCAT( (min($field) + ( 1 * FLOOR((max($field) - min($field))/4))),
               ' - <',
               (min($field) + ( 2 * FLOOR((max($field) - min($field))/4)))
             ) AS tier
  FROM $table

UNION ALL

SELECT CONCAT( (min($field) + ( 2 * FLOOR((max($field) - min($field))/4))),
               ' - <',
               (min($field) + ( 3 * FLOOR((max($field) - min($field))/4)))
             ) AS tier
  FROM $table

UNION ALL

SELECT CONCAT( (min($field) + ( 3 * FLOOR((max($field) - min($field))/4))),
               ' - ',
               max($field)
             ) AS tier
  FROM $table

HEREQUERY;

  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, $query);
  DisconnectDB($dblink);
  
  return $res;
}


#------------------------------------------------------------------------------
# Function DAO_GetCostRanges
#
# Gets a list of cost ranges from the database
#
# IN: 
#
# OUT: Array of the cost ranges.
#------------------------------------------------------------------------------
function DAO_GetCostRanges()
{
  $res = GetRangeStringsFromData("Trails", "price");
  
  return $res;
}

#------------------------------------------------------------------------------
# Function DAO_GetLengthRanges
#
# Gets a list of length ranges from the database
#
# IN: 
#
# OUT: Array of the length ranges.
#------------------------------------------------------------------------------
function DAO_GetCostRanges()
{
  $res = GetRangeStringsFromData("Trails", "length");
  
  return $res;
}

#------------------------------------------------------------------------------
# Function DAO_GetUses
#
# Gets a list of activities from the database
#
# IN: 
#
# OUT: Array of the activities.
#------------------------------------------------------------------------------
function DAO_GetUses()
{
  $query = <<<"HEREQUERY"
SELECT DISTINCT activity_name
  FROM Uses
ORDER BY activity_name
HEREQUERY;
  
  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, $query);
  DisconnectDB($dblink);
  
  return $res;
}

#------------------------------------------------------------------------------
# Function DAO_GetCities
#
# Gets a list of trail cities from the database
#
# IN: 
#
# OUT: Array of the cities.
#------------------------------------------------------------------------------
function DAO_GetCities()
{
  $query = <<<"HEREQUERY"
SELECT DISTINCT city
  FROM Trails
ORDER BY city
HEREQUERY;

  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, $query);
  DisconnectDB($dblink);
  
  return $res;
}

#------------------------------------------------------------------------------
# Function DAO_GetDifficulties
#
# Gets a list of trail cities from the database
#
# IN: 
#
# OUT: Array of the cities.
#------------------------------------------------------------------------------
function DAO_GetDifficulties()
{
  $query = <<<"HEREQUERY"
SELECT DISTINCT difficulty_rank
  FROM Difficulties
ORDER BY difficulty_rank
HEREQUERY;

  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, $query);
  DisconnectDB($dblink);
  
  return $res;
}

#------------------------------------------------------------------------------
# Function DAO_GetAmenities
#
# Gets a list of trail amenities from the database
#
# IN: 
#
# OUT: Array of the cities.
#------------------------------------------------------------------------------
function DAO_GetAmenities()
{
  $query = <<<"HEREQUERY"
SELECT DISTINCT amenity_name
  FROM Amenities
ORDER BY amenity_name
HEREQUERY;

  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, $query);
  DisconnectDB($dblink);
  
  return $res;
}

#------------------------------------------------------------------------------
# Function DAO_SearchTrails
#
# Gets a list of trails matching some criteria
#
# IN: Search Criteria
#     
#       $filterFlags     Array of bools to turn various filters on and off
#          city
#          dog
#          difficulty
#          cost
#          length
#          bag
#          use
#          terrain
#          amenity
#
# OUT: Array of matching trail ids.
#------------------------------------------------------------------------------
# TODO - this function I am currently working on...not done yet.
function DAO_SearchTrails($filterFlags,   # array of bool
                          $city,           # string
                          
                          $dogFriendly,          # bool
                          
                          $difficulties,        # array of strings
                          
                          $costRanges,    # array of strings
                         )
{
  $query = <<<"HEREQUERY"
SELECT trail_id
  FROM Trails
  
  LEFT JOIN TrailUses ON Trails.trail_id = TrailUses.trail_id
  LEFT JOIN Uses ON TrailUses.use_id = Uses.use_id
  
  LEFT JOIN TerrainsOnTrails ON Trails.trail_id = TerrainsOnTrails.trail_id
  LEFT JOIN Terrains ON TerrainsOnTrails.terrain_id = Terrains.terrain_id
  
  LEFT JOIN ParksOnTrails ON Trails.trail_id = ParksOnTrails.trail_id
  LEFT JOIN AmenitiesAtParks ON ParksOnTrails.park_id = AmenitiesAtParks.park_id
  LEFT JOIN Amenities ON AmenitiesAtParks.amenity_id = Amenities.amenity_id
  
  WHERE
HEREQUERY;
  
  if ($filterFlags['city'] == TRUE)
  {
    $i = 0;
    foreach ($city as $k = $v)
    {
      if 
      $query .= "Trails.city = $v\n";
    }
  }
  $query  = "SELECT trail_id";
  $query .= "FROM Trails";
  $query .= "WHERE";
  $query .= "";
  
  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, $query);
  DisconnectDB($dblink);
  
  return $res;
}

#------------------------------------------------------------------------------
# Function TestDAO
#
# For testing DAO Functionality
#
# IN: 
#
# OUT: 
#------------------------------------------------------------------------------
function TestDAO()
{
  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, "show tables");
  print_r($res);
  DisconnectDB($dblink);
}


?>
<!-- ################ END atxtrails-dao.php ################ -->
