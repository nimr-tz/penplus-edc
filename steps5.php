<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Font -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600,200' rel='stylesheet' type='text/css'>

    <!-- Stylesheets -->
    <!-- <link rel="stylesheet" href="css/style.css"> -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript"></script>

    <!-- <script src="js/jquery.validate.js"></script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <title>Document</title>
    <style>
        html {
            height: 100%;
            /*
	background-color: #e3e9ee;
*/
        }

        html,
        body {
            font-family: 'Source Sans Pro', sans-serif;
            font-size: 14px;
            width: 100%;
        }

        a {
            color: #d91b5b;
            text-decoration: none;
            font-weight: 600;
            padding-bottom: 3px;
            -webkit-transition: all 0.2s;
            -moz-transition: all 0.2s;
            transition: all 0.2s;
        }

        a:hover {
            border-bottom: 1px solid;
        }

        .navbar-brand>img {
            max-width: 240px;
        }

        /* Form styles */
        #msform {
            width: 100%;
            margin: 50px auto;

            position: relative;
        }

        #msform fieldset {
            background: #fff;
            border: 0 none;
            border-radius: 5px;
            box-sizing: border-box;
            width: 100%;
            margin: 0 0% 20px;

            /*stacking fieldsets above each other*/
            position: relative;
        }

        /* Hide all except first fieldset */
        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        img.logo {
            max-width: 155px;
            margin-top: 5px;
        }

        #msform p {
            color: #8b9ab0;
            font-size: 12px;
        }

        #msform label {
            padding-right: 0px 15px;
            font-size: 16px;
            text-align: left;

            /*
	font-weight:600px !important;
*/
        }


        /* Inputs */
        #msform input,
        #msform textarea {
            padding: 5px 15px;
            border: 1px solid transparent;
            border-radius: 3px;
            margin-bottom: 10px;
            margin-top: 5px;
            background-color: #eef5ff;
            width: 100%;
            box-sizing: border-box;
            font-family: montserrat;
            color: #333;
            font-size: 14px;
            font-family: inherit;
        }

        #msform input:focus,
        #msform textarea:focus {
            outline: none;
            border-color: #7bbdf3;
        }

        /* Buttons */

        #msform .submitbutton {
            width: 30%;
            text-transform: uppercase;
            background: #d91b5b;
            font-weight: bold;
            color: white;
            border: 1px solid transparent;
            border-radius: 3px;
            cursor: pointer;
            padding: 12px 5px;
            margin: 10px 0;
            font-size: 16px;
            display: inline-block;
            -webkit-transition: all 0.2s;
            -moz-transition: all 0.2s;
            transition: all 0.2s;
        }

        #msform .action-button {
            width: 30%;
            text-transform: uppercase;
            background: #d91b5b;
            font-weight: bold;
            color: white;
            border: 1px solid transparent;
            border-radius: 3px;
            cursor: pointer;
            padding: 12px 5px;
            margin: 10px 0;
            font-size: 16px;
            display: inline-block;
            -webkit-transition: all 0.2s;
            -moz-transition: all 0.2s;
            transition: all 0.2s;
        }

        #msform .previous.action-button {
            background: #fff;
            border: 1px solid #7bbdf3;
            color: #7bbdf3;
        }

        #msform .action-button:hover,
        #msform .action-button:focus {
            box-shadow: 0 10px 30px 1px rgba(0, 0, 0, 0.2);
        }

        /* Headings */
        .fs-title {
            font-size: 20px;
            font-weight: 400;
            color: #a94442;
            margin-bottom: 20px;
            background-color: #9999CC;
            margin-top: 20px;
            padding: 5px;
            color: #fff;
        }

        .fs-subtitle {
            font-weight: 400;
            font-size: 19px;
            color: #434a54;
            margin-bottom: 20px;
        }

        /* Progressbar */
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            /*CSS counters to number the steps*/
            counter-reset: step;
        }

        #progressbar li {
            list-style-type: none;
            color: #8b9ab0;
            text-transform: uppercase;
            font-size: 9px;
            width: 25%;
            float: left;
            position: relative;
            text-align: center;
        }

        #progressbar li.active {
            color: #d91b5b;
        }

        #progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 20px;
            line-height: 20px;
            display: block;
            font-size: 10px;
            color: #333;
            background: white;
            border-radius: 3em;
            margin: 0 auto 5px auto;
            text-align: center;
        }

        /* Progressbar connectors */
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: white;
            position: absolute;
            left: -50%;
            top: 9px;
            z-index: -1;
        }

        #progressbar li:first-child:after {
            /* connector not needed before the first step */
            content: none;
        }

        /* Marking active/completed steps green */
        /*The number of the step and the connector before it = green*/
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #d91b5b;
            color: white;
        }

        /* css for checkbox */

        /* The container */
        #msform .checkstyle {
            display: inline-flex;
            position: relative;
            width: auto;
            padding-left: 35px;
            padding-right: 25px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 16px;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        #msform .checkstyle input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        #msform .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        #msform .checkstyle:hover input~.checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        #msform .checkstyle input:checked~.checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        #msform .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        #msform .checkstyle input:checked~.checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        #msform .checkstyle .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .terms_text a:hover {
            text-decoration: none !important;
        }

        .listordercls {
            line-height: 25px !important;
            font-size: 13px !important;
            list-style: none !important;
            padding-left: 0px !important;
        }

        /* Header */
        header {
            margin: 0;
            padding: 0;
            position: sticky;
            position: -webkit-sticky;
            top: 0;
            z-index: 999;
            border-bottom: 1px solid #00000024;
        }

        header .navbar {
            margin: 0;
            padding: 0;
            background-color: #fff;
            border-radius: 0px;
        }

        /* Footer */
        footer {
            margin: 0;
            padding: 55px 0 50px;
            float: left;
            width: 100%;
            text-align: center;
        }

        footer img {
            max-height: 60px;
            margin-bottom: 35px;
        }

        .footer-img2 {
            margin-left: auto;
            margin-right: auto;
            display: table;
            width: auto;
            float: none;
        }

        .footer-img3 {
            float: right;
        }

        footer p {
            margin-bottom: 2px;
            color: #272727;
            font-size: 13px;
        }

        footer a {
            color: #272727;
            font-size: 13px;
        }


        @media (max-width:480px) {

            .navbar-brand>img {
                max-width: 180px !important;
            }

            #enroltextcls {
                font-size: 14px !important;
            }

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12" style="padding-right:0px;padding-left:0px;">
                <div class="col-md-10 col-xs-12 col-md-offset-1">

                    <!-- multistep form -->
                    <form class="form-horizontal form" id="msform" method="POST" action="" enctype="multipart/form-data">
                        <!-- progressbar -->
                        <ul id="progressbar">
                            <li class="active">Step One</li>
                            <li>Step Two</li>
                            <li>Step Three</li>
                            <li>Final Step</li>
                        </ul>

                        <div id="sucessmsg" style="display:none;">
                            <h2 class="fs-subtitle" style="color: #dc3c52; font-size:22px; text-align:center;">Form Submitted Successfully</h2>
                        </div>
                        <div id="errormsg" style="display:none;">
                            <h2 class="fs-subtitle" style="color: #dc3c52; font-size:22px; text-align:center;">Oops.. Something wrong.</h2>
                        </div>

                        <!-- fieldsets -->
                        <fieldset id="step1">


                            <div align="center">
                                <h3 class="fs-subtitle">Multi Step form</h3>

                            </div>

                            <h2 class="fs-title">Personal Details</h2>

                            <div class="form-group required">
                                <label class="control-label col-sm-2">First Name: </label>
                                <div class="col-sm-10">
                                    <input type="text" name="firstname" />
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="control-label col-sm-2">Last Names: </label>
                                <div class="col-sm-10">
                                    <input type="text" name="lastname" />
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="control-label col-sm-2">Gender:</label>
                                <div class="col-sm-10">
                                    <label class="checkstyle">Male
                                        <input type="radio" name="gender" value="Male">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="checkstyle">Female
                                        <input type="radio" name="gender" value="Female">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="checkstyle">Other
                                        <input type="radio" name="gender" value="Other">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label for="gender" class="error" generated="true"></label>
                                </div>
                            </div>

                            <input style="float:right;" type="button" id="stepone" name="next" class="next action-button" value="Next" />

                        </fieldset>

                        <fieldset id="step2">

                            <h2 class="fs-title">Contact Information</h2>

                            <div class="form-group required">
                                <label class="control-label col-sm-2">Email: </label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" />
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="control-label col-sm-2">Mobile Number: </label>
                                <div class="col-sm-10">
                                    <input type="text" name="mobilenumber" />
                                </div>
                            </div>


                            <input type="button" name="previous" id="previous1" class="previous action-button" value="Previous" />
                            <input style="float:right;" type="button" id="steptwo" name="next" class="next action-button" value="Next" />
                        </fieldset>


                        <fieldset id="step3">

                            <h2 class="fs-title">Address Information</h2>

                            <div class="form-group required">
                                <label class="control-label col-sm-2">Address: </label>
                                <div class="col-sm-10">
                                    <input type="text" name="address" />
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="control-label col-sm-2">Postal Code: </label>
                                <div class="col-sm-10">
                                    <input type="text" name="postalcode" />
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="control-label col-sm-2">City Name: </label>
                                <div class="col-sm-10">
                                    <input type="text" name="cityname" />
                                </div>
                            </div>


                            <input type="button" name="previous" id="previous2" class="previous action-button" value="Previous" />
                            <input style="float:right;" type="button" id="stepthree" name="next" class="next action-button" value="Next" />
                        </fieldset>


                        <fieldset id="step4">


                            <h2 class="fs-title">Upload Document</h2>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Upload File: </label>
                                <div class="col-sm-5">
                                    <input type="file" name="uploadFile">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10">
                                    <a href="#" style="text-decoration: none;" class="terms_text">
                                        <label style="padding-left: 30px;" class="checkstyle">I have read, agree and accept the terms and conditions
                                            <input type="checkbox" id="termscls" name="termsclsname">
                                            <span class="checkmark"></span></a>
                                    </label>
                                    <label id="temsandconderror" style="color:red;display:none;">Please Accept Terms & Conditions</label>
                                </div>
                            </div>

                            <input type="button" name="previous" id="previous3" class="previous action-button" value="Previous" />

                            <input style="float:right;" type="submit" id="stepfour" name="submit" class="submitbutton" value="Submit" />

                        </fieldset>

                    </form>

                </div>
            </div>
        </div>
    </div>
</body>

<script>
    $("#stepone").click(function() {

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        if (v.form()) {

            $("input", "#step2").removeClass("ignore");

            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            $('#step1').hide();
            $('#step2').show();
            window.scrollTo(0, 0);
        }

    });

    $("#previous1").click(function() {

        $("input", "#step2").addClass("ignore");

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
        $('#step2').hide();
        $('#step1').show();
        window.scrollTo(0, 0);
    });
</script>

</html>