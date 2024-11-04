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


$result = $override->AllTables();
$tables = [];
// while ($row = $result) {
// $table = $row[0];
// $tables[$table] = [];

// Get column information for each table
// $result_columns = $override->AllColmuns($table);

// while ($row_column = $result_columns) {
//     $tables[$table][] = $row_column;
// }
// }





// $tables = [];

$tables = $override->AllTables();
// foreach ($tables as $table) {
//     $result = $table['Tables_in_penplus'];
//     $tables = $override->AllColmuns($result);
//     // print_r($result);
//     // $x[$table][] = $row_column;

// }
?>


<!DOCTYPE html>
<html>

<head>
    <title>Data Dictionary</title>
</head>

<body>
    <h1>Data Dictionary</h1>

    <?php
    foreach ($tables as $table) :

    ?>

        <h2><?php print_r($table['Tables_in_penplus']); ?></h2>
        <table>
            <tr>
                <th>Column Name</th>
                <th>Data Type</th>
                <th>Default Value</th>
                <th>Is Nullable</th>
                <th>Comment</th>
            </tr>
            <?php foreach ($override->AllColmuns($table['Tables_in_penplus']) as $column) :
                $COLUMN_COMMENT = $override->AllColmunsComments($table['Tables_in_penplus']);
                // print_r($COLUMN_COMMENT['COLUMN_COMMENT']);

            ?>
                <tr>
                    <td><?php echo $column['Field']; ?></td>
                    <td><?php echo $column['Type']; ?></td>
                    <td><?php echo $column['Default']; ?></td>
                    <td><?php echo $column['Null']; ?></td>
                    <td><?php  print_r($COLUMN_COMMENT['COLUMN_COMMENT']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</body>

</html>