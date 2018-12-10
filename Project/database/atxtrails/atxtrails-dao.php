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
  $password = "BwTc935ZfUd4rom2";  #redacted :)
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

/* can't overload looks like :(
#------------------------------------------------------------------------------
# Function RunQuerySelect (overload for 2 parameters)
#
# Creates a connection to the database
#
# IN: Query to run on the database
#
# OUT: array of query results
#------------------------------------------------------------------------------
function RunQuerySelect($link, $query, $paramTypes, $params)
{
  RunQuerySelect($link, $query, NULL, NULL);
}
*/

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
  LogMessage("Running query:\n$query");
  
  $result = mysqli_query($link, $query);
    
  $arrindex = 0;
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
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
# Function RunQuerySelectPrep
#
# Runs a select query as a prepared statement
#
# IN: Query to run on the database
#
# OUT: array of query results
#------------------------------------------------------------------------------
function RunQuerySelectPrep($link, $query, $paramTypes, $params)
{# TODO - add dynamic binding
  
  # Log fn call and parameters
  LogMessage("RunQuerySelect called");
  #LogMessage("  link=$link");
  LogMessage("  query=$query");
  if ($params != NULL)
  {
    LogMessage("  paramTypes=$paramTypes");
    LogMessage("  params=");
    foreach ($params as $k => $v)
    {
      LogMessage("    $k => $v");
    }
  }
  
  # prepare query
  $stmt = $link->prepare("$query");
  LogMessage("    stmt type is " . gettype($stmt));
  
  if ($stmt == TRUE)
  { LogMessage("stmt is true"); }
  else
  { LogMessage("stmt is false"); }
  
  if ($params != NULL)
  {
    LogMessage("In RunQuerySelect got a param list, processing...");
    
    # put param type string on beginning of array
    array_unshift($params, $paramTypes);
    $stmt->bind_param(...$params);
    
    #$stmt->bind_param("sss", $firstname, $lastname, $email); # normal bind template
    #$ref = new ReflectionClass('mysqli_stmt');
    #$method = $ref->getMethod("bind_param");
    #$method->invokeArgs($stmt, $params);
    
    # bind parameters
    #call_user_func_array([$stmt, 'bind_param'], $params);
    
  }
  $result=mysqli_stmt_execute($stmt);
  #$result = mysqli_query($link, $query);
  print_r($result);
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
function DAO_GetLengthRanges()
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
# Function AddFiltersClause
#
# Creates a query where clause part for adding to a query
#
# IN: $filtersToCheck - array of filter values for this field
#     $sourceField - source field in the database to be filtered on
#     &$activatedFilters - int count of how many filters have been activated
#
# OUT: Filter clause to be added to the query
#------------------------------------------------------------------------------
function AddFiltersClause($filtersToCheck, $sourceField, &$activatedFilters)
{
  $query = "";
  
  # add field filter clause
  if ($filtersToCheck != NULL)
  {
    # in each filter type we want to add an AND if it is not the first
    if ($activatedFilters > 0)
    {
      $query .= "     AND\n";
    }
    $activatedFilters++; # flag that a filter has been activated
    
    
    $query .= "       (    ";
    # count interations so we can add an OR to any beyond 0
    $i = 0;
    foreach ($filtersToCheck as $k => $v)
    {
      # need an or for multiple values after the first one
      if ($i != 0)
      {
        $query .= "         OR ";
      }
      
      # add field to filter
      $query .= "$sourceField = $v\n";
      
      # add parameter to list and type string
      #$parameterList[] = $v;
      #$types .= $parameterType;
      
      # increment iteration counter
      $i++;
    }
    $query .= "       )\n";
  }
  
  return $query;
}

#------------------------------------------------------------------------------
# Function DAO_SearchTrails
#
# Gets a list of trails matching some criteria
#
# IN: Search Criteria
#       Each of these is an array of filter values
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
function DAO_SearchTrails(#$city,
                          #$dog,
                          $difficulty,
                          #$cost,
                          $length,
                          #$bag,
                          $use,
                          $terrain
                          #, $amenity
                         )
{
  $activatedFilters = 0; # to count activated filters (any >0 require AND between)
  $parameterList = NULL; # list of parameters to be run with the query
  $paramTypes = NULL;    # string of parameter types
  
  $query = <<<"HEREQUERY"
SELECT Trails.trail_id,
       Trails.name,
       Terrains.terrain_type,
       Difficulties.difficulty_rank
       
  FROM Trails
  
  LEFT JOIN TrailUses ON Trails.trail_id = TrailUses.trail_id
  LEFT JOIN Uses ON TrailUses.use_id = Uses.use_id
  
  LEFT JOIN TerrainsOnTrails ON Trails.trail_id = TerrainsOnTrails.trail_id
  LEFT JOIN Terrains ON TerrainsOnTrails.terrain_id = Terrains.terrain_id
  
  LEFT JOIN ParksOnTrails ON Trails.trail_id = ParksOnTrails.trail_id
  LEFT JOIN AmenitiesAtPark ON ParksOnTrails.park_id = AmenitiesAtPark.park_id
  LEFT JOIN Amenities ON AmenitiesAtPark.amenity_id = Amenities.amenity_id
  
  LEFT JOIN Difficulties ON Trails.difficulty_id = Difficulties.difficulty_id
  
HEREQUERY;
  
  # if any filter flags are enabled we'll need a where clause
  if (   $difficulty != NULL
      || $length != NULL
      || $use != NULL
      || $terrain != NULL
     )
  {
    $query .= "WHERE\n";
  }
  
  # add filter clauses for each type of searchable field as necessary
  $query .= AddFiltersClause($difficulty, "Difficulties.difficulty_rank", $activatedFilters);
  $query .= AddFiltersClause($length, "Trails.length", $activatedFilters);
  $query .= AddFiltersClause($use, "Uses.activity_name", $activatedFilters);
  $query .= AddFiltersClause($terrain, "Terrains.terrain_type", $activatedFilters);
  
  # log the built query just so we can check it
  LogMessage("In DAO_SearchTrails built query:\n$query");
  
  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, $query);
  DisconnectDB($dblink);
  
  return $res;
}

#------------------------------------------------------------------------------
# Function DAO_GetTrailData
#
# Gets complete data for a single trail
#
# IN: trail id
#
# OUT: all connected trail data
#------------------------------------------------------------------------------
function DAO_GetTrailData($trailid)
{
  # TODO in progress!
  
  $query = <<<"HEREQUERY"
SELECT Trails.trail_id,
       Trails.name,
       Trails.address,
       Trails.city,
       Trails.zip,
       Trails.ada,
       Trails.length,
       
       Uses.activity_name,
       
       Terrains.terrain_type,
       
       Trailheads.gps_lat,
       Trailheads.gps_long,
       
       Scores.score,
       Scores.score_link
       
       
FROM Trails
  
  LEFT JOIN TrailUses ON Trails.trail_id = TrailUses.trail_id
  LEFT JOIN Uses ON TrailUses.use_id = Uses.use_id
  
  LEFT JOIN TerrainsOnTrails ON Trails.trail_id = TerrainsOnTrails.trail_id
  LEFT JOIN Terrains ON TerrainsOnTrails.terrain_id = Terrains.terrain_id
  
  LEFT JOIN Trailheads ON Trails.trail_id = Trailheads.trail_id
  
  LEFT JOIN Scores ON Trails.trail_id = Scores.trail_id
  
  LEFT JOIN ParksOnTrails ON Trails.trail_id = ParksOnTrails.trail_id
  LEFT JOIN AmenitiesAtPark ON ParksOnTrails.park_id = AmenitiesAtPark.park_id
  LEFT JOIN Amenities ON AmenitiesAtPark.amenity_id = Amenities.amenity_id
  
  LEFT JOIN Difficulties ON Trails.difficulty_id = Difficulties.difficulty_id

HEREQUERY;
  
  $query .= "WHERE Trails.trail_id = $trailid";
  
  $dblink=ConnectDB();
  $res=RunQuerySelect($dblink, $query);
  DisconnectDB($dblink);
  
  return $res;
}

#------------------------------------------------------------------------------
# Function TestDAO
#
# Scratch area for testing DAO Functionality
#
# IN: 
#
# OUT: 
#------------------------------------------------------------------------------
function TestDAO()
{
  #$dblink=ConnectDB();
  #$res=RunQuerySelect($dblink, "show tables", NULL, NULL);
  #print_r($res);
  #DisconnectDB($dblink);
  

  $difficulty = NULL;
  $length = NULL;
  $use = NULL;
  $terrain = NULL;
  
  $difficulty[0] = "'Varies'";
  $difficulty[1] = "'Easier'";
  $terrain[0] = "'Imported Material'";
  
  #$searchResult = DAO_SearchTrails($difficulty,
  #                                 $length,
  #                                 $use,
  #                                 $terrain
  #                                );
  #LogMessage($searchResult);
  #print_r($searchResult);
                  # on results page display
                  # name
                  # terrain
                  # difficulty
                  
  #print_r(DAO_GetUses());
  
  $traildata=DAO_GetTrailData(5);
  print_r($traildata);
  
}


?>
<!-- ################ END atxtrails-dao.php ################ -->
