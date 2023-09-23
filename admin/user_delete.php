<?php
    require('../server_pdo.php');

    $iduser = $_GET['iduser'] ;

<<<<<<< HEAD
    $sql= "DELETE FROM user WHERE user_id = '$iduser'";
=======
    $sql= "DELETE FROM user WHERE tel_id = '$iduser'";
>>>>>>> origin/main

    $stmt = $conn->prepare($sql) ;

    if ($stmt->execute()) {
        echo '<script type="text/Javascript">
        window.alert("ลบข้อมูลเรียบร้อยแล้ว") ;
        window.location.href = "user_management.php" ;
    </script>';
    } else {
        echo '<script type="text/Javascript">
<<<<<<< HEAD
        window.alert("ไม่สามารถลบข้อมูลได้") ;
=======
        window.alert("ไม่สวามารถลบข้อมูลได้") ;
>>>>>>> origin/main
        window.location.href = "user_management.php" ;
    </script>';
    }
?>