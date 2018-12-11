<!-- ################ BEGIN atxtrails-log.php ################ -->
<?php

#------------------------------------------------------------------------------
# Function LogMessage
#
# Very simple logging function, writes a message to the log
#
# IN: message to be written
#
# OUT: if write is successful, message is written to log file return true
#      else return false
#------------------------------------------------------------------------------
function LogMessage($message)
{
  $logFile="../../atxtrails-logs/atxtrails.log";
  $date=date('Y-m-d H:i:s.u');
  
  $message="[$date] $message\n";
  
  if (is_writable($logFile))
  {
    if (!file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX))
    {
      return false;
    }
    else
    {
      return true;
    }
  }
  else
  {
    return false;
  } 
}

?>
<!-- ################ END atxtrails-log.php ################ -->
