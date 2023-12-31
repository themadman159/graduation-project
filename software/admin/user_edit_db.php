<?php
require('../server_pdo.php');

$iduser = $_GET['iduser'];

$tel = $_POST['tel'];
$username = $_POST['username'];
$role = $_POST['role'];

$checkSql = "SELECT * FROM user WHERE user_id = :iduser";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bindParam(':iduser', $iduser, PDO::PARAM_STR);
$checkStmt->execute();

$sql = "UPDATE `user` SET `username_id`=:username, `tel_id`=:tel, `role_user`=:role WHERE user_id = :user_id ";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':tel', $tel, PDO::PARAM_STR);
$stmt->bindParam(':role', $role, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $iduser, PDO::PARAM_STR);

if ($stmt->execute()) {
    echo '<script type="text/Javascript">
        window.alert("แก้ไขข้อมูลผู้ใช้เรียบร้อยแล้ว") ;
        window.location.href = "user_management.php" ;
    </script>';
} else {
    echo '<script type="text/Javascript">
        window.alert("ไม่สามารถแก้ไขข้อมูลได้") ;
        window.location.href = "user_management.php" ;
    </script>';
}

?>
