<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hybrid Step Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <!-- Linear Steps -->
            <div class="col-md-6">
                <h2>Linear Steps</h2>
                <form id="linear-form">
                    <!-- Step 1 -->
                    <div class="step">
                        <h3>Step 1</h3>
                        <div class="form-group">
                            <label for="linear-field1">Field 1:</label>
                            <input type="text" id="linear-field1" class="form-control" required>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="step">
                        <h3>Step 2</h3>
                        <div class="form-group">
                            <label for="linear-field2">Field 2:</label>
                            <input type="text" id="linear-field2" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Vertical Steps -->
            <div class="col-md-6">
                <h2>Vertical Steps</h2>
                <form id="vertical-form">
                    <!-- Step 1 -->
                    <div class="step">
                        <h3>Step 1</h3>
                        <div class="form-group">
                            <label for="vertical-field1">Field 1:</label>
                            <input type="text" id="vertical-field1" class="form-control" required>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="step">
                        <h3>Step 2</h3>
                        <div class="form-group">
                            <label for="vertical-field2">Field 2:</label>
                            <input type="text" id="vertical-field2" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        // JavaScript to handle the linear step navigation
        $(document).ready(function() {
            const form = $("#linear-form");
            const steps = form.find(".step");
            let currentStep = 1;

            $(".next-step").click(function() {
                hideStep(currentStep);
                currentStep++;
                showStep(currentStep);
            });

            $(".prev-step").click(function() {
                hideStep(currentStep);
                currentStep--;
                showStep(currentStep);
            });

            function hideStep(step) {
                steps.filter('[data-step="' + step + '"]').hide();
            }

            function showStep(step) {
                steps.filter('[data-step="' + step + '"]').show();
            }

            showStep(currentStep);
        });

        // JavaScript to handle the vertical step navigation
        $(document).ready(function() {
            const form = $("#vertical-form");
            const steps = form.find(".step");
            let currentStep = 1;

            $(".next-step").click(function() {
                hideStep(currentStep);
                currentStep++;
                showStep(currentStep);
            });

            $(".prev-step").click(function() {
                hideStep(currentStep);
                currentStep--;
                showStep(currentStep);
            });

            function hideStep(step) {
                steps.filter('[data-step="' + step + '"]').hide();
            }

            function showStep(step) {
                steps.filter('[data-step="' + step + '"]').show();
            }

            showStep(currentStep);
        });
    </script>
</body>

</html>