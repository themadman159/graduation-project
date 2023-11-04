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
    $user_id = $userData['user_id'];

    $sql_fetch_sale = "SELECT * FROM sale WHERE user_id = 0 AND basket_status = 0 ";
    $checkStmt_sale = $conn->prepare($sql_fetch_sale);

    // Execute the prepared statement
    $checkStmt_sale->execute();

    // Fetch the results, for example, using a while loop
    while ($row = $checkStmt_sale->fetch(PDO::FETCH_ASSOC)) {

        $sql_insert = "UPDATE `sale` SET `user_id`=:user_id, `basket_status`=:basket_status  WHERE user_id = 0 AND basket_status = 0 ";
        // Prepare the SQL statement
        $insertStmt = $conn->prepare($sql_insert);

        // Bind parameters
        $insertStmt->bindParam(':user_id', $iduser, PDO::PARAM_STR);
        $insertStmt->bindValue(':basket_status', 1, PDO::PARAM_INT);

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

        $sql_get_sale_id = "SELECT MAX(sale_id) as max_sale_id FROM sale WHERE user_id = :iduser";
        $getSaleIdStmt = $conn->prepare($sql_get_sale_id);
        $getSaleIdStmt->bindParam(':iduser', $iduser, PDO::PARAM_STR);
        $getSaleIdStmt->execute();

        $sale_data = $getSaleIdStmt->fetch(PDO::FETCH_ASSOC);
        if ($sale_data) {
            $sale_id = $sale_data['max_sale_id'];

            // Now that you have the sale_id, you can update it in the "basket" table
            $sql_update_basket = "UPDATE basket SET sale_id = :sale_id WHERE sale_id = 0";
            $updateStmt_basket = $conn->prepare($sql_update_basket);
            $updateStmt_basket->bindParam(':sale_id', $sale_id, PDO::PARAM_INT);
            $updateStmt_basket->execute();
        }
    }
} else {
    // No user data found for the given iduser
    echo "User not found.";
}
