<!-- ################ BEGIN atxtrails.php ################ -->
<?php
#==============================================================================
# 
# Users: jmo2525, , , 
# Assignment: atxtrails
# Date: 2018-12-05
# Filename: atxtrails.php
# URL: 
# 
#==============================================================================

# turn on error stuff
ini_set('display_errors', 1);
error_reporting(E_ALL);

#------------------------------------------------------------------------------
# Function DisplayContent
#
# Displays a table with the content inside
#
# IN: DisplayContent called
#
# OUT: body table has been displayed
#------------------------------------------------------------------------------
function DisplayContent($behavior)
{
  print "<hr>";
  
  print "<table>\n";
  print "  <tr>\n";
  print "    <td style=\"vertical-align:top;min-width:175px\">\n";
  DisplayForm();
  print "    </td>\n";
  print "    <td  style=\"vertical-align:top\">\n";
  
  # main panel content determined by behavior flag
  if ($behavior == "search")
  {
    ProcessForm($_POST);
  }
  
  elseif ($behavior == "detail")
  {
    if (is_numeric($_GET['id']))
    {
      $id = $_GET['id'];
      DisplayTrail($id);
    }
  }
  
  print "    </td>\n";
  print "  </tr>\n";
  print "</table>\n";
  
  print "<hr>";
}

#------------------------------------------------------------------------------
# External Functions
#------------------------------------------------------------------------------
include('atxtrails-log.php');
include('atxtrails-dao.php');
include('atxtrails-displaytrail.php');
include('atxtrails-displayform.php');
include('atxtrails-processform.php');

#------------------------------------------------------------------------------
# Main
#------------------------------------------------------------------------------

# add header
include('header.html');


if (   $_SERVER['REQUEST_METHOD'] == 'POST'
    && isset($_POST['submitted'])
   )
{ 
  DisplayContent("search");
}
elseif ( $_SERVER['REQUEST_METHOD'] == 'GET' 
        && isset($_GET['id'])
       )
{ 
  DisplayContent("detail");
}
else
{
  DisplayContent("new");
}


include('footer.html');

?>
<!-- ################ END atxtrails.php ################ -->
