<?php 
$con = mysqli_connect('localhost' , 'root' , '' , 'product') ; 
$open_connect = 1 ;
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['save_excel'])){
    $filename = $_FILES['file_csv']['name'] ; 
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION) ; 

    $allow_ext = ['xls','csv','xlsx'] ; 

    if (in_array($file_ext, $allow_ext)) {
        $inputFileNamePath = $_FILES['file_csv']['tmp_name'];

        /** Load $inputFileName to a Spreadsheet object **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach($data as $row){
            $product_name = $row['0'] ;
            $barcode = $row['1'] ;
            $price = $row['2'] ;

            $result_data = $con->query("SELECT product_name, barcode, price FROM test_product WHERE barcode = '$barcode'");
            if ( $result_data->num_rows>0 ) {
                $data_query = ("TRUNCATE TABLE test_product");
                $result_data_query = mysqli_query($con,$data_query) ; 

            } else {
                $data_query = "INSERT INTO test_product (product_name, barcode, price ) VALUE ('$product_name' , '$barcode', '$price') ";
                $result_data_query = mysqli_query($con,$data_query) ; 

                echo '<script type="text/Javascript">
                    window.alert("อัพเดทข้อมูลเรียบร้อย") ;
                    window.location.href = "import.php" ;
                </script>';
            }
            

        }
    } else {; 
        echo '<script type="text/Javascript">
                    window.alert("ยังไม่มีการนำเข้าไฟล์") ;
                    window.location.href = "import.php" ;
            </script>';
    }

}
?>