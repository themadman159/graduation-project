<?php
$con = mysqli_connect('localhost', 'root', '', 'product');
$open_connect = 1;
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['save_excel'])) {
    $filename = $_FILES['file_csv']['name'];
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

    $allow_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($file_ext, $allow_ext)) {
        $inputFileNamePath = $_FILES['file_csv']['tmp_name'];

        // Start a database transaction
        mysqli_begin_transaction($con);

        try {
            $spreadsheet = IOFactory::load($inputFileNamePath);
            $data = $spreadsheet->getActiveSheet()->toArray();

            foreach ($data as $row) {
                $product_name = $con->real_escape_string($row[0]);
                $barcode = $con->real_escape_string($row[1]);
                $price = $con->real_escape_string($row[2]);

                $result_data = $con->query("SELECT product_name, barcode, price FROM main_product WHERE barcode = '$barcode'");
                if ($result_data->num_rows > 0) {
                    // Data already exists, truncate the table
                    $data_query = "TRUNCATE TABLE main_product";
                    mysqli_query($con, $data_query);
                } else {
                    // Insert the new data
                    $data_query = "INSERT INTO main_product (product_name, barcode, price) VALUES ('$product_name', '$barcode', '$price')";
                    mysqli_query($con, $data_query);
                }
            }

            // Commit the transaction
            mysqli_commit($con);

            echo '<script type="text/javascript">
                    window.alert("อัพเดทข้อมูลเรียบร้อย");
                    window.location.href = "import.php";
                  </script>';
        } catch (Exception $e) {
            // Rollback the transaction on error
            mysqli_rollback($con);

            echo '<script type="text/javascript">
                    window.alert("เกิดข้อผิดพลาดในการนำเข้าข้อมูล");
                    window.location.href = "import.php";
                  </script>';
        }
    } else {
        echo '<script type="text/javascript">
                window.alert("ยังไม่มีการนำเข้าไฟล์");
                window.location.href = "import.php";
              </script>';
    }
}
?>
