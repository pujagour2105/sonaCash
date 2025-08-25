<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Invoice List</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
				<?php if(checkPermission("invoice_managment", "is_add")) { ?>
					<a href="javascript:;" class="btn btn-primary btn-sm" data-post-type="invoice" data-act="ajax-modal" data-title="Generate Purchase Invoice"  data-action-url="<?php echo base_url('invoice/addInvoiceForm'); ?>"> <i class="fa fa-plus"></i> Generate Purchase Invoice</a>
				<?php } ?>
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
                </div>
                
				<div class="table-responsive table-cover">
					<table class="table table-bordered table-hover" id="invoice_table">
						<thead class="thead-default thead-lg">
							<tr>
								<th>#</th>
                                <th>Branch Name</th>
								<th>Customer Name</th>
								<th>Mobile</th>
								<th>Gross Weight(gm)</th>
								<th>Amount</th>
								<th>Date</th>
								<th>Action></th>
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
		initDataTable("invoice_table", "/invoice/getInvoiceList",[], []); // // table id, url, filters, search
		
		$('#status-filter, #branch_filter').change(function(event) {
			$('#invoice_table').DataTable().destroy();
			_filter=[
				{
					"id":"branch_filter",
					"field":"branch_id"
				}
			];
			_search=[
					{ "data":"branch_name" },{ "data":"custoemr_name" },{ "data":"mobile" },{ "data":"ts" }
				];
			initDataTable("invoice_table", "/invoice/getInvoiceList",_filter, _search);
		});
	});


	$(".selectpicker").selectpicker({
		"title": "Select Options"
	}).selectpicker("render");

</script>



