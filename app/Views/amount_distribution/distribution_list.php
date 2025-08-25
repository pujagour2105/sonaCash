<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Amount Distribution</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
				<?php if(checkPermission("amt_distribution_management", "is_add")) { ?>
					<a href="javascript:;" class="btn btn-primary btn-sm" data-post-type="AmountDistribution" data-act="ajax-modal" data-title="Amount Distribution"  data-action-url="<?php echo base_url('distribution/addAmountDistribution'); ?>"> <i class="fa fa-plus"></i> Add Amount Distribution</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="page-content fade-in-up">
		<div class="ibox">
			<div class="ibox-body"> 
                <div class="row mb-3">
      <!--              <div class="col-lg-3">-->
      <!--                  <label class="mb-0 mr-2">Status:</label>-->
						<!--<select class="selectpicker show-tick form-control" id="status-filter" title="Please select" data-style="btn-solid">-->
						<!--	<option value="">All</option>-->
						<!--	<option value="0">Inactive</option>-->
						<!--	<option value="1" selected>Active</option>-->
						<!--</select>-->
      <!--              </div>-->
      
                    <div class="col-lg-3">
						<label class="mb-0 mr-1">Is Approved</label>
						<select class="selectpicker show-tick form-control" id="approval-filter" title="Please select" data-style="btn-solid">
						<?php //echo dropDownStr(statusArray, '', 'All'); ?>
							<option value="all">All</option>
							<option value="0" selected>Pending</option>
							<option value="1">Approved</option>
							<option value="2">Rejected</option>
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

					<div class="col-lg-2">
						<label class="mb-0 mr-1">From Date:</label>
						<input type="date" class="form-control" id="from_date_filter" placeholder="From Date">
					</div>

					<div class="col-lg-2">
						<label class="mb-0 mr-1">To Date:</label>
						<input type="date" class="form-control" id="to_date_filter" placeholder="To Date">
					</div>
					
                </div>
                
				<div class="table-responsive table-cover">
					<table class="table table-bordered table-hover" id="amount_distribution_table">
						<thead class="thead-default thead-lg">
							<tr>
								<th>#</th>
                                <th>Branch</th>
                                <th>Customer Name</th>
								<th>Amount</th>
								<th>Duration in month</th>
								<th>Description</th>
								<th>Date</th>
								<!--<th>Status</th>-->
                                <th>Is Approved Status</th>
								<th class="no-sort" style="width: 150px;">Action</th>
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


<script>		
	$(document).ready(function() {			
		initDataTable("amount_distribution_table", "/distribution/getAllList",[],[]); // // table id, url, filters, search
		
		$('#status-filter, #branch_filter, #from_date_filter, #to_date_filter,#approval-filter').change(function(event) {
			$('#amount_distribution_table').DataTable().destroy();
			_filter=[
				{
					id: "status-filter",
					field: "status"
				},
				{
					id: "branch_filter",
					field: "branch_id"
				},
				{
					id: "from_date_filter",
					field: "from_date"
				},
				{
					id: "to_date_filter",
					field: "to_date"
				},
				{
					id: "approval-filter",
					field: "isApproved"
				},
			];
			_search=[
					{ "data":"customer_name" }
				];
			initDataTable("amount_distribution_table", "/distribution/getAllList",_filter, _search);
		});
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
						$('#amount_distribution_table').DataTable().ajax.reload(null, false);
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

	$(".selectpicker").selectpicker({
		"title": "Select Options"
	}).selectpicker("render");

</script>



