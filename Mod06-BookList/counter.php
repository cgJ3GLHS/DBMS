<html>
  <head>
    <title>A Simple Hit Counter</title>
  </head>
  <body>
    <h1>A Simple hit counter</h1>
<?php
$current_count = file("count.txt");
$count = $current_count[0];
print "This page has been accessed $count times.";
$count = $count + 1;
file_put_contents("count.txt", $count);
?>
  </body>
</html>
