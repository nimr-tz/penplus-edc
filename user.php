<?php

require 'pdf.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

if ($user->isLoggedIn()) {
    try {
        $clients = $override->getData('user');
        $Total = $override->getNo('user');
        $Total2 = $override->getCount('user', 'status', 0);
        $successMessage = 'Report Successful Created';
    } catch (Exception $e) {
        die($e->getMessage());
    }
} else {
    Redirect::to('index.php');
}

$span0 = 13;
$span1 = 7;
$span2 = 6;

$title = 'PENPLUS STUDY ' . date('Y-m-d');

$pdf = new Pdf();

// $title = 'NIMREGENIN SUMMARY REPORT_'. date('Y-m-d');
$file_name = $title . '.pdf';

$output = ' ';


$output .= '
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
                <td colspan="' . $span0 . '" align="center" style="font-size: 18px">
                        <b>' . $title . '</b>
                    </td>
                </tr>
                                <tr>
                    <td colspan="' . $span0 . '" align="center" style="font-size: 18px">
                            <b>LIST OF USERS </b>
                        </td>
                    </tr>
                <tr>
                <tr>
                <td colspan="' . $span0 . '" align="center" style="font-size: 18px">
                        <b>Total Users ( ' . $Total . ' ):  Disabled( ' . $Total2 . ' )</b>
                    </td>
                </tr>
    
                <tr>
                    <th colspan="1">No.</th>
                    <th colspan="6"> Name</th>
                    <th colspan="2"> Date Joined</th>
                    <th colspan="2"> Status </th>
                    <th colspan="2"> Date Disabled</th>
                </tr>
    
     ';

// Load HTML content into dompdf
$x = 1;
foreach ($clients as $client) {
    if ($client['status'] == 1) {
        $stautus = 'Active';
        $last_login = '';
    } else {
        $stautus = 'Not Active( Disabled )';
        $last_login = $client['last_login'];
    }

    $output .= '
         <tr>
            <td colspan="1">' . $x . '</td>
            <td colspan="6">' . $client['firstname'] .' - '. $client['lastname'] . '</td>
            <td colspan="2">' . $client['create_on'] . '</td>
            <td colspan="2">' . $stautus . '</td>
            <td colspan="2">' . $last_login . '</td>
        </tr>
        ';

    $x += 1;
}

$output .= '
    <tr>
        <td colspan="' . $span0 . '" align="center" style="font-size: 18px">
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <p align="right">---- @NIMR - MUHIMBILI -----<br />Printed By</p>
            <br />
            <br />
            <br />
        </td>       
    </tr>
        </table>  
    ';





// $output = '<html><body><h1>Hello, dompdf!' . $row . '</h1></body></html>';
$pdf->loadHtml($output);

// SetPaper the HTML as PDF
$pdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$pdf->render();

// Output the generated PDF
$pdf->stream($file_name, array("Attachment" => false));
