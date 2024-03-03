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
        if (Input::get('add_social_economic')) {
            $validate = $validate->check($_POST, array(
                'social_economic_date' => array(
                    'required' => true,
                ),

            ));
            if ($validate->passed()) {
                try {

                    $social_economic = $override->get3('social_economic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                    if ($social_economic) {
                        $user->updateRecord('social_economic', array(
                            'visit_date' => Input::get('social_economic_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'social_economic_date' => Input::get('social_economic_date'),
                            'distance_km' => Input::get('distance_km'),
                            'distance_minutes' => Input::get('distance_minutes'),
                            'transport_mode' => Input::get('transport_mode'),
                            'transport_mode_other' => Input::get('transport_mode_other'),
                            'transportation_cost' => Input::get('transportation_cost'),
                            'household_head' => Input::get('household_head'),
                            'household_head_other' => Input::get('household_head_other'),
                            'household_years' => Input::get('household_years'),
                            'household_people' => Input::get('household_people'),
                            'primary_income_earner' => Input::get('primary_income_earner'),
                            'primary_income_earner_other' => Input::get('primary_income_earner_other'),
                            'formally_employed' => Input::get('formally_employed'),
                            'formally_employed_other' => Input::get('formally_employed_other'),
                            'main_income_based_other' => Input::get('main_income_based_other'),
                            'main_income_based' => Input::get('main_income_based'),
                            'reason_not_working' => Input::get('reason_not_working'),
                            'last_working' => Input::get('last_working'),
                            'stopped_working' => Input::get('stopped_working'),
                            'stopped_duration' => Input::get('stopped_duration'),
                            'someone_take_care' => Input::get('someone_take_care'),
                            'take_care_duration' => Input::get('take_care_duration'),
                            'quit_job' => Input::get('quit_job'),
                            'affect_social' => Input::get('affect_social'),
                            'affect_social_how' => Input::get('affect_social_how'),
                            'financial_burden' => Input::get('financial_burden'),
                            'affect_social_other' => Input::get('affect_social_other'),
                            'wealth_rate' => Input::get('wealth_rate'),
                            'contributer_occupation' => Input::get('contributer_occupation'),
                            'contributer_occupation_other' => Input::get('contributer_occupation_other'),
                            'main_occupation' => Input::get('main_occupation'),
                            'main_occupation_other' => Input::get('main_occupation_other'),
                            'main_icome_based' => Input::get('main_icome_based'),
                            'main_icome_other' => Input::get('main_icome_other'),
                            'earn_individual' => Input::get('earn_individual'),
                            'earn_household' => Input::get('earn_household'),
                            'main_transport' => Input::get('main_transport'),
                            'time_from_home' => Input::get('time_from_home'),
                            'leave_children' => Input::get('leave_children'),
                            'looking_children' => Input::get('looking_children'),
                            'looking_children_other' => Input::get('looking_children_other'),
                            'occupation_looking_child' => Input::get('occupation_looking_child'),
                            'occupation_looking_child_other' => Input::get('occupation_looking_child_other'),
                            'accompany' => Input::get('accompany'),
                            'accompany_occupation' => Input::get('accompany_occupation'),
                            'accompany_occupation_other' => Input::get('accompany_occupation_other'),
                            'accompany_transport' => Input::get('accompany_transport'),
                            'accompany_expenses' => Input::get('accompany_expenses'),
                            'activities_disrupted' => Input::get('activities_disrupted'),
                            'material_floor' => Input::get('material_floor'),
                            'material_floor_other' => Input::get('material_floor_other'),
                            'material_roof' => Input::get('material_roof'),
                            'material_roof_other' => Input::get('material_roof_other'),
                            'cooking_fuel' => Input::get('cooking_fuel'),
                            'cooking_fuel_other' => Input::get('cooking_fuel_other'),
                            'water_access' => Input::get('water_access'),
                            'water_source' => Input::get('water_source'),
                            'water_source_other' => Input::get('water_source_other'),
                            'toilet_access' => Input::get('toilet_access'),
                            'toilet_facility' => Input::get('toilet_facility'),
                            'toilet_access_other' => Input::get('toilet_access_other'),
                            'television' => Input::get('television'),
                            'refrigerator' => Input::get('refrigerator'),
                            'sofa' => Input::get('sofa'),
                            'clock' => Input::get('clock'),
                            'fan' => Input::get('fan'),
                            'vcr_dvd' => Input::get('vcr_dvd'),
                            'bank_account' => Input::get('bank_account'),
                            'no_food' => Input::get('no_food'),
                            'sleep_hungry' => Input::get('sleep_hungry'),
                            'day_hungry' => Input::get('day_hungry'),
                            'patient_education' => Input::get('patient_education'),
                            'patient_education_other' => Input::get('patient_education_other'),
                            'primary_earner_edctn' => Input::get('primary_earner_edctn'),
                            'household_education' => Input::get('household_education'),
                            'household_education_other' => Input::get('household_education_other'),
                            'earner_edctn_other' => Input::get('earner_edctn_other'),
                            'spouse_edctn' => Input::get('spouse_edctn'),
                            'spouse_edctn_other' => Input::get('spouse_edctn_other'),
                            'socioeconomic_notes' => Input::get('socioeconomic_notes'),
                            'socioeconomic_notes' => Input::get('socioeconomic_notes'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'site_id' => $user->data()->site_id,
                        ), $social_economic['id']);
                    } else {
                        $user->createRecord('social_economic', array(
                            'visit_date' => Input::get('social_economic_date'),
                            'study_id' => $_GET['sid'],
                            'visit_code' => $_GET['vcode'],
                            'visit_day' => $_GET['vday'],
                            'seq_no' => $_GET['seq'],
                            'vid' => $_GET['vid'],
                            'social_economic_date' => Input::get('social_economic_date'),
                            'distance_km' => Input::get('distance_km'),
                            'distance_minutes' => Input::get('distance_minutes'),
                            'transport_mode' => Input::get('transport_mode'),
                            'transport_mode_other' => Input::get('transport_mode_other'),
                            'transportation_cost' => Input::get('transportation_cost'),
                            'household_head' => Input::get('household_head'),
                            'household_head_other' => Input::get('household_head_other'),
                            'household_years' => Input::get('household_years'),
                            'household_people' => Input::get('household_people'),
                            'primary_income_earner' => Input::get('primary_income_earner'),
                            'primary_income_earner_other' => Input::get('primary_income_earner_other'),
                            'formally_employed' => Input::get('formally_employed'),
                            'formally_employed_other' => Input::get('formally_employed_other'),
                            'main_income_based_other' => Input::get('main_income_based_other'),
                            'main_income_based' => Input::get('main_income_based'),
                            'reason_not_working' => Input::get('reason_not_working'),
                            'last_working' => Input::get('last_working'),
                            'stopped_working' => Input::get('stopped_working'),
                            'stopped_duration' => Input::get('stopped_duration'),
                            'someone_take_care' => Input::get('someone_take_care'),
                            'take_care_duration' => Input::get('take_care_duration'),
                            'quit_job' => Input::get('quit_job'),
                            'affect_social' => Input::get('affect_social'),
                            'affect_social_how' => Input::get('affect_social_how'),
                            'affect_social_other' => Input::get('affect_social_other'),
                            'financial_burden' => Input::get('financial_burden'),
                            'wealth_rate' => Input::get('wealth_rate'),
                            'contributer_occupation' => Input::get('contributer_occupation'),
                            'contributer_occupation_other' => Input::get('contributer_occupation_other'),
                            'main_occupation' => Input::get('main_occupation'),
                            'main_occupation_other' => Input::get('main_occupation_other'),
                            'main_icome_based' => Input::get('main_icome_based'),
                            'main_icome_other' => Input::get('main_icome_other'),
                            'earn_individual' => Input::get('earn_individual'),
                            'earn_household' => Input::get('earn_household'),
                            'main_transport' => Input::get('main_transport'),
                            'time_from_home' => Input::get('time_from_home'),
                            'leave_children' => Input::get('leave_children'),
                            'looking_children' => Input::get('looking_children'),
                            'looking_children_other' => Input::get('looking_children_other'),
                            'occupation_looking_child' => Input::get('occupation_looking_child'),
                            'occupation_looking_child_other' => Input::get('occupation_looking_child_other'),
                            'accompany' => Input::get('accompany'),
                            'accompany_occupation' => Input::get('accompany_occupation'),
                            'accompany_occupation_other' => Input::get('accompany_occupation_other'),
                            'accompany_transport' => Input::get('accompany_transport'),
                            'accompany_expenses' => Input::get('accompany_expenses'),
                            'activities_disrupted' => Input::get('activities_disrupted'),
                            'material_floor' => Input::get('material_floor'),
                            'material_floor_other' => Input::get('material_floor_other'),
                            'material_roof' => Input::get('material_roof'),
                            'material_roof_other' => Input::get('material_roof_other'),
                            'cooking_fuel' => Input::get('cooking_fuel'),
                            'cooking_fuel_other' => Input::get('cooking_fuel_other'),
                            'water_access' => Input::get('water_access'),
                            'water_source' => Input::get('water_source'),
                            'water_source_other' => Input::get('water_source_other'),
                            'toilet_access' => Input::get('toilet_access'),
                            'toilet_facility' => Input::get('toilet_facility'),
                            'toilet_access_other' => Input::get('toilet_access_other'),
                            'television' => Input::get('television'),
                            'refrigerator' => Input::get('refrigerator'),
                            'sofa' => Input::get('sofa'),
                            'clock' => Input::get('clock'),
                            'fan' => Input::get('fan'),
                            'vcr_dvd' => Input::get('vcr_dvd'),
                            'bank_account' => Input::get('bank_account'),
                            'no_food' => Input::get('no_food'),
                            'sleep_hungry' => Input::get('sleep_hungry'),
                            'day_hungry' => Input::get('day_hungry'),
                            'patient_education' => Input::get('patient_education'),
                            'patient_education_other' => Input::get('patient_education_other'),
                            'primary_earner_edctn' => Input::get('primary_earner_edctn'),
                            'household_education' => Input::get('household_education'),
                            'household_education_other' => Input::get('household_education_other'),
                            'earner_edctn_other' => Input::get('earner_edctn_other'),
                            'spouse_edctn' => Input::get('spouse_edctn'),
                            'spouse_edctn_other' => Input::get('spouse_edctn_other'),
                            'socioeconomic_notes' => Input::get('socioeconomic_notes'),
                            'patient_id' => $_GET['cid'],
                            'staff_id' => $user->data()->id,
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                            'site_id' => $user->data()->site_id,
                        ));
                    }
                    $successMessage = 'Social economic  details added Successful';
                    Redirect::to('info.php?id=7&cid=' . $_GET['cid'] . '&vid=' . $_GET['vid'] . '&vcode=' . $_GET['vcode'] . '&seq=' . $_GET['seq'] . '&sid=' . $_GET['sid'] . '&vday=' . $_GET['vday']);
                    die;
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
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

    <style>
        #medication_table {
            border-collapse: collapse;
        }

        #medication_table th,
        #medication_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #medication_table th {
            text-align: left;
            background-color: #f2f2f2;
        }

        #medication_table {
            border-collapse: collapse;
        }

        #medication_list th,
        #medication_list td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #medication_list th {
            text-align: left;
            background-color: #f2f2f2;
        }

        .remove-row {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
        }

        .remove-row:hover {
            background-color: #da190b;
        }

        .edit-row {
            background-color: #3FF22F;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
        }

        .edit-row:hover {
            background-color: #da190b;
        }

        #hospitalization_details_table {
            border-collapse: collapse;
        }

        #hospitalization_details_table th,
        #hospitalization_details_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #hospitalization_details_table th,
        #hospitalization_details_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #hospitalization_details_table th {
            text-align: left;
            background-color: #f2f2f2;
        }

        #sickle_cell_table {
            border-collapse: collapse;
        }

        #sickle_cell_table th,
        #sickle_cell_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #sickle_cell_table th,
        #sickle_cell_table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        #sickle_cell_table th {
            text-align: left;
            background-color: #f2f2f2;
        }

        .hidden {
            display: none;
        }
    </style>
    
    <!-- <script type="text/javascript" src="hospital.js"></script> -->

</head>

<body>
    <div class="wrapper">

        <?php include 'topbar.php' ?>
        <?php include 'menu.php' ?>
        <div class="content">


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
                    <?php if ($_GET['id'] == 1 && ($user->data()->position == 1 || $user->data()->position == 2)) { ?>
                         <?php
                        $social_economic = $override->get3('social_economic', 'patient_id', $_GET['cid'], 'seq_no', $_GET['seq'], 'visit_code', $_GET['vcode'])[0];
                          $patient = $override->get('clients', 'id', $_GET['cid'])[0];
                            $category = $override->get('main_diagnosis', 'patient_id', $_GET['cid'])[0];
                            $cat = '';

                            if ($category['cardiac'] == 1) {
                                $cat = 'Cardiac';
                            } elseif ($category['diabetes'] == 1) {
                                $cat = 'Diabetes';
                            } elseif ($category['sickle_cell'] == 1) {
                                $cat = 'Sickle cell';
                            } else {
                                $cat = 'Not Diagnosed';
                            }


                            if ($patient['gender'] == 1) {
                                $gender = 'Male';
                            } elseif ($patient['gender'] == 2) {
                                $gender = 'Female';
                            }



                            $name = 'Name: ' . $patient['firstname'] . ' ' . $patient['lastname'] . ' Age: ' . $patient['age']. ' Gender: ' . $gender. ' Type: ' . $cat;
                        ?>
                        <div class="col-md-offset-1 col-md-8">
                            <div class="head clearfix">
                                <div class="isw-ok"></div>
                                <h1>Socioeconomic Status ( EXIT-TB SOCIAL ECONOMIC TOOL (2018)) </h1>
                                                                <h4><strong style="font-size: larger"><?= $name ?></strong></h4>

                            </div>
                            <div class="block-fluid">
                                <form id="validation" method="post">

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Visit date:</label>
                                                    <input type="text" class="validate[required,custom[date]]" name="social_economic_date" id="social_economic_date" value="<?php if ($social_economic['social_economic_date']) {
                                                                                                                                        print_r($social_economic['social_economic_date']);
                                                                                                                                    }  ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Distance to clinic:</label>
                                                    <input type="text" name="distance_km" id="distance_km" value="<?php if ($social_economic['distance_km']) {
                                                                                                                        print_r($social_economic['distance_km']);
                                                                                                                    }  ?>" />
                                                    <span>( km )</span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Distance to clinic:</label>
                                                    <input type="text" name="distance_minutes" id="distance_minutes" value="<?php if ($social_economic['distance_minutes']) {
                                                                                                                                print_r($social_economic['distance_minutes']);
                                                                                                                            }  ?>" />
                                                    <span>( minutes )</span>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Mode of transportation to clinic:</label>
                                                    <select name="transport_mode" id="transport_mode" style="width: 100%;" onchange="checkQuestionValue96('transport_mode','transport_mode_other')">
                                                        <option value="<?= $social_economic['transport_mode'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['transport_mode'] == 1) {
                                                                                                                            echo 'Walk';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 2) {
                                                                                                                            echo 'Taxi';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 3) {
                                                                                                                            echo 'Bodaboda Motorcycle';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 4) {
                                                                                                                            echo 'Bodaboda Bicycle';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 5) {
                                                                                                                            echo 'My own car';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 6) {
                                                                                                                            echo 'My own bicycle';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 7) {
                                                                                                                            echo 'Commuter bus/Daladala';
                                                                                                                        } elseif ($social_economic['transport_mode'] == 96) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Walk</option>
                                                        <option value="2">Taxi</option>
                                                        <option value="3">Bodaboda Motorcycle</option>
                                                        <option value="4">Bodaboda Bicycle</option>
                                                        <option value="5">My own car</option>
                                                        <option value="6">My own bicycle</option>
                                                        <option value="7">Commuter bus/Daladala</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 hidden" id="transport_mode_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="transport_mode_other"  value="<?php if ($social_economic['transport_mode_other']) {
                                                                                                                                        print_r($social_economic['transport_mode_other']);
                                                                                                                                    }  ?>" />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Cost of transportation to clinic (round trip)::</label>
                                                    <input type="text" name="transportation_cost" id="transportation_cost" value="<?php if ($social_economic['transportation_cost']) {
                                                                                                                                        print_r($social_economic['transportation_cost']);
                                                                                                                                    }  ?>" />
                                                    <span>( TSHS )</span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Who is the head of your household?:</label>
                                                    <select name="household_head" id="household_head" style="width: 100%;" onchange="checkQuestionValue96('household_head','household_head_other')">
                                                        <option value="<?= $social_economic['household_head'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['household_head'] == 1) {
                                                                                                                            echo 'Yourself';
                                                                                                                        } elseif ($social_economic['household_head'] == 2) {
                                                                                                                            echo 'Your spouse/partner';
                                                                                                                        } elseif ($social_economic['household_head'] == 3) {
                                                                                                                            echo 'Your father or mother';
                                                                                                                        } elseif ($social_economic['household_head'] == 96) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yourself</option>
                                                        <option value="2">Your spouse/partner</option>
                                                        <option value="3">Your father or mother</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 hidden" id="household_head_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="household_head_other"  value="<?php if ($social_economic['household_head_other']) {
                                                                                                                                        print_r($social_economic['household_head_other']);
                                                                                                                                    }  ?>" />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>How old is your head of household?</label>
                                                    <input type="text" name="household_years" id="household_years" value="<?php if ($social_economic['household_years']) {
                                                                                                                                print_r($social_economic['household_years']);
                                                                                                                            }  ?>" />
                                                    <span>( Age in Years )</span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>How many people are in your household?</label>
                                                    <input type="text" name="household_people" id="household_head_other" value="<?php if ($social_economic['household_head_other']) {
                                                                                                                                    print_r($social_economic['household_head_other']);
                                                                                                                                }  ?>" />
                                                    <span>( ENTER NUMBERS )</span>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>How do you rate your wealth in comparison to others?:</label>
                                                    <select name="wealth_rate" id="wealth_rate" style="width: 100%;">
                                                        <option value="<?= $social_economic['wealth_rate'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['wealth_rate'] == 1) {
                                                                                                                        echo 'Among most wealthy';
                                                                                                                    } elseif ($social_economic['wealth_rate'] == 2) {
                                                                                                                        echo 'Above average';
                                                                                                                    } elseif ($social_economic['wealth_rate'] == 3) {
                                                                                                                        echo 'Average wealth';
                                                                                                                    } elseif ($social_economic['wealth_rate'] == 4) {
                                                                                                                        echo 'Below average';
                                                                                                                    } elseif ($social_economic['wealth_rate'] == 5) {
                                                                                                                        echo 'Among least wealthy';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Among most wealthy</option>
                                                        <option value="2">Above average</option>
                                                        <option value="3">Average wealth</option>
                                                        <option value="4">Below average</option>
                                                        <option value="5">Among least wealthy</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Who is the primary income earner in the household ?:</label>
                                                    <select name="primary_income_earner" id="primary_income_earner" style="width: 100%;" onchange="checkQuestionValue96('primary_income_earner','primary_income_earner_other')">
                                                        <option value="<?= $social_economic['primary_income_earner'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['primary_income_earner'] == 1) {
                                                                                                                                    echo 'Patient';
                                                                                                                                } elseif ($social_economic['primary_income_earner'] == 2) {
                                                                                                                                    echo 'Wife / Mother';
                                                                                                                                } elseif ($social_economic['primary_income_earner'] == 3) {
                                                                                                                                    echo 'Husband / father';
                                                                                                                                } elseif ($social_economic['primary_income_earner'] == 4) {
                                                                                                                                    echo 'Extended family';
                                                                                                                                } elseif ($social_economic['primary_income_earner'] == 5) {
                                                                                                                                    echo 'Son / Daughter';
                                                                                                                                } elseif ($social_economic['primary_income_earner'] == 96) {
                                                                                                                                    echo 'Other';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                        <option value="1">Patient</option>
                                                        <option value="2">Wife / Mother</option>
                                                        <option value="3">Husband / father</option>
                                                        <option value="4">Extended family</option>
                                                        <option value="5">Son / Daughter</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 hidden" id="primary_income_earner_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="primary_income_earner_other"  value="<?php if ($social_economic['primary_income_earner_other']) {
                                                                                                                                                        print_r($social_economic['primary_income_earner_other']);
                                                                                                                                                    }  ?>" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Is the patients currently formally employed?:</label>
                                                    <select name="formally_employed" id="formally_employed" style="width: 100%;" onchange="checkQuestionValue96('formally_employed','formally_employed_other')">
                                                        <option value="<?= $social_economic['formally_employed'] ?>"><?php if ($social_economic) {
                                                                                                                            if ($social_economic['formally_employed'] == 1) {
                                                                                                                                echo 'Yes, formal work ';
                                                                                                                            } elseif ($social_economic['formally_employed'] == 2) {
                                                                                                                                echo 'No, informal work';
                                                                                                                            } elseif ($social_economic['formally_employed'] == 3) {
                                                                                                                                echo 'Housework';
                                                                                                                            } elseif ($social_economic['formally_employed'] == 4) {
                                                                                                                                echo 'Retired';
                                                                                                                            } elseif ($social_economic['formally_employed'] == 5) {
                                                                                                                                echo 'On sick leave';
                                                                                                                            } elseif ($social_economic['formally_employed'] == 6) {
                                                                                                                                echo 'School, university';
                                                                                                                            } elseif ($social_economic['formally_employed'] == 7) {
                                                                                                                                echo 'Not working';
                                                                                                                            } elseif ($social_economic['formally_employed'] == 8) {
                                                                                                                                echo 'Combination (specify)';
                                                                                                                            } elseif ($social_economic['formally_employed'] == 96) {
                                                                                                                                echo 'Other (specify)';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?></option>
                                                        <option value="1">Yes, formal work </option>
                                                        <option value="2">No, informal work</option>
                                                        <option value="3">Housework</option>
                                                        <option value="4">Retired</option>
                                                        <option value="5">On sick leave</option>
                                                        <option value="6">School, university</option>
                                                        <option value="7">Not working</option>
                                                        <option value="8">Combination (specify)</option>
                                                        <option value="96">Other (specify)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 hidden" id="formally_employed_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="formally_employed_other"  value="<?php if ($social_economic['formally_employed_other']) {
                                                                                                                                                print_r($social_economic['formally_employed_other']);
                                                                                                                                            }  ?>" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If formal /informal work/housework what is your main income based on?:</label>
                                                    <select name="main_income_based" id="main_income_based" style="width: 100%;" onchange="checkQuestionValue96('main_income_based','main_income_based_other')">
                                                        <option value="<?= $social_economic['main_income_based'] ?>"><?php if ($social_economic) {
                                                                                                                            if ($social_economic['main_income_based'] == 1) {
                                                                                                                                echo 'Monthly salary';
                                                                                                                            } elseif ($social_economic['main_income_based'] == 2) {
                                                                                                                                echo 'Daily wage';
                                                                                                                            } elseif ($social_economic['main_income_based'] == 3) {
                                                                                                                                echo 'Business/firm earnings';
                                                                                                                            } elseif ($social_economic['main_income_based'] == 4) {
                                                                                                                                echo 'Sale of farm produce';
                                                                                                                            } elseif ($social_economic['main_income_based'] == 5) {
                                                                                                                                echo 'Inkind payment';
                                                                                                                            } elseif ($social_economic['main_income_based'] == 6) {
                                                                                                                                echo 'Have no income';
                                                                                                                            } elseif ($social_economic['main_income_based'] == 96) {
                                                                                                                                echo 'Other';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?></option>
                                                        <option value="1">Monthly salary</option>
                                                        <option value="2">Daily wage</option>
                                                        <option value="3">Business/firm earnings</option>
                                                        <option value="4">Sale of farm produce</option>
                                                        <option value="5">Inkind payment</option>
                                                        <option value="6">Have no income</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 hidden" id="main_income_based_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="main_income_based_other" value="<?php if ($social_economic['main_income_based_other']) {
                                                                                                                                                print_r($social_economic['main_income_based_other']);
                                                                                                                                            }  ?>" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If patient is not working/retired/sick leave is the reason is it because of this NCD illness? :</label>
                                                    <select name="reason_not_working" id="reason_not_working" style="width: 100%;" onchange="checkQuestionValue1('reason_not_working','last_working')">
                                                        <option value="<?= $social_economic['reason_not_working'] ?>"><?php if ($social_economic) {
                                                                                                                            if ($social_economic['reason_not_working'] == 1) {
                                                                                                                                echo 'Yes';
                                                                                                                            } elseif ($social_economic['reason_not_working'] == 2) {
                                                                                                                                echo 'No';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 hidden" id="last_working">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Yes: When was the last time you were working? (mm/yy)</label>
                                                    <input type="text" name="last_working"  value="<?php if ($social_economic['last_working']) {
                                                                                                                        print_r($social_economic['last_working']);
                                                                                                                    }  ?>" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Have you ever stopped working/going to school/doing housework due to this disease?:</label>
                                                    <select name="stopped_working" id="stopped_working" style="width: 100%;" onchange="checkQuestionValue1('stopped_working','stopped_duration')">
                                                        <option value="<?= $social_economic['stopped_working'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['stopped_working'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($social_economic['stopped_working'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 hidden" id="stopped_duration">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If YES: for how long? Specify</label>
                                                    <select name="stopped_duration" style="width: 100%;">
                                                        <option value="<?= $social_economic['stopped_duration'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['stopped_duration'] == 1) {
                                                                                                                            echo 'Less than 1 month ';
                                                                                                                        } elseif ($social_economic['stopped_duration'] == 2) {
                                                                                                                            echo 'One month ';
                                                                                                                        } elseif ($social_economic['stopped_duration'] == 3) {
                                                                                                                            echo '2-3 months';
                                                                                                                        } elseif ($social_economic['stopped_duration'] == 4) {
                                                                                                                            echo '4-5 months';
                                                                                                                        } elseif ($social_economic['stopped_duration'] == 5) {
                                                                                                                            echo 'More than 6 months ';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Less than 1 month </option>
                                                        <option value="2">One month </option>
                                                        <option value="3">2-3 months </option>
                                                        <option value="4">4-5 months </option>
                                                        <option value="5">More than 6 months </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Does someone stay home specifically to take care of you?</label>
                                                    <select name="someone_take_care" id="someone_take_care" style="width: 100%;" onchange="checkQuestionValue1('someone_take_care','take_care_duration1')">
                                                        <option value="<?= $social_economic['someone_take_care'] ?>"><?php if ($social_economic) {
                                                                                                                            if ($social_economic['someone_take_care'] == 1) {
                                                                                                                                echo 'Yes';
                                                                                                                            } elseif ($social_economic['someone_take_care'] == 2) {
                                                                                                                                echo 'No';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hidden" id="take_care_duration1">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If YES: for how long? ( weeks) </label>
                                                    <input type="text" name="take_care_duration" value="<?php if ($social_economic['take_care_duration']) {
                                                                                                                                    print_r($social_economic['take_care_duration']);
                                                                                                                                }  ?>" />

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Did they quit their income-earning job to stay home and care for you?:</label>
                                                    <select name="quit_job" id="quit_job" style="width: 100%;">
                                                        <option value="<?= $social_economic['quit_job'] ?>"><?php if ($social_economic) {
                                                                                                                if ($social_economic['quit_job'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($social_economic['quit_job'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label> Has this NCD illness affected your social or private life in any way? :</label>
                                                    <select name="affect_social" id="affect_social" style="width: 100%;" onchange="checkQuestionValue1('affect_social','financial_burden1')">
                                                        <option value="<?= $social_economic['affect_social'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['affect_social'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($social_economic['affect_social'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 hidden" id="financial_burden1">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Yes: Has this resulted in a financial burden? </label>
                                                    <select name="financial_burden" id="financial_burden" style="width: 100%;" onchange="checkQuestionValue1('financial_burden','affect_social_how1')">
                                                        <option value="<?= $social_economic['financial_burden'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['financial_burden'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($social_economic['financial_burden'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 hidden" id="affect_social_how1">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>If Yes: How ? </label>
                                                    <select name="affect_social_how" id="affect_social_how" style="width: 100%;" onchange="checkQuestionValue96('affect_social_how','affect_social_other')">
                                                        <option value="<?= $social_economic['affect_social_how'] ?>"><?php if ($social_economic) {
                                                                                                                            if ($social_economic['affect_social_how'] == 1) {
                                                                                                                                echo 'Divorce';
                                                                                                                            } elseif ($social_economic['affect_social_how'] == 2) {
                                                                                                                                echo 'Loss of Job';
                                                                                                                            } elseif ($social_economic['affect_social_how'] == 3) {
                                                                                                                                echo 'Dropped out of school';
                                                                                                                            } elseif ($social_economic['affect_social_how'] == 4) {
                                                                                                                                echo 'Separated from spouse/partner.';
                                                                                                                            } elseif ($social_economic['affect_social_how'] == 5) {
                                                                                                                                echo 'Disruption of sexual life';
                                                                                                                            } elseif ($social_economic['affect_social_how'] == 6) {
                                                                                                                                echo 'Sick child';
                                                                                                                            } elseif ($social_economic['affect_social_how'] == 96) {
                                                                                                                                echo 'Other (specify)';
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            echo 'Select';
                                                                                                                        } ?></option>
                                                        <option value="1">Divorce</option>
                                                        <option value="2">Loss of Job</option>
                                                        <option value="3">Dropped out of school</option>
                                                        <option value="4">Separated from spouse/partner</option>
                                                        <option value="5">Disruption of sexual life</option>
                                                        <option value="6">Sick child</option>
                                                        <option value="96">Other (specify)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 hidden" id="affect_social_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other (specify)</label>
                                                    <input type="text" name="affect_social_other"  value="<?php if ($social_economic['affect_social_other']) {
                                                                                                                                        print_r($social_economic['affect_social_other']);
                                                                                                                                    }  ?>" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What is the main occupation of the person
                                                        who contributes most for your regular
                                                        expenditure?:</label>
                                                    <select name="contributer_occupation" id="contributer_occupation" style="width: 100%;" onchange="checkQuestionValue96('contributer_occupation','contributer_occupation_other')">
                                                        <option value="<?= $social_economic['contributer_occupation'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['contributer_occupation'] == 1) {
                                                                                                                                    echo 'Employed';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 2) {
                                                                                                                                    echo 'Self employed';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 3) {
                                                                                                                                    echo 'Unemployed';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 4) {
                                                                                                                                    echo 'Farmer';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 5) {
                                                                                                                                    echo 'Fisher';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 6) {
                                                                                                                                    echo 'Student';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 7) {
                                                                                                                                    echo 'Housewife';
                                                                                                                                } elseif ($social_economic['contributer_occupation'] == 96) {
                                                                                                                                    echo 'Other';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                        <option value="1">Employed</option>
                                                        <option value="2">Self employed</option>
                                                        <option value="3">Unemployed</option>
                                                        <option value="4">Farmer</option>
                                                        <option value="5">Fisher</option>
                                                        <option value="6">Student</option>
                                                        <option value="7">Housewife</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 hidden" id="contributer_occupation_other" >
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="contributer_occupation_other" rows="4">
                                                        <?php if ($social_economic['contributer_occupation_other']) {
                                                            print_r($social_economic['contributer_occupation_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What type of work do you do for your main occupation?:</label>
                                                    <select name="main_occupation" id="main_occupation" style="width: 100%;" onchange="checkQuestionValue96('main_occupation','main_occupation_other')">
                                                        <option value="<?= $social_economic['main_occupation'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['main_occupation'] == 1) {
                                                                                                                            echo 'Employed';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 2) {
                                                                                                                            echo 'Self employed';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 3) {
                                                                                                                            echo 'Unemployed';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 4) {
                                                                                                                            echo 'Farmer';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 5) {
                                                                                                                            echo 'Fisher';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 6) {
                                                                                                                            echo 'Student';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 7) {
                                                                                                                            echo 'Housewife';
                                                                                                                        } elseif ($social_economic['main_occupation'] == 96) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Employed</option>
                                                        <option value="2">Self employed</option>
                                                        <option value="3">Unemployed</option>
                                                        <option value="4">Farmer</option>
                                                        <option value="5">Fisher</option>
                                                        <option value="6">Student</option>
                                                        <option value="7">Housewife</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 hidden" id="main_occupation_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="main_occupation_other" rows="4">
                                                        <?php if ($social_economic['main_occupation_other']) {
                                                            print_r($social_economic['main_occupation_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What is your main income based on?:</label>
                                                    <select name="main_icome_based" id="main_icome_based" style="width: 100%;" onchange="checkQuestionValue96('main_icome_based','main_icome_other')">
                                                        <option value="<?= $social_economic['main_icome_based'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['main_icome_based'] == 1) {
                                                                                                                            echo 'Monthly salary';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 2) {
                                                                                                                            echo 'Daily wage';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 3) {
                                                                                                                            echo 'Business/firm earnings';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 4) {
                                                                                                                            echo 'Sale of farm produce';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 5) {
                                                                                                                            echo 'Have no income';
                                                                                                                        } elseif ($social_economic['main_icome_based'] == 96) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Monthly salary</option>
                                                        <option value="2">Daily wage</option>
                                                        <option value="3">Business/firm earnings</option>
                                                        <option value="4">Sale of farm produce</option>
                                                        <option value="5">Have no income</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 hidden" id="main_icome_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="main_icome_other" rows="4">
                                                        <?php if ($social_economic['main_icome_other']) {
                                                            print_r($social_economic['main_icome_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Altogether, how much did you earn from
                                                        all your different sources of income in the
                                                        last month?</label>
                                                    <input type="text" name="earn_individual" id="earn_individual" value="<?php if ($social_economic['earn_individual']) {
                                                                                                                                print_r($social_economic['earn_individual']);
                                                                                                                            }  ?>" />
                                                    <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Altogether how much did others in your
                                                        household (including the household head)
                                                        earn in the last month?</label>
                                                    <input type="text" name="earn_household" id="earn_household" value="<?php if ($social_economic['earn_household']) {
                                                                                                                            print_r($social_economic['earn_household']);
                                                                                                                        }  ?>" />
                                                    <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What was the main form of transport that
                                                        you used to get here today?</label>
                                                    <input type="text" name="main_transport" id="main_transport" value="<?php if ($social_economic['main_transport']) {
                                                                                                                            print_r($social_economic['main_transport']);
                                                                                                                        }  ?>" />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>About how long did it take you to arrive here from home?</label>
                                                    <input type="text" name="time_from_home" id="time_from_home" value="<?php if ($social_economic['time_from_home']) {
                                                                                                                            print_r($social_economic['time_from_home']);
                                                                                                                        }  ?>" />
                                                    <span>Amount in hours (e.g 0.5, 2.25 etc)</span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>In the last month, on how many days were
                                                        your normal activities disrupted through
                                                        illness?</label>
                                                    <input type="text" name="activities_disrupted" id="activities_disrupted" value="<?php if ($social_economic['activities_disrupted']) {
                                                                                                                                        print_r($social_economic['activities_disrupted']);
                                                                                                                                    }  ?>" />
                                                    <!-- <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span> -->

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Did you leave young children (aged less
                                                        than about 6 years) at home to come here
                                                        today?</label>
                                                    <select name="leave_children" id="leave_children" style="width: 100%;" onchange="checkQuestionValue1('leave_children','looking_children1')">
                                                        <option value="<?= $social_economic['leave_children'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['leave_children'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($social_economic['leave_children'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span>IF no, skip next question</span>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 hidden" id="looking_children1">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label> If yes, who is looking after them?
                                                    </label>
                                                    <select name="looking_children" id="looking_children" style="width: 100%;" onchange="checkQuestionValue96('looking_children','looking_children_other')">
                                                        <option value="<?= $social_economic['looking_children'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['looking_children'] == 1) {
                                                                                                                            echo 'Adult relatives/Household members';
                                                                                                                        } elseif ($social_economic['looking_children'] == 2) {
                                                                                                                            echo 'Other older children';
                                                                                                                        } elseif ($social_economic['looking_children'] == 3) {
                                                                                                                            echo 'Neighbour';
                                                                                                                        } elseif ($social_economic['looking_children'] == 4) {
                                                                                                                            echo 'Maid';
                                                                                                                        } elseif ($social_economic['looking_children'] == 96) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Adult relatives/Household members</option>
                                                        <option value="2">Other older children</option>
                                                        <option value="3">Neighbouro</option>
                                                        <option value="4">Maid</option>
                                                        <option value="96">Other</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 hidden" id="looking_children_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify?</label>
                                                    <textarea name="looking_children_other" rows="4">
                                                        <?php if ($social_economic['looking_children_other']) {
                                                            print_r($social_economic['looking_children_other']);
                                                        }  ?>
                                                        </textarea>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>What type of work does the person
                                                        looking after the children do for their main
                                                        occupation??:</label>
                                                    <select name="occupation_looking_child" id="occupation_looking_child" style="width: 100%;" onchange="checkQuestionValue96('occupation_looking_child','occupation_looking_child_other')">
                                                        <option value="<?= $social_economic['occupation_looking_child'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['occupation_looking_child'] == 1) {
                                                                                                                                    echo 'Employed';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 2) {
                                                                                                                                    echo 'Self employed';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 3) {
                                                                                                                                    echo 'Unemployed';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 4) {
                                                                                                                                    echo 'Farmer';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 5) {
                                                                                                                                    echo 'Fisher';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 6) {
                                                                                                                                    echo 'Student';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 7) {
                                                                                                                                    echo 'Housewife';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 96) {
                                                                                                                                    echo 'Other';
                                                                                                                                } elseif ($social_economic['occupation_looking_child'] == 9) {
                                                                                                                                    echo 'Don’t know';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                        <option value="1">Employed</option>
                                                        <option value="2">Self employed</option>
                                                        <option value="3">Unemployed</option>
                                                        <option value="4">Farmer</option>
                                                        <option value="5">Fisher</option>
                                                        <option value="6">Student</option>
                                                        <option value="7">Housewife</option>
                                                        <option value="96">Other</option>
                                                        <option value="9">Don’t know</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 hidden" id="occupation_looking_child_other" >
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="occupation_looking_child_other" rows="4">
                                                        <?php if ($social_economic['occupation_looking_child_other']) {
                                                            print_r($social_economic['occupation_looking_child_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Did anyone accompany you today?</label>
                                                    <select name="accompany" id="accompany" style="width: 100%;" onchange="checkQuestionValue1('accompany','accompany_occupation1')">
                                                        <option value="<?= $social_economic['accompany'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['accompany'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['accompany'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span>IF no, skip next question</span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="hidden" id="accompany_occupation1">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>What is the main occupation of the person
                                                            you came with today?:</label>
                                                        <select name="accompany_occupation" id="accompany_occupation" style="width: 100%;" onchange="checkQuestionValue96('accompany_occupation','accompany_occupation_other')">
                                                            <option value="<?= $social_economic['accompany_occupation'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['accompany_occupation'] == 1) {
                                                                                                                                    echo 'Employed';
                                                                                                                                } elseif ($social_economic['accompany_occupation'] == 2) {
                                                                                                                                    echo 'Self employed';
                                                                                                                                } elseif ($social_economic['accompany_occupation'] == 3) {
                                                                                                                                    echo 'Unemployed';
                                                                                                                                } elseif ($social_economic['accompany_occupation'] == 4) {
                                                                                                                                    echo 'Farmer';
                                                                                                                                } elseif ($social_economic['accompany_occupation'] == 5) {
                                                                                                                                    echo 'Fisher';
                                                                                                                                } elseif ($social_economic['accompany_occupation'] == 6) {
                                                                                                                                    echo 'Student';
                                                                                                                                } elseif ($social_economic['accompany_occupation'] == 7) {
                                                                                                                                    echo 'Housewife';
                                                                                                                                } elseif ($social_economic['accompany_occupation'] == 96) {
                                                                                                                                    echo 'Other';
                                                                                                                                } elseif ($social_economic['accompany_occupation'] == 9) {
                                                                                                                                    echo 'Don’t know';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                            <option value="1">Employed</option>
                                                            <option value="2">Self employed</option>
                                                            <option value="3">Unemployed</option>
                                                            <option value="4">Farmer</option>
                                                            <option value="5">Fisher</option>
                                                            <option value="6">Student</option>
                                                            <option value="7">Housewife</option>
                                                            <option value="96">Other</option>
                                                            <option value="9">Don’t know</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 hidden" id="accompany_occupation_other">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Specify</label>
                                                        <textarea name="accompany_occupation_other" rows="4">
                                                            <?php if ($social_economic['accompany_occupation_other']) {
                                                                print_r($social_economic['accompany_occupation_other']);
                                                            }  ?>
                                                            </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>How much was spent on transport for the
                                                            person to accompany you today?</label>
                                                        <input type="text" name="accompany_transport" id="accompany_transport" value="<?php if ($social_economic['accompany_transport']) {
                                                                                                                                            print_r($social_economic['accompany_transport']);
                                                                                                                                        }  ?>" />
                                                        <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="row-form clearfix">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>What other expenses have they made to
                                                            accompany you today? (for example food,
                                                            child care)</label>
                                                        <input type="text" name="accompany_expenses" id="accompany_expenses" value="<?php if ($social_economic['accompany_expenses']) {
                                                                                                                                        print_r($social_economic['accompany_expenses']);
                                                                                                                                    }  ?>" />
                                                        <span>Amount in shillings (write 0 if none, 99 if Don’t know ) </span>

                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div>
                                    </div>


                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>What is the highest level of education of ? </h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>The patient?:</label>
                                                    <select name="patient_education" id="patient_education" style="width: 100%;" onchange="checkQuestionValue96('patient_education','patient_education_other')">
                                                        <option value="<?= $social_economic['patient_education'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['patient_education'] == 1) {
                                                                                                                                    echo 'Not attended/illiterate ';
                                                                                                                                } elseif ($social_economic['patient_education'] == 2) {
                                                                                                                                    echo 'Primaryr';
                                                                                                                                } elseif ($social_economic['patient_education'] == 3) {
                                                                                                                                    echo 'Secondary';
                                                                                                                                } elseif ($social_economic['patient_education'] == 4) {
                                                                                                                                    echo 'Graduate/certificate';
                                                                                                                                } elseif ($social_economic['patient_education'] == 96) {
                                                                                                                                    echo 'Other';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                        <option value="1">Not attended/illiterate </option>
                                                        <option value="2">Primary</option>
                                                        <option value="3">Secondary</option>
                                                        <option value="4">Graduate/certificate</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 hidden" id="patient_education_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="patient_education_other"  value="<?php if ($social_economic['patient_education_other']) {
                                                                                                                                                        print_r($social_economic['patient_education_other']);
                                                                                                                                                    }  ?>" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Primary income earner?:</label>
                                                    <select name="primary_earner_edctn" id="primary_earner_edctn" style="width: 100%;" onchange="checkQuestionValue96('primary_earner_edctn','earner_edctn_other')">
                                                        <option value="<?= $social_economic['primary_earner_edctn'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['primary_earner_edctn'] == 1) {
                                                                                                                                    echo 'Not attended/illiterate ';
                                                                                                                                } elseif ($social_economic['primary_earner_edctn'] == 2) {
                                                                                                                                    echo 'Primaryr';
                                                                                                                                } elseif ($social_economic['primary_earner_edctn'] == 3) {
                                                                                                                                    echo 'Secondary';
                                                                                                                                } elseif ($social_economic['primary_earner_edctn'] == 4) {
                                                                                                                                    echo 'Graduate/certificate';
                                                                                                                                } elseif ($social_economic['primary_earner_edctn'] == 96) {
                                                                                                                                    echo 'Other';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                        <option value="1">Not attended/illiterate </option>
                                                        <option value="2">Primary</option>
                                                        <option value="3">Secondary</option>
                                                        <option value="4">Graduate/certificate</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 hidden" id="earner_edctn_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="earner_edctn_other"  value="<?php if ($social_economic['earner_edctn_other']) {
                                                                                                                                                        print_r($social_economic['earner_edctn_other']);
                                                                                                                                                    }  ?>" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Head of household?:</label>
                                                    <select name="household_education" id="household_education" style="width: 100%;" onchange="checkQuestionValue96('household_education','household_education_other')">
                                                        <option value="<?= $social_economic['household_education'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['household_education'] == 1) {
                                                                                                                                    echo 'Not attended/illiterate ';
                                                                                                                                } elseif ($social_economic['household_education'] == 2) {
                                                                                                                                    echo 'Primaryr';
                                                                                                                                } elseif ($social_economic['household_education'] == 3) {
                                                                                                                                    echo 'Secondary';
                                                                                                                                } elseif ($social_economic['household_education'] == 4) {
                                                                                                                                    echo 'Graduate/certificate';
                                                                                                                                } elseif ($social_economic['household_education'] == 96) {
                                                                                                                                    echo 'Other';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                        <option value="1">Not attended/illiterate </option>
                                                        <option value="2">Primary</option>
                                                        <option value="3">Secondary</option>
                                                        <option value="4">Graduate/certificate</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                    <span>primary income earner = head of household.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 hidden" id="household_education_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="household_education_other"  value="<?php if ($social_economic['household_education_other']) {
                                                                                                                                                        print_r($social_economic['household_education_other']);
                                                                                                                                                    }  ?>" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Spouse of the head of household?:</label>
                                                    <select name="spouse_edctn" id="spouse_edctn" style="width: 100%;" onchange="checkQuestionValue96('spouse_edctn','spouse_edctn_other')">
                                                        <option value="<?= $social_economic['spouse_edctn'] ?>"><?php if ($social_economic) {
                                                                                                                                if ($social_economic['spouse_edctn'] == 1) {
                                                                                                                                    echo 'Not attended/illiterate ';
                                                                                                                                } elseif ($social_economic['spouse_edctn'] == 2) {
                                                                                                                                    echo 'Primaryr';
                                                                                                                                } elseif ($social_economic['spouse_edctn'] == 3) {
                                                                                                                                    echo 'Secondary';
                                                                                                                                } elseif ($social_economic['spouse_edctn'] == 4) {
                                                                                                                                    echo 'Graduate/certificate';
                                                                                                                                } elseif ($social_economic['spouse_edctn'] == 96) {
                                                                                                                                    echo 'Other';
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                echo 'Select';
                                                                                                                            } ?></option>
                                                        <option value="1">Not attended/illiterate </option>
                                                        <option value="2">Primary</option>
                                                        <option value="3">Secondary</option>
                                                        <option value="4">Graduate/certificate</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                    <span>If more than one spouse, choose highest level of education.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 hidden" id="spouse_edctn_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Other Specify</label>
                                                    <input type="text" name="spouse_edctn_other"  value="<?php if ($social_economic['spouse_edctn_other']) {
                                                                                                                                                        print_r($social_economic['spouse_edctn_other']);
                                                                                                                                                    }  ?>" required />

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>DHS Questions </h1>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Main material of the floor::</label>
                                                    <select name="material_floor" id="material_floor" style="width: 100%;" onchange="checkQuestionValue96('material_floor','material_floor_other')">
                                                        <option value="<?= $social_economic['material_floor'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['material_floor'] == 1) {
                                                                                                                            echo 'Earth/ sand/dung ';
                                                                                                                        } elseif ($social_economic['material_floor'] == 2) {
                                                                                                                            echo 'Concrete cement';
                                                                                                                        } elseif ($social_economic['material_floor'] == 96) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Earth/ sand/dung</option>
                                                        <option value="2">Concrete cement</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 hidden" id="material_floor_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="material_floor_other" rows="4">
                                                        <?php if ($social_economic['material_floor_other']) {
                                                            print_r($social_economic['material_floor_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Main material of the Roof:</label>
                                                    <select name="material_roof" id="material_roof" style="width: 100%;" onchange="checkQuestionValue96('material_roof','material_roof_other')">
                                                        <option value="<?= $social_economic['material_roof'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['material_roof'] == 1) {
                                                                                                                            echo 'Thatch/ palm ';
                                                                                                                        } elseif ($social_economic['material_roof'] == 96) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Thatch/ palm</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 hidden" id="material_roof_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="material_roof_other" rows="4">
                                                        <?php if ($social_economic['material_roof_other']) {
                                                            print_r($social_economic['material_roof_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Main type of cooking fuel:</label>
                                                    <select name="cooking_fuel" id="cooking_fuel" style="width: 100%;" onchange="checkQuestionValue96('cooking_fuel','cooking_fuel_other')">
                                                        <option value="<?= $social_economic['cooking_fuel'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['cooking_fuel'] == 1) {
                                                                                                                        echo 'Electricity ';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 2) {
                                                                                                                        echo 'LPG/ natural gas/ biogas';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 3) {
                                                                                                                        echo 'Kerosene ';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 4) {
                                                                                                                        echo 'coal/lignite/ charcoal';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 5) {
                                                                                                                        echo 'wood/ straw/shrub/grass/agricultural crop animal dung ';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 6) {
                                                                                                                        echo 'no food cooked';
                                                                                                                    } elseif ($social_economic['cooking_fuel'] == 96) {
                                                                                                                        echo 'Other';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Electricity</option>
                                                        <option value="2">LPG/ natural gas/ biogas</option>
                                                        <option value="3">Kerosene </option>
                                                        <option value="4">coal/lignite/ charcoal</option>
                                                        <option value="5">wood/ straw/shrub/grass/agricultural crop animal dung</option>
                                                        <option value="6">no food cooked</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 hidden" id="cooking_fuel_other">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="cooking_fuel_other" rows="4">
                                                        <?php if ($social_economic['cooking_fuel_other']) {
                                                            print_r($social_economic['cooking_fuel_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Water Access:?</label>
                                                    <select name="water_access" id="water_access" style="width: 100%;" onchange="checkQuestionValue1('water_access','water_source1')">
                                                        <option value="<?= $social_economic['water_access'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['water_access'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['water_access'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span>IF no, skip next question</span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 hidden" id="water_source1">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Drinking water source:?</label>
                                                    <select name="water_source" id="water_source" style="width: 100%;" onchange="checkQuestionValue96('water_source','water_source_other')">
                                                        <option value="<?= $social_economic['water_source'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['water_source'] == 1) {
                                                                                                                        echo 'Piped into dwelling';
                                                                                                                    } elseif ($social_economic['water_source'] == 2) {
                                                                                                                        echo 'Piped to neighbor ';
                                                                                                                    } elseif ($social_economic['water_source'] == 3) {
                                                                                                                        echo 'Tube well or borehole';
                                                                                                                    } elseif ($social_economic['water_source'] == 4) {
                                                                                                                        echo 'Unprotected well';
                                                                                                                    } elseif ($social_economic['water_source'] == 5) {
                                                                                                                        echo 'Unprotected spring';
                                                                                                                    } elseif ($social_economic['water_source'] == 6) {
                                                                                                                        echo 'Surface water';
                                                                                                                    } elseif ($social_economic['water_source'] == 7) {
                                                                                                                        echo 'Piped to yard/plot';
                                                                                                                    } elseif ($social_economic['water_source'] == 8) {
                                                                                                                        echo 'Public tap or standpipe ';
                                                                                                                    } elseif ($social_economic['water_source'] == 9) {
                                                                                                                        echo 'Protected well';
                                                                                                                    } elseif ($social_economic['water_source'] == 10) {
                                                                                                                        echo 'Protected spring';
                                                                                                                    } elseif ($social_economic['water_source'] == 11) {
                                                                                                                        echo 'Rainwater';
                                                                                                                    } elseif ($social_economic['water_source'] == 96) {
                                                                                                                        echo 'Other';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Piped into dwelling</option>
                                                        <option value="2">Piped to neighbor</option>
                                                        <option value="3">Tube well or borehole</option>
                                                        <option value="4">Unprotected well </option>
                                                        <option value="5">Unprotected spring</option>
                                                        <option value="6">Surface water</option>
                                                        <option value="7">Piped to yard/plot</option>
                                                        <option value="8">Public tap or standpipe</option>
                                                        <option value="9">Protected well</option>
                                                        <option value="10">Protected spring</option>
                                                        <option value="11">Rainwater</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 hidden" id="water_source_other" >
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="water_source_other" rows="4">
                                                        <?php if ($social_economic['water_source_other']) {
                                                            print_r($social_economic['water_source_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Toilet Access:?</label>
                                                    <select name="toilet_access" id="toilet_access" style="width: 100%;" onchange="checkQuestionValue1('toilet_access','toilet_facility1')">
                                                        <option value="<?= $social_economic['toilet_access'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['toilet_access'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } elseif ($social_economic['toilet_access'] == 2) {
                                                                                                                            echo 'No';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                    <span>IF no, skip next question</span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 hidden" id="toilet_facility1">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Type of Toilet facility?</label>
                                                    <select name="toilet_facility" id="toilet_facility" style="width: 100%;" onchange="checkQuestionValue96('toilet_facility','toilet_access_other')">
                                                        <option value="<?= $social_economic['toilet_facility'] ?>"><?php if ($social_economic) {
                                                                                                                        if ($social_economic['toilet_facility'] == 1) {
                                                                                                                            echo 'Flush to pit latrine';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 2) {
                                                                                                                            echo 'Flush to somewhere else';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 3) {
                                                                                                                            echo 'Flush, don’t know where';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 4) {
                                                                                                                            echo 'Ventilated improved pit latrine';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 5) {
                                                                                                                            echo 'Pit latrine with slab';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 6) {
                                                                                                                            echo 'Pit latrine without slab/open pit';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 7) {
                                                                                                                            echo 'Composting toilet ';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 8) {
                                                                                                                            echo 'Hanging toilet';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 9) {
                                                                                                                            echo 'Bucket toilet';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 10) {
                                                                                                                            echo 'No facilities';
                                                                                                                        } elseif ($social_economic['toilet_facility'] == 96) {
                                                                                                                            echo 'Other';
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo 'Select';
                                                                                                                    } ?></option>
                                                        <option value="1">Flush to pit latrine</option>
                                                        <option value="2">Flush to somewhere else</option>
                                                        <option value="3">Flush, don’t know where</option>
                                                        <option value="4">Ventilated improved pit latrine</option>
                                                        <option value="5">Pit latrine with slab</option>
                                                        <option value="6">Pit latrine without slab/open pit</option>
                                                        <option value="7">Composting toilet </option>
                                                        <option value="8">Hanging toilet</option>
                                                        <option value="9">Bucket toilet</option>
                                                        <option value="10">No facilities</option>
                                                        <option value="96">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 hidden" id="toilet_access_other" >
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Specify</label>
                                                    <textarea name="toilet_access_other" rows="4">
                                                        <?php if ($social_economic['toilet_access_other']) {
                                                            print_r($social_economic['toilet_access_other']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="head clearfix">
                                        <div class="isw-ok"></div>
                                        <h1>Does your household have </h1>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Television?</label>
                                                    <select name="television" id="television" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['television'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['television'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['television'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Refrigerator?</label>
                                                    <select name="refrigerator" id="refrigerator" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['refrigerator'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['refrigerator'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['refrigerator'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Sofa ?</label>
                                                    <select name="sofa" id="sofa" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['sofa'] ?>"><?php if ($social_economic) {
                                                                                                            if ($social_economic['sofa'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($social_economic['sofa'] == 2) {
                                                                                                                echo 'No';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Clock:?</label>
                                                    <select name="clock" id="clock" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['clock'] ?>"><?php if ($social_economic) {
                                                                                                                if ($social_economic['clock'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($social_economic['clock'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Fan ?</label>
                                                    <select name="fan" id="fan" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['fan'] ?>"><?php if ($social_economic) {
                                                                                                            if ($social_economic['fan'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($social_economic['fan'] == 2) {
                                                                                                                echo 'No';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>VCR/DVD?</label>
                                                    <select name="vcr_dvd" id="vcr_dvd" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['vcr_dvd'] ?>"><?php if ($social_economic) {
                                                                                                                if ($social_economic['vcr_dvd'] == 1) {
                                                                                                                    echo 'Yes';
                                                                                                                } elseif ($social_economic['vcr_dvd'] == 2) {
                                                                                                                    echo 'No';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Does any member of this household have a bank account? </label>
                                                    <select name="bank_account" id="bank_account" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['bank_account'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['bank_account'] == 1) {
                                                                                                                        echo 'Yes';
                                                                                                                    } elseif ($social_economic['bank_account'] == 2) {
                                                                                                                        echo 'No';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Was there ever no food at all in your household because there were not enough resources to get more?</label>
                                                    <select name="no_food" id="no_food" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['no_food'] ?>"><?php if ($social_economic) {
                                                                                                                if ($social_economic['no_food'] == 1) {
                                                                                                                    echo 'never, rarely (once or twice)';
                                                                                                                } elseif ($social_economic['no_food'] == 2) {
                                                                                                                    echo 'sometimes (3-10 times)';
                                                                                                                } elseif ($social_economic['no_food'] == 3) {
                                                                                                                    echo 'often (>10 times)';
                                                                                                                }
                                                                                                            } else {
                                                                                                                echo 'Select';
                                                                                                            } ?></option>
                                                        <option value="1">never, rarely (once or twice)</option>
                                                        <option value="2">sometimes (3-10 times)</option>
                                                        <option value="3">often (>10 times)</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Did you or any household member go to sleep at night hungry because there was not enough food?</label>
                                                    <select name="sleep_hungry" id="sleep_hungry" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['sleep_hungry'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['sleep_hungry'] == 1) {
                                                                                                                        echo 'never, rarely (once or twice)';
                                                                                                                    } elseif ($social_economic['sleep_hungry'] == 2) {
                                                                                                                        echo 'sometimes (3-10 times)';
                                                                                                                    } elseif ($social_economic['sleep_hungry'] == 3) {
                                                                                                                        echo 'often (>10 times)';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">never, rarely (once or twice)</option>
                                                        <option value="2">sometimes (3-10 times)</option>
                                                        <option value="3">often (>10 times)</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row-form clearfix">
                                                <!-- s8elect -->
                                                <div class="form-group">
                                                    <label>Did you or any household member go a whole day without eating anything because there was not enough food?</label>
                                                    <select name="day_hungry" id="day_hungry" style="width: 100%;" required>
                                                        <option value="<?= $social_economic['day_hungry'] ?>"><?php if ($social_economic) {
                                                                                                                    if ($social_economic['day_hungry'] == 1) {
                                                                                                                        echo 'never, rarely (once or twice)';
                                                                                                                    } elseif ($social_economic['day_hungry'] == 2) {
                                                                                                                        echo 'sometimes (3-10 times)';
                                                                                                                    } elseif ($social_economic['day_hungry'] == 3) {
                                                                                                                        echo 'often (>10 times)';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?></option>
                                                        <option value="1">never, rarely (once or twice)</option>
                                                        <option value="2">sometimes (3-10 times)</option>
                                                        <option value="3">often (>10 times)</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row-form clearfix">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Notes on socioeconomic situation & needs: </label>
                                                    <textarea name="socioeconomic_notes" rows="4">
                                                        <?php if ($social_economic['socioeconomic_notes']) {
                                                            print_r($social_economic['socioeconomic_notes']);
                                                        }  ?>
                                                        </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                <?php if ($user->data()->position == 1 || $user->data()->position == 3 || $user->data()->position == 4 || $user->data()->position == 5) { ?>
                                    <div class="footer tar">
                                        <input type="submit" name="add_social_economic" value="Submit" class="btn btn-default">
                                    </div>
                                <?php } ?>

                                </form>
                            </div>

                        </div>

                    <?php } elseif ($_GET['id'] == 27) { ?>

                    <?php } ?> <div class="dr"><span></span></div>
                </div>

            </div>
        </div>
    </div>


    <script>
        <?php if ($user->data()->pswd == 0) { ?>
            $(window).on('load', function() {
                $("#change_password_n").modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            });
        <?php } ?>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        function checkQuestionValue1(currentQuestion, elementToHide) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide = document.getElementById(elementToHide);

            var questionValue = currentQuestionInput.value;

            if (questionValue === "1") {
                elementToHide.classList.remove("hidden");
            } else {
                elementToHide.classList.add("hidden");
            }
        }

        function checkQuestionValue21(currentQuestion, elementToHide) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide = document.getElementById(elementToHide);

            var questionValue = currentQuestionInput.value;

            if (questionValue === "2") {
                elementToHide.classList.remove("hidden");
            } else {
                elementToHide.classList.add("hidden");
            }
        }

        function checkQuestionValue2(currentQuestion, elementToHide1, elementToHide2) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide1 = document.getElementById(elementToHide1);
            var elementToHide2 = document.getElementById(elementToHide2);

            var questionValue = currentQuestionInput.value;

            if (questionValue === "1") {
                elementToHide1.classList.remove("hidden");
            } else if (questionValue === "2") {
                elementToHide2.classList.remove("hidden");

            } else {
                elementToHide1.classList.add("hidden");
                elementToHide2.classList.add("hidden");

            }
        }

        function check2QuestionValue2(currentQuestion, elementToHide1, elementToHide2) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide1 = document.getElementById(elementToHide1);
            var elementToHide2 = document.getElementById(elementToHide2);

            var questionValue = currentQuestionInput.value;

            if (questionValue === "1") {
                elementToHide1.classList.remove("hidden");
                elementToHide2.classList.remove("hidden");
            } else if (questionValue === "2") {
                elementToHide1.classList.remove("hidden");
            } else {
                elementToHide1.classList.add("hidden");
                elementToHide2.classList.add("hidden");

            }
        }

        function checkNotQuestionValue3(currentQuestion, elementToHide1, elementToHide2) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide1 = document.getElementById(elementToHide1);
            var elementToHide2 = document.getElementById(elementToHide2);

            var questionValue = currentQuestionInput.value;

            if (questionValue != "3") {
                elementToHide1.classList.remove("hidden");
                elementToHide2.classList.remove("hidden");

            } else {
                elementToHide1.classList.add("hidden");
                elementToHide2.classList.add("hidden");
            }
        }

        function checkNot1QuestionValue3(currentQuestion, elementToHide) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide = document.getElementById(elementToHide1);
            var questionValue = currentQuestionInput.value;

            if (questionValue != "3") {
                elementToHide.classList.remove("hidden");
            } else {
                elementToHide.classList.add("hidden");
            }
        }

        function checkNotQuestionValue5(currentQuestion, elementToHide) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide = document.getElementById(elementToHide);

            var questionValue = currentQuestionInput.value;
            if (questionValue != "5") {
                elementToHide.classList.remove("hidden");
            } else {
                elementToHide.classList.add("hidden");
            }
        }

                function checkNotQuestionValue4(currentQuestion, elementToHide) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide = document.getElementById(elementToHide);

            var questionValue = currentQuestionInput.value;
            if (questionValue != "4") {
                elementToHide.classList.remove("hidden");
            } else {
                elementToHide.classList.add("hidden");
            }
        }

        function checkQuestionValue96(currentQuestion, elementToHide) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide = document.getElementById(elementToHide);

            var questionValue = currentQuestionInput.value;

            if (questionValue === "96") {
                elementToHide.classList.remove("hidden");
            } else {
                elementToHide.classList.add("hidden");
            }
        }

        function checkQuestionValue3(currentQuestion, elementToHide1, elementToHide2) {
            var currentQuestionInput = document.getElementById(currentQuestion);
            var elementToHide1 = document.getElementById(elementToHide1);
            var elementToHide2 = document.getElementById(elementToHide2);

            var questionValue = currentQuestionInput.value;

            if (questionValue === "1") {
                elementToHide1.classList.remove("hidden");
                elementToHide2.classList.remove("hidden");

            } else {
                elementToHide1.classList.add("hidden");
                elementToHide2.classList.add("hidden");

            }
        }


        function autocomplete(inp, arr) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function(e) {
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) {
                    return false;
                }
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                this.parentNode.appendChild(a);
                /*for each item in the array...*/
                for (i = 0; i < arr.length; i++) {
                    /*check if the item starts with the same letters as the text field value:*/
                    if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                        /*create a DIV element for each matching element:*/
                        b = document.createElement("DIV");
                        /*make the matching letters bold:*/
                        b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                        b.innerHTML += arr[i].substr(val.length);
                        /*insert a input field that will hold the current array item's value:*/
                        b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                        /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function(e) {
                            /*insert the value for the autocomplete text field:*/
                            inp.value = this.getElementsByTagName("input")[0].value;
                            /*close the list of autocompleted values,
                            (or any other open lists of autocompleted values:*/
                            closeAllLists();
                        });
                        a.appendChild(b);
                    }
                }
            });
            /*execute a function presses a key on the keyboard:*/
            inp.addEventListener("keydown", function(e) {
                var x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                    /*If the arrow DOWN key is pressed,
                    increase the currentFocus variable:*/
                    currentFocus++;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 38) { //up
                    /*If the arrow UP key is pressed,
                    decrease the currentFocus variable:*/
                    currentFocus--;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 13) {
                    /*If the ENTER key is pressed, prevent the form from being submitted,*/
                    e.preventDefault();
                    if (currentFocus > -1) {
                        /*and simulate a click on the "active" item:*/
                        if (x) x[currentFocus].click();
                    }
                }
            });

            function addActive(x) {
                /*a function to classify an item as "active":*/
                if (!x) return false;
                /*start by removing the "active" class on all items:*/
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                /*add class "autocomplete-active":*/
                x[currentFocus].classList.add("autocomplete-active");
            }

            function removeActive(x) {
                /*a function to remove the "active" class from all autocomplete items:*/
                for (var i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }

            function closeAllLists(elmnt) {
                /*close all autocomplete lists in the document,
                except the one passed as an argument:*/
                var x = document.getElementsByClassName("autocomplete-items");
                for (var i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }
            /*execute a function when someone clicks in the document:*/
            document.addEventListener("click", function(e) {
                closeAllLists(e.target);
            });
        }

        function fetchData() {

            /*An array containing all the country names in the world:*/
            // var countries = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Anguilla", "Antigua & Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia & Herzegovina", "Botswana", "Brazil", "British Virgin Islands", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central Arfrican Republic", "Chad", "Chile", "China", "Colombia", "Congo", "Cook Islands", "Costa Rica", "Cote D Ivoire", "Croatia", "Cuba", "Curacao", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "France", "French Polynesia", "French West Indies", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauro", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russia", "Rwanda", "Saint Pierre & Miquelon", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "St Kitts & Nevis", "St Lucia", "St Vincent", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor L'Este", "Togo", "Tonga", "Trinidad & Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks & Caicos", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Virgin Islands (US)", "Yemen", "Zambia", "Zimbabwe"];
            // var getUid = $(this).val();
            fetch('fetch_medications.php')
                .then(response => response.json())
                .then(data => {
                    // Process the data received from the PHP script
                    // console.log(data);
                    autocomplete(document.getElementById("myInput"), data);
                })
                .catch(error => {
                    // Handle any errors that occurred during the fetch request
                    console.error('Error:', error);
                });

                        fetch('fetch_firstname.php')
            .then(response => response.json())
            .then(data => {
                // Process the data received from the PHP script
                // console.log(data);
                autocomplete(document.getElementById("firstname"), data);
            })
            .catch(error => {
                // Handle any errors that occurred during the fetch request
                console.error('Error:', error);
            });

        fetch('fetch_middlename.php')
            .then(response => response.json())
            .then(data => {
                // Process the data received from the PHP script
                // console.log(data);
                autocomplete(document.getElementById("middlename"), data);
            })
            .catch(error => {
                // Handle any errors that occurred during the fetch request
                console.error('Error:', error);
            });


        fetch('fetch_lastname.php')
            .then(response => response.json())
            .then(data => {
                // Process the data received from the PHP script
                autocomplete(document.getElementById("lastname"), data);
            })
            .catch(error => {
                // Handle any errors that occurred during the fetch request
                console.error('Error:', error);
            });
        

     
     
            $(document).ready(function() {

            $("#add_crf6").click(function(e) {
                // if ($("#validation")[0].checkValidity()) {
                //   PREVENT PAGE TO REFRESH
                // e.preventDefault();



                // if($("#FDATE").val() == ''){
                //     $("#FDATEError").text('* Date is empty');
                // };
                // if($("#cDATE").val() == ''){
                //     $("#cDATEError").text('* Date is empty');
                // };
                // if($("#cpersid").val() == ''){
                //     $("#cpersidError").text('* NAME is empty');
                // };


                if ($("#renal_urea").val() == '') {
                    $("#renal_ureaError").text('* Renal Urea is empty');
                };

                if ($("#renal_urea_units").val() == '') {
                    $("#renal_urea_unitsError").text('* Renal Urea Units is empty');
                };

                // if ($("#password1").val() != $("#password2").val()) {
                //     $("#passError").text('* Passowrd do not match');
                //     //console.log("Not matched"); 
                //     $("#register-btn").val('Sign Up');
                // }
                // }
            });

            $('#weight, #height').on('input', function() {
                setTimeout(function() {
                    var weight = $('#weight').val();
                    var height = $('#height').val() / 100; // Convert cm to m
                    var bmi = weight / (height * height);
                    $('#bmi').text(bmi.toFixed(2));
                }, 1);
            });

            $("#one").on("input", null, null, function(e) {
                if ($("#one").val().length == 2) {
                    setTimeout(function() {
                        $("#two").focus();
                    }, 1);
                }
            });
            $("#three").click(function() {
                $("#four").focus();
            });
            $("#five").on("input", null, null, function() {
                if ($("#five").val().length == 2) {
                    $("#six").val("It works!");
                }
            });


            $('#fl_wait').hide();
            $('#wait_ds').hide();
            $('#region').change(function() {
                var getUid = $(this).val();
                $('#wait_ds').show();
                $.ajax({
                    url: "process.php?cnt=region",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#ds_data').html(data);
                        $('#wait_ds').hide();
                    }
                });
            });
            $('#wait_wd').hide();
            $('#ds_data').change(function() {
                $('#wait_wd').hide();
                var getUid = $(this).val();
                $.ajax({
                    url: "process.php?cnt=district",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#wd_data').html(data);
                        $('#wait_wd').hide();
                    }
                });

            });

            $('#a_cc').change(function() {
                var getUid = $(this).val();
                $('#wait').show();
                $.ajax({
                    url: "process.php?cnt=payAc",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#cus_acc').html(data);
                        $('#wait').hide();
                    }
                });

            });

            $('#study_id').change(function() {
                var getUid = $(this).val();
                var type = $('#type').val();
                $('#fl_wait').show();
                $.ajax({
                    url: "process.php?cnt=study",
                    method: "GET",
                    data: {
                        getUid: getUid,
                        type: type
                    },

                    success: function(data) {
                        console.log(data);
                        $('#s2_2').html(data);
                        $('#fl_wait').hide();
                    }
                });

            });

        });
        }

        // function fetchData() {
        //     // Clear previous search results
        //     // document.getElementById("searchResults").innerHTML = "";
        //     // document.getElementById("myInput").innerHTML = "";


        //     // Get the search input value
        //     // var searchInput = document.getElementById("searchInput").value;
        //     var searchInput = document.getElementById("myInput").value;


        //     // Fetch data from the server
        //     // fetch("search.php?query=" + searchInput)
        //     fetch("fetch_medications.php")

        //         .then(response => response.json())
        //         .then(data => {
        //             // Process the fetched data
        //             // data.forEach(result => {
        //             //     // Create a list item for each result
        //             //     var li = document.createElement("li");
        //             //     li.textContent = result;
        //             //     document.getElementById("searchResults").appendChild(li);
        //             // });
        //             // var searchInput = document.getElementById("myInput").value;

        //             // console.log(data);
        //         })
        //         .catch(error => console.error(error));
        // }


        $('#weight, #height').on('input', function() {
            setTimeout(function() {
                var weight = $('#weight').val();
                var height = $('#height').val() / 100; // Convert cm to m
                var bmi = weight / (height * height);
                $('#bmi').text(bmi.toFixed(2));
            }, 1);
        });

        $(document).ready(function() {
            $('#fl_wait').hide();
            $('#wait_ds').hide();
            $('#region').change(function() {
                var getUid = $(this).val();
                $('#wait_ds').show();
                $.ajax({
                    url: "process.php?cnt=region",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#ds_data').html(data);
                        $('#wait_ds').hide();
                    }
                });
            });
            $('#wait_wd').hide();
            $('#ds_data').change(function() {
                $('#wait_wd').hide();
                var getUid = $(this).val();
                $.ajax({
                    url: "process.php?cnt=district",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#wd_data').html(data);
                        $('#wait_wd').hide();
                    }
                });

            });

            $('#a_cc').change(function() {
                var getUid = $(this).val();
                $('#wait').show();
                $.ajax({
                    url: "process.php?cnt=payAc",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#cus_acc').html(data);
                        $('#wait').hide();
                    }
                });

            });


            $('#study_id').change(function() {
                var getUid = $(this).val();
                var type = $('#type').val();
                $('#fl_wait').show();
                $.ajax({
                    url: "process.php?cnt=study",
                    method: "GET",
                    data: {
                        getUid: getUid,
                        type: type
                    },

                    success: function(data) {
                        console.log(data);
                        $('#s2_2').html(data);
                        $('#fl_wait').hide();
                    }
                });

            });

        });

        // Add row chemotherapy
        document.getElementById("add-medication").addEventListener("click", function() {
            var table = document.getElementById("medication_list").getElementsByTagName("tbody")[0];
            var newRow = table.insertRow(table.rows.length);
            var medication_type = newRow.insertCell(0);
            var medication_action = newRow.insertCell(1);
            var medication_dose = newRow.insertCell(2);
            var actionCell = newRow.insertCell(3);
            medication_type.innerHTML = '<input class="autocomplete" type="text" name="medication_type[]" id="myInput" placeholder="Type medications name..." onkeyup="fetchData()">';
            medication_action.innerHTML = '<select name="medication_action[]" id="medication_action[]" style="width: 100%;"><option value="">Select</option><option value="1">Continue</option><option value="2">Start</option><option value="3">Stop</option><option value="4">Not Eligible</option></select>';
            medication_dose.innerHTML = '<input type="text" name="medication_dose[]">';
            actionCell.innerHTML = '<button type="button" class="remove-row">Remove</button>';
            // console.log(medication_type);

        });

        // Add row chemotherapy
        document.getElementById("add-hospitalization-details").addEventListener("click", function() {
            var table = document.getElementById("hospitalization_details_table").getElementsByTagName("tbody")[0];
            var newRow = table.insertRow(table.rows.length);
            var admission_date = newRow.insertCell(0);
            var admission_reason = newRow.insertCell(1);
            var discharge_diagnosis = newRow.insertCell(2);
            var actionCell = newRow.insertCell(3);
            admission_date.innerHTML = '<input type="text" name="admission_date[]"><span>(Example: 2010-12-01)</span>';
            admission_reason.innerHTML = '<input type="text" name="admission_reason[]">';
            discharge_diagnosis.innerHTML = '<input type="text" name="discharge_diagnosis[]">';
            actionCell.innerHTML = '<button type="button" class="remove-row">Remove</button>';
        });


        // Add row surgery
        document.getElementById("add-sickle-cell-status").addEventListener("click", function() {
            var table = document.getElementById("sickle_cell_table").getElementsByTagName("tbody")[0];
            var newRow = table.insertRow(table.rows.length);
            var age = newRow.insertCell(0);
            var sex = newRow.insertCell(1);
            var status = newRow.insertCell(2);
            var actionCell = newRow.insertCell(3);
            age.innerHTML = '<input type="text" name="age[]">';
            sex.innerHTML = '<select name="sex[]" id="sex[]" style="width: 100%;"><option value="">Select</option><option value="1">Male</option><option value="2">Female</option></select>';
            status.innerHTML = '<input type="text" name="sickle_status[]">';
            actionCell.innerHTML = '<button type="button" class="remove-row">Remove</button>';
        });

        // Remove row
        document.addEventListener("click", function(e) {
            if (e.target && e.target.classList.contains("remove-row")) {
                var row = e.target.parentNode.parentNode;
                row.parentNode.removeChild(row);
            }
        });
    </script>
</body>

</html>