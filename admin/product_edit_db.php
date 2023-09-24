<?php
    require('../server_pdo.php');

    $product_id = $_GET['product_id'] ;
    
    $product_barcode = $_POST['barcode'];
    $product_name = $_POST['productName'];
    $product_price = $_POST['productPrice'];

    $sql = "UPDATE `main_product` SET `barcode`=:barcode, `product_name`=:productName, `price`=:productPrice WHERE product_id =:product_id";

    $stmt = $conn->prepare($sql);       

    $stmt->bindParam(':barcode', $product_barcode, PDO::PARAM_STR) ; 
    $stmt->bindParam(':productName', $product_name, PDO::PARAM_STR) ; 
    $stmt->bindParam(':productPrice', $product_price, PDO::PARAM_STR) ; 
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo '<script type="text/Javascript">
        window.alert("แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว") ;
        window.location.href = "product_page.php" ;
    </script>';
    } else {
        echo '<script type="text/Javascript">
        window.alert("ไม่สามารถแก้ไขข้อมูลได้") ;
        window.location.href = "product_edit.php" ;
    </script>';
    }

?>