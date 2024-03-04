<?php
// always start with php, don't output any html until php stuff is all done.

// initialize variables
$tableRows = array();


// work with user input
if (isset($_POST['row'])) {

    // normally would save things to database, but
    // since there's no database, we'll just take the data and stuff it back into the page

    $row = $_POST['row'];    // for ease of typing
    settype($row, 'array');  // just to be sure it's an array

    foreach ($row as $r) {

        // if the checkbox is not checked, add the submitted row to the new list
        if (!isset($r['completed'])) {

            $tableRows[] = $row;
        }
    }

    // normally would redirect to self or another page
    // header('Location: /');
}

// normally would do any other logic here


// now that PHP processing is finished, present the view (HTML)
?>
<html>

<head>
    <title>ToDo List</title>
</head>

<body>
    <form method="post">
        <table id="table" class="table table-bordered table-dark">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">To Do</th>
                    <th scope="col">Due On</th>
                    <th scope="col">Completed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tableRows as $index => $row) : ?>
                    <tr>
                        <th scope="row"><?= $index ?></th>
                        <td><input type="text" name="row[<?= $index ?>][date]" value="<?= $row[$index]['date']  ?>"></td>
                        <td><input type="text" name="row[<?= $index ?>][todo]" value="<?= $row[$index]['todo']     ?>"></td>
                        <td><input type="text" name="row[<?= $index ?>][duedate]" value="<?= $row[$index]['due date'] ?>"></td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="row[<?= $index ?>][completed]" id="completed-<?= $index ?>" value="task_completed">
                                <label id="labelYes" class="form-check-label" for="completed-<?= $index ?>">Yes</label>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="Date">Date</label>
                <input type="date" name="row[][date]" class="form-control" id="Date" placeholder="MM/DD/YY">
            </div>
            <div class="form-group col-md-4">
                <label for="To Do">To Do</label>
                <input class="form-control" name="row[][todo]" id="To Do" placeholder="To Do">
            </div>
            <div class="form-group col-md-4">
                <label for="Due On">Due On</label>
                <input type="date" class="form-control" name="row[][duedate]" id="Due On" placeholder="MM/DD/YY">
            </div>
            <button type="submit" class="btn btn-primary text-right">Submit</button>
        </div>
    </form>
</body>

</html>