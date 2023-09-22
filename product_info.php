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
$basket_code = $_GET["basket_code"] ; 
$amount_get = $_GET["product_amount"] ;

// Sanitize the barcode value before using it in the query
$barcode = $conn->real_escape_string($barcode);
$basket_code = $conn->real_escape_string($basket_code);
$product_amount = $conn->real_escape_string($amount_get);

// Fetch product info from database based on barcode
$result = $conn->query("SELECT product_name, price FROM main_product WHERE barcode = '$barcode'");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_name = $row["product_name"];
    $price = $row["price"];
    

    // Create JSON response
    $response = array("product_name" => $product_name, "price" => $price);

    $result_basket_data = $conn->query("SELECT product_name, price, basket_code, product_amount FROM basket WHERE product_name = '$product_name' AND basket_code = '$basket_code'");
    if ($result_basket_data->num_rows > 0) {
        $row_basket_data = $result_basket_data->fetch_assoc();
        $product_name = $row_basket_data['product_name'];

        $conn->query("UPDATE basket SET product_amount = product_amount + 1 WHERE product_name = '$product_name'");

    } else {
        $conn->query("INSERT INTO basket (product_name, price, basket_code, product_amount, barcode) VALUES ('$product_name', '$price', '$basket_code', '$product_amount' , '$barcode')");
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE) ;
} else {
    echo "Product not found" ; 
}

$conn->close();