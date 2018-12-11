<!-- ################ BEGIN atxtrails-displayform.php ################ -->
<?php

#------------------------------------------------------------------------------
# Function DisplayForm
#
# Displays a form to the user
#
# IN: displayform called
#
# OUT: form has been printed
#------------------------------------------------------------------------------
function DisplayForm()
{
  # get form data from db
  $difficulties=DAO_GetDifficulties();
  $lengths=DAO_GetLengthRanges();
  $uses=DAO_GetUses();
  $terrains=DAO_GetTerrains();
  
  
  # start form
  print '    <form action="atxtrails.php" method="post">';
  print "\n";
  
  # form elements here...
  print "<h3>Difficulty</h3>\n";
  foreach ($difficulties as $k => $row)
  {
    foreach ($row as $k2 => $v2)
    {
      print "<input type=\"checkbox\" name=\"difficulty-$v2\" value=\"$v2\">$v2<br>";
    }
  }
  
  # length
  print "<h3>Length (mi)</h3>\n";
  $lengthTier=0;
  foreach ($lengths as $k => $row)
  {
    foreach ($row as $k2 => $v2)
    {
      print "<input type=\"checkbox\" name=\"length-tier$lengthTier\" value=\"length-tier$lengthTier\">$v2<br>";
      $lengthTier++;
    }
  }
  
  # uses
  print "<h3>Uses</h3>\n";
  foreach ($uses as $k => $row)
  {
    foreach ($row as $k2 => $v2)
    {
      print "<input type=\"checkbox\" name=\"uses-$v2\" value=\"$v2\">$v2<br>";
    }
  }
  
  # terrains
  print "<h3>Terrains</h3>\n";
  foreach ($terrains as $k => $row)
  {
    foreach ($row as $k2 => $v2)
    {
      print "<input type=\"checkbox\" name=\"terrains-$v2\" value=\"$v2\">$v2<br>";
    }
  }
    
  # submit button
  print '      <input type="submit" name="submit" value="submit">';
  print "\n";
  
  # submited flag
  print '      <input type="hidden" name="submitted" value="true">';
  print "\n";
  
  # end form
  print "    </form>\n";

}
?>
<!-- ################ END atxtrails-displayform.php ################ -->
