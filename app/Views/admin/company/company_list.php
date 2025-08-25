<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <div class="row align-items-center">
            <div class="col-sm-8">
                <h1 class="page-title">Company List</h1>
            </div>
            <div class="col-sm-4 text-right mt-3">
                <!-- <a href="javascript:;" class="btn btn-primary btn-sm" data-post-type="customer" data-act="ajax-modal" data-title="Add Company" data-action-url="<?php echo base_url('admin/addCompanyForm'); ?>">
                    <i class="fa fa-plus"></i> Add Company</a> -->
            </div>
        </div>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-body">
                <div class="flexbox mb-4">

                </div>
                <div class="table-responsive table-cover">
                    <table class="table table-bordered table-hover" id="company-table">
                        <thead class="thead-default thead-lg">
                            <tr>
                                <th>#</th>
                                <th>Company Name</th>
                                <th>Website Url</th>
                                <th>Phone/Landline</th>
                                <th>Mobile No.</th>
                                <th>Email Id</th>
                                <th>GST No.</th>
                                <th>Company Address</th>
                                <th>Logo</th>
                                <th>Favicon</th>
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

        initDataTable("company-table", "/admin/getCompanyList", [], []); // // table id, url, filters, search


        $('#status-filter, #verify-filter, #type-filter').change(function(event) {
            //subAdminDatatable.destroy();
            $('#company-table').DataTable().destroy();
            _filter = [{
                "id": "status-filter",
                "field": "status"
            }];
            _search = [{
                "data": "email"
            }, {
                "data": "company_name"
            }, {
                "data": "mobile_no"
            }];
            initDataTable("company-table", "/admin/getCustomerList", _filter, _search);
        });

        $(".selectpicker").selectpicker({
            "title": "Select Options"
        }).selectpicker("render");

    });
</script>