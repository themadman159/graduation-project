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

$barcode_product = $_GET['barcode_product'];
$sql_edit = "SELECT * FROM main_product WHERE product_id = '$barcode_product'";
$stmt_edit = $conn->prepare($sql_edit);
$stmt_edit->execute();
$result_edit = $stmt_edit->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า</title>
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
                    <a href="user_management.php" class="nav-link text-white" aria-current="page">
                        จัดการผู้ใช้งาน
                    </a>
                </li>
                <li>
                    <a href="import.php" class="nav-link text-white">
                        นำเข้าข้อมูล
                    </a>
                </li>
                <li>
                    <a href="basket_page.php" class="nav-link text-white    ">
                        จัดการรถเข็น
                    </a>
                </li>
                <li>
                    <a href="product_page.php" class="nav-link active">
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
            <?php foreach ($result_edit as $product) { ?>
                <form method="post" action="product_edit_db.php?product_id=<?= $product['product_id']; ?>">
                    <h1>แก้ไขสินค้า</h1>

                    <h2>
                        <?= $product['product_name']; ?>
                    </h2>
                <?php } ?>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">เลขบาร์โค้ด</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required name="barcode" value="<?= $product['barcode']; ?>" maxlength="13">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">ชื่อสินค้า</label>
                    <input type="text" class="form-control" id="exampleInputPassword1" required name="productName" value="<?= $product['product_name']; ?>">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">ราคาสินค้า</label>
                    <input type="text" class="form-control" id="exampleInputPassword1" required name="productPrice" value="<?= $product['price']; ?>">
                </div>
                <div class="container d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-outline-warning mx-2" onclick="document.location='product_page.php'">ย้อนกลับ</button>
                    <button type="submit" class="btn btn-outline-primary mx-2" onclick="document.location='product_edit_db.php?product_id=<?= $product['product_id']; ?>'">แก้ไขสินค้า</button>
                </div>
                </form>         
        </div>
    </main>
</body>

</html>