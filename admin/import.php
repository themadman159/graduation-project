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
  <title>นำเข้าข้อมูล</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link href="navbar-top.css" rel="stylesheet">

  <link rel="icon" href="../  img/logo.png">
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
          <a href="user_management.php" class="nav-link text-white" aria-current="page">
            จัดการผู้ใช้งาน
          </a>
        </li>
        <li>
          <a href="import.php" class="nav-link active">
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
    <div class="container d-flex align-items-center justify-content-center vh-100">
      <form class="row w-50 border d-flex" action="import_db.php" method="POST" enctype="multipart/form-data">
        <div class="">
          <h2 class="my-2">นำเข้าข้อมูล</h2>
          <label for="formFile" class="form-label">นำเข้าข้อมูลสินค้ากรุณานำเข้าด้วยไฟล์ .xls, .csv, .xlsx</label>
          <br>
          <label for="formFile" class="form-label bg-danger px-2 text-white ">คำแนะนำ</label>
          <label for="formFile" class="form-label">กรุณานำเข้าไฟล์ที่มีขนาดไม่เกิน 8 KB.</label>
          <input class="form-control my-3" type="file" name="file_csv">
          <button type="submit" name="save_excel" class="btn btn-outline-primary my-3 d-flex align-items-center justify-content-center">นำเข้าข้อมูล</button>
        </div>
        <div>
          <h2 class="my-2">คำแนะนำในการอัพโหลดไฟล์</h2>
          <div class="text-center">
            <img src="../img/import_ex.png" alt="">
          </div>
      </form>

    </div>
  </main>

</body>

</html>