<?php

session_start();
$open_connect = 1;
require('../server_pdo.php');
require('../server.php') ;

if (!isset($_SESSION['tel_id']) || $_SESSION['role_user'] != 'admin') {
    die(header('Location: login_page.php'));
} elseif (isset($_GET['logout'])) {
    session_destroy();
    die(header('Location: login_page.php'));
} else {
    $tel_id = $_SESSION['tel_id'] ; 
    $query_show = "SELECT * FROM user WHERE tel_id = '$tel_id'" ; 
    $call_back_show = mysqli_query($conn,$query_show) ; 
    $result_show = mysqli_fetch_assoc($call_back_show) ; 
}

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
        <a href="#" class="nav-link active" aria-current="page">
            จัดการผู้ใช้งาน
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          นำเข้าข้อมูล
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          จัดการรถเข็น
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          จัดการสินค้า
        </a>
      </li>
    </ul>
    <hr>
    <div class="">
        <p class="font-weight-bold">แอดมิน : <?php echo $result_show['username_id'];?>
        <button type="button" class="btn btn-outline-danger" onclick="document.location='../register-login/login_page.php?logout=1'">ออกจากระบบ</button>
        </p>
    </div>
  </div>
  <div class="container d-flex align-items-center justify-content-center vh-100">
        <form method="post" action="user_add_db.php">
            <h1>เพิ่มข้อมูลผู้ใช้งาน</h1>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required name="username">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">เบอร์โทร</label>
                <input type="text" class="form-control" id="exampleInputPassword1" required name="tel">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">สถานะผู้ใช้</label>
                <select class="form-select" aria-label="Default select example" name = "role">
                    <option value="member" selected name="member">Member</option>
                    <option value="admin" name="admin">Admin</option>
                </select>
            </div>
            <div class="container d-flex align-items-center justify-content-between">
                <button type="button" class="btn btn-outline-warning mx-2" onclick="document.location='user_management.php'">ย้อนกลับ</button>
                <button type="submit" class="btn btn-outline-primary mx-2" onclick="document.location='user_add.php'">เพิ่มผู้ใช้งาน</button>
            </div>
        </form>
    </div>
  
    </main>
</body>
</html>