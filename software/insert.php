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
$barcode = $_POST["barcode"];
$basket_code = $_POST["basket_code"];
$amount_get = $_POST["product_amount"] ;

$barcode = $conn->real_escape_string($barcode);
$product_amount = $conn->real_escape_string($amount_get);

$query = "SELECT MAX(sale_id) as max_sale_id FROM sale";
$result_sale = $conn->query($query);
$row = $result_sale->fetch_assoc();
$max_sale_id = $row['max_sale_id'];

if ( $product_amount == 1  ) {

    $result_up_pd_amount = $conn->query("UPDATE basket SET product_amount = product_amount - 1 WHERE barcode = '$barcode' AND sale_id = '$max_sale_id'");

    $result_check = $conn->query("SELECT * FROM basket WHERE product_amount = 0 AND barcode = '$barcode' AND sale_id = '$max_sale_id'"); ;
    if ( $result_check->num_rows > 0 ) {
        $conn->query("DELETE FROM basket WHERE barcode = '$barcode' AND sale_id = '$max_sale_id'");
    }
} else if ( $product_amount == 2 ) {
    $result_up_pd_amount = $conn->query("UPDATE basket SET product_amount = product_amount + 1 WHERE barcode = '$barcode' AND sale_id = '$max_sale_id'");
} else if ( $product_amount == 0 ) {
    $result_basket_data = $conn->query("UPDATE sale SET basket_status = 1 WHERE basket_status = 0");
    
}

?>

