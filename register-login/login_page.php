<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เข้าสู่ระบบ</title>

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
    <form method="post" action="login_db.php">
      <img class="mb-4" src="../img/logo.png" alt="" width="72" height="72">
      <h1 class="h3 mb-3 fw-normal">เข้าสู่ระบบ</h1>

      <div class="form-floating">
        <input type="text" class="form-control" id="floatingPassword" placeholder="Password" name="tel" required maxlength="10">
        <label for="floatingPassword">กรอกเบอร์โทรศัพท์</label>
      </div>
      <label for="">ยังไม่มีสมาชิก ? <a href="register_page.php">สมัครสมาชิก</a></label>
      <div class="d-flex align-items-center">
        <button class="w-100 btn btn-lg btn-primary" type="submit" name="submitfrom"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z" />
            <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
          </svg>
          <span class="ms-2">เข้าสู่ระบบ</span>
        </button>
      </div>


      <p class="mt-5 mb-3 text-muted">© 2023</p>
    </form>
  </main>





</body>

</html>