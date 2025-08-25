

<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Purchase Invoice Report</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
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
                        <label class="mb-0 mr-1">Branch:</label>
						<select class="selectpicker show-tick form-control" id="branch_filter"  data-live-search="true" title="Please select" data-style="btn-solid">
						<?php 

						$checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null; // 1 = admin, 0 = non-admin
    					$branchId   = session()->get('branch_id');
						
						$checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
                        $branchId = session()->get('branch_id');

                        if ($checkAdmin == 1) {
                            // Admin: show "All" option selected
                            echo '<option value="" selected>All</option>';
                        }

                        foreach ($branchArray as $branch) {
                            // For non-admins, pre-select their branch
                            $selected = ($checkAdmin != 1 && $branch['id'] == $branchId) ? 'selected' : '';
                            echo '<option value="' . $branch['id'] . '" ' . $selected . '>'
                                . htmlspecialchars($branch['branch_name'], ENT_QUOTES, 'UTF-8')
                                . '</option>';
                        }
						?>
						</select>
                    </div>
		

					<div class="col-lg-3">
                        <label class="mb-0 mr-1">Payment Mode:</label>
						<select class="selectpicker show-tick form-control" id="payment_filter"  data-live-search="true" title="Please select" data-style="btn-solid">
						    <option value="">All</option>
                            <option value="CASH">CASH</option>
                            <option value="NEFT">NEFT</option>
                            <option value="UPI">UPI</option>
                            <option value="RTGS">RTGS</option>
                            <option value="IMPS">IMPS</option>
						</select>
                    </div>

                    <div class="col-lg-3">
						<label class="mb-0 mr-1">From Date:</label>
						<input type="date" class="form-control" id="from_date_filter" placeholder="From Date">
					</div>

					<div class="col-lg-3">
						<label class="mb-0 mr-1">To Date:</label>
						<input type="date" class="form-control" id="to_date_filter" placeholder="To Date">
					</div>

				</div>
                
				<div class="table-responsive table-cover">
					<table class="table table-bordered table-hover" id="item_table">
						<thead class="thead-default thead-lg">
							<tr>
                                <th>Branch name</th>
                                <th>Customer name</th>
                                <th>Total amount</th>
                                <th>Paid amount</th>
                                <th>Balance amount</th>
                                <th>Payment Mode</th>
                                <th>Date</th>
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
var itemTable;

$(document).ready(function () {
     function initializeTable() {
        itemTable = $('#item_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: "/report/get_invoiceReport",
                type: "POST",
                data: function (d) {
                    d.branch_id = $('#branch_filter').val();
                    d.from_date = $('#from_date_filter').val();
                    d.to_date = $('#to_date_filter').val();
                    d.payment_mode = $('#payment_filter').val();
                },
                dataSrc: function (json) {
                    return json.data;
                }
            },
            columns: [
                { title: "Branch name", data: 0 },
                { title: "Customer name", data: 1 },
                { title: "Total amount", data: 2 },
                { title: "Paid amount", data: 3 },
                { title: "Balance amount", data: 4 },
                { title: "Payment Mode", data: 5 },
                { title: "Date", data: 6 }
            ],
            language: {
                emptyTable: "No data found",
                processing: "Loading data... Please wait"
            }
        });
    }

    function reloadTable() {
        if ($.fn.DataTable.isDataTable('#item_table')) {
            itemTable.ajax.reload();
        } else {
            initializeTable();
        }
    }

    // Filter change triggers reload
    $('#branch_filter, #to_date_filter, #from_date_filter, #payment_filter').change(function () {
        reloadTable();
    });

    // Initialize selectpicker
    $(".selectpicker").selectpicker().selectpicker("render");

    // ✅ Force table load if branch_filter has a value (even if it's empty string for admin)
    if ($('#branch_filter').val() !== null) {
        reloadTable();
    }
});


// Print function
function printTable() {
    var table = document.getElementById("item_table");
    var theadHTML = `
        <thead>
            <tr>
                <th>Branch name</th>
                <th>Customer name</th>
                <th>Total amount</th>
                <th>Paid amount</th>
                <th>Balance amount</th>
                <th>Payment Mode</th>
                <th>Date</th>
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
                        margin-top: 20px;
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
                            margin: 10px;
                            padding: 0;
                        }
                        a, button {
                            display: none !important;
                        }
                    }
                </style>
            </head>
            <body>
                <h2>Purchase Invoice Report</h2>
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
</script>


