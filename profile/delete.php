<?php

session_start();

require_once 'pdo.php';

$prof_id = $_GET['profile_id'];
$nm = $pdo->query("SELECT first_name, last_name, email, headline, summary,
        user_id, profile_id FROM profile WHERE profile_id = ".$prof_id);
$row = $nm->fetch(PDO::FETCH_ASSOC);


// if the user has tried to access this page without logging in
if ( ! isset($_SESSION['name'])) {
    die("<p>Please <a href='login.php'>Log In</a> to start</p>");
} elseif ($_SESSION['user_id'] !== $row['user_id']) {
    die("<p>ACCESS DENIED</p>");
}



if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}


if ( isset($_POST['delete'])) {
    $stmt = $pdo->prepare('DELETE from profile WHERE profile_id='.$prof_id);
        $stmt->execute();
        $_SESSION['success'] = 'Record deleted';
        header("Location: index.php");
        return;
}

?>

<!-- VIEW -->
<!DOCTYPE html>
<html>
<head>
<title>Profile DB</title>
</head>
<body>
<div class="container">
<h1>Delete Profile for <?= htmlentities($row['first_name']." ".$row['last_name']) ?></h1>
<p>Do you want to delete the profile for <?= htmlentities($row['first_name']." ".$row['last_name']) ?>?</p>
<form method='POST'>
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>
