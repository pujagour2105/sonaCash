<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Customer List</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
				<?php if(checkPermission("customer_management", "is_add")) { ?>
					<a href="javascript:;" class="btn btn-primary btn-sm" data-post-type="customer" data-act="ajax-modal" data-title="Add Customer"  data-action-url="<?php echo base_url('customer/addCustomerForm'); ?>"> <i class="fa fa-plus"></i> Add Customer</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="page-content fade-in-up">
		<div class="ibox">
			<div class="ibox-body"> 
                <div class="row mb-3">
                    <div class="col-lg-3">
                        <label class="mb-0 mr-2">Status:</label>
						<select class="selectpicker show-tick form-control" id="status-filter" title="Please select" data-style="btn-solid">
						<?php 
							echo dropDownStr(statusArray, '', 'All'); ?>
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
					<table class="table table-bordered table-hover" id="customer_table">
						<thead class="thead-default thead-lg">
							<tr>
								<th>#</th>
                                <th>Customer Name</th>
								<th>Email</th>
								<th>Mobile</th>
								<th>Address</th>
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
	$(document).ready(function() {			
		initDataTable("customer_table", "/customer/getCustomerList",[], []); // // table id, url, filters, search
		
		$('#status-filter, #state_filter,#from_date,#to_date').change(function(event) {
			$('#customer_table').DataTable().destroy();
			_filter=[
				{
					"id":"status-filter",
					"field":"status"
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
			initDataTable("customer_table", "/customer/getCustomerList",_filter, _search);
		});
	});


	$(".selectpicker").selectpicker({
		"title": "Select Options"
	}).selectpicker("render");

</script>



