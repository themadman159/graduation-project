<?php
require('../server_pdo.php');

$basket = $_GET['basket_code'];

$sql = "DELETE FROM basket WHERE basket_code = '$basket'";

$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    echo '<script type="text/Javascript">
        window.alert("ลบรถเข็นเรียบร้อยแล้ว") ;
        window.location.href = "basket_page.php" ;
    </script>';
} else {
    echo '<script type="text/Javascript">
        window.alert("ไม่สวามารถลบข้อมูลได้") ;
        window.location.href = "basket_page.php" ;
    </script>';
}
