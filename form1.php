<html>

<head>
    <title>ToDo List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="row">
        <div class="col-sm-12">
            <form method="post">
                <div>&nbsp;</div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="Date">Date</label>
                        <input type="date" id="date-enter" class="form-control" placeholder="MM/DD/YY" value="" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="To Do">To Do</label>
                        <input class="form-control" id="todo-enter" placeholder="To Do" value="" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="Due On">Due On</label>
                        <input type="date" class="form-control" id="due-enter" placeholder="MM/DD/YY" value="" required>
                    </div>
                    <button type="submit" class="btn btn-primary text-right" id="add-row">Submit</button>
                </div>
                <div>&nbsp;</div>

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
                    <tbody id="table-tbody">
                        <script type="text/template" data-template="table-template">
                            <tr>
            <th scope="row">${index}</th>
            <td>${date}</td>
            <td>${todo}</td>
            <td>${due}</td>
            <td>
              <!-- 
                <input type="date" id="date-row-${index}" value="${date}"> 
                <input type="text" id="todo-row-${index}" value="${todo}">
                <input type="date" id="due-row-${index}" value="${due}">
              -->
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="row-completed-${index}" data-index="${index}">
                <label class="form-check-label" for="row-completed-${index}">Yes</label>
              </div>
            </td>
          </tr>
        </script>
                    </tbody>
                </table>

            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>


    <script>
        // ensures DOM is loaded first
        $(document).ready(function() {

            // initialize "global" variables.  These are available at all child levels. (i.e., in functions)
            var todoData = []; // this is all your data as a javascript array
            var template = $('script[data-template="table-template"]').text().split(/\$\{(.+?)\}/g);

            // first, get anything previously stored and write table rows
            todoData = getFromStorage();
            writeTable(todoData);


            // begin "listeners" which wait for events like form submission and click on the "completed" checkbox
            $('form').on('submit', function(e) {
                e.preventDefault(); // html5 validation requires the form be submitted, but this prevents it from following through on the submission.
                addRow();
            });

            // note, this listener must be anchored to document, since it is monitoring DOM elements that are dynamic
            $(document).on('click', '.form-check-input', function() {
                var i = $(this).data("index");
                console.log(i);
                if (confirm("Delete this row?")) {
                    // delete this row
                    todoData.splice(i - 1, 1); // array starts at 0, we start at 1...

                    saveToLocalStorage(todoData);
                    writeTable(todoData);
                }
            });


            // begin functions that do all the work
            function addRow() {

                var data = {
                    // index: index,
                    date: $('#date-enter').val(),
                    todo: $('#todo-enter').val(),
                    due: $('#due-enter').val()
                };

                clearForm();

                todoData.push(data);

                saveToLocalStorage(todoData);

                var fromStorage = getFromStorage();
                writeTable(fromStorage);
            }

            function clearForm() {

                $('#date-enter').val('');
                $('#todo-enter').val('');
                $('#due-enter').val('');
            }

            // store the entire array, not just a single row
            function saveToLocalStorage(data) {

                localStorage.clear();
                console.log('storing data in local storage:');
                console.log(data);
                localStorage.setItem('todoList', JSON.stringify(data));
            }

            function getFromStorage() {

                // addRow is intended to take json values from ajax.  It will support multiple rows.
                // this is test data:
                // var data = [
                //   {date: '2019-01-01',todo: 'Start the year', due: '2019-12-31'},
                //   {date: '2019-12-25',todo: 'Celebrate Christmas',due: '2019-12-25'}
                // ];

                // get data from local storage
                data = JSON.parse(localStorage.getItem('todoList'));

                // todoData = data; // using a "global" variable is probably not good practice.
                return data;
            }

            function addIndexToData(data) {

                var index = 1;
                // takes each row of data, and adds "index" property to it, giving it the value of index and then incrementing index
                data.map(function(row) {
                    row.index = index++;
                    return row;
                });

                return data;
            }

            // html template rendering function. see https://stackoverflow.com/a/39065147/2129574
            function render(props) {
                return function(tok, i) {
                    return (i % 2) ? props[tok] : tok;
                };
            }

            // html template rendering function. see https://stackoverflow.com/a/39065147/2129574
            function writeTable(data) {

                // if not an array, stop before things puke.
                if (!Array.isArray(data)) {
                    console.log("data was not an array.  That just won't work.");
                    console.log(data);
                    return;
                }

                data = addIndexToData(data);

                var trow = data.map(function(rows) {
                    return template.map(render(rows)).join('');
                });

                // $('#table-tbody').append(trow);
                $('#table-tbody').html(trow);

            }
        });
    </script>
</body>

</html>