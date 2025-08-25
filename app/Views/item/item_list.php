<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">		
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Item List</h1>
			</div>
			<div class="col-sm-4 text-right mt-3">
				<?php if(checkPermission("item_management", "is_add")) { ?>
					<a href="javascript:;" class="btn btn-primary btn-sm" data-post-type="item" data-act="ajax-modal" data-title="Add Item"  data-action-url="<?php echo base_url('item/addItemForm'); ?>"> <i class="fa fa-plus"></i> Add Item</a>
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
						<select class="selectpicker show-tick form-control" id="status-filter" title="Please select" data-style="btn-solid" data-width="150px">
						<?php 
							echo dropDownStr(statusArray, '', 'All'); ?>
						</select>
                    </div>
                </div>
                
				<div class="table-responsive table-cover">
					<table class="table table-bordered table-hover" id="item_table">
						<thead class="thead-default thead-lg">
							<tr>
								<th>#</th>
                                <th>Item Name</th>
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
		initDataTable("item_table", "/item/getItemLists",[], []); // // table id, url, filters, search
		
		$('#status-filter, #state_filter').change(function(event) {
			$('#item_table').DataTable().destroy();
			_filter=[
				{
					"id":"status-filter",
					"field":"status"
				}
			];
			_search=[
					{ "data":"item_name" }
				];
			initDataTable("item_table", "/item/getItemLists",_filter, _search);
		});
	});


	$(".selectpicker").selectpicker({
		"title": "Select Options"
	}).selectpicker("render");

</script>



