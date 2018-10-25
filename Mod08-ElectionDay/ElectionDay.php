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
    <p>Election day has arrived, cast your ballot here.  Vote early and vote often!</p>

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
function WriteVote($filename, $candidate)
{
  # convert data to a string...
  $data  = time();
  $data .= chr(31);
  $data .= $candidate;
  $data .= chr(30);
  # $data .= "\n"; # for fun trying record delimiter character instead of newline...
  
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
  print '<input type="radio" name="candidate" value="A">Austin Parks Project<br>';
  print "\n";
  print '<input type="radio" name="candidate" value="B">Old Radio Project<br>';
  print "\n";
   print '<input type="radio" name="candidate" value="B">Moto GP Project<br>';
  print "\n";
   print '<input type="radio" name="candidate" value="B">Medieval Latin Music Projectt<br>';
  print "\n";
  
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
  if ( isset($formdata['fieldNameHere']) )
  {
    $filteredData = filter_var($formdata['fieldNameHere'], FILTER_SANITIZE_STRING);
    $formdata['fieldNameHere'] = $filteredData;
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
         isset($formdata['candidate'])
         
         # regex checking form data
      && preg_match('/^[AB]|"Parks|OTR|MotoGP|MedievalMusic$/', $formdata['candidate'])
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
$filename = "../../electionDay/electionData.txt";


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
  # get candidate that was voted for
  $candidate = $_POST['candidate'];
  
  # record the vote
  WriteVote($filename, $candidate);
  
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
