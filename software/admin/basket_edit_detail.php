<?php

session_start();
$open_connect = 1;
require('../server_pdo.php');

if (!isset($_SESSION['tel_id']) && $_SESSION['role_user'] != 'admin') {
  die(header('Location: ./register-login/login_page.php'));
} elseif (isset($_GET['logout']) == 1 ) {
  session_destroy();
  die(header('Location: ./register-login/login_page.php'));
} 

$name = $_SESSION['tel_id'] ;
$sql_show_name = "SELECT * FROM user WHERE tel_id = '$name'";
$stmt_show_name = $conn->prepare($sql_show_name) ; 
$stmt_show_name->execute();
$result_show_name = $stmt_show_name->fetchAll(PDO::FETCH_ASSOC);

$basket_id = $_GET['basket_id'] ;
$sale_id = $_GET['sale_id'];
$sql_edit = "SELECT * FROM basket WHERE sale_id = '$sale_id' AND basket_id = '$basket_id'";
$stmt_edit = $conn->prepare($sql_edit);
$stmt_edit->execute();
$result_edit = $stmt_edit->fetch(PDO::FETCH_ASSOC);

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
                    <a href="user_management.php" class="nav-link active d-flex align-items-center" aria-current="page">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                            <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Zm9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                        </svg>
                        <span class="ms-2">จัดการผู้ใช้งาน</span>
                    </a>
                </li>
                <li class="">
                    <a href="import.php" class="nav-link text-white d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                            <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z" />
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                        </svg>
                        <span class="ms-2">นำเข้าข้อมูล</span>
                    </a>
                </li>
                <li>
                    <a href="basket_page.php" class="nav-link text-white d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                            <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z" />
                        </svg>
                        <span class="ms-2">จัดการรถเข็น</span>
                    </a>
                </li>
                <li>
                    <a href="product_page.php" class="nav-link text-white d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                            <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                            <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z" />
                        </svg>
                        <span class="ms-2">จัดการสินค้า</span>
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
        <form method="post" action="basket_edit_db.php?basket_id=<?= $result_edit['basket_id']; ?>">
            <h1>แก้ไขข้อมูลสินค้า</h1>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">ชื่อสินค้า</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required name="username" value="<?=$result_edit['product_name'];?>" disabled >
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">จำนวนสินค้า</label>
                <input type="text" class="form-control" id="exampleInputPassword1" required name="product_amount" value="<?=$result_edit['product_amount'];?>">
            </div>
            <div class="container d-flex align-items-center justify-content-between">
                <button type="button" class="btn btn-outline-warning mx-2" onclick="document.location='basket_page.php'">ย้อนกลับ</button>
                <button type="submit" class="btn btn-outline-primary mx-2" onclick="document.location='basket_edit_db.php?basket_id=<?=$result_edit['basket_id'];?>'">แก้ไขข้อมูลสินค้า</button>
            </div>
        </form>
    </div>
  
    </main>
</body>
</html>