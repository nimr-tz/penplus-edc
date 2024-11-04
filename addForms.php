<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <title>Document</title>
</head>

<body>

    <div class="container">
        <!-- <h1><a href="https://learncodeweb.com/jquery/add-more-&-reorder-table-rows-with-php-jquery/">Add More rows into the existing table with PHP & JQuery</a></h1> -->
        <hr>
        <div class="clearfix"></div>
        <?php
        //Get all countries names array
        $result = $override->get('medication_treatments', 'status', 1);
        foreach ($result as $val) {
            $counrtyName[$val['id']]    =   $val['medication_type'];
        }
        ?>
        <script>
            $(document).ready(function(e) {
                $('.selectpicker').selectpicker();

                $('body').on('mousemove', function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });

                $("#addmore").on("click", function() {
                    $.ajax({
                        type: 'POST',
                        url: 'addFormsAjax.php',
                        data: {
                            'action': 'addDataRow'
                        },
                        success: function(data) {
                            $('#tb').append(data);
                            $('.selectpicker').selectpicker('refresh');
                            $('#save').removeAttr('hidden', true);
                        }
                    });
                });

                $("#form").on("submit", function() {
                    $.ajax({
                        type: 'POST',
                        url: 'addFormsAjax.php',
                        data: $(this).serialize(),
                        success: function(data) {
                            var a = data.split('|***|');
                            if (a[1] == "add") {
                                $('#mag').html(a[0]);
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        }
                    });
                });

            });
        </script>

        <div id="msg"></div>
        <form id="form" method="post" onSubmit="return false;">
            <input type="hidden" name="action" value="saveAddMore">
            <table class="table table-bordered table-striped" id="sortable">
                <thead>
                    <tr>
                        <th width="20">Sr#</th>
                        <th width="120" class="text-center">Insetion Date</th>
                        <th>User Name</th>
                        <th width="250">User Country</th>
                        <th>User Email</th>
                        <th>User Phone#</th>
                    </tr>
                </thead>
                <tbody id="tb">
                    <?php
                    $result = $override->get('medication_treatments', 'status', 1);
                    if ($result) {
                        $s  =   '';
                        foreach ($result as $val) {
                            $s++;  ?>
                            <tr>
                                <td align="center"><?php echo $s; ?></td>
                                <td align="center"><?php echo date('', strtotime($val['dt'])); ?></td>
                                <td><?php echo mb_strtoupper($val['username'], 'UTF-8'); ?></td>
                                <td><?php echo mb_strtoupper($counrtyName[$val['usercountry']], 'UTF-8'); ?></td>
                                <td><?php echo $val['useremail']; ?></td>
                                <td><?php echo $val['userphone']; ?></td>
                            </tr>
                        <?php
                        }
                    } else { ?>
                        <tr>
                            <td colspan="6" class="bg-light text-center"><strong>No Record(s) Found!</strong></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <a href="javascript:;" class="btn btn-danger" id="addmore"><i class="fa fa-fw fa-plus-circle"></i> Add More</a>
                            <button type="submit" name="save" id="save" value="save" class="btn btn-primary" hidden><i class="fa fa-fw fa-save"></i> Save</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>
        <div class="clearfix"></div>

    </div>
    <!--/.container-->

</body>

</html>