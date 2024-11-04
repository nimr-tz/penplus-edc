<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .step {
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .step.active {
            background-color: #007bff;
            color: #fff;
        }

        .step-pane {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div id="stepper" class="d-flex flex-column">
                    <div class="step">Step 1</div>
                    <div class="step">Step 2</div>
                    <div class="step">Step 3</div>
                </div>
            </div>
            <div class="col-md-9">
                <form id="stepperForm">
                    <div id="step-content">
                        <div class="step-pane">
                            <h3>Step 1 Content</h3>
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                        </div>
                        <div class="step-pane">
                            <h3>Step 2 Content</h3>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                        <div class="step-pane">
                            <h3>Step 3 Content</h3>
                            <div class="form-group">
                                <label for="message">Message:</label>
                                <textarea class="form-control" id="message" rows="4" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-primary" onclick="prevStep()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                        <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</body>

</html>


<script>
    const steps = document.querySelectorAll('.step');
    const stepContent = document.querySelectorAll('.step-pane');
    const submitButton = document.getElementById('submitBtn');

    let currentStep = 0;

    function showStep(index) {
        steps.forEach((step, i) => {
            if (i === index) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        stepContent.forEach((content, i) => {
            if (i === index) {
                content.style.display = 'block';
            } else {
                content.style.display = 'none';
            }
        });

        if (index === stepContent.length - 1) {
            submitButton.style.display = 'block';
        } else {
            submitButton.style.display = 'none';
        }
    }

    function nextStep() {
        if (currentStep < steps.length - 1 && validateInputs()) {
            currentStep++;
            showStep(currentStep);
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    }

    function validateInputs() {
        const currentInputs = stepContent[currentStep].querySelectorAll('input[required], textarea[required]');
        let valid = true;

        currentInputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                valid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        return valid;
    }

    document.getElementById('stepperForm').addEventListener('submit', function(event) {
        event.preventDefault();

        if (validateInputs()) {
            // Perform form submission or any additional processing here
            alert('Form submitted successfully!');
        } else {
            alert('Please fill in all required fields.');
        }
    });

    showStep(currentStep);
</script>