<!-- ################ BEGIN ElectionDay.php ################ -->
<?php
#==============================================================================
# 
# Users: jmo2525, , , 
# Assignment: Module 8 - ElectionDay
# Date: 2018-10-11
# Filename: ElectionDay.php
# URL: http://hornet.ischool.utexas.edu/~jmo2525/ElectionDay.php
# 
#==============================================================================

# turn on error stuff
ini_set('display_errors', 1);
error_reporting(E_ALL);

#------------------------------------------------------------------------------
# Function DisplayInstructions
#
# Displays the instructions to the user
#
# IN: (none)
#
# OUT: instructions have been output to the user
#------------------------------------------------------------------------------
function DisplayInstructions()
{
  # display explanation/instructions
  $instructions = <<<"HEREYAGO"
    <p>Rank-vote the projects, 1 is the lowest and 4 is the highest.</p>

HEREYAGO;
  print $instructions;
}

#------------------------------------------------------------------------------
# Function ReadFromFile
#
# Loads data from file
#
# IN: name of file
#
# OUT: returns data as a string
#------------------------------------------------------------------------------
function ReadFromFile($filename)
{
  if (is_readable($filename))
  {
    $data = file($filename);
  }
  
  return $data;
}


#------------------------------------------------------------------------------
# Function WriteToFile
#
# Writes quiz array out to file
#
# IN: name of file, 1-d array containing quiz data
#
# OUT: false if write failed, otherwise true
#------------------------------------------------------------------------------
function WriteToFile($filename, $data)
{
  if (is_writable($filename))
  {
    if (!file_put_contents($filename, $data, FILE_APPEND | LOCK_EX))
    {
      return false;
    }
    else
    {
      return true;
    }
  }
}


#------------------------------------------------------------------------------
# Function WriteVote
#
# Adds a vote with timestamp to the file
#
# IN: name of file, value of vote
#
# OUT: false if write failed, otherwise true
#------------------------------------------------------------------------------
function WriteVote($filename, $parks, $otr, $motogp, $music)
{
  # convert data to a string...
  # init string
  $data = "";
  $i = 0;
  
  for ($i=0; $i < $parks; $i++)
  {
    $data .= time();
    $data .= chr(31);
    $data .= "parks";
    $data .= chr(30);
  }
  
  for ($i=0; $i < $otr; $i++)
  {
    $data .= time();
    $data .= chr(31);
    $data .= "otr";
    $data .= chr(30);
  }
  
  for ($i=0; $i < $motogp; $i++)
  {
    $data .= time();
    $data .= chr(31);
    $data .= "motogp";
    $data .= chr(30);
  }
  
  for ($i=0; $i < $music; $i++)
  {
    $data .= time();
    $data .= chr(31);
    $data .= "music";
    $data .= chr(30);
  }
  
  if (WriteToFile($filename, $data))
  {
    return true;
  }
  else
  {
    return false;
  }
  
}


#------------------------------------------------------------------------------
# Function DisplayResults
#
# Displays the current total election votes to the user
#
# IN: file name to load voting data from
#
# OUT: results printed to screen
#------------------------------------------------------------------------------
function DisplayResults($filename)
{
  # read data from file
  $fileData = ReadFromFile($filename);
  
  # Parse data into an array...
  $recordDelimeter = chr(30);
  $fieldDelimeter  = chr(31);
  
  # first split by record recordDelimeter
  $regex = '/['.$recordDelimeter.']+/';
  $fileArray = preg_split($regex, $fileData[0]);
  
  # pop last item as final delimiter will have no data after
  array_pop($fileArray);
  
  # then drop parse through array and aplit off the vote from the timestamp
  foreach ($fileArray as $k => $v)
  {
    $oldval = $v;
    $newval = substr(  $oldval,
                       strpos($oldval, $fieldDelimeter) + 1,
                       strlen($oldval) - strpos($oldval, $fieldDelimeter)
                    );
    $fileArray[$k] = $newval;
  }
  
  # count results from array
  # --------------------------
  
  # initialize all existing votes to 0
  foreach ($fileArray as $k => $v)
  {
    $resultsArray[$v] = 0;
  }
  
  # then increment counts for all votes
  foreach ($fileArray as $k => $v)
  {
    $resultsArray[$v] += 1;
  }
  
  # sort final result array
  ksort($resultsArray);
  
  # display results
  # ----------------
  print "Current votes:<br>\n";
  print "<table border=\"1\">\n";
  foreach ($resultsArray as $k => $v)
  {
    print "<tr><td>$k</td><td>$v</td></tr>\n";
  }
  print "</table>\n";
}

#------------------------------------------------------------------------------
# Function DisplayForm
#
# Displays a form to the user
#
# IN: 
#
# OUT: 
#------------------------------------------------------------------------------
function DisplayForm()
{
  # start form
  print '    <form action="ElectionDay.php" method="post">';
  print "\n";
  
  # form elements here...
  print '<input type="number" min="1" max="4" value="1" name="parks">Austin Parks Project<br>'; print "\n";
  print '<input type="number" min="1" max="4" value="1" name="otr">Old Radio Project<br>'; print "\n";
  print '<input type="number" min="1" max="4" value="1" name="motogp">Moto GP Project<br>'; print "\n";
  print '<input type="number" min="1" max="4" value="1" name="music">Medieval Latin Music Projectt<br>'; print "\n";
  
  # submit button
  print '      <input type="submit" name="submit" value="submit">';
  print "\n";
  
  # submited flag
  print '      <input type="hidden" name="submitted" value="true">';
  print "\n";
  
  # end form
  print "    </form>\n";

}

#------------------------------------------------------------------------------
# Function FilterInputs
#
# Runs the inputs through the PHP sanitization filters
#
# IN: $formdata by ref.
#
# OUT: form data has been filtered and set with filter values
#------------------------------------------------------------------------------
function FilterInputs(&$formdata)
{
  # using sanitize filter found at
  # https://secure.php.net/manual/en/filter.filters.sanitize.php
  # check that page for other filter constants to use
  if ( isset($formdata['parks']) )
  {
    $filteredData = filter_var($formdata['parks'], FILTER_SANITIZE_STRING);
    $formdata['parks'] = $filteredData;
  }
  if ( isset($formdata['otr']) )
  {
    $filteredData = filter_var($formdata['otr'], FILTER_SANITIZE_STRING);
    $formdata['otr'] = $filteredData;
  }
  if ( isset($formdata['motogp']) )
  {
    $filteredData = filter_var($formdata['motogp'], FILTER_SANITIZE_STRING);
    $formdata['motogp'] = $filteredData;
  }
  if ( isset($formdata['music']) )
  {
    $filteredData = filter_var($formdata['music'], FILTER_SANITIZE_STRING);
    $formdata['music'] = $filteredData;
  }
}

#------------------------------------------------------------------------------
# Function FormDataIsValid
#
# Determines true/false if the form data is valid.
#
# IN: $formdata - form data array
#
# OUT: true - if field value is set and consists of
#
#      false - if quizquestions is not set or has some character outside the
#              above list
#------------------------------------------------------------------------------
function FormDataIsValid(&$formdata)
{
  if (   # input field should be set
         isset($formdata['parks'])
         
         # regex checking form data
      && preg_match('/^[0-4]$/', $formdata['parks'])
      
         # input field should be set
      && isset($formdata['otr'])
         
         # regex checking form data
      && preg_match('/^[0-4]$/', $formdata['otr'])
      
         # input field should be set
      && isset($formdata['motogp'])
         
         # regex checking form data
      && preg_match('/^[0-4]$/', $formdata['motogp'])
      
         # input field should be set
      && isset($formdata['music'])
         
         # regex checking form data
      && preg_match('/^[0-4]$/', $formdata['music'])
     )
  {
    return true;
  }
  else
  {
    # otherwise, failed validation
    return false;
  }
}

#------------------------------------------------------------------------------
# Main
#------------------------------------------------------------------------------

# add header and main menu
include('../sharedres/header.html');
include('../menu/menu.html');

# display explanation/instructions
DisplayInstructions();

# define data file
$filename = "../../electionDay/electionDatab.txt";


# validate inputs, if any
#--------------------------
$passedValidation = false; # validation passing flag, start assuming false

if (   $_SERVER['REQUEST_METHOD'] == 'POST'
    && isset($_POST['submitted'])
   )
{ 
  # before using the values, run them through the sanitize filters
  FilterInputs($_POST);
  
  
  # then check that they are valid, and set flag if so
  if (FormDataIsValid($_POST))
  {
    $passedValidation = true;
  }
}


# if we got valid input, process that data
if ($passedValidation)
{
  # get rand vote values that was voted for
  $parks  = $_POST['parks'];
  $otr    = $_POST['otr'];
  $motogp = $_POST['motogp'];
  $music  = $_POST['music'];
  
  # record the vote
  WriteVote($filename, $parks, $otr, $motogp, $music);
  
  # display the results
  DisplayResults($filename);
  
}
else # otherwise, if there was no valid input
{
  # do whatever else needs doing if not processing input
  
  # Display Form
  DisplayForm();
}



include('../sharedres/footer.html');

?>
<!-- ################ END ElectionDay.php ################ -->
