<?php
require('../server_pdo.php');
// Get the iduser parameter from the URL
$iduser = $_GET['iduser'];

$sql = "SELECT * FROM user WHERE user_id = :iduser";
$checkStmt = $conn->prepare($sql);
$checkStmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);
$checkStmt->execute();

// Fetch the user data as an associative array
$userData = $checkStmt->fetch(PDO::FETCH_ASSOC);

// Check if user data was found
if ($userData) {
    // You can access user data like $userData['column_name']
    $tel = $userData['tel_id'];
    $username = $userData['username_id'];


    $sql_fetch_basket = "SELECT * FROM basket WHERE basket_code = 1";
    $checkStmt = $conn->prepare($sql_fetch_basket);

    // Execute the prepared statement
    $checkStmt->execute();

    // Fetch the results, for example, using a while loop
    while ($row = $checkStmt->fetch(PDO::FETCH_ASSOC)) {
        // Process the data here
        $productname = $row['product_name'];
        $barcode = $row['barcode'];
        $price = $row['price'];
        $productAmount = $row['product_amount'];


        $sql_insert = "INSERT INTO user_history (barcode, product_name, price, tel_id ,username_id , date , product_amount  ) VALUES (:barcode, :productname, :price, :tel_id, :username_id, NOW(), :product_amount)";

        // Prepare the SQL statement
        $insertStmt = $conn->prepare($sql_insert);

        // Bind parameters
        $insertStmt->bindParam(':barcode', $barcode, PDO::PARAM_STR);
        $insertStmt->bindParam(':productname', $productname, PDO::PARAM_STR);
        $insertStmt->bindParam(':price', $price, PDO::PARAM_INT);
        $insertStmt->bindParam(':tel_id', $tel, PDO::PARAM_STR);
        $insertStmt->bindParam(':username_id', $username, PDO::PARAM_STR);
        $insertStmt->bindParam(':product_amount', $productAmount, PDO::PARAM_STR);

        if ($insertStmt->execute()) {
            // The insertion was successful for this row
            echo '<script type="text/Javascript">
                window.alert("บันทึกข้อมูลย้อนหลังเรียบร้อยแล้ว") ;
                window.location.href = "index.php" ;
            </script>';
        } else {
        // There was an error during insertion
        echo "Error inserting data into user_history: " . $insertStmt->errorInfo()[2] . "<br>";
        }
    }
} else {
    // No user data found for the given iduser
    echo "User not found.";
}
