<?php

session_start();
$open_connect = 1;
require('../server.php');

if (!isset($_SESSION['tel_id']) || !isset($_SESSION['role_user'])) {
    die(header('Location: login_page.php'));
} elseif (isset($_GET['logout'])) {
    session_destroy();
    die(header('Location: login_page.php'));
} else {
    $tel_id = $_SESSION['tel_id'];
    $query_show = "SELECT * FROM user WHERE tel_id = '$tel_id'";
    $call_back_show = mysqli_query($conn, $query_show);
    $result_show_name = mysqli_fetch_assoc($call_back_show);
}

$sql = "SELECT * FROM sale ";
$result = mysqli_query($conn, $sql);
$result_show = mysqli_fetch_assoc($result);

$sale_id = $_GET['sale_id'];

$date = $_GET['date'];
$dateInYYYYMMDD = $date;
$dateInDMY = date("d-m-Y", strtotime($dateInYYYYMMDD));
$timeInHM = date("H:i", strtotime($dateInYYYYMMDD));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการย้อนหลัง</title>
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

<body class="bg-light">
    <div class="container vh-100 d-flex flex-column justify-content-center align-items-center bg-white w-50">
        <section class="text-center">
            <img src="../img/logo.png" alt="" width="150" height="150">
            <h2>รายการย้อนหลัง</h2>
            <h3>ในวันที่ <?php echo $dateInDMY ?></h3>
            <h5>เมื่อเวลา <?php echo $timeInHM ?> น.</h5>
            <h4>ของคุณ <?php echo $result_show_name['username_id'] ?></h4>
        </section>
        <section class="justify-content-center text-center">
            <table class="text-center p-3 m-3 mx-auto">
                <tr class="py-1">
                    <td class="px-2">ชื่อสินค้า</td>
                    <td class="px-2">ราคาสินค้า</td>
                    <td class="px-2">จำนวนสินค้า</td>
                    <td>รวมราคาทั้งหมด</td>
                </tr>
                <?php $sql = "SELECT * FROM basket WHERE sale_id = '$sale_id'"; ?>
                <?php $query = mysqli_query($conn, $sql);  ?>
                <?php $rows = mysqli_num_rows($query);  ?>
                <?php $arr_amount = [];  ?>
                <?php if ($rows > 0) {  ?>
                    <?php foreach ($query as $val) {  ?>
                        <?php if ($val['sale_id'] == $sale_id) { ?>

                            <tr class="py-1">
                                <td class="px-2"><?= $val['product_name']; ?></td>
                                <td class="px-2"><?= $val['price']; ?></td>
                                <td class="px-2"><?= $val['product_amount']; ?></td>
                                <td class="px-2"><?= $val['price'] * $val['product_amount']; ?></td>
                            </tr>
                        <?php     } ?>
                    <?php  } ?>
                    <?php
                    $sql_amount = mysqli_query($conn, "SELECT SUM(product_amount * price) as total_price FROM basket WHERE sale_id = '$sale_id';");
                    while ($rows = mysqli_fetch_assoc($sql_amount)) {
                        $total_price = $rows['total_price'];
                    }
                    ?>
                    <hr>
                    <tr class="py-1">
                        <td colspan="3" class="bg-success text-white">ราคาสินค้าทั้งหมด</td>
                        <td class="px-2"><?php echo $total_price ?></td>
                    </tr>
                <?php } ?>

            </table>
        </section>
        <section class="d-grid gap-2 py-2 w-100">
            <hr class="w-75 mx-auto">
            <button type="button" class="btn btn-outline-primary mx-2" onclick="document.location='history_page.php'">ย้อนกลับ</button>
        </section>
    </div>

</body>

</html>