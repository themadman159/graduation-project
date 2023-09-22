<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>

    <link rel="icon" href="img/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/signin.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500&display=swap" rel="stylesheet"> 
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>
<body class="text-center">
    
<main class="form-signin">
  <form method="post" action="register_db.php">
    <img class="mb-4" src="../img/logo.png" alt="" width="72" height="72">
    <h1 class="h3 mb-3 fw-normal">สมัครสมาชิก</h1>

    <div class="form-floating">
      <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="username" required>
      <label for="floatingInput">กรอกชื่อผู้ใช้</label>
    </div>
    <div class="form-floating">
      <input type="text" class="form-control" id="floatingPassword" placeholder="Password" name="tel" required>
      <label for="floatingPassword">กรอกเบอร์โทรศัพท์</label>
    </div>
    <label for="">มีสมาชิกอยู่แล้ว ? <a href="login_page.php">เข้าสู่ระบบ</a></label>
    <button class="w-100 btn btn-lg btn-primary" type="submit" name ="submitfrom">ยืนยัน</button>

    <p class="mt-5 mb-3 text-muted">© 2023</p>
  </form>
</main>
</body>
</html>