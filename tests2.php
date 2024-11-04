<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Request Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <h2>Lab Request Form</h2>
        <form>
            <div class="mb-3">
                <label for="patientName" class="form-label">Patient Name</label>
                <input type="text" class="form-control" id="patientName" placeholder="Enter patient name">
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob">
            </div>
            <div class="mb-3">
                <label for="labTests" class="form-label">Lab Tests</label>
                <select class="form-select" id="labTests" multiple>
                    <option value="cbc">Complete Blood Count (CBC)</option>
                    <option value="lipid">Lipid Profile</option>
                    <option value="glucose">Glucose Test</option>
                    <option value="urine">Urinalysis</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-check-label">Select additional tests:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="ecg" id="ecg">
                    <label class="form-check-label" for="ecg">
                        ECG
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="xray" id="xray">
                    <label class="form-check-label" for="xray">
                        X-Ray
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>