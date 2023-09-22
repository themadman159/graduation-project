<?php

session_start() ;
$open_connect = 1 ;
require ('server.php') ;

$username = mysqli_real_escape_string( $conn , $_POST['username']) ;
$tel = mysqli_real_escape_string( $conn , $_POST['tel']) ;
$role = mysqli_real_escape_string( $conn , $_POST['role']) ; 

if ( empty ( $username ) ) {
    die (header('Locaiton : user_add.php')) ;
} elseif ( empty ( $tel ) ) {
    die (header('Locaiton : user_add.php')) ; 
} else {
    $query_check_tel = "SELECT tel_id FROM user WHERE tel_id = '$tel' " ;
    $call_back_query_check_tel = mysqli_query( $conn , $query_check_tel ) ;

    if ( mysqli_num_rows( $call_back_query_check_tel ) > 0 ) {
        echo '<script type="text/Javascript">
                window.alert("มีผู้ใช้นี้อยู่แล้ว") ;
                window.location.href = "user_add.php" ;
            </script>';
    } else {
        $query_create_user = "INSERT INTO user(username_id , tel_id , role_user) VALUE ('$username' , '$tel' , '$role') " ;
        $call_back_query_create_user = mysqli_query( $conn , $query_create_user ) ;

        if ( $call_back_query_create_user ) {
            echo '<script type="text/Javascript">
                window.alert("เพิ่มข้อมูลผู้ใช้งานเรียบร้อยแล้ว") ;
                window.location.href = "user_management.php" ;
            </script>';
        } else {
            die ( header ('Location: user_add.php')) ;
        }
    }

}

?>