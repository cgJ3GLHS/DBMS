<!-- ################ BEGIN atxtrails-displaytrail.php ################ -->
<?php

#------------------------------------------------------------------------------
# Function DisplayTrail
#
# Displays the current total election votes to the user
#
# IN: file name to load voting data from
#
# OUT: results printed to screen
#------------------------------------------------------------------------------
function DisplayTrail($trailid)
{
  $trailData = DAO_GetTrailData($trailid);
  
  foreach ($trailData as $k => $v)
  {
    $row = $v;
    $name    = $row['name'];
    $address = $row['address'];
    $city    = $row['city'];
    $zip     = $row['zip'];
    $ada     = $row['ada'];
    $length  = $row['length'];
    $uses[]       = $row['activity_name'];
    $gpsLat[]     = $row['gps_lat'];
    $gpsLong[]    = $row['gps_long'];
    $score[]      = $row['score'];
    $score_link[] = $row['score_link'];
    
    LogMessage("DisplayTrail Data for trail $trailid");
    foreach ($v as $k2 => $v2)
    {
      LogMessage("$k2 => $v2 <br>\n");
    }
  }
  
  print "<hr></hr>\n";
  
  print "<table border=\"1\">\n";
  print "  <tr><td colspan=\"2\" align=\"center\"><h2>$name</h2></td></tr>\n";
  print "  <tr><td>Address:</td><td>$address<br>$city, TX<br>$zip</td></tr>\n";
  print "  <tr><td>ADA Accessible:</td><td>$ada</td></tr>\n";
  print "  <tr><td>Length:</td><td>$length mi.</td></tr>\n";
  
  # multiple uses
  print "  <tr><td>Uses:</td>\n    <td>\n";
  foreach ($uses as $k => $v)
  {
    print "    $v<br>\n";
  }
  print "    </td>\n  </tr>\n";
  
  # multiple trailheads
  print "  <tr><td>Trailheads GPS Locations:</td><td>\n";
  print "<table border=\"1\">";
  print "<tr><td>Latitude</td><td>Longitude</td></tr>\n";
  foreach ($gpsLat as $k => $v)
  {
    print "<tr><td>$gpsLat[$k]</td><td>$gpsLong[$k]</td></tr>\n";
  }
  print "</table>\n";
  print "</td></tr>\n";
  
  # multiple scores
  print "  <tr><td>Ratings:</td><td>";
  print "<table border=\"1\">";
  print "<tr><td>Score /5</td><td>Score Link</td></tr>";
  foreach ($score as $k => $v)
  {
    print "<tr><td>$score[$k]</td><td><a href=\"$score_link[$k]\">$score_link[$k]</a></td></tr>";
  }
  print "</table>";
  print "</td></tr>";
  
  print "</table>";
}

?>
<!-- ################ END atxtrails-displaytrail.php ################ -->
