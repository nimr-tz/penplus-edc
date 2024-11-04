<?php

require 'pdf.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

if ($user->isLoggedIn()) {
    try {
        // switch (Input::get('report')) {
        //     case 1:
        //         $data = $override->searchBtnDate3('batch', 'create_on', $_GET['start'], 'create_on', $_GET['end'], 'use_group', $_GET['group']);
        //         $data_count = $override->getCountReport('batch', 'create_on', $_GET['start'], 'create_on', $_GET['end'], 'use_group', $_GET['group']);
        //         break;
        //     case 2:
        //         $data = $override->searchBtnDate3('check_records', 'create_on', $_GET['start'], 'create_on', $_GET['end'], 'use_group', $_GET['group']);
        //         $data_count = $override->getCountReport('check_records', 'create_on', $_GET['start'], 'create_on', $_GET['end'], 'use_group', $_GET['group']);
        //         break;
        //     case 3:
        //         $data = $override->searchBtnDate3('batch_records', 'create_on', $_GET['start'], 'create_on', $_GET['end'], 'use_group', $_GET['group']);
        //         $data_count = $override->getCountReport('batch_records', 'create_on', $_GET['start'], 'create_on', $_GET['end'], 'use_group', $_GET['group']);
        //         break;
        // }

        $site_data = $override->getData('site');
        $Total = $override->getCount('clients', 'status', 1);
        $data_enrolled = $override->getCount1('clients', 'status', 1, 'enrolled', 1);
        // $name = $override->get('user', 'status', 1, 'screened', $user->data()->id);
        // $data_count = $override->getCount2('clients', 'status', 1, 'screened',1, 'site_id', $ussite_dataer->data()->site_id);

        $successMessage = 'Report Successful Created';
    } catch (Exception $e) {
        die($e->getMessage());
    }
} else {
    Redirect::to('index.php');
}

// if ($_GET['group'] == 1) {
//     $title = 'Medicines';
// } elseif ($_GET['group'] == 2) {
//     $title = 'Medical Equipments';
// } elseif ($_GET['group'] == 3) {
//     $title = 'Accessories';
// } elseif ($_GET['group'] == 4) {
//     $title = 'Supplies';
// }



$title = 'PENPLUS Cardiomyopathy REPORT_' . date('Y-m-d');

$pdf = new Pdf();

// $title = 'NIMREGENIN SUMMARY REPORT_'. date('Y-m-d');
$file_name = $title . '.pdf';

$output = ' ';

// if ($_GET['group'] == 2) {
if ($site_data) {

    $output .= '
            <table width="100%" border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <td colspan="12" align="center" style="font-size: 18px">
                        <b>DATE  ' . date('Y-m-d') . '</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="12" align="center" style="font-size: 18px">
                        <b>' . $title . '</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="12" align="center" style="font-size: 18px">
                        <b> Total Enrolled( ' . $data_enrolled . ' )</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="12">                        
                        <br />
                        <table width="100%" border="1" cellpadding="5" cellspacing="0">
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">SITE</th>
                                <th rowspan="2">ENROLLED</th>
                                <th colspan="8"> Cardiomyopathy </th>
                            </tr>
                            <tr>
                                <th>Ischemic</th>
                                <th>Alcohol-related</th>
                                <th>Peripartum</th>
                                <th>Arrhythmia-related</th>
                                <th>HIV-related</th>
                                <th>Chemotherapy-related </th>
                                <th>Viral</th>
                                <th>Other</th>
                            </tr>
            ';

    // Load HTML content into dompdf
    $x = 1;
    foreach ($site_data as $row) {
        $enrolled = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $row['id']);
        $enrolled_Total = $override->countData('clients', 'status', 1, 'enrolled', 1);
        $cardiac1 = $override->countData2('cardiac', 'status', 1, 'sub_cardiomyopathy', 1, 'site_id', $row['id']);
        $cardiac_Total1 = $override->countData('cardiac', 'status', 1, 'sub_cardiomyopathy', 1);
        $cardiac2 = $override->countData2('cardiac', 'status', 1, 'sub_cardiomyopathy', 2, 'site_id', $row['id']);
        $cardiac_Total2 = $override->countData('cardiac', 'status', 1, 'sub_cardiomyopathy', 2);
        $cardiac3 = $override->countData2('cardiac', 'status', 1, 'sub_cardiomyopathy', 3, 'site_id', $row['id']);
        $cardiac_Total3 = $override->countData('cardiac', 'status', 1, 'sub_cardiomyopathy', 3);
        $cardiac4 = $override->countData2('cardiac', 'status', 1, 'sub_cardiomyopathy', 4, 'site_id', $row['id']);
        $cardiac_Total4 = $override->countData('cardiac', 'status', 1, 'sub_cardiomyopathy', 4);
        $cardiac5 = $override->countData2('cardiac', 'status', 1, 'sub_cardiomyopathy', 5, 'site_id', $row['id']);
        $cardiac_Total5 = $override->countData('cardiac', 'status', 1, 'sub_cardiomyopathy', 5);
        $cardiac6 = $override->countData2('cardiac', 'status', 1, 'sub_cardiomyopathy', 6, 'site_id', $row['id']);
        $cardiac_Total6 = $override->countData('cardiac', 'status', 1, 'sub_cardiomyopathy', 6);
        $cardiac7 = $override->countData2('cardiac', 'status', 1, 'sub_cardiomyopathy', 7, 'site_id', $row['id']);
        $cardiac_Total7 = $override->countData('cardiac', 'status', 1, 'sub_cardiomyopathy', 7);
        $cardiac8 = $override->countData2('cardiac', 'status', 1, 'sub_cardiomyopathy', 96, 'site_id', $row['id']);
        $cardiac_Total8 = $override->countData('cardiac', 'status', 1, 'sub_cardiomyopathy', 96);
        // $diabetes_Total = $override->countData('cardiac', 'status', 1, 'diabetes', 1);
        // $end_study = $override->countData2('cardiac', 'status', 1, 'end_study', 1, 'site_id', $row['id']);
        // $end_study_Total = $override->countData('cardiac', 'status', 1, 'end_study', 1);

        $output .= '
                <tr>
                    <td>' . $x . '</td>
                    <td>' . $row['name']  . '</td>
                    <td align="right">' . $enrolled . '</td>
                    <td align="right">' . $cardiac1 . '</td>
                    <td align="right">' . $cardiac2 . '</td>
                    <td align="right">' . $cardiac3 . '</td>
                    <td align="right">' . $cardiac4 . '</td>
                    <td align="right">' . $cardiac5 . '</td>
                    <td align="right">' . $cardiac6 . '</td>
                    <td align="right">' . $cardiac7 . '</td>
                    <td align="right">' . $cardiac8 . '</td>
                </tr>
            ';

        $x += 1;
    }

    $output .= '
                <tr>
                    <td align="right" colspan="2"><b>Total</b></td>
                    <td align="right"><b>' . $enrolled_Total . '</b></td>
                    <td align="right"><b>' . $cardiac_Total1 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total2 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total3 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total4 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total5 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total6 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total7 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total8 . '</b></td>
                </tr>  

    ';
}

// $output = '<html><body><h1>Hello, dompdf!' . $row . '</h1></body></html>';
$pdf->loadHtml($output);

// SetPaper the HTML as PDF
// $pdf->setPaper('A4', 'portrait');
$pdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$pdf->render();

// Output the generated PDF
$pdf->stream($file_name, array("Attachment" => false));
