
<?php 
$uri = service('uri');
$customer_id = $uri->getSegment(3);

$modal = isset($_GET['modal']) && $_GET['modal'] == '1' ? 1 : 0 ;
$valuation_id = $_GET['valuation_id'] ?? '';
$source = $_GET['source'] ?? '';





?>
<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Valuation List</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
				<?php if(checkPermission("valuation_management", "is_add")) { ?>
					<a href="javascript:;" class="btn btn-primary btn-sm btn-trigger-modal" 
					data-post-type="customer" data-act="ajax-modal" 
					data-title="Add Valuation"  
					data-action-url="<?= base_url('valuation/addValuationForm/' . ($customer_id ?? '')) ?>"> 
					<i class="fa fa-plus"></i> Add Valuation</a>
				<?php } ?>
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
							<option value="1" selected>Pending</option>
						</select>
                    </div>
					<div class="col-lg-3">
                        <label class="mb-0 mr-1">Customer:</label>
						<select class="selectpicker show-tick form-control" id="customer_filter"  data-live-search="true" title="Please select" data-style="btn-solid">
						<option value="">All</option>
						<?php 
							foreach ($customerArray as $id => $name) {
								
								$selected = (isset($customer_id) && $customer_id == $id) ? 'selected' : '';
								echo '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
							}
						?>
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
                        <label class="mb-0 mr-1">Verification Status:</label>
						<select class="selectpicker show-tick form-control" id="verify_filter"  data-live-search="true" title="Please select" data-style="btn-solid">
							<option value="">All</option>
							<option value="0">Not Verified</option>
							<option value="1">Verified</option>
						</select>
                    </div>
					<div class="col-lg-3">
						<label class="mb-0 mr-2">From Date:</label>
						<input type="date" class="form-control" id="from_date" name="from_date">
					</div>
					<div class="col-lg-3">
						<label class="mb-0 mr-2">To Date:</label>
						<input type="date" class="form-control" id="to_date" name="to_date">
					</div>

					<?php $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null; 
					if($checkAdmin == 1){ ?>
						<div class="col-lg-3 mb-0 mt-4">
							<input type="button" class="btn btn-primary btn-md" id="btn-delete" value="delete" onclick="deleteSelected()">
						</div>
					<?php }?>
                </div>

				
				 
                
				<div class="table-responsive table-cover">
					<table class="table table-bordered table-hover" id="valuation_table">
						<thead class="thead-default thead-lg">
							<tr>
								<th class="no-sort">
									<div class="form-group"><label class="checkbox checkbox-success">
									<input type="checkbox" name="checkall" id="selectAll">
									<span class="input-span"></span></label></div> 
									ID</th>
                                <th>Customer Name</th>
                                <th>Mobile</th>
								<th>Branch </th>
								<th>Gross Wt</th>
								<th>Total Amount</th>
								<th>Date</th>
								<th>Verification Status</th>
								<th>Status</th>
								<th class="no-sort">Action</th>
							</tr>
						</thead>
						<tbody>						
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
</div>

	<div id="otpModal" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
			<div class="modal-content" id="otpModalContent">
				<!-- Content will be loaded here -->
			</div>
		</div>
	</div>


<script>	

	$(document).ready(function() {	

		var defaultCustomerId = "<?= $customer_id ?? '' ?>";
		var modalOpen = "<?= $modal ?? '' ?>";
		if (defaultCustomerId) {
			if(modalOpen == '1' ) {
				$('.btn-trigger-modal').trigger('click');
			}
			// Pre-select the dropdown
			$('#customer_filter').val(defaultCustomerId).selectpicker('refresh');
		}

		

		// Define filters
		_filter = [
			{
				id: "status-filter",
				field: "status"
			},
			{
				id: "customer_filter",
				field: "customer_id"
			},
			{
				id: "branch_filter",
				field: "branch_id"
			},
			
			{
				id: "delete_filter",
				field: "is_deleted"
			},
			
		];

		_search = [
			{ data: "name" },
			{ data: "mobile" },
			{ data: "branch_name" },
			{ data: "item_name" },
			{ data: "otp_verified" }
		];

		// Init table with filter
		initDataTable("valuation_table", "/valuation/getValuationLists", _filter, _search);

		// initDataTable("valuation_table", "/valuation/getValuationLists",[], []); // // table id, url, filters, search
		
		$('#status-filter, #customer_filter,#branch_filter,#delete_filter,#verify_filter,#from_date,#to_date').change(function(event) {
			$('#valuation_table').DataTable().destroy();
			
			_filter=[
				{
					"id":"status-filter",
					"field":"status"
				},
				{
					id: "customer_filter",
					field: "customer_id"
				},
				{
					id: "branch_filter",
					field: "branch_id"
				},
				{
					id: "delete_filter",
					field: "is_deleted"
				},
				{
					id: "verify_filter",
					field: "otp_verified"
				},
				{
					"id":"from_date",
					"field":"from_date"
				},
				{
					"id":"to_date",
					"field":"to_date"
				}
				
			];
			_search=[
					{ "data":"name" },{ "data":"mobile" },
					{ "data":"branch_name" },{ "data":"item_name" }
				];
			initDataTable("valuation_table", "/valuation/getValuationLists",_filter, _search);
		});

		$(document).on('click', '.btnChangeStatus', function() {
			var actionUrl = $(this).attr('data-action-url');
			var title = $(this).data('title');
			const confirmed = window.confirm(title);

			if(!confirmed) {
				return;
			}

			$.ajax({
					url: actionUrl,
					type: 'POST',
					dataType: 'json',
					success: function(response) {
						if (response.success == true) {
							$('#valuation_table').DataTable().ajax.reload(null, false);
							toastr.success(response.message);
						} else {
							toastr.error(response.message);
						}
					},
					error: function(xhr, status, error) {
						toastr.error('An error occurred while processing your request.');
					}
				});

		});

		// $(document).on('click', '.btnIsDeleted', function() {
		// 	var actionUrl = $(this).attr('data-action-url');
		// 	var title = $(this).data('title');
		// 	const confirmed = window.confirm(title);

		// 	if(!confirmed) {
		// 		return;
		// 	}

		// 	$.ajax({
		// 			url: actionUrl,
		// 			type: 'POST',
		// 			dataType: 'json',
		// 			success: function(response) {
		// 				if (response.success == true) {
		// 					$('#valuation_table').DataTable().ajax.reload(null, false);
		// 					toastr.success(response.message);
		// 				} else {
		// 					toastr.error(response.message);
		// 				}
		// 			},
		// 			error: function(xhr, status, error) {
		// 				toastr.error('An error occurred while processing your request.');
		// 			}
		// 		});

		// });



		$(document).on('click', '.resendOtp', function() {
			var actionUrl = $(this).attr('data-action-url');
	

			$.ajax({
				url: actionUrl,
				type: 'POST',
				dataType: 'json',
				success: function(response) {
					if (response.success == true) {
						$('#valuation_table').DataTable().ajax.reload(null, false);
						toastr.success(response.message);
						$("#otpModalContent").load("/valuation/loadOtpModel/", function () {
							$("#otpModal").modal("show");
						});

					} else {
						toastr.error(response.message);
					}
				},
				error: function(xhr, status, error) {
					toastr.error('An error occurred while processing your request.');
				}
			});
		});


		// Define Upload Image Button
		var valuationId = "<?= $valuation_id ?? '' ?>";
		var source = "<?= $source ?? '' ?>";
		if (valuationId && source == 'uploadImage') {
        setTimeout(function() {
            var btnID = "btnUploadImage_" + valuationId;
            if ($('#' + btnID).length) {
                $('#' + btnID).trigger('click');
            } else {
                console.warn('Button not found:', btnID);
            }
        }, 2000); // half second delay; adjust if needed
    	}

		if (valuationId && source == 'viewValuation') {
        setTimeout(function() {
            var btnID = "btnviewValuation_" + valuationId;
            if ($('#' + btnID).length) {
                $('#' + btnID).trigger('click');
            } else {
                console.warn('Button not found:', btnID);
            }
        }, 2000); // half second delay; adjust if needed
    	}

	});


	$(".selectpicker").selectpicker({
		"title": "Select Options"
	}).selectpicker("render");


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
				url: '/valuation/deleteMultipleValuations', // 🔁 Change to your controller URL
				type: 'POST',
				data: {
					ids: selected
				},
				dataType: 'json',
				success: function (response) {
					if (response.success) {
						$('#valuation_table').DataTable().ajax.reload(null, false);
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




