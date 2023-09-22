<?php
    require('../server_pdo.php');

    $iduser = $_GET['iduser'] ;

    $sql= "DELETE FROM user WHERE tel_id = '$iduser'";

    $stmt = $conn->prepare($sql) ;

    if ($stmt->execute()) {
        echo '<script type="text/Javascript">
        window.alert("ลบข้อมูลเรียบร้อยแล้ว") ;
        window.location.href = "user_management.php" ;
    </script>';
    } else {
        echo '<script type="text/Javascript">
        window.alert("ไม่สวามารถลบข้อมูลได้") ;
        window.location.href = "user_management.php" ;
    </script>';
    }
?>