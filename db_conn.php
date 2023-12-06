<?php
// con to db 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "example";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// check conn
if (!$conn) {
    die("DB conn error: " . mysqli_connect_error());
}

?>