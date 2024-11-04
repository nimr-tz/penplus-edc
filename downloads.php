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

    $table = $_GET['table'];
    $ext = $_GET['ext'];

    $file = $table;
    $ext = $ext;

$result = $override->get($table,'status',1);


    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the column headers
    $columns = array();
    $columnIndex = 'A';

    if (count($result) > 0) {
        // Fetch the field names from the first row
        $fieldinfo = array_keys($result[0]);
        foreach ($fieldinfo as $fieldname) {
            $sheet->setCellValue($columnIndex . '1', $fieldname);
            $columns[$columnIndex] = $fieldname;
            $columnIndex++;
        }

        // Fill data
        $rowNumber = 2; // Start on the second row after headers
        foreach ($result as $row) {
            $columnIndex = 'A';
            foreach ($columns as $column) {
                $sheet->setCellValue($columnIndex . $rowNumber, $row[$column]);
                $columnIndex++;
            }
            $rowNumber++;
        }
    }

    // Set the appropriate writer based on the file extension
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
