<?php
// include 'database.php';
// if (isset($_POST['submit'])) {
//     $checked_array = $_POST['prodid'];
//     foreach ($_POST['prodname'] as $key => $value) {
//         if (in_array($_POST['prodname'][$key], $checked_array)) {
//             $prodname = $_POST['prodname'][$key];
//             $prod_price = $_POST['prod_price'][$key];
//             $prod_qty = $_POST['prod_qty'][$key];


//             $insertqry = "INSERT INTO `product`( `product_name`, `product_price`, `product_quantity`) VALUES ('$prodname','$prod_price','$prod_qty')";
//             $insertqry = mysqli_query($con, $insertqry);
//         }
//     }
// }
// header('Location: index.php');



if (isset($_POST['submit'])) {
    $checked_array = $_POST['prodid'];
    foreach ($_POST['prodname'] as $key => $value) {
        if (in_array($_POST['prodname'][$key], $checked_array)) {
            $prodname = $_POST['prodname'][$key];
            $prod_price = $_POST['prod_price'][$key];
            $prod_qty = $_POST['prod_qty'][$key];

            // $insertqry = "INSERT INTO `product`( `product_name`, `product_price`, `product_quantity`) VALUES ('$prodname','$prod_price','$prod_qty')";
            // $insertqry = mysqli_query($con, $insertqry);
        }
        // print_r($checked_array);
        // echo '<br>';

    }
    print_r($checked_array);
}
?>






<!DOCTYPE html>
<html>

<head>
    <title>Multiple Checkbox</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h4>Multiple Checkbox</h4>
        <hr>

        <!-- <form method="post" action="checkbox-db.php"> -->
        <form method="post">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Lab Test</th>
                        <th>Price</th>
                        <th>value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="prodid[]" value="haematology"></td>
                        <td>Hematology
                            <input type="hidden" name="prodname[]" value="haematology">
                        </td>
                        <td><input type="number" name="prod_price[]" class="form-control"></td>
                        <td><input type="number" name="prod_qty[]" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="prodid[]" value="chemistry"></td>
                        <td>Chemstry
                            <input type="hidden" name="prodname[]" value="chemistry">
                        </td>
                        <td><input type="number" name="prod_price[]" class="form-control"></td>
                        <td><input type="number" name="prod_qty[]" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="prodid[]" value="bacteriology"></td>
                        <td>Bacteriology
                            <input type="hidden" name="prodname[]" value="bacteriology">
                        </td>
                        <td><input type="number" name="prod_price[]" class="form-control"></td>
                        <td><input type="number" name="prod_qty[]" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <input type="submit" name="submit" class="btn btn-success" value="Submit">
            </div>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>