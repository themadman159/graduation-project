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

// Modify your SQL query to include LIMIT and OFFSET
$sql = "SELECT * FROM main_product";

// Define the number of items per page
$itemsPerPage = 10;

// Get the current page number from the URL
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the OFFSET for the SQL query
$offset = ($page - 1) * $itemsPerPage;

// Ensure OFFSET is non-negative
if ($offset < 0) {
    $offset = 0;
}

// Add LIMIT and OFFSET to the SQL query
$sql .= " ORDER BY barcode ASC LIMIT $itemsPerPage OFFSET $offset";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count the total number of records
$totalRecords = $conn->query("SELECT COUNT(*) FROM main_product")->fetchColumn();

// Calculate the total number of pages
$totalPages = ceil($totalRecords / $itemsPerPage);

// Calculate previous and next page numbers
$prevPage = ($page > 1) ? $page - 1 : $totalPages; // Wrap to the last page if on the first page
$nextPage = ($page < $totalPages) ? $page + 1 : 1; // Wrap to the first page if on the last page

// Define the range of page links to display around the current page
$paginationRange = 5;

// Initialize $search_text to an empty string
$search_text = '';

if (isset($_POST['search']) && $_POST['search'] != '') {
    $search_text = $_POST['search'];
    $_SESSION['search_text'] = $search_text; // Store the search text in a session variable
} elseif (isset($_POST['search']) && $_POST['search'] === '') {
    // If a search query is empty, reset the session search text
    unset($_SESSION['search_text']);
} elseif (isset($_SESSION['search_text'])) {
    $search_text = $_SESSION['search_text']; // Retrieve the search text from the session
}

// Define $sql after adding conditions
$sql = "SELECT * FROM main_product WHERE barcode LIKE '%$search_text%' OR product_name LIKE '%$search_text%'";

// Your existing code continues here...

// Count the total number of records (taking into account the search condition)
$totalRecords = $conn->query("SELECT COUNT(*) FROM main_product WHERE barcode LIKE '%$search_text%' OR product_name LIKE '%$search_text%'")->fetchColumn();

// Calculate the total number of pages
$totalPages = ceil($totalRecords / $itemsPerPage);

// Calculate previous and next page numbers
$prevPage = ($page > 1) ? $page - 1 : $totalPages; // Wrap to the last page if on the first page
$nextPage = ($page < $totalPages) ? $page + 1 : 1; // Wrap to the first page if on the last page

// Define the range of page links to display around the current page
$paginationRange = 5;

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
    <title>จัดการสินค้า</title>
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
                    <a href="basket_page.php" class="nav-link text-white">
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
        <div class="container mt-3 vh-100">
            <div class="d-flex justify-content-between">
                <h1>จัดการสินค้า</h1>
                <form class="d-flex" method="post" action="product_page.php">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="<?php echo $search_text ?>">
                    <button class="btn btn-outline-primary" type="submit" name="submit">Search</button>
                </form>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">บาร์โค้ด</th>
                        <th>ชื่อสินค้า</th>
                        <th>ราคาสินค้า</th>
                        <th>เครื่องมือ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($result) > 0) { ?>
                        <?php foreach ($result as $product) { ?>
                            <tr>
                                <td><?= $product['barcode']; ?></td>
                                <td><?= $product['product_name']; ?></td>
                                <td><?= $product['price']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-outline-warning" onclick="document.location='product_edit.php?barcode_product=<?= $product['product_id']; ?>'">แก้ไขสินค้า</button>
                                    <button type="button" class="btn btn-outline-danger" onclick="document.location='product_delete.php?product_barcode=<?= $product['product_id']; ?>'">ลบสินค้า</button>
                                </td>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <td colspan="4" class="text-center">ไม่มีสินค้านี้อยู่ในระบบ</td>

                    <?php } ?>
                </tbody>
            </table>
            <div class="container">
                <div class="text-center">
                    <ul class="pagination justify-content-center">
                        <?php
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='product_page.php?page=" . ($page - 1) . "'>Previous</a></li>";
                        }

                        for ($i = 1; $i <= $totalPages; $i++) {
                            if (
                                $i == 1 ||               // Always show the first page
                                $i == $totalPages ||     // Always show the last page
                                abs($i - $page) <= $paginationRange  // Show pages within the defined range
                            ) {
                                echo "<li class='page-item";
                                if ($i === $page) {
                                    echo " active";
                                }
                                echo "'><a class='page-link' href='product_page.php?page=$i'>$i</a></li>";
                            } elseif (
                                ($i == 2 && abs($i - $page) > $paginationRange) ||  // Collapse pages after the first page
                                ($i == $totalPages - 1 && abs($i - $page) > $paginationRange)  // Collapse pages before the last page
                            ) {
                                echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                            }
                        }

                        if ($page < $totalPages) {
                            echo "<li class='page-item'><a class='page-link' href='product_page.php?page=" . ($page + 1) . "'>Next</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>

        </div>
    </main>
</body>

</html>