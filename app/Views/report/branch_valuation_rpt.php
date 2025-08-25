<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Branch Data</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
				<!-- 🖨️ Print Button -->
				<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="printTable()"> 
					<i class="fa fa-print"></i> Print
				</a>
			</div>
		</div>
	</div>

	<div class="page-content fade-in-up">
		<div class="ibox">
			<div class="ibox-body"> 
                <div class="row mb-3">
					<div class="col-lg-3">
                        <label class="mb-0 mr-1">Deposite Status:</label>
						<select class="selectpicker show-tick form-control" id="status-filter" title="Please select" data-style="btn-solid">
							<option value="">All</option>
							<option value="0">Deposited</option>
							<option value="1">Pending</option>
						</select>
                    </div>
					<div class="col-lg-3">
                        <label class="mb-0 mr-1">Branch:</label>
						<select class="selectpicker show-tick form-control" id="branch_filter"  data-live-search="true" title="Please select" data-style="btn-solid">
						<?php 

						$checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null; // 1 = admin, 0 = non-admin
    					$branchId   = session()->get('branch_id');
						
						if ($checkAdmin == 1) {
							echo '<option value="">All</option>';
						}
							foreach ($branchArray as $branch) {
								// For non-admins, pre-select their branch; admins get no pre-selection
								$selected = ($checkAdmin != 1 && $branch['id'] == $branchId) ? 'selected' : '';
								echo '<option value="' . $branch['id'] . '" ' . $selected . '>'
										. htmlspecialchars($branch['branch_name'], ENT_QUOTES, 'UTF-8')
									. '</option>';
							}
						?>
						</select>
                    </div>
					<div class="col-lg-3">
                        <label class="mb-0 mr-1">Customer:</label>
						<select class="selectpicker show-tick form-control" id="customer_filter"  data-live-search="true" title="Please select" data-style="btn-solid">
						<option value="">All</option>
						<?php 
							foreach ($customerArray as $id => $name) {
								$uri = service('uri');
								$customer_id = $uri->getSegment(3);
								$selected = (isset($customer_id) && $customer_id == $id) ? 'selected' : '';
								echo '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
							}
						?>
						</select>
                    </div>

					<div class="col-lg-3">
                        <label class="mb-0 mr-1">Month:</label>
						<select class="selectpicker show-tick form-control" id="month_filter"  data-live-search="true" title="Please select" data-style="btn-solid">
						<option value="">All</option>
						<?php 
							foreach ($monthArray as $id => $name) {
								// Output option
								echo '<option value="' . $id . '" >' . $name . '</option>';
							}
							?>
						</select>
                    </div>

				</div>
                
				<div class="table-responsive table-cover">
					<table class="table table-bordered table-hover" id="item_table">
						<thead class="thead-default thead-lg">
							<tr>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Item Name</th>
                                <th>Weight</th>
                                <th>Rate</th>
                                <th>Amount</th>
							</tr>
						</thead>
						<tbody>						
						</tbody>
                        <tfoot>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
// 📌 Define printTable globally so the button can call it
function printTable() {
    var table = document.getElementById("item_table");

    // Rebuild thead manually if missing
    var theadHTML = `
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Date</th>
                <th>Item Name</th>
                <th>Weight</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
    `;

    var tbody = table.querySelector("tbody").outerHTML;
    var tfoot = table.querySelector("tfoot") ? table.querySelector("tfoot").outerHTML : '';

    var newWin = window.open("");
    newWin.document.write(`
        <html>
            <head>
                <title>Print Table</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        padding: 20px;
                    }
                    h2 {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    th, td {
                        border: 1px solid #333;
                        padding: 8px 12px;
                        text-align: center;
                        font-size: 14px;
                    }
                    thead {
                        background-color: #f1f1f1;
                        font-weight: bold;
                    }
                    tfoot td {
                        font-weight: bold;
                        background-color: #e9ecef;
                    }
                    @media print {
                        body {
                            margin: 0;
                            padding: 0;
                        }
                        a, button {
                            display: none !important;
                        }
                    }
                </style>
            </head>
            <body>
                <h2>Branch Data Report</h2>
                <table>
                    ${theadHTML}
                    ${tbody}
                    ${tfoot}
                </table>
            </body>
        </html>
    `);
    newWin.document.close();
    newWin.focus();
    newWin.print();
    newWin.close();
}


$(document).ready(function() {
    $('#item_table').on('draw.dt', function() {
        updateTotals();
    });

    $('#branch_filter, #status-filter,#customer_filter,#month_filter').change(function(event) {
        $('#item_table').DataTable().destroy();
        
        _filter = [
            { 	"id": "branch_filter", 
				"field": "branch_id" 
			},
			{
				id: "customer_filter",
				field: "customer_id"
			},
			{
				id: "status-filter",
				field: "status"
			},
			{
				id: "month_filter",
				field: "month_id"
			},
        ];
        _search = [
            { "data": "item_name" }
        ];
        // updateTotals();
        initDataTable("item_table", "/report/getReport", _filter, _search);

        $('#item_table').on('draw.dt', function() {
            updateTotals();
        });

        $('#item_table').on('init.dt', function() {
            updateTotals();
        });
        
        $('#item_table').on('xhr.dt', function() {
            updateTotals();
        });
    });

    $(".selectpicker").selectpicker({
        "title": "Select Options"
    }).selectpicker("render");

    function updateTotals() {
        var totalWeight = 0;
        var totalRate = 0;
        var totalAmount = 0;

        var $table = $('#item_table');

        $table.find('tbody tr').each(function () {
            var weight = parseFloat($(this).find('td:eq(3)').text().replace(/,/g, '').trim()) || 0;
            var rate = parseFloat($(this).find('td:eq(4)').text().replace(/,/g, '').trim()) || 0;
            var amount = parseFloat($(this).find('td:eq(5)').text().replace(/,/g, '').trim()) || 0;

            totalWeight += weight;
            totalRate += rate;
            totalAmount += amount;
        });

        if ($table.find('tfoot').length === 0) {
            $table.append(`
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td id="total_weight"><strong>${totalWeight.toFixed(2)}</strong></td>
                        <td id="total_rate"><strong> </strong></td>
                        <td id="total_amount"><strong>${totalAmount.toFixed(2)}</strong></td>
                    </tr>
                </tfoot>
            `);
        } else {
            $('#total_weight').html('<strong>' + totalWeight.toFixed(2) + '</strong>');
            $('#total_rate').html('');
            $('#total_amount').html('<strong>' + totalAmount.toFixed(2) + '</strong>');
        }
    }
});
</script>



