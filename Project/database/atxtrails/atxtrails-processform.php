<!-- ################ BEGIN atxtrails-processform.php ################ -->
<?php

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
  
  foreach ($formdata as $formKey => $formValue)
  {
    $filteredData = filter_var($formdata[$formKey], FILTER_SANITIZE_STRING);
    $formdata[$formKey] = $filteredData;
  }
}

#------------------------------------------------------------------------------
# Function FormDataIsValid
#
# Determines true/false if the form data is valid.
#
# IN: $formdata - form data array
#
# OUT: true - if field value is set and consists of expected values
#
#      false - if field values are not found in set of expected values
#------------------------------------------------------------------------------
function FormDataIsValid(&$formdata)
{
  # for each form field
  foreach ($formdata as $formKey => $formValue)
  {
    #---------------------------------------------------
    # if it is a difficulty field
    if (preg_match('/^difficulty.*/', $formKey))
    {
      $match = FALSE;
      $validDifficulties = DAO_GetDifficulties();
      
      # check field against each valid difficulty
      foreach ($validDifficulties as $k => $row)
      {
        foreach ($row as $k2 => $v2)
        {
          # if there is a match, flag it and quit checking
          if ($formValue == $v2)
          {
            $match = TRUE;
            break 2;
          }
        }
      }
      
      # if no match was found, data is invalid, no need to continue
      if ($match == FALSE)
      {
        return FALSE;
      }
    }
    #---------------------------------------------------
    # if it is a length field
    if (preg_match('/^length.*/', $formKey))
    {
      $match = FALSE;
      $validLengths = ['length-tier0', 
                       'length-tier1', 
                       'length-tier2',
                       'length-tier3'];
      
      # check field against each valid difficulty
      foreach ($validLengths as $k => $v2)
      {
        # if there is a match, flag it and quit checking
        if ($formValue == $v2)
        {
          $match = TRUE;
          break;
        }
      }
      
      # if no match was found, data is invalid, no need to continue
      if ($match == FALSE)
      {
        return FALSE;
      }
    }
    #---------------------------------------------------
    # if it is a use field
    if (preg_match('/^uses.*/', $formKey))
    {
      $match = FALSE;
      $validUses = DAO_GetUses();
      
      # check field against each valid use
      foreach ($validUses as $k => $row)
      {
        foreach ($row as $k2 => $v2)
        {
          # if there is a match, flag it and quit checking
          if ($formValue == $v2)
          {
            $match = TRUE;
            break 2;
          }
        }
      }
      
      # if no match was found, data is invalid, no need to continue
      if ($match == FALSE)
      {
        return FALSE;
      }
    }
    #---------------------------------------------------
    # if it is a terrain field
    if (preg_match('/^terrains.*/', $formKey))
    {
      $match = FALSE;
      $validTerrains = DAO_GetTerrains();
      
      # check field against each valid difficulty
      foreach ($validTerrains as $k => $row)
      {
        foreach ($row as $k2 => $v2)
        {
          # if there is a match, flag it and quit checking
          if ($formValue == $v2)
          {
            $match = TRUE;
            break 2;
          }
        }
      }
      
      # if no match was found, data is invalid, no need to continue
      if ($match == FALSE)
      {
        return FALSE;
      }
    }
    #---------------------------------------------------
  }
}

#------------------------------------------------------------------------------
# Function DisplayTrailList
#
# display filtered trail list
#
# IN: array result of trail data
#
# OUT: 
#------------------------------------------------------------------------------
function DisplayTrailList($trailData)
{
  
  # add trail row to the table
  if (gettype($trailData) != 'boolean')
  {
    print "<table border=\"1\">\n";
    print "  <tr><th>Name</th><th>Terrain</th><th>Difficulty</th></tr>\n";
    
    foreach ($trailData as $k => $row)
    {
      $trailid    = $row['trail_id'];
      $name       = $row['name'];
      $terrain    = $row['terrain_type'];
      $difficulty = $row['difficulty_rank'];
      
      print "  <tr>\n";
      print "    <td><a href=\"./atxtrails.php?id=$trailid\">$name</a></td>\n";
      print "    <td>$terrain</td>\n";
      print "    <td>$difficulty</td>\n";
      print "  </tr>\n";
    }
    
    print "</table>\n";
  }
  else
  {
    print "No trails found matching those criteria.";
  }
  
  
}

#------------------------------------------------------------------------------
# Function ProcessForm
#
# Process Form data
#
# IN: 
#
# OUT: 
#------------------------------------------------------------------------------
function ProcessForm($postdata)
{
  # validate inputs, if any
  #--------------------------
  # before using the values, run them through the sanitize filters
  FilterInputs($_POST);
  
  $passedValidation = false; # validation passing flag, start assuming false
  # then check that they are valid, and set flag if so
  if (FormDataIsValid($_POST))
  {
    $passedValidation = true;
  }
  
  # init the filter arrays
  $difficulties = [];
  $lengths = [];
  $uses = [];
  $terrains = [];
  
  # load filter values into respective arrays
  foreach ($postdata as $formKey => $v)
  {
    LogMessage("Got post data key $formKey and value $v");
    
    if (preg_match('/^difficulty.*/', $formKey))
    {
      $difficulties[] = $postdata[$formKey];
    }
    if (preg_match('/^length.*/', $formKey))
    {
      $lengths[] = $postdata[$formKey];
    }
    if (preg_match('/^uses.*/', $formKey))
    {
      $uses[] = $postdata[$formKey];
    }
    if (preg_match('/^terrains.*/', $formKey))
    {
      $terrains[] = $postdata[$formKey];
    }
  }
  
  $filteredTrails = DAO_SearchTrails($difficulties, $lengths, $uses, $terrains);
  
  DisplayTrailList($filteredTrails);
  
}

?>
<!-- ################ END atxtrails-processform.php ################ -->
