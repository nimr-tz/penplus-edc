<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;
$numRec = 50;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funda of Web IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>How to Filter or Find or Search data using Multiple Checkbox in php</h4>
                    </div>
                </div>
            </div>

            <!-- Brand List  -->
            <div class="col-md-3">
                <form action="" method="GET">
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h5>Filter
                                <button type="submit" class="btn btn-primary btn-sm float-end">Search</button>
                            </h5>
                        </div>
                        <div class="card-body">
                            <h6>Brand List</h6>
                            <hr>
                            <?php
                            $brand_query = $override->getData('a_brands');
                            $brand_count = $override->getNo('a_brands');
                            if ($brand_count > 0) {
                                foreach ($brand_query as $brandlist) {
                                    $checked = [];
                                    if ($_GET['brands']) {
                                        $checked = $_GET['brands'];
                                    }
                            ?>
                                    <div>
                                        <input type="checkbox" name="brands[]" value="<?= $brandlist['id']; ?>" <?php if (in_array($brandlist['id'], $checked)) {
                                                                                                                    echo "checked";
                                                                                                                } ?> />
                                        <?= $brandlist['name']; ?>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "No Brands Found";
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Brand Items - Products -->
            <div class="col-md-9 mt-3">
                <div class="card ">
                    <div class="card-body row">
                        <?php
                        if ($_GET['brands']) {
                            $branchecked = [];
                            $branchecked = $_GET['brands'];

                            foreach ($branchecked as $rowbrand) {
                                print_r($rowbrand['brands']);
                                $products_query = $override->getIn('a_products', 'brand_id', $rowbrand);
                                $products_count = $override->getInNo('a_products', 'brand_id', $rowbrand);

                                if ($products_count > 0) {
                                    foreach ($products_query as $proditems) {
                        ?>
                                        <div class="col-md-4 mt-3">
                                            <div class="border p-2">
                                                <h6><?= $proditems['name']; ?></h6>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                }
                            }
                        } else {
                            $products_query = $override->getData('a_products');
                            $products_count = $override->getNo('a_products');
                            if ($products_count > 0) {
                                foreach ($products_query as $proditems) :
                                    ?>
                                    <div class="col-md-4 mt-3">
                                        <div class="border p-2">
                                            <h6><?= $proditems['name']; ?></h6>
                                        </div>
                                    </div>
                        <?php
                                endforeach;
                            } else {
                                echo "No Items Found";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>