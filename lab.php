<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();
$successMessage = null;
$pageError = null;
$errorMessage = null;
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('add_haematology')) {
            $validate = $validate->check($_POST, array(
                'hospitalization_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $hospitalization_details = $override->get3('hospitalization_details', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                    if ($hospitalization_details) {
                        $user->updateRecord('hospitalization_details', array(
                            'visit_date' => Input::get('hospitalization_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'hospitalization_date' => Input::get('hospitalization_date'),
                            'hospitalization_ncd' => Input::get('hospitalization_ncd'),
                            'hospitalization_year' => Input::get('hospitalization_year'),
                            'hospitalization_day' => Input::get('hospitalization_day'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $hospitalization_details['id']);
                    } else {
                        $user->createRecord('hospitalization_details', array(
                            'visit_date' => Input::get('hospitalization_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'hospitalization_date' => Input::get('hospitalization_date'),
                            'hospitalization_ncd' => Input::get('hospitalization_ncd'),
                            'hospitalization_year' => Input::get('hospitalization_year'),
                            'hospitalization_day' => Input::get('hospitalization_day'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }

                    $multiArray = $override->get('hospitalization_table', 'patient_id', $_GET['cid']);
                    $i = 0;
                    foreach (Input::get('admission_date') as $searchValue) {
                        if ($user->isValueInMultiArrays($searchValue, $multiArray)) {
                            // echo "The value '{$searchValue}' exists in the multi-dimensional array.";
                            // $user->isValueInMultiArrays($searchValue, $multiArray);
                            // $id = $override->get('card_test', 'cardiac', $searchValue);
                            // $user->updateRecord('card_test', array(
                            //     'cardiac' => $searchValue,
                            // ), $id['id']);
                        } else {
                            // echo "The value '{$searchValue}' does not exist in the multi-dimensional array.";
                            // $user->createRecord('card_test', array(
                            //     'cardiac' => $searchValue,
                            // ));vehicle11
                            $user->createRecord('hospitalization_table', array(
                                'study_id' => $_GET['sid'],
                                'visit_code' => $_GET['vcode'],
                                'visit_day' => $_GET['vday'],
                                'seq_no' => $_GET['seq'],
                                'vid' => $_GET['vid'],
                                'admission_date' => $searchValue,
                                'admission_reason' => Input::get('admission_reason')[$i],
                                'discharge_diagnosis' => Input::get('discharge_diagnosis')[$i],
                                'patient_id' => $_GET['cid'],
                                'staff_id' => $user->data()->id,
                                'status' => 1,
                                'created_on' => date('Y-m-d'),
                                'site_id' => $user->data()->site_id,
                            ));
                        }
                        $i++;
                    }


                    $successMessage = 'Hospitalization details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
} else {
    Redirect::to('index.php');
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <title> Add - PenPLus </title>
    <?php include "head.php"; ?>

</head>

<body>
    <div class="wrapper">

        <?php include 'topbar.php' ?>
        <?php include 'menu.php' ?>
        <div class="content">

            <!-- <style>
                body {
                    padding: 50px;
                }

                label {
                    position: relative;
                    cursor: pointer;
                    color: #666;
                    font-size: 30px;
                }

                input[type="checkbox"],
                input[type="radio"] {
                    position: absolute;
                    right: 9000px;
                }

                /*Check box*/
                input[type="checkbox"]+.label-text:before {
                    content: "\f096";
                    font-family: "FontAwesome";
                    speak: none;
                    font-style: normal;
                    font-weight: normal;
                    font-variant: normal;
                    text-transform: none;
                    line-height: 1;
                    -webkit-font-smoothing: antialiased;
                    width: 1em;
                    display: inline-block;
                    margin-right: 5px;
                }

                input[type="checkbox"]:checked+.label-text:before {
                    content: "\f14a";
                    color: #2980b9;
                    animation: effect 250ms ease-in;
                }

                input[type="checkbox"]:disabled+.label-text {
                    color: #aaa;
                }

                input[type="checkbox"]:disabled+.label-text:before {
                    content: "\f0c8";
                    color: #ccc;
                }

                /*Radio box*/

                input[type="radio"]+.label-text:before {
                    content: "\f10c";
                    font-family: "FontAwesome";
                    speak: none;
                    font-style: normal;
                    font-weight: normal;
                    font-variant: normal;
                    text-transform: none;
                    line-height: 1;
                    -webkit-font-smoothing: antialiased;
                    width: 1em;
                    display: inline-block;
                    margin-right: 5px;
                }

                input[type="radio"]:checked+.label-text:before {
                    content: "\f192";
                    color: #8e44ad;
                    animation: effect 250ms ease-in;
                }

                input[type="radio"]:disabled+.label-text {
                    color: #aaa;
                }

                input[type="radio"]:disabled+.label-text:before {
                    content: "\f111";
                    color: #ccc;
                }

                /*Radio Toggle*/

                .toggle input[type="radio"]+.label-text:before {
                    content: "\f204";
                    font-family: "FontAwesome";
                    speak: none;
                    font-style: normal;
                    font-weight: normal;
                    font-variant: normal;
                    text-transform: none;
                    line-height: 1;
                    -webkit-font-smoothing: antialiased;
                    width: 1em;
                    display: inline-block;
                    margin-right: 10px;
                }

                .toggle input[type="radio"]:checked+.label-text:before {
                    content: "\f205";
                    color: #16a085;
                    animation: effect 250ms ease-in;
                }

                .toggle input[type="radio"]:disabled+.label-text {
                    color: #aaa;
                }

                .toggle input[type="radio"]:disabled+.label-text:before {
                    content: "\f204";
                    color: #ccc;
                }


                @keyframes effect {
                    0% {
                        transform: scale(0);
                    }

                    25% {
                        transform: scale(1.3);
                    }

                    75% {
                        transform: scale(1.4);
                    }

                    100% {
                        transform: scale(1);
                    }
                }
            </style> -->
            <div class="breadLine">

                <ul class="breadcrumb">
                    <li><a href="#">Simple Admin</a> <span class="divider">></span></li>
                    <li class="active">Add Info</li>
                </ul>
                <?php include 'pageInfo.php' ?>
            </div>

            <div class="workplace">
                <?php if ($errorMessage) { ?>
                    <div class="alert alert-danger">
                        <h4>Error!</h4>
                        <?= $errorMessage ?>
                    </div>
                <?php } elseif ($pageError) { ?>
                    <div class="alert alert-danger">
                        <h4>Error!</h4>
                        <?php foreach ($pageError as $error) {
                            echo $error . ' , ';
                        } ?>
                    </div>
                <?php } elseif ($successMessage) { ?>
                    <div class="alert alert-success">
                        <h4>Success!</h4>
                        <?= $successMessage ?>
                    </div>
                <?php } ?>
                <div class="row">
                    <?php { ?>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <form id="validation" method="post">
                                        <h2>1. HAEMATOLOGY</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="haematology"> <span class="label-text">Full Blood Picture</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="haematology"> <span class="label-text">Blood Grouping and Crossmatching</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="haematology"> <span class="label-text">Hb Level</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="haematology"> <span class="label-text">Sickling Test</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="haematology"> <span class="label-text">Peripheral Blood Smear</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="haematology"> <span class="label-text">ESR</span>
                                            </label>
                                        </div>
                                        <div class="footer tar">
                                            <input type="submit" name="add_haematology" value="Submit" class="btn btn-default">
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-4">
                                    <form>
                                        <h2>2. PARASITOLOGY</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="parasitology"> <span class="label-text">MRDT</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="parasitology"> <span class="label-text">B/S</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="parasitology"> <span class="label-text">Urine sediments</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="parasitology"> <span class="label-text">Urinalysis</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="parasitology"> <span class="label-text">Stool analysis</span>
                                            </label>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-4">
                                    <form>
                                        <h2>3. MICROBIOLOGY</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="microbiology"> <span class="label-text">ZN Stain for AFB & Leprosy</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="microbiology"> <span class="label-text">Auramine O Stain for AFB</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="microbiology"> <span class="label-text">Modified Zn Stain for AFB</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="microbiology"> <span class="label-text">AFB Detection by Gene X-Part</span>
                                            </label>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <form>
                                        <h2>4. Renal Function Test</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="renal"> <span class="label-text">Creatinine</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="renal"> <span class="label-text">Urea</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="renal"> <span class="label-text">Uric acid</span>
                                            </label>
                                        </div>
                                    </form>

                                </div>
                                <div class="col-md-4">
                                    <form>
                                        <h2>5. Liver Function Test</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="liver"> <span class="label-text">ALAT</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="liver"> <span class="label-text">ASAT</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="liver"> <span class="label-text">Bilirubin Test</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="liver"> <span class="label-text">Protein</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="liver"> <span class="label-text">Albumin</span>
                                            </label>
                                        </div>
                                    </form>

                                </div>
                                <div class="col-md-4">
                                    <form>
                                        <h2>6. Thyroid Function Test</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="thyroid"> <span class="label-text">TSH (Thyroid Stimulating Hormone)</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="thyroid"> <span class="label-text">T3 (Triiodo Thyronine)</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="thyroid"> <span class="label-text">T4 (Thyroxine)</span>
                                            </label>
                                        </div>
                                    </form>

                                </div>
                            </div>


                            <div class="row">

                                <div class="col-md-4">
                                    <form>
                                        <h2>7. Metabolic/Electrolyte</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="metabolic_electrolyte"> <span class="label-text">Calcium</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="metabolic_electrolyte"> <span class="label-text">Chloride</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="metabolic_electrolyte"> <span class="label-text">Mg</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="metabolic_electrolyte"> <span class="label-text">Zn</span>
                                            </label>
                                        </div>
                                    </form>

                                </div>
                                <div class="col-md-4">
                                    <form>
                                        <h2>8. Lipid Panel/Cardiac</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="lipid_panel_cardiac"> <span class="label-text">Cholesterol</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="lipid_panel_cardiac"> <span class="label-text">LDL (Lower Density Lipoprotein)</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="lipid_panel_cardiac"> <span class="label-text">HDL (High Density)</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="lipid_panel_cardiac"> <span class="label-text">Lipoprotein</span>
                                            </label>
                                        </div>
                                    </form>

                                </div>
                                <div class="col-md-4">
                                    <form>
                                        <h2>9. Diabete</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="diabete"> <span class="label-text">RBG/FBG</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="diabete"> <span class="label-text">HbA1c</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="diabete"> <span class="label-text">C-Peptide</span>
                                            </label>
                                        </div>
                                    </form>

                                </div>

                            </div>


                            <div class="row">
                                <div class="col-md-4">
                                    <form>
                                        <h2>10. Tumor Marker</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="tumor_marker"> <span class="label-text">PSA</span>
                                            </label>
                                        </div>

                                    </form>

                                </div>
                                <div class="col-md-4">
                                    <form>
                                        <h2>11. Fertility</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="fertility"> <span class="label-text">LH (Luteinizing Hormone)</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="fertility"> <span class="label-text">FSH (Follicle Stimulating Hormone)</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="fertility"> <span class="label-text">HCG/UPT</span>
                                            </label>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">


                                <div class="col-md-4">
                                    <form>
                                        <h2>12. SEROLOGY</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">HIV Test</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">Syphilis (VDRL/RPR)</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">Hepatitis B</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">Hepatitis C</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">Widal Test</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">CD4 Count</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">HVL Count</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">DBS Testing</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">Rheumatoid Factor</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">Cryptococcus Ag Test</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">Pylori test</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">Brucella Test</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="serology"> <span class="label-text">Covid 19</span>
                                            </label>
                                        </div>
                                    </form>

                                </div>


                                <div class="col-md-4">
                                    <form>
                                        <h2>13. BACTERIOLOGY</h2>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="bacteriology"> <span class="label-text">Gram Stain</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="bacteriology"> <span class="label-text">Stool for C/S</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="bacteriology"> <span class="label-text">Urine for C/S</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="bacteriology"> <span class="label-text">Body Fluid culture</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="bacteriology"> <span class="label-text">Pus swab culture</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="bacteriology"> <span class="label-text">HVS for C/S</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="bacteriology"> <span class="label-text">Skin scraping</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label>
                                                <input type="checkbox" name="bacteriology"> <span class="label-text">Seminal analysis</span>
                                            </label>
                                        </div>
                                    </form>

                                </div>



                            </div>
                        </div>


                    <?php } ?> <div class="dr"><span></span></div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>