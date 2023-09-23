<?php
    require('../server_pdo.php');

    $product = $_GET['product_code'] ;

    $sql= "DELETE FROM main_product WHERE barcode = '$product'";

    $stmt = $conn->prepare($sql) ;

    if ($stmt->execute()) {
        echo '<script type="text/Javascript">
        window.alert("ลบข้อมูลสินค้าเรียบร้อยแล้ว") ;
        window.location.href = "product_page.php" ;
    </script>';
    } else {
        echo '<script type="text/Javascript">
        window.alert("ไม่สามารถลบข้อมูลได้") ;
        window.location.href = "product_page.php" ;
    </script>';
    }
?>