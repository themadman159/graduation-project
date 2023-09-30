<?php

if ( $open_connect != 1 ) {
    die (header ('Location: ../register-login/login_page.php') ) ;
}
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product";

$conn = new mysqli($servername, $username, $password, $dbname);

$conn->set_charset("utf8");

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


?>