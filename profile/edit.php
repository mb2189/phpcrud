<?php

session_start();

require_once 'pdo.php';

$prof_id = $_GET['profile_id'];

// if the user has tried to access this page without logging in
if ( ! isset($_SESSION['name'])) {
    die("<p>Please <a href='login.php'>Log In</a> to start</p>");
}

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}

//Check to see if there is POST data
if ( isset($_POST['first_name']) && isset($_POST['last_name']) 
    && isset($_POST['email']) && isset($_POST["headline"]) && isset($_POST['summary'])) {
    // Check to see if all fields are greater than zero
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || 
        strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || 
        strlen($_POST['summary']) < 1) {
            $_SESSION['fail'] = "All fields are required";
            header("Location: edit.php");
            return;
    // Check to see if email contains @ symbol
    } elseif ( strpos($_POST['email'], '@') === false) {
        $_SESSION['fail'] = "Email address must contain @";
        header("Location: edit.php");
        return;
    // If checks pass, send POST data to the database
    } else { 
        $stmt = $pdo->prepare('UPDATE profile SET
            user_id=:uid, first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su 
            WHERE profile_id='.$prof_id);
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'])
        );
        $_SESSION['success'] = 'Record updated';
        header("Location: index.php");
        return;
        
    }
}

$nm = $pdo->query("SELECT first_name, last_name, email, headline, summary,
        user_id, profile_id FROM profile WHERE profile_id = ".$prof_id);

$row = $nm->fetch(PDO::FETCH_ASSOC);

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
<h1>Edit Profile for <?= htmlentities($row['first_name']." ".$row['last_name']) ?></h1>

<?php
// Flash message if the POST data fails
if ( isset($_SESSION['fail']) ) {
    echo('<p style="color: red;">'.$_SESSION['fail']."</p>\n");
    unset($_SESSION['fail']);
}
?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $row['first_name']?>" ></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $row['last_name']?>" ></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $row['email']?>" ></p>
<p>Headline:<br>
<input type="text" name="headline" size="80" value="<?= $row['headline']?>" ></p>
<p>Summary:<br>
<textarea name="summary" rows="8" cols="80"><?= $row['summary']?></textarea>
<p>
<input type="submit" value="Update">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>