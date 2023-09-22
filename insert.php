<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product";

$conn = new mysqli($servername, $username, $password, $dbname);

$conn->set_charset("utf8");

// Check connection
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get barcode from query parameter
$barcode = $_GET["barcode"];
$basket_code = $_GET["basket_code"];
$amount_get = $_GET["product_amount"] ;

$barcode = $conn->real_escape_string($barcode);
$product_amount = $conn->real_escape_string($amount_get);

if ( $product_amount == 1  ) {

    $result_up_pd_amount = $conn->query("UPDATE basket SET product_amount = product_amount - 1 WHERE barcode = '$barcode'");

    $result_check = $conn->query("SELECT * FROM basket WHERE product_amount = 0 AND barcode = '$barcode'"); ;
    if ( $result_check->num_rows > 0 ) {
        $conn->query("DELETE FROM basket WHERE barcode = '$barcode'");
    }
} else if ( $product_amount == 2 ) {
    $result_up_pd_amount = $conn->query("UPDATE basket SET product_amount = product_amount + 1 WHERE barcode = '$barcode'");
} else if ( $product_amount == 0 ) {
    $result_up_pd_amount = $conn->query("DELETE FROM basket WHERE basket_code =  '$basket_code' ");
}

?>

