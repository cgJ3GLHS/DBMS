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
    <p>Put some user instructions here!</p>

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
  # convert data to a string...
  
  if (is_writable($filename))
  {
    if (!file_put_contents($filename, $strOut, LOCK_EX))
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
         isset($formdata['fieldNameHere'])
         
         # regex checking form data
      && preg_match('/regexHere/', $formdata['fieldNameHere'])
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
include('./sharedres/header.html');
include('./menu/menu.html');

# display explanation/instructions
DisplayInstructions();

# load data from file
$filename = "../path/to/file.txt";


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
  # do some processing
}
else # otherwise, if there was no valid input
{
  # do whatever else needs doing if not processing input
}

# Display Form
DisplayForm();

include('./sharedres/footer.html');

?>
<!-- ################ END ElectionDay.php ################ -->
