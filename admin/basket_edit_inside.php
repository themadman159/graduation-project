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

$barcode_product = $_GET['barcode_product'] ;
$basket_num = $_GET['basket_code'] ;
$sql = "SELECT * FROM basket WHERE barcode = '$barcode_product' AND basket_code = '$basket_num'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$name = $_SESSION['tel_id'] ;
$sql_show_name = "SELECT * FROM user WHERE tel_id = '$name'";
$stmt_show_name = $conn->prepare($sql_show_name) ; 
$stmt_show_name->execute();
$result_show_name = $stmt_show_name->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="sidebars.css" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>

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
        <?php foreach ( $result_show_name as $user_name ) { ?>
            <p class="font-weight-bold">แอดมิน : <?= $user_name['tel_id']; ?>
        <?php } ?>
        <button type="button" class="btn btn-outline-danger" onclick="document.location='../register-login/login_page.php?logout=1'">ออกจากระบบ</button>
        </p>
    </div>
  </div>
  <div class="container d-flex align-items-center justify-content-center vh-100">
  <?php foreach ($result as $basket) { ?>
                    

        <form method="post" action="basket_edit_db.php">
            <h1>แก้ไขสินค้าในรถเข็น</h1>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">ชื่อสินค้า</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required name="username" disabled value="<?=$basket['product_name'];?>">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">จำนวน</label>
                <input type="text" class="form-control" id="exampleInputPassword1" required name="tel" value="<?=$basket['price'];?>">
            </div>
            <?php }?>
            <div class="container d-flex align-items-center justify-content-between">
                <button type="button" class="btn btn-outline-warning mx-2" onclick="document.location='basket_edit.php?basket_code=<?= $basket['basket_code']; ?>'">ย้อนกลับ</button>
                <button type="submit" class="btn btn-outline-primary mx-2" onclick="document.location='basket_edit_db.php?basket_code=<?= $basket['basket_code']; ?>'">แก้ไขสินค้า</button>
            </div>
        </form>
    </div>
  
    </main>
</body>
</html>