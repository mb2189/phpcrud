<?php
$pdo = new PDO('mysql:host=hostname;port=portnumber;dbname=databasename', 'username', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>