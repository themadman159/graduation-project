<?php

require('../server_pdo.php');

$basket_product = $_GET['basket_product'];
$basket_num = $_GET['basket_code'];

$sql_product = "DELETE FROM basket WHERE basket_code = '$basket_num' AND product_name = '$basket_product' ";

$stmt_product = $conn->prepare($sql_product);

if ($stmt_product->execute()) {
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
