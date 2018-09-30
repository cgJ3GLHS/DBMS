<html>
  <head>
    <title>List of Books</title>
  </head>
  <body>
    <h1>List of Books</h1>
<?php
$bookarray = file('titles.txt');
$howmany = count($bookarray);
print "There are $howmany books in the list";
/* For this assignment, insert code here 
to ouput the list of titles */

# print paragraph containing books from bookarray
print "<p>";
foreach ($bookarray as $i => $book)
{
  print "$book<br/>\n";
}
print "</p>";

?>
  </body>
</html>
