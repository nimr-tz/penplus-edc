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



$title = 'PENPLUS DIABTES REPORT_' . date('Y-m-d');

$pdf = new Pdf();

// $title = 'NIMREGENIN SUMMARY REPORT_'. date('Y-m-d');
$file_name = $title . '.pdf';

$output = ' ';

// if ($_GET['group'] == 2) {
if ($site_data) {

    $output .= '
            <table width="100%" border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <td colspan="28" align="center" style="font-size: 18px">
                        <b>DATE  ' . date('Y-m-d') . '</b>
                    </td>
                </tr>


                <tr>
                    <td colspan="28" align="center" style="font-size: 18px">
                        <b>TABLE 4 </b>
                    </td>
                </tr>

                <tr>
                    <td colspan="28" align="center" style="font-size: 18px">
                        <b>' . $title . '</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="28" align="center" style="font-size: 18px">
                        <b> Total Enrolled( ' . $data_enrolled . ' )</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="28">                        
                        <br />
                        <table width="100%" border="1" cellpadding="5" cellspacing="0">
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">SITE</th>
                                <th colspan="12"> Cardiac </th>
                            </tr>
                            <tr>
                                <th>Cardio myopathy</th>
                                <th>Rheumatic Heart Disease</th>
                                <th>Severe / Uncontrolled Hypertension</th>
                                <th>Hyper tensive heart Disease</th>
                                <th>Conge nital Disease</th>
                                <th>Right heart failure </th>
                                <th>Pericar dialc Disease</th>
                                <th>Coronary Artery Disease</th>
                                <th>Arrhy thmia</th>
                                <th>Thrombo embolic</th>
                                <th>Stroke </th>
                                <th>Other </th>
                            </tr>
            ';

    // Load HTML content into dompdf
    $x = 1;
    foreach ($site_data as $row) {
        $enrolled = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $row['id']);
        $enrolled_Total = $override->countData('clients', 'status', 1, 'enrolled', 1);
        $cardiac1 = $override->countData2('cardiac', 'status', 1, 'cardiomyopathy', 1, 'site_id', $row['id']);
        $cardiac_Total1 = $override->countData('cardiac', 'status', 1, 'cardiomyopathy', 1);
        $cardiac2 = $override->countData2('cardiac', 'status', 1, 'heumatic', 1, 'site_id', $row['id']);
        $cardiac_Total2 = $override->countData('cardiac', 'status', 1, 'heumatic', 1);
        $cardiac3 = $override->countData2('cardiac', 'status', 1, 'severe_hypertension', 1, 'site_id', $row['id']);
        $cardiac_Total3 = $override->countData('cardiac', 'status', 1, 'severe_hypertension', 1);
        $cardiac4 = $override->countData2('cardiac', 'status', 1, 'hypertensive_heart', 1, 'site_id', $row['id']);
        $cardiac_Total4 = $override->countData('cardiac', 'status', 1, 'hypertensive_heart', 1);
        $cardiac5 = $override->countData2('cardiac', 'status', 1, 'congenital', 1, 'site_id', $row['id']);
        $cardiac_Total5 = $override->countData('cardiac', 'status', 1, 'congenital', 1);
        $cardiac6 = $override->countData2('cardiac', 'status', 1, 'heart_failure', 1, 'site_id', $row['id']);
        $cardiac_Total6 = $override->countData('cardiac', 'status', 1, 'heart_failure', 1);
        $cardiac7 = $override->countData2('cardiac', 'status', 1, 'pericardial', 1, 'site_id', $row['id']);
        $cardiac_Total7 = $override->countData('cardiac', 'status', 1, 'pericardial', 1);
        $cardiac8 = $override->countData2('cardiac', 'status', 1, 'coronary_artery', 1, 'site_id', $row['id']);
        $cardiac_Total8 = $override->countData('cardiac', 'status', 1, 'coronary_artery', 1);
        $cardiac9 = $override->countData2('cardiac', 'status', 1, 'arrhythmia', 1, 'site_id', $row['id']);
        $cardiac_Total9 = $override->countData('cardiac', 'status', 1, 'arrhythmia', 1);
        $cardiac10 = $override->countData2('cardiac', 'status', 1, 'thromboembolic', 1, 'site_id', $row['id']);
        $cardiac_Total10 = $override->countData('cardiac', 'status', 1, 'thromboembolic', 1);
        $cardiac11 = $override->countData2('cardiac', 'status', 1, 'stroke', 1, 'site_id', $row['id']);
        $cardiac_Total11 = $override->countData('cardiac', 'status', 1, 'stroke', 1);
        $cardiac12 = $override->countData2('cardiac', 'status', 1, 'diagnosis_other', 1, 'site_id', $row['id']);
        $cardiac_Total12 = $override->countData('cardiac', 'status', 1, 'diagnosis_other', 1);
        // $diabetes_Total = $override->countData('cardiac', 'status', 1, 'diabetes', 1);
        // $end_study = $override->countData2('cardiac', 'status', 1, 'end_study', 1, 'site_id', $row['id']);
        // $end_study_Total = $override->countData('cardiac', 'status', 1, 'end_study', 1);

        $output .= '
                <tr>
                    <td>' . $x . '</td>
                    <td>' . $row['name']  . '</td>
                    <td align="right">' . $cardiac1 . '</td>
                    <td align="right">' . $cardiac2 . '</td>
                    <td align="right">' . $cardiac3 . '</td>
                    <td align="right">' . $cardiac4 . '</td>
                    <td align="right">' . $cardiac5 . '</td>
                    <td align="right">' . $cardiac6 . '</td>
                    <td align="right">' . $cardiac7 . '</td>
                    <td align="right">' . $cardiac8 . '</td>
                    <td align="right">' . $cardiac9 . '</td>
                    <td align="right">' . $cardiac10 . '</td>
                    <td align="right">' . $cardiac11 . '</td>
                    <td align="right">' . $cardiac12 . '</td>
                </tr>
            ';

        $x += 1;
    }

    $output .= '
                <tr>
                    <td align="right" colspan="2"><b>Total</b></td>
                    <td align="right"><b>' . $cardiac_Total1 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total2 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total3 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total4 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total5 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total6 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total7 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total8 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total9 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total10 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total11 . '</b></td>
                    <td align="right"><b>' . $cardiac_Total12 . '</b></td>

                </tr>  

    ';

    $output .= '
            </table>    
                <tr>
                    <td colspan="14" align="center" style="font-size: 18px">
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <p align="right">----' . $user->data()->firstname . ' ' . $user->data()->lastname . '-----<br />Prepared By</p>
                        <br />
                        <br />
                        <br />
                    </td>

                    <td colspan="14" align="center" style="font-size: 18px">
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <p align="right">-----' . date('Y-m-d') . '-------<br />Date Prepared</p>
                        <br />
                        <br />
                        <br />
                    </td>
                </tr>
        </table>    
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
