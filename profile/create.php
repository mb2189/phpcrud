<?php

session_start();

//Link to the pdo.php so we can use the $pdo variable later
require_once 'pdo.php';


if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';

//Check to see if there is POST data
if ( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass']) ) {
    // Check to see if all fields are greater than zero
    if ( strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
            $_SESSION['fail'] = "Name, Email, and Password are required";
            header("Location: create.php");
            return;
    // Check to see if email contains @ symbol
    } elseif ( strpos($_POST['email'], '@') === false) {
        $_SESSION['fail'] = "Email address must contain @";
        header("Location: create.php");
        return;
    // Check to see if the email already exists in the database    
    } else { 
        $acctcheck = $pdo->prepare("SELECT email FROM users
        WHERE email = :em");
        $acctcheck->execute(array(":em" => $_POST["email"]));
        $row = $acctcheck->fetch(PDO::FETCH_ASSOC);
        // If $row is not false then the email already exists in the database
        if ($row !== false) {
            $_SESSION['fail'] = "This email is already in use";
            header("Location: create.php");
            return;
        // If checks pass, send POST data to the database after hashing and salting the password sent from user
        } else { 
            $check = hash("md5", $salt.$_POST["pass"]);
            $stmt = $pdo->prepare('INSERT INTO users
                (name, email, password)
                VALUES (:nm, :em, :pw)');
            $stmt->execute(array(
                ':nm' => $_POST['name'],
                ':em' => $_POST['email'],
                ':pw' => $check,));
            $_SESSION['success'] = 'New User Created';
            header("Location: login.php");
            return;
        }
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
    <h1>Create New User</h1>
<?php
// Flash message if the POST data fails
if ( isset($_SESSION['fail']) ) {
    echo('<p style="color: red;">'.$_SESSION['fail']."</p>\n");
    unset($_SESSION['fail']);
}
?>
<form method="POST">
<label for="name">Name</label>
<input type="text" name="name" id="name"><br>
<label for="email">Email</label>
<input type="text" name="email" id="email"><br>
<label for="pass">Password</label>
<input type="password" name="pass" id="pass"><br>
<input type="submit" value="Create">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>