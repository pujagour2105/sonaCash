<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Branch Data</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
				<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="printTable()"> 
					<i class="fa fa-print"></i> Print
				</a>
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="exportCustomerExcel()"> 
                    <i class="fa fa-download"></i> Export Excel
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
						
						if ($checkAdmin == 1) {
							// echo '<option value="">All</option>';
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
                                <th>Date</th>
                                <th>Opening amount</th>
                                <th>Total Credit Amount</th>
                                <th>Total Debit Amount</th>
                                <th>Closing Amount</th>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
var itemTable;

$(document).ready(function() {

    // var itemTable;

function initializeTable() {
    itemTable = $('#item_table').DataTable({
        processing: true,
        serverSide: true,
        searching: false, // 🔥 disable searching if needed
        ajax: {
            url: "/report/branchReport",
            type: "POST",
            data: function(d) {
                d.branch_id = $('#branch_filter').val();
                d.from_date = $('#from_date_filter').val();
                d.to_date = $('#to_date_filter').val();
                d.month_id = $('#month_filter').val();
            },
            dataSrc: function(json) {
                // 🛡️ Protect: If backend returns empty due to missing branch_id
                if (!$('#branch_filter').val()) {
                    return [];
                }
                return json.data;
            }
        },
        columns: [
            { title: "Date", data: 0 },
            { title: "Opening Amount", data: 1 },
            { title: "Total Credit Amount", data: 2 },
            { title: "Total Debit Amount", data: 3 },
            { title: "Closing Amount", data: 4 }
        ],
        language: {
            emptyTable: "Please select a branch to load data",
            processing: "Loading data... Please wait"
        }
    });
}

// 🔥 Only reload table if branch_id is selected
$('#branch_filter, #to_date_filter, #from_date_filter, #month_filter').change(function() {
    if ($('#branch_filter').val()) {
        if ($.fn.DataTable.isDataTable('#item_table')) {
            itemTable.ajax.reload();
        } else {
            initializeTable();
        }
    } else {
        if ($.fn.DataTable.isDataTable('#item_table')) {
            itemTable.clear().draw(); // 🔥 Clear table if branch deselected
        }
    }
});
    // Initialize selectpicker nicely
    $(".selectpicker").selectpicker({
        "title": "Select Options"
    }).selectpicker("render");
});

// 📄 Optional: Print function
function printTable() {
    var table = document.getElementById("item_table");

    var theadHTML = `
        <thead>
            <tr>
                <th>Date</th>
                <th>Opening Amount</th>
                <th>Total Credit Amount</th>
                <th>Total Debit Amount</th>
                <th>Closing Amount</th>
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


function exportCustomerExcel() {
    if (!itemTable) {
        alert("Please select a branch and load the table first.");
        return;
    }

    // Get filtered table data
    const data = itemTable.rows({ search: 'applied' }).data();

    if (data.length === 0) {
        alert("No data available to export.");
        return;
    }

    // Header row (must match your DataTable columns)
    let excelData = [['Date', 'Opening Amount', 'Total Credit Amount', 'Total Debit Amount', 'Closing Amount']];

    // Loop through each row of DataTable data
    for (let i = 0; i < data.length; i++) {
        const row = data[i];
        excelData.push([row[0], row[1], row[2], row[3], row[4]]);
    }

    // Create sheet and workbook
    const ws = XLSX.utils.aoa_to_sheet(excelData);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Branch Report");

    // Export to Excel file
    XLSX.writeFile(wb, "Branch_Report.xlsx");
}

</script>



