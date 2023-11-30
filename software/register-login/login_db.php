<?php

session_start() ;
$open_connect = 1 ;
require('../server.php') ;


if ( isset($_POST["tel"])) {
    $tel = mysqli_real_escape_string( $conn , $_POST["tel"] ) ;
    
    $query_check_account = "SELECT * FROM user WHERE tel_id = '$tel'" ; 
    $call_back_query_check_account = mysqli_query($conn,$query_check_account);

    if (mysqli_num_rows($call_back_query_check_account) == 1 ) {
        $result_check_account = mysqli_fetch_assoc($call_back_query_check_account) ;

        if($result_check_account['role_user'] == 'member') {
            $_SESSION['tel_id'] = $result_check_account['tel_id'] ; 
            $_SESSION['role_user'] = $result_check_account['role_user'] ; 
            die (header('Location: ../index.php')) ; 
        } elseif ($result_check_account['role_user'] == 'admin') {
            $_SESSION['tel_id'] = $result_check_account['tel_id'] ; 
            $_SESSION['role_user'] = $result_check_account['role_user'] ; 
            die (header('Location: ../admin/user_management.php')) ; 
        }
    } else {
        echo '<script type="text/Javascript">
                    window.alert("ไม่มีผู้ใช้งานนี้อยู่ในระบบ") ;
                    window.location.href = "login_page.php" ;
            </script>';
    }
} else {
    echo '<script type="text/Javascript">
                    window.alert("กรุณากรอกข้อมูล") ;
                    window.location.href = "login_page.php" ;
            </script>';
}
?>