<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
    <table id="myTable" class=" table order-list">
        <thead>
            <tr>
                <td>Test Name</td>
                <td>Client Id</td>
                <td>results</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-sm-4">
                    <input type="text" name="test_name" class="form-control" />
                </td>
                <td class="col-sm-4">
                    <input type="text" name="client_id" class="form-control" />
                </td>
                <td class="col-sm-3">
                    <input type="text" name="results" class="form-control" />
                </td>
                <td class="col-sm-2"><a class="deleteRow"></a>

                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: left;">
                    <input type="button" class="btn btn-lg btn-block " id="addrow" value="Add Row" />
                </td>
            </tr>
            <tr>
            </tr>
        </tfoot>
    </table>
</div>


<script>
    $(document).ready(function() {
        var counter = 0;

        $("#addrow").on("click", function() {
            var newRow = $("<tr>");
            var cols = "";

            cols += '<td><input type="text" class="form-control" name="test_name' + counter + '"/></td>';
            cols += '<td><input type="text" class="form-control" name="client_id' + counter + '"/></td>';
            cols += '<td><input type="text" class="form-control" name="results' + counter + '"/></td>';

            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
            newRow.append(cols);
            $("table.order-list").append(newRow);
            counter++;
        });



        $("table.order-list").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            counter -= 1
        });


    });



    function calculateRow(row) {
        var price = +row.find('input[test_name^="price"]').val();

    }

    function calculateGrandTotal() {
        var grandTotal = 0;
        $("table.order-list").find('input[test_name^="price"]').each(function() {
            grandTotal += +$(this).val();
        });
        $("#grandtotal").text(grandTotal.toFixed(2));
    }
</script>