<?php

require('../server_pdo.php');

$user_id = $_GET['user_id'];
$sale_id = $_GET['sale_id'];

$sql_product_basket = "DELETE FROM basket WHERE sale_id = '$sale_id'";
$stmt_product_basket = $conn->prepare($sql_product_basket);
$sql_product = "DELETE FROM sale WHERE sale_id = '$sale_id' AND user_id = '$user_id' ";
$stmt_product = $conn->prepare($sql_product);

if ($stmt_product->execute() AND $stmt_product_basket->execute()) {
    echo '<script type="text/Javascript">
        window.alert("ลบสินค้าในรถเข็นเรียบร้อยแล้ว") ;
        window.location.href = "basket_page.php"
        </script>';
} else {
    echo '<script type="text/Javascript">
        window.alert("ไม่สวามารถลบข้อมูลได้") ;
        window.location.href = "basket_page.php" ;
    </script>';
}
