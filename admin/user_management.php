<?php

session_start();
$open_connect = 1;
require('../server_pdo.php');

if (!isset($_SESSION['tel_id']) || $_SESSION['role_user'] != 'admin') {
    die(header('Location: login_page.php'));
} elseif (isset($_GET['logout'])) {
    session_destroy();
    die(header('Location: login_page.php'));
}

$name = $_SESSION['tel_id'];

$sql = "SELECT * FROM user";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_show_name = "SELECT * FROM user WHERE tel_id = '$name'";
$stmt_show_name = $conn->prepare($sql_show_name);
$stmt_show_name->execute();
$result_show_name = $stmt_show_name->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลผู้ใช้งาน</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="navbar-top.css" rel="stylesheet">

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
    <main class="d-flex">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px; ">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            </a>
            <h2>จัดการข้อมูล</h2>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="user_management.php" class="nav-link active" aria-current="page">
                        จัดการผู้ใช้งาน
                    </a>
                </li>
                <li>
                    <a href="import.php" class="nav-link text-white">
                        นำเข้าข้อมูล
                    </a>
                </li>
                <li>
                    <a href="basket_page.php" class="nav-link text-white">
                        จัดการรถเข็น
                    </a>
                </li>
                <li>
                    <a href="product_page.php" class="nav-link text-white">
                        จัดการสินค้า
                    </a>
                </li>
            </ul>
            <hr>
            <div class="container align-items-center justify-content-center">
                <?php foreach ($result_show_name as $user_name) { ?>
                    <p class="font-weight-bold">แอดมิน : <?= $user_name['tel_id']; ?>

                    <?php } ?>
                    <button type="button" class="btn btn-outline-danger" onclick="document.location='../register-login/login_page.php?logout=1'">ออกจากระบบ</button>
                    </p>
            </div>
        </div>
        <div class="container mt-3 vh-100">
            <h1>จัดการข้อมูลผู้ใช้งาน <button type="button" class="btn btn-outline-primary" onclick="document.location='user_add.php'">เพิ่มผู้ใช้งาน</button></h1>


            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ชื่อผู้ใช้</th>
                        <th scope="col">รหัสผ่าน</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col">เครื่องมือ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $user) { ?>
                        <tr>
                            <td><?= $user['username_id']; ?></td>
                            <td><?= $user['tel_id']; ?></td>
                            <td><?= $user['role_user']; ?></td>
                            <td>
                                <button type="button" class="btn btn-outline-warning" onclick="document.location='user_edit.php?iduser=<?= $user['tel_id']; ?>'">แก้ไขผู้ใช้งาน</button>
                                <button type="button" class="btn btn-outline-danger" onclick="document.location='user_delete.php?iduser=<?= $user['tel_id']; ?>'">ลบผู้ใช้งาน</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>