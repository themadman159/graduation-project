<?php
session_start();
$open_connect = 1;
require('../server_pdo.php');

if (!isset($_SESSION['tel_id']) && $_SESSION['role_user'] != 'admin') {
    die(header('Location: ./register-login/login_page.php'));
} elseif (isset($_GET['logout']) == 1) {
    session_destroy();
    die(header('Location: ./register-login/login_page.php'));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR-CODE Generator</title>
    <link rel="icon" href="../img/logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/javascript">
        function generateBarCode() {
            var nric = $('https://hang-chat-market.000webhostapp.com/').val();
            var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + nric + '&amp;size=50x50';
            $('#barcode').attr('src', url);
        }
    </script>

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
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="text-center">
            <div id="download_qr">
                <img id="barcode" src="https://api.qrserver.com/v1/create-qr-code/?data=https://hang-chat-market.000webhostapp.com/&amp;size=100x100" alt="QR Code" title="HELLO" width="150" height="150" onblur='generateBarCode();' />
            </div>
            <h1>QR CODE สำหรับติดรถเข็น</h1>

            <button type="button" class="btn btn-primary " id="qr_code">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"></path>
                </svg>
                ดาวน์โหลด QR-CODE
            </button>


        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../dom-to-image.js"></script>
    <script>
        var receipt_bill = document.getElementById("download_qr");
        var btn_download = document.getElementById("qr_code");

        btn_download.addEventListener("click", () => {
            domtoimage.toJpeg(receipt_bill).then((data) => {
                var link = document.createElement("a");
                link.download = "basket_qr_code.jpeg";
                link.href = data;
                link.click();
            });
        });
    </script>
</body>

</html>