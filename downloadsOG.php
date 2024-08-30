<?php

require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();


if ($user->isLoggedIn()) {
} else {
    Redirect::to('index.php');
}


$servername = "localhost";
$username = "root";
$password = "Data@2020";
$dbname = "penplus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table = $_GET['table'];
$ext = $_GET['ext'];

$file = $table;
$ext = $ext;

$sql = "SELECT * FROM $table";
$result = $conn->query($sql);

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the column headers
$columns = array();
$columnIndex = 'A';

if ($result->num_rows > 0) {
    while ($fieldinfo = $result->fetch_field()) {
        $sheet->setCellValue($columnIndex . '1', $fieldinfo->name);
        $columns[$columnIndex] = $fieldinfo->name;
        $columnIndex++;
    }

    // Fill data
    $rowNumber = 2; // Start on the second row after headers
    while ($row = $result->fetch_assoc()) {
        $columnIndex = 'A';
        foreach ($columns as $column) {
            $sheet->setCellValue($columnIndex . $rowNumber, $row[$column]);
            $columnIndex++;
        }
        $rowNumber++;
    }
}

$conn->close();


// $writer = null;
$filename = $file . '.' . $ext;

switch ($ext) {
    case 'xlsx':
        $writer = new Xlsx($spreadsheet); // Use the correct namespace
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        break;
    case 'xls':
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet); // For XLS files
        header('Content-Type: application/vnd.ms-excel');
        break;
    case 'csv':
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet); // For CSV files
        header('Content-Type: text/csv');
        break;
    default:
        throw new Exception('Unsupported file format');
}


// Set the download headers
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
