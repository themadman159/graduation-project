<?php

session_start() ;
$open_connect = 1 ;
require('../server.php') ;

if(!isset($_SESSION['tel_id']) || !isset($_SESSION['role_user'])) {
    die(header('Location: login_page.php')) ; 
} elseif (isset($_GET['logout'])){
    session_destroy() ; 
    die(header('Location: login_page.php')) ; 
} else {
    $tel_id = $_SESSION['tel_id'] ; 
    $query_show = "SELECT * FROM user WHERE tel_id = '$tel_id'" ; 
    $call_back_show = mysqli_query($conn,$query_show) ; 
    $result_show = mysqli_fetch_assoc($call_back_show) ; 
}

date_default_timezone_set('asia/bangkok');
$date = date("d-m-Y");
$time = date("H:i");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบเสร็จ</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <link rel="icon" href="../img/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500&display=swap" rel="stylesheet"> 
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>

</head>
<body class ="bg-light text-dark">  
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div id = "receipt_bill" class="bill text-center rounded-3 bg-white p-5 m-5">
        <table align="center" class="m-3">
            <img src="../img/logo.png" alt="" width="150" height="150">
                <p>ใบเสร็จของคุณ : <?php echo $result_show['username_id'] ?></p>
                <p>เบอร์โทร์ : <?php echo $result_show['tel_id'] ?></p>
                <p><?php echo "วันที่ ". $date . " เวลา " . $time ." น." ; ?></p>
                <hr>
            <tr>
                <td class = "px-3">ชื่อสินค้า</td>
                <td class = "px-3">จำนวนสินค้า</td>
                <td class = "px-3">ราคาสินค้าทั้งหมด</td>
            </tr>
            <?php 
                $sql = "SELECT * FROM basket order by 'basket' ; " ; 
                $query = mysqli_query( $conn , $sql ) ; 
                $rows = mysqli_num_rows( $query ) ;
                $arr_amount = [] ; 
                if ( $rows > 0 ) {
                     foreach ( $query as $val ) {
                        if ( $val['basket_code'] == "1" ) {
                            echo "<tr>" ;
                                echo "<td class = 'px-3'>".$val['product_name']."</td>" ;
                                if ( $val['product_amount'] > 0 ) {
                                    echo "<td class = 'px-3'>".$val['product_amount']."</td>" ;
                                }
                                if ( $val['price'] > 0 ) {
                                    echo "<td class = 'px-3'>".$val['price'] * $val['product_amount']."</td>" ;
                                    $total_current = $val['price'] * $val['product_amount'] ; 
                                }
                            echo "</tr>" ;
                        }
                    }
                // ราคารวมสินค้าทั้งหมด 
                $sql_amount = mysqli_query($conn , "SELECT SUM(product_amount * price) as total_price FROM basket ;" ) ;
                while ( $rows = mysqli_fetch_assoc($sql_amount)) {
                    $total_price = $rows['total_price'] ; 
                }
                echo "<td colspan = '2' class ='bg-success text-white'>ราคาสินค้าทั้งหมด</td>"."<td>".$total_price."</td>" ; 
                } else { 
                    echo "<tr>" ;
                    echo "<td colspan = '3'class = 'px-3'>ไม่มีสินค้าอยู่ในตะกร้าของคุณ</td>" ;
                    echo "</tr>" ; 
                }
            ?>
        </table>
        <hr>
        <button type="button" class="btn btn-primary" id = "download_bill">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"></path>
        </svg>
        ดาวน์โหลดใบเสร็จ
        </button>
        <button type="button" class="btn btn-outline-danger" onclick="document.location='../register-login/login_page.php?logout=1'">ออกจากระบบ</a></button>
        </div>
        <br>
        <!-- ใบเสร็จ -->
        
        <script src="../dom-to-image.js"></script>
        <script>
            var receipt_bill = document.getElementById("receipt_bill") ;
            var btn_download = document.getElementById("download_bill");

            btn_download.addEventListener("click" , () =>  {
                domtoimage.toJpeg(receipt_bill).then((data) => {
                    var link = document.createElement("a") ;
                    link.download = "my-receipt-bill.jpeg" ;
                    link.href = data ;
                    link.click() ;
                });
            });
        </script>
    </div>
    
</body>
</html>
