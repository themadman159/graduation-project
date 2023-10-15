<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>สมัครสมาชิก</title>

  <link rel="icon" href="../img/logo.png">
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
        <input type="text" class="form-control" id="floatingPassword" placeholder="Password" name="tel" required maxlength="10">
        <label for="floatingPassword">กรอกเบอร์โทรศัพท์</label>
      </div>
      <label for="">มีสมาชิกอยู่แล้ว ? <a href="login_page.php">เข้าสู่ระบบ</a></label>
      <div class="d-flex align-items-center">
        <button class="w-100 btn btn-lg btn-primary" type="submit" name="submitfrom"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-add" viewBox="0 0 16 16">
            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            <path d="M2 13c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z" />
          </svg>
          <span class="ms-2">ยืนยัน</span>
        </button>
      </div>


      <p class="mt-5 mb-3 text-muted">© 2023</p>
    </form>
  </main>
</body>

</html>