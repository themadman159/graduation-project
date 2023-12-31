<?php
require('../server_pdo.php');

$username = $_POST['username'] ;
$tel = $_POST['tel'] ; 
$role = $_POST['role'] ; 

$checkSql = "SELECT COUNT(*) FROM user WHERE tel_id = :tel"; 
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bindParam(':tel', $tel, PDO::PARAM_STR);
$checkStmt->execute();
$userCount = $checkStmt->fetchColumn();

if ($userCount > 0) {
    echo '<script type="text/javascript">
        window.alert("มีผู้ใช้นี้อยู่แล้ว");
        window.location.href = "user_add.php";
    </script>';

} else {

    $sql = "INSERT INTO user (username_id, tel_id, role_user) VALUES (:username, :tel, :role)"; 

    $stmt = $conn->prepare($sql) ;  
        
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':tel', $tel, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    
    if($stmt->execute()) {
        echo '<script type="text/Javascript">
            window.alert("เพิ่มผู้ใช้งานเรียบร้อยแล้ว") ;
            window.location.href = "user_management.php" ;
        </script>';
    } else {
        echo '<script type="text/Javascript">
                        window.alert("สมัครสมาชิกเรียบร้อยแล้ว") ;
                        window.location.href = "user_add.php" ;
                    </script>';
    }
}
