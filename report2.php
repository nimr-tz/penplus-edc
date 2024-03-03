<?php

require 'pdf.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

if ($user->isLoggedIn()) {
    try {
        $clients = $override->get('clients', 'status', 1);
        $Total = $override->getCount('clients', 'status', 1);
        $data_enrolled = $override->getCount1('clients', 'status', 1, 'enrolled', 1);
        $successMessage = 'Report Successful Created';
    } catch (Exception $e) {
        die($e->getMessage());
    }
} else {
    Redirect::to('index.php');
}

$title = 'PENPLUS SCREENING AND ENROLLMENT LOG REPORT ' . date('Y-m-d');

$pdf = new Pdf();

// $title = 'NIMREGENIN SUMMARY REPORT_'. date('Y-m-d');
$file_name = $title . '.pdf';

$output = ' ';


    $output .= '
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
    
                <tr>
                    <td colspan="9" align="center" style="font-size: 18px">
                        <b>DATE  ' . date('Y-m-d') . '</b>
                    </td>
                </tr>


                <tr>
                    <td colspan="9" align="center" style="font-size: 18px">
                        <b>TABLE 2 </b>
                    </td>
                </tr>

                <tr>
                    <td colspan="9" align="center" style="font-size: 18px">
                        <b>' . $title . '</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="9" align="center" style="font-size: 18px">
                        <b>Total Registered ( ' . $Total . ' ):  Total Enrolled( ' . $data_enrolled . ' )</b>
                    </td>
                </tr>
    
                <tr>
                    <th colspan="1">No.</th>
                    <th colspan="2">Date</th>
                    <th colspan="2">Study ID</th>
                    <th colspan="2">ELIGIBILTY</th>        
                    <th colspan="2">Reason</th>
                </tr>
    
     ';

    // Load HTML content into dompdf
    $x = 1;
    foreach ($clients as $client) {
        if($client['eligible'] == 1){
            $eligible = 'ELIGIBLE';
        }else{
             $eligible = 'NOT ELIGIBLE';
        }

        
        $screening = $override->get('screening', 'patient_id', $client['id'])[0];


        if ($screening['consent'] == 1) {
            $consent = 'Consented';
        } else {
            $consent = 'Not Consented';
        }

    

        $output .= '
         <tr>
            <td colspan="1">' . $x . '</td>
            <td colspan="2">' . $client['clinic_date'] . '</td>
            <td colspan="2">' . $client['study_id'] .'</td>
            <td colspan="2">' . $eligible . '</td>
            <td colspan="2">' . $consent . '</td>
        </tr>
        ';

        $x += 1;
    }

    $output .= '
    <tr>
        <td colspan="5" align="center" style="font-size: 18px">
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <p align="right">----' . $user->data()->firstname . ' ' . $user->data()->lastname . '-----<br />Printed By</p>
            <br />
            <br />
            <br />
        </td>

        <td colspan="4" align="center" style="font-size: 18px">
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <p align="right">-----' . date('Y-m-d') . '-------<br />Date Printed</p>
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
