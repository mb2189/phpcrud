<?php

session_start();

require_once 'pdo.php';


if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}

$prof_id = $_GET['profile_id'];

$stmt = $pdo->query("SELECT first_name, last_name, email, headline, summary,
        user_id, profile_id FROM profile WHERE profile_id = ".$prof_id);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

?> 

<!-- VIEW -->
<!DOCTYPE html>
<html lang ='en'>
<head>
    <meta charset="UTF-8">
    <title>Profile Database</title>
</head>
<body>
<div class='container'>
<h1>View Profile for <?= htmlentities($row['first_name']." ".$row['last_name']) ?></h1>
<?php
// Flash message if the POST data fails
if ( isset($_SESSION['fail']) ) {
    echo('<p style="color: red;">'.$_SESSION['fail']."</p>\n");
    unset($_SESSION['fail']);
}
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $row['first_name']?>" readonly ></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $row['last_name']?>" readonly></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $row['email']?>" readonly></p>
<p>Headline:<br>
<input type="text" name="headline" size="80" value="<?= $row['headline']?>" readonly></p>
<p>Summary:<br>
<textarea name="summary" rows="8" cols="80" readonly><?= $row['summary']?></textarea>
</form>
</div>
</body>
</html>