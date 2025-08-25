<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Fund List</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
				<?php if(checkPermission("branch_fund_management", "is_add")) { ?>
					<a href="javascript:;" class="btn btn-primary btn-sm" data-modal-lg="1" data-post-type="customer" data-act="ajax-modal" data-title="Add Fund"  data-action-url="<?php echo base_url('branch/add_fund_form'); ?>"> <i class="fa fa-plus"></i> Add Fund</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="page-content fade-in-up">
		<div class="ibox">
			<div class="ibox-body">
                <div class="row">
                    <div class="col-lg-3">
                        <label class="mb-0 mr-2">Status:</label>
						<select class="selectpicker show-tick form-control" id="status-filter" title="Please select" data-style="btn-solid">
							<option value="">All</option>
							<option value="1" selected>active</option>
							<option value="0">Inactive</option>
						</select>
                    </div>
					<div class="col-lg-3">
                        <label class="mb-0 mr-1">Branch:</label>
						<select class="selectpicker show-tick form-control" id="branch_filter"  data-live-search="true" title="Please select" data-style="btn-solid">
						<?php 

						$checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null; // 1 = admin, 0 = non-admin
    					$branchId   = session()->get('branch_id');
						
						if ($checkAdmin == 1) {
							echo '<option value="" selected>All</option>';
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
						<label class="mb-0 mr-2">From Date:</label>
						<input type="date" class="form-control" id="from_date" name="from_date">
					</div>
					<div class="col-lg-3">
						<label class="mb-0 mr-2">To Date:</label>
						<input type="date" class="form-control" id="to_date" name="to_date">
					</div>
                </div>
                
				<div class="table-responsive table-cover">
					<table class="table table-bordered table-hover" id="branch_table">
						<thead class="thead-default thead-lg">
							<tr>
								<th>#</th>
                                <th>Name</th>
								<th>Amount</th>
								<th>Description</th>
								<th>Date</th>
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


<script>
		
		
		
		//var subAdminDatatable;
		$(document).ready(function() {
			
			initDataTable("branch_table", "/branch/get_funds_lists",[], []); // // table id, url, filters, search
		
			$('#status-filter, #branch_filter,#from_date,#to_date').change(function(event) {
				$('#branch_table').DataTable().destroy();
				_filter=[
						{
							"id":"status-filter",
							"field":"status"
						},
						{
							"id":"branch_filter",
							"field":"branch_id"
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
						{ "data":"name" }
					];
				initDataTable("branch_table", "/branch/get_funds_lists",_filter, _search);
			});
		});


		$(".selectpicker").selectpicker({
			"title": "Select Options"
		}).selectpicker("render");

	</script>



