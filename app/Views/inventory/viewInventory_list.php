<div class="content-wrapper">
    <!-- START PAGE CONTENT -->
    <div class="page-heading mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold text-primary">Inventory List</h2>
            </div>
            <div class="col-sm-4 text-right mt-3">
                <?php if(checkPermission("inventory_management", "is_add")) { ?>
                    <a href="javascript:;" 
                       class="btn btn-primary shadow-sm" 
                       data-post-type="inventory" 
                       data-act="ajax-modal" 
                       data-title="Add Inventory"
                       data-action-url="<?php echo base_url('inventory/addInventoryForm'); ?>">
                       <i class="fa fa-plus me-1"></i> Add Inventory
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="page-content fade-in-up">
        <div class="card shadow-lg border-0">
            <div class="card-body">

                <!-- Filter Section -->
                <form id="filterForm" class="row g-3 mb-3">

				 	<div class="col-md-2">
						<label for="status" class="form-label">Status</label>
						<select name="status" id="status_filter" class="form-control">
							<?php $isAdmin = session()->get('is_admin'); ?>
								<option value="" >All</option>
								<option value="0" selected>Pending</option>
							<?php if($isAdmin == 1){ ?>
								<option value="2">Received</option>
							<?php } ?>
							<option value="1">Visible To Branch</option>
						</select>
					</div>
				<!-- Item Dropdown -->
					<div class="col-md-2">
						<label for="item" class="form-label">Item</label>
						<select class="form-control selectpicker" data-live-search="true" name="item" id="item_filter">
							<?php echo dropDownStr($itemArray, '', 'All'); ?>
						</select>
					</div>

					<div class="col-md-2">
						<label class="form-label">Branch</label>
						<select class="form-control selectpicker" name="branch_id" id="branch_filter">
							<?php 
								$checkAdmin = session()->get('is_admin'); 
								$branchId   = session()->get('branch_id');
								if ($checkAdmin == 1) echo '<option value="">All</option>';
								foreach ($branchArray as $branch) {
									$selected = ($checkAdmin != 1 && $branch['id'] == $branchId) ? 'selected' : '';
									echo '<option value="' . $branch['id'] . '" ' . $selected . '>' . htmlspecialchars($branch['branch_name']) . '</option>';
								}
							?>
						</select>
					</div>

					<div class="col-md-3">
						<label class="form-label fw-semibold">From Date</label>
						<input type="date" class="form-control form-control-sm" id="from_date" name="from_date">
					</div>

					<div class="col-md-3">
						<label class="form-label fw-semibold">To Date</label>
						<input type="date" class="form-control form-control-sm" id="to_date" name="to_date">
					</div>

					<div class="col-lg-4 col-md-4 mt-4">
						<button type="button" class="btn btn-primary btn-sm" onclick="printInventoryPDF()"><i class="fa fa-print me-1"></i> printInventoryPDF</button>
						<?php if($checkAdmin == 1){ ?>
							<button type="button" class="btn btn-danger btn-sm" id="btn-delete"><i class="fa fa-trash me-1"></i> Delete</button>
						<?php } ?>
					</div>

				</form>


                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle" id="inventory_table">
                        <thead class="table-dark">
                            <tr>
                               <th class="no-sort">
									<div class="form-group">
										<label>
											<input type="checkbox" name="checkall" id="selectAll">
										 	All IDs
										</label>
									</div>
								</th>
                                <th>Branch</th>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Gross Wt</th>
                                <th>Diamond</th>
                                <th>Silver</th>
                                <th>Net Wt.</th>
                                <th>Per(%)</th>
                                <th>Gold</th>
                                <th>Silver</th>
                                <th>Diamond</th>
                                <th>Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>

						<tfoot>
							<tr>
								<th colspan="4" class="text-end">Page Total</th>
								<th></th> <!-- Gross Wt -->
								<th></th> <!-- Diamond -->
								<th></th> <!-- Silver -->
								<th></th> <!-- Net Wt -->
								<th></th> <!-- Per -->
								<th></th> <!-- Gold -->
								<th></th> <!-- Silver Rate -->
								<th></th> <!-- Diamond Rate -->
								<th></th> <!-- Amount -->
								<th></th>
							</tr>
						</tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
	$(document).ready(function () {

		// --- Initialize Selectpicker ---
		$(".selectpicker").selectpicker({
			title: "Select Options"
		}).selectpicker("render");

		// --- Function to Initialize DataTable ---
		function initInventoryTable() {
			$('#inventory_table').DataTable({
				processing: true,
				serverSide: true,
				destroy: true, // Important to allow reinit
				ajax: {
					url: "/inventory/getInventoryLists",
					type: "POST",
					data: function (d) {
						d.status = $('#status_filter').val();
						d.item_name = $('#item_filter').val();
						d.branch_id = $('#branch_filter').val();
						d.from_date = $('#from_date').val();
						d.to_date = $('#to_date').val();
					}
				},
				footerCallback: function (row, data, start, end, display) {
					var api = this.api();

					// Helper function to convert string → number
					var intVal = function (i) {
						return typeof i === 'string'
							? i.replace(/[\$,]/g, '') * 1
							: typeof i === 'number'
								? i
								: 0;
					};

					// Calculate totals
					var grossTotal = api.column(4, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
					var diamondTotal = api.column(5, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
					var silverTotal = api.column(6, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
					var netTotal = api.column(7, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
					var amountTotal = api.column(12, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);

					// Update footer
					$(api.column(4).footer()).html(grossTotal.toFixed(2));
					$(api.column(5).footer()).html(diamondTotal.toFixed(2));
					$(api.column(6).footer()).html(silverTotal.toFixed(2));
					$(api.column(7).footer()).html(netTotal.toFixed(2));
					$(api.column(12).footer()).html(amountTotal.toFixed(2));
				}
			});
		}

		// --- Init Table on Page Load ---
		initInventoryTable();

		// --- Re-init Table on Filter Change ---
		$('#status_filter, #item_filter, #branch_filter, #from_date, #to_date').change(function () {
			$('#inventory_table').DataTable().destroy();
			initInventoryTable();
		});

		// --- Change Status Button ---
		$(document).on('click', '.btnChangeStatus', function () {
			var actionUrl = $(this).attr('data-action-url');
			var title = $(this).data('title');
			const confirmed = window.confirm(title);

			if (!confirmed) return;

			$.ajax({
				url: actionUrl,
				type: 'POST',
				dataType: 'json',
				success: function (response) {
					if (response.success == true) {
						$('#inventory_table').DataTable().ajax.reload(null, false);
						toastr.success(response.message);
					} else {
						toastr.error(response.message);
					}
				},
				error: function () {
					toastr.error('An error occurred while processing your request.');
				}
			});
		});

	});
</script>

<script>
$(document).ready(function () {
    $('#printTableBtn').on('click', function (e) {
		e.preventDefault();

		let branch_id = $('#branch_filter').val();
		let status = $('#status-filter').val();
		let item = $('#item_filter').val();
		let from_date = $('#from_date').val();
		let to_date = $('#to_date').val();

		let actionUrl = $(this).data('action-url');

		$.ajax({
			url: actionUrl,
			type: 'POST',
			data: {
				branch_id: branch_id,
				status: status,
				item: item
			},
			success: function (response) {
				// Open response in your modal
				$('#ajaxModal').find('.modal-content').html(response);
				$('#ajaxModal').modal('show');
			},
			error: function () {
				alert('Failed to load print view.');
			}
		});
	});

});


</script>

<script>
function printInventoryPDF() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const queryString = new URLSearchParams(formData).toString();
    window.open(`<?= base_url('inventory/generateInventoryPdf') ?>?${queryString}`, '_blank');
}
</script>
<script>
	$(document).ready(function() {
        // When the 'select all' checkbox is clicked
        $('#selectAll').click(function() {
            $('.row-checkbox').prop('checked', this.checked);
        });

        // Optional: To ensure 'select all' checkbox is updated based on individual checkboxes
        $(document).on('change', '.row-checkbox', function() {
			let total = $('.row-checkbox').length;
			let checked = $('.row-checkbox:checked').length;

			$('#selectAll').prop('checked', total > 0 && total === checked);
		});

		$(document).on('click', '#btn-delete', function () {
			var selected = [];
			$('.row-checkbox:checked').each(function () {
				selected.push($(this).val());
			});

			if (selected.length === 0) {
				toastr.warning('Please select at least one record to delete.');
				return;
			}

			const confirmed = window.confirm('Are you sure you want to delete the selected record(s)?');

			if (!confirmed) return;

			$.ajax({
				url: '/inventory/deleteInventory', // 🔁 Change to your controller URL
				type: 'POST',
				data: {
					ids: selected
				},
				dataType: 'json',
				success: function (response) {
					if (response.success) {
						$('#inventory_table').DataTable().ajax.reload(null, false);
						$('#selectAll').prop('checked', false);
						toastr.success(response.message);
					} else {
						toastr.error(response.message);
					}
				},
				error: function () {
					toastr.error('An error occurred while processing your request.');
				}
			});
		});

    });
</script>



