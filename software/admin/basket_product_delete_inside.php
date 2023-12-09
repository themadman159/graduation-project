<?php

require('../server_pdo.php');

$basket_id = $_GET['basket_id'];
$sale_id = $_GET['sale_id'];

$sql_product = "DELETE FROM basket WHERE sale_id = '$sale_id' AND basket_id = '$basket_id' ";
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
