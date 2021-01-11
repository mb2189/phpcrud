<?php 
require_once 'pdo.php';

session_start();

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';


// Check to see if we have some POST data, if we do process it
if ( isset($_POST["email"]) && isset($_POST['pass']) ) {
    if ( strlen($_POST["email"]) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION["fail"] = "Email and password are required";
        header("Location: login.php");
        return;
    }

    $check = hash("md5", $salt.$_POST["pass"]);
    $stmt = $pdo->prepare("SELECT user_id, name FROM users
            WHERE email = :em AND password = :pw");
    $stmt->execute(array(":em" => $_POST["email"], ":pw" => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false) {
        $_SESSION["name"] = $row["name"];
        $_SESSION["user_id"] = $row["user_id"];
        // Redirect browser to index.php
        header("Location: index.php");
        return;
    } else {
        $_SESSION["fail"] = "Incorrect Email or Password";
        header("Location: login.php");
        return;
    }
}    
?>
<!-- VIEW -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>
    <?php
    if ( isset($_SESSION['fail']) ) {
        echo('<p style="color: red;">'.$_SESSION['fail']."</p>\n");
        unset($_SESSION['fail']);
    }
    ?>
    <form method="POST">
    <label for="email">Email</label>
    <input type="text" name="email" id="email"><br/>
    <label for="pass">Password</label>
    <input type="password" name="pass" id="pass"><br/>
    <input type="submit" value="Log In">
    <input type="submit" name="cancel" value="Cancel">
    </form>
</div>
</body>
