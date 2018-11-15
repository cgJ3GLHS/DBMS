<html>
<head>
<title>Voting Booth</title>
</head>
<body>
<h1>Voting Booth</h1>
<?php
$host = "localhost";
$username = "democracy";
$password = "landslide";
$database = "votes";
$link = mysqli_connect($host, $username, $password, $database);
if (isset($_GET['species'])) {
     $species = $_GET['species'];
     $addquery = "INSERT INTO votes_cast (species_id) VALUES ('$species')";
     mysqli_query($link, $addquery);
     print "<p>Thanks for voting!</p> <h3>Votes so far:</h3>";
     // Per this week's assignment, Write a query that selects the species 
     // names and the count of votes cast for each species_id from the two 
     // tables (don't forget to GROUP them) in highest to lowest order. Then 
     // use the function mysqli_fetch_array() to print each line.
     
     # run the query!
     $voteresult = mysqli_query($link, "SELECT s.name, count(*) AS qty FROM species s JOIN votes_cast vc ON s.species_id = vc.species_id GROUP BY s.species_id ORDER BY count(*) DESC;");
     
     # display the results in a table
     print "<table>";
     print "<tr><th>Penguin</th><th>Votes</th></tr>";
     
     # fetch the row from the result object and print
     while ($row = mysqli_fetch_array($voteresult)) {
          print "<tr>";
          print "  <td>$row[name]</td>";
          print "  <td>$row[qty]</td>";
          print "</tr>";
     }
     print "</table>";
     
} else {
     print "Choose your favorite penguin:";
     print "<form method=GET action='voter.php'><select name='species'>";
     $listresult = mysqli_query($link, "SELECT species_id, name FROM species");
     while ($row = mysqli_fetch_array($listresult)) {
          print "<option value='$row[species_id]'> $row[name]</option>";
     }
     print "</select>";
     print "<input type='submit' name='submit' value='submit'></form>";
}
mysqli_close($link);
?>
</body>
</html>


