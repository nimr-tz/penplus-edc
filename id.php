<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
if ($user->isLoggedIn()) {
    $client = $override->get('clients','id',$_GET['cid'])[0];
    if($client['client_image'] !='' || is_null($client['client_image'])){$img=$client['client_image'];}else{$img='img/users/blank.png';}
    $endDate = date('Y-m-d', strtotime('+1 year', strtotime($client['clinic_date'])));
} else {
    Redirect::to('index.php');
}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
    <link rel="stylesheet" href="css/dashboard.css">

    <link rel="icon" type="image/png" href="images/favicon.png"/>

    <title> PenPlus | ID Card </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">

    <style>
        body{
            font-family:'arial';
        }
        .lavkush img {
            border-radius: 8px;
            border: 2px solid blue;
        }
        span{
            font-family: 'Orbitron', sans-serif;
            font-size:16px;
        }
        hr.new2 {
            border-top: 1px dashed black;
            width:350px;
            text-align:left;
            align-items:left;
        }
        /* second id card  */
        p{
            font-size: 13px;
            margin-top: -5px;
        }
        .container {
            width: 80vh;
            height: 45vh;
            margin: auto;
            background-color: white;
            box-shadow: 0 1px 10px rgb(146 161 176 / 50%);
            overflow: hidden;
            border-radius: 10px;
        }

        .header {
            /* border: 2px solid black; */
            width: 73vh;
            height: 40px;
            margin: 20px auto;
            background-color: white;
            /* box-shadow: 0 1px 10px rgb(146 161 176 / 50%); */
            /* border-radius: 10px; */
            /*background-image: url(assets/images/BDM2.png);*/
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        .header h1 {
            color: rgb(27, 27, 49);
            text-align: right;
            margin-right: 20px;
            margin-top: 0px;
        }

        .header p {
            color: rgb(157, 51, 0);
            text-align: right;
            margin-right: 22px;
            margin-top: -10px;
        }

        .container-2 {
            /* border: 2px solid red; */
            width: 73vh;
            height: 10vh;
            margin: 0px auto;
            margin-top: -20px;
            display: flex;
        }

        .box-1 {
            border: 4px solid black;
            width: 208px;
            height: 208px;
            margin: -40px 25px;
            border-radius: 3px;
        }

        .box-1 img {
            width: 200px;
            height: 200px;
        }

        .box-2 {
            /* border: 2px solid purple; */
            width: 33vh;
            height: 8vh;
            margin: 7px 0px;
            padding: 5px 7px 0px 0px;
            text-align: left;
            font-family: 'Poppins', sans-serif;
        }

        .box-2 h2 {
            font-size: 1.3rem;
            margin-top: -5px;
            color: rgb(27, 27, 49);
        }

        .box-2 p {
            font-size: 0.7rem;
            margin-top: -5px;
            color: rgb(179, 116, 0);
        }

        .box-3 {
            /* border: 2px solid rgb(21, 255, 0); */
            width: 8vh;
            height: 8vh;
            margin: 8px 0px 8px 30px;
        }

        .box-3 img {
            width: 8vh;
        }

        .container-3 {
            /* border: 2px solid rgb(111, 2, 161); */
            width: 73vh;
            height: 12vh;
            margin: 0px auto;
            margin-top: 10px;
            display: flex;
            font-family: 'Shippori Antique B1', sans-serif;
            font-size: 0.7rem;
        }

        .info-1 {
            /* border: 1px solid rgb(255, 38, 0); */
            width: 17vh;
            height: 12vh;
        }

        .id {
            /* border: 1px solid rgb(2, 92, 17); */
            width: 17vh;
            height: 5vh;
        }

        .id h4 {
            color: rgb(179, 116, 0);
            font-size:15px;
        }

        .dob {
            /* border: 1px solid rgb(0, 46, 105); */
            width: 17vh;
            height: 5vh;
            margin: 8px 0px 0px 0px;
        }

        .dob h4 {
            color: rgb(179, 116, 0);
            font-size:15px;
        }

        .info-2 {
            /* border: 1px solid rgb(4, 0, 59); */
            width: 17vh;
            height: 12vh;
        }

        .join-date {
            /* border: 1px solid rgb(2, 92, 17); */
            width: 17vh;
            height: 5vh;
        }

        .join-date h4 {
            color: rgb(179, 116, 0);
            font-size:15px;
        }

        .expire-date {
            /* border: 1px solid rgb(0, 46, 105); */
            width: 17vh;
            height: 5vh;
            margin: 8px 0px 0px 0px;
        }

        .expire-date h4 {
            color: rgb(179, 116, 0);
            font-size:15px;
        }

        .info-3 {
            /* border: 1px solid rgb(255, 38, 0); */
            width: 17vh;
            height: 12vh;
        }

        .email {
            /* border: 1px solid rgb(2, 92, 17); */
            width: 22vh;
            height: 5vh;
        }

        .email h4 {
            color: rgb(179, 116, 0);
            font-size:15px;
        }

        .phone {
            /* border: 1px solid rgb(0, 46, 105); */
            width: 17vh;
            height: 5vh;
            margin: 8px 0px 0px 0px;
        }

        .info-4 {
            /* border: 2px solid rgb(255, 38, 0); */
            width: 22vh;
            height: 12vh;
            margin: 0px 0px 0px 0px;
            font-size:15px;
        }

        .phone h4 {
            color: rgb(179, 116, 0);
            font-size:15px;
        }

        .sign {
            /* border: 1px solid rgb(0, 46, 105); */
            width: 17vh;
            height: 5vh;
            margin: 41px 0px 0px 20px;
            text-align: center;
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.js"></script>
</head>
<body>
<br>
<div class="row" style="margin: 0px 20px 5px 20px">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header" >
                Here is your Id Card Front
            </div>
            <div class="card-body" id="mycard">
                <div class='container' style='text-align:left; border:2px dotted black;'>
                    <img src='img/nimr.png' alt='' style="width: 10vh">
                    <img src='img/tancov.png' alt='' style="float: right;width: 30vh" >
                    <div class='header'>
                    </div>
                    <div class='container-2'>
                        <div class='box-1'>
                            <img src='<?=$img?>'/>
                        </div>
                        <div class='box-2'>
                            <h2><?=$client['firstname'].' '.$client['lastname']?></h2>
                            <p style='font-size: 14px;'>Participant</p>
                        </div>

                    </div>
                    <div class='header'>
                    </div>

                    <div class='container-3'>
                        <div class='info-1'>
                            <div class='id'>
                                <h4>ID No</h4>
                                <p><strong><?=$client['study_id']?></strong></p>
                            </div>

                        </div>
                        <div class='info-2'>
                            <div class='join-date'>
                                <h4>Joined Date</h4>
                                <p><?=$client['clinic_date']?></p>
                            </div>

                        </div>
                        <div class='info-3'>
                            <div class='expire-date'>
                                <h4>Expire Date</h4>
                                <p><?=$endDate?></p>
                            </div>
                        </div>

                        <div class='info-4'>
                            <div class='sign'>
                                <br>
                                <p style='font-size:12px;'>Your Signature</p>
                            </div>
                        </div>
                    </div>
                    <br>

                </div>
            </div>
            <hr>
            <button id="demo" class="downloadtable btn btn-primary" onclick="downloadtable()"> Download Id Card (Front)</button>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

            <script>

                function downloadtable() {
                    var node = document.getElementById('mycard');
                    domtoimage.toPng(node)
                        .then(function (dataUrl) {
                            var img = new Image();
                            img.src = dataUrl;
                            downloadURI(dataUrl, "patient-id-card.png")
                        })
                        .catch(function (error) {
                            console.error('oops, something went wrong', error);
                        });

                }

                function downloadURI(uri, name) {
                    var link = document.createElement("a");
                    link.download = name;
                    link.href = uri;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    delete link;
                }

            </script>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header" >
                Here is your Id Card Back
            </div>
            <div class="card-body" id="mycard1">
                <div class='container' style='text-align:left; border:2px dotted black;'>
                    <img src='img/nimr.png' alt='' style="" class="center">
                    <br>
                    <h4 style="text-align: center">Contact: +255 25 250 3364 , +255 22 2152232</h4>
                </div>
            </div>
            <hr>
            <button id="demo1" class="downloadtable btn btn-primary" onclick="downloadtable1()"> Download Id Card (Back)</button>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

            <script>

                function downloadtable1() {
                    var node = document.getElementById('mycard1');
                    domtoimage.toPng(node)
                        .then(function (dataUrl) {
                            var img = new Image();
                            img.src = dataUrl;
                            downloadURI(dataUrl, "patient-id-card-back.png")
                        })
                        .catch(function (error) {
                            console.error('oops, something went wrong', error);
                        });

                }

                function downloadURI(uri, name) {
                    var link = document.createElement("a");
                    link.download = name;
                    link.href = uri;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    delete link;
                }

            </script>
        </div>
    </div>
</div>
</body>
</html>