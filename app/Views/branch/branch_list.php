<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Branches</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
				<?php if(checkPermission("branch_management", "is_add")) { ?>
					<a href="javascript:;" class="btn btn-primary btn-sm" data-modal-lg="1" data-post-type="branch" data-act="ajax-modal" data-title="Add Branch"  data-action-url="<?php echo base_url('branch/addForm'); ?>"> <i class="fa fa-plus"></i> Add Branch</a>
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
						<select class="selectpicker show-tick form-control" id="status-filter" title="Please select" data-style="btn-solid" data-width="150px">
							<option value="">All</option>
							<option value="1">active</option>
							<option value="0">Inactive</option>
						</select>
                    </div>
                </div>
                
				<div class="table-responsive table-cover">
					<table class="table table-bordered table-hover" id="branch_table">
						<thead class="thead-default thead-lg">
							<tr> 
								<th>#</th>
                                <th>Name</th>
								<th>Contact Person</th>
								<th>Contact No.</th>
								<th>Address</th>
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
			
			initDataTable("branch_table", "/branch/get_lists",[], []); // // table id, url, filters, search
		
			$('#status-filter, #state_filter').change(function(event) {
				$('#branch_table').DataTable().destroy();
				_filter=[
						{
							"id":"status-filter",
							"field":"status"
						}
					];
				_search=[
						{ "data":"name" }
					];
				initDataTable("branch_table", "/branch/get_lists",_filter, _search);
			});
		});


		$(".selectpicker").selectpicker({
			"title": "Select Options"
		}).selectpicker("render");

	</script>



