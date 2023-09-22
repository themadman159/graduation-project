<?php
$open_connect = 1 ; 
require('../server.php') ; 

if ( isset ($_POST['tel']) && $_POST['username']) {

    $username = mysqli_real_escape_string( $conn , $_POST['username']) ;
    $tel = mysqli_real_escape_string( $conn , $_POST['tel']) ;

    if ( empty ( $username ) ) {
        die (header('Location: register_page.php')) ; 
    } elseif ( empty ( $tel ) ) {
        die (header('Location: register_page.php')) ; 
    } else {
        $query_check_tel = "SELECT tel_id FROM user WHERE tel_id = '$tel' " ;
        $call_back_query_check_tel = mysqli_query( $conn , $query_check_tel ) ;
    
        if ( mysqli_num_rows( $call_back_query_check_tel ) > 0 ) {
            echo '<script type="text/Javascript">
                    window.alert("มีผู้ใช้นี้อยู่แล้ว") ;
                    window.location.href = "register_page.php" ;
                </script>';
        } else {
            $query_create_user = "INSERT INTO user(username_id , tel_id , role_user) VALUE ('$username' , '$tel' , 'member') " ;
            $call_back_query_create_user = mysqli_query( $conn , $query_create_user ) ;
    
            if ( $call_back_query_create_user ) {
                echo '<script type="text/Javascript">
                    window.alert("สมัครสมาชิกเรียบร้อยแล้ว") ;
                    window.location.href = "login_page.php" ;
                </script>';
            } else {
                die ( header ('Location: register_page.php')) ;
            }
        }
    }
} else {
    die ( header ('Location: register_page.php')) ;
}
