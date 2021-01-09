<?php
session_start();

require_once 'pdo.php';

// If the user has tried to access this page without logging in
if ( ! isset($_SESSION["name"])) {
    die("<p>Please <a href='login.php'>Log In</a> to start</p>");
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: logout.php');
    return;
}

// If the user requested to Add New vehicles go to add.php
if ( isset($_POST['add']) ) {
    header('Location: add.php');
    return;
}

?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <title>Auto Tracker</title>
</head>
<body>
<h1>Tracking Autos for <?= htmlentities($_SESSION["name"]) ?></h1>
<?php
    // Flash message if the login was successful or adding new data was successful
    if (isset($_SESSION["success"])) {
        echo('<p style="color: green">'.$_SESSION["success"]."</p>\n");
        unset($_SESSION["success"]);
    }
?>
<h2>Automobiles</h2>
<table border=1>
<?php
   echo "<tr><th>";
   echo("Year");
   echo "</th><th>";
   echo("Make");
   echo "</th><th>";
   echo("Mileage");
   echo("</th><th>");
   echo("Action");
   echo "</th></tr>";
//Show the records from the database
if ( isset($_SESSION["name"])) {
    $stmt = $pdo->query("SELECT year, make, mileage, auto_id FROM autos");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo("<tr><td>");
        echo(htmlentities($row['year']));
        echo("</td><td>");
        echo(htmlentities($row['make']));
        echo "</td><td>";
        echo(htmlentities($row['mileage']));
        echo "</td><td>";
        echo('<a href="edit.php?auto_id='.$row['auto_id'].'">Edit</a> | '); 
        echo('<a href="delete.php?auto_id='.$row['auto_id'].'">Delete</a>');
        echo("</td></tr>");
    }
}
?>
</table><br>
<form method="post">
<input type="submit" name="add" value="Add New">
<input type="submit" name="logout" value="Log Out">
</form>
</body>
</html>