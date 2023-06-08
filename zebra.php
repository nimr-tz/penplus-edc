<!DOCTYPE html>
<html lang="en">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<head>
    <script type="text/javascript" src="js/BrowserPrint-3.0.216.min.js"></script>
    <script type="text/javascript">
        var selected_device;
        var devices = [];
        function setup(){
            //Get the default device from the application as a first step. Discovery takes longer to complete.
            BrowserPrint.getDefaultDevice("printer", function(device){
                //Add device to list of devices and to html select element
                selected_device = device;
                devices.push(device);
                var html_select = document.getElementById("selected_device");
                var option = document.createElement("option");
                option.text = device.name;
                html_select.add(option);
                //Discover any other devices available to the application
                BrowserPrint.getLocalDevices(function(device_list){
                    for(var i = 0; i < device_list.length; i++){
                        //Add device to list of devices and to html select element
                        var device = device_list[i];
                        if(!selected_device || device.uid != selected_device.uid){
                            devices.push(device);
                            var option = document.createElement("option");
                            option.text = device.name;
                            option.value = device.uid;
                            html_select.add(option);
                        }
                    }
                }, function(){alert("Error getting local devices")},"printer");
            }, function(error){alert(error);})
        }
        function labelType($label_type) {
            var l_type = '';
            switch ($label_type) {
                case '01':
                    l_type = 'Nasopharyngeal Swab';
                    break;
                case '02':
                    l_type = 'Saliva';
                    break;
                case '03':
                    l_type = 'Blood (EDTA)';
                    break;
                case '04':
                    l_type = 'Blood (SST)';
                    break;
                case '05':
                    l_type = 'ACD Blood';
                    break;
                case '06':
                    l_type = 'Citrate Blood';
                    break;
                case '07':
                    l_type = 'Urine';
                    break;
                case '08':
                    l_type = 'PBMCs';
                    break;
                case '09':
                    l_type = 'Plasma';
                    break;
                case '10':
                    l_type = 'Serum';
                    break;
                case '11':
                    l_type = "Fluoride Blood"
                    break;
            }
            return l_type;
        }
        function writeToSelectedPrinter(){
            var x=1;var study_id=document.getElementById('study_id').value;
            var no=Number(document.getElementById('no_label').value);
            var label_type=document.getElementById('label_type').value;
            var label_name=labelType(document.getElementById('label_type').value);
            var visit_type=document.getElementById('visit_type').value;
            var label_date=document.getElementById('label_date').value;
            var sid = study_id+'V'+visit_type+'-'+label_type;
            // window.alert(sid)
            while(x < no+1){
                var dataToWrite ='^XA^CF0,21^FO230,20^FDTANCoV-1^FS^FO510,20^FDTanCoV-1^FS^FO170,110^FD'+sid+'^FS^FO450,110^FD'+sid+'^FS^FO170,140^FD'+label_name+'^FS^FO450,140^FD'+label_name+'^FS^FO170,170^FD'+label_date+'^FS^FO450,170^FD'+label_date+'^FS^BY2,2,50^FO300,0^BQN,2,3^FDQAM'+sid+'^FS^BY2,2,50^FO580,0^BQN,2,3^FDQAM'+sid+'^FS^XZ'
                selected_device.send(dataToWrite, undefined, errorCallback);
                x++;
            }
        }
        var readCallback = function(readData) {
            if(readData === undefined || readData === null || readData === ""){
                alert("No Response from Device");
            }else{
                alert(readData);
            }
        }
        var errorCallback = function(errorMessage){
            alert("Error: " + errorMessage);
        }
        window.onload = setup;
    </script>
</head>
<body >
<span style="padding-right:50px; font-size:200%">TANCov Label Printing &nbsp;&nbsp;&nbsp;&nbsp; <button onclick="location.href = 'dashboard.php';" id="myHome">Go Back Home</button></span><br/>

Selected Device: <select id="selected_device" onchange=onDeviceSelected(this);></select> <!--  <input type="button"
value="Change" onclick="changeDevice();">--> <br/><br/>
<p>&nbsp;</p>
<label for="fname">Enter Number of Labels : </label>
<input type="number" name="no_label" id="no_label"><br><br>
<label for="fname">Label ID : </label>
<input type="text" name="study_id" id="study_id"><br><br>
<label for="visit_type">Visit Type : </label>
<select name="visit_type" id="visit_type">
    <option value="">Visit</option>
    <option value="01">Visit 1</option>
    <option value="02">Visit 2</option>
    <option value="03">Visit 3</option>
    <option value="04">Visit 4</option>
    <option value="05">Visit 5</option>
    <option value="06">Visit 6</option>
    <option value="07">Visit 7</option>
    <option value="08">Visit 8</option>
    <option value="09">Visit 9</option>
    <option value="10">Visit 10</option>
    <option value="11">Visit 11</option>
</select><br><br>
<label for="label_type">Sample Type : </label>
<select name="label_type" id="label_type">
    <option value="">Type</option>
    <option value="01">Nasopharyngeal Swab</option>
    <option value="02">Saliva</option>
    <option value="03">Blood (EDTA)</option>
    <option value="04">Blood (SST)</option>
    <option value="05">ACD Blood</option>
    <option value="06">Citrate Blood</option>
    <option value="07">Urine</option>
    <option value="08">PBMCs</option>
    <option value="09">Plasma</option>
    <option value="10">Serum</option>
    <option value="11">Fluoride Blood</option>
</select><br><br>
<label for="fname">Date : </label>
<input type="text" name="label_date" id="label_date"><br><br>
<button id="myButton">Print Barcode</button>
<script>
    var button = document.querySelector('#myButton');
    button.addEventListener('click', function() { writeToSelectedPrinter()});
    button.click();
</script>
</body>
</html>