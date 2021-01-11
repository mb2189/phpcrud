<?php

session_start();

require_once 'pdo.php';

?>
<!DOCTYPE html>
<html>
<head>
<title>Profile DB</title>
</head>
<body>
<div class="container">
<h1>Welcome to the Profile Database</h1>
<?php 
    if(isset($_SESSION["name"])) {
        echo('<a href="logout.php">Log Out</a>');
    } else {
        echo('<a href="login.php">Please Log In</a>');
    } 
    // Flash message if the POST data fails
    if ( isset($_SESSION['fail'])) {
        echo('<p style="color: red;">'.$_SESSION['fail']."</p>\n");
        unset($_SESSION['fail']);
    } else if ( isset($_SESSION['success'])) {
        echo('<p style="color: green;">'.$_SESSION['success']."</p>\n");
        unset($_SESSION['success']);
    }
?>
</div>
<div class='tables'>
<h2>Profiles</h2>
<table border=1>
<?php
    // Table Headers
    echo "<tr><th>";
    echo("Name");
    echo "</th><th>";
    echo("Headline");
    echo "</th>";
    if(isset($_SESSION["name"])) {
        echo("<th>");
        echo("Action");
        echo "</th></tr>";
    } else {
        echo("</tr>");
    }    
//Show the records from the database
$stmt = $pdo->query("SELECT first_name, last_name, headline, user_id, profile_id FROM profile");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo("<tr><td>");
    echo('<a href ="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row["first_name"].' '.$row["last_name"]).'</a>');
    // echo(htmlentities($row["last_name"]));
    echo("</td><td>");
    echo(htmlentities($row["headline"]));
    echo "</td>";
    // If the user is logged in, allow them to edit and delete entries to the database
    if (isset($_SESSION['name'])) {
        if($_SESSION["user_id"] === $row["user_id"]) {
            echo("<td>");
            echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> | '); 
            echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
            echo("</td></tr>"); 
        }   
    } else {
        echo("</tr>");
    }
}
?>
</table><br>
<?php
    if (isset($_SESSION['name'])) {
        echo('<a href="add.php">Add New Entry</a>');
    }
?>
</body>
</html>
