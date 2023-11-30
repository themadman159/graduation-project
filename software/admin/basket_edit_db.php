<?php
    require('../server_pdo.php');

    $basket_id = $_GET['basket_id'] ;
    $product_amount = $_POST['product_amount'];

    $sql = "UPDATE `basket` SET `product_amount`=:product_amount WHERE basket_id =:basket_id";

    $stmt = $conn->prepare($sql);       

    $stmt->bindParam(':product_amount', $product_amount, PDO::PARAM_STR) ; 
    $stmt->bindParam(':basket_id', $basket_id, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo '<script type="text/Javascript">
        window.alert("แก้ไขข้อมูลสินค้าในตระกร้าเรียบร้อยแล้ว") ;
        window.location.href = "basket_page.php" ;
    </script>';
    } else {
        echo '<script type="text/Javascript">
        window.alert("ไม่สามารถแก้ไขข้อมูลได้") ;
        window.location.href = "basket_page.php" ;
    </script>';
    }

?>