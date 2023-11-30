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
    $result_show = mysqli_fetch_assoc($call_back_show);

    $iduser = $result_show['user_id'];
}

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

<body>
    <div class="container vh-100 d-flex flex-column justify-content-center align-items-center bg-white">
        <section class="text-center">
            <h2>รายการย้อนหลัง</h2>
            <h4>ของคุณ <?php echo $result_show['username_id'] ?></h4>

        </section>
        <section>
            <?php $sql = "SELECT DISTINCT sale_id, date_time FROM sale WHERE user_id = '$iduser' ORDER BY date_time"; ?>
            <?php $query = mysqli_query($conn, $sql); ?>
            <?php $rows = mysqli_num_rows($query); ?>
            <?php if ($rows > 0) { ?>
                <?php foreach ($query as $val) { ?>

                    <?php
                    $dateInYYYYMMDD = $val['date_time'];
                    $dateInDMY = date("d-m-Y", strtotime($dateInYYYYMMDD));
                    ?>
                    <a class="btn btn-outline-primary mx-2 my-1" href="history_detail.php?sale_id=<?=$val['sale_id']?>&date=<?= $val['date_time']?>">วันที่ <?php echo $dateInDMY ?></a>
                    <br>
                <?php } ?>
            <?php } else { ?>
                <p>คุณยังไม่ได้เคยบันทึกรายการสินค้าย้อนหลัง</p>
            <?php } ?>
            <hr>
        </section>
        <div>
            <button type="button" class="btn btn-outline-warning mx-2" onclick="document.location='../index.php'">ย้อนกลับ</button>
        </div>
    </div>
</body>

</html>