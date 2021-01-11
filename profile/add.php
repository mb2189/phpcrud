<?php

session_start();

//Link to the pdo.php so we can use the $pdo variable later
require_once 'pdo.php';

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
            header("Location: add.php");
            return;
    // Check to see if email contains @ symbol
    } elseif ( strpos($_POST['email'], '@') === false) {
        $_SESSION['fail'] = "Email address must contain @";
        header("Location: add.php");
        return;
    // If checks pass, send POST data to the database
    } else { 
        $stmt = $pdo->prepare('INSERT INTO profile
            (user_id, first_name, last_name, email, headline, summary)
            VALUES ( :uid, :fn, :ln, :em, :he, :su)');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'])
        );
        $_SESSION['success'] = 'Record inserted';
        header("Location: index.php");
        return;
        
    }
}
?>
<!-- VIEW -->
<!DOCTYPE html>
<html>
<head>
<title>Profile Database</title>
</head>
<body>
<div class="container">
    <h1>Add Profile for <?= htmlentities($_SESSION['name']) ?></h1>
<?php
// Flash message if the POST data fails
if ( isset($_SESSION['fail']) ) {
    echo('<p style="color: red;">'.$_SESSION['fail']."</p>\n");
    unset($_SESSION['fail']);
}
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"></p>
<p>Last Name:
<input type="text" name="last_name" size="60"></p>
<p>Email:
<input type="text" name="email" size="30"></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>
