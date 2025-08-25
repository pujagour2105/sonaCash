<div class="">
    <!-- START PAGE CONTENT-->
    <div class="page-header">
        <div class="ibox flex-1">
            <?php echo form_open_multipart("/admin/save_company", array("id" => "company-add-form", "class" => "general-form", "role" => "form")); ?>
            <div class="modal-body clearfix ibox-body">
                <div class="row">
                    <input class="form-control" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
                    <div class="col-sm-4 form-group mb-4">
                        <label>Company Name <span class="text-danger">*</span></label>
                        <input class="form-control" name="company_name" type="text" value="<?php echo isset($data['company_name']) ? $data['company_name'] : ''; ?>" placeholder="Company Name" data-rule-required="0" data-msg-required="This field is required.">
                    </div>
                    <div class="col-sm-4 form-group mb-4">
                        <label>Website URL <span class="text-danger">*</span></label>
                        <input class="form-control" name="website_url" type="text" value="<?php echo isset($data['website_url']) ? $data['website_url'] : ''; ?>" placeholder="Website Url" data-rule-required="0" data-msg-required="This field is required.">
                    </div>
                    <div class="col-sm-4 form-group mb-4">
                        <label>Phone/Landline No.</label>
                        <input class="form-control" name="phone_landline" type="text" value="<?php echo isset($data['phone_landline']) ? $data['phone_landline'] : ''; ?>" placeholder="Phone/Landline No." data-rule-required="0" data-msg-required="This field is required.">
                    </div>

                    <div class="col-sm-4 form-group mb-4">
                        <label>Mobile No. <span class="text-danger">*</span></label>
                        <input class="form-control" name="mobile_no" type="text" value="<?php echo isset($data['mobile_no']) ? $data['mobile_no'] : ''; ?>" placeholder="Mobile No." data-rule-required="0" data-msg-required="This field is required.">
                    </div>
                    <div class="col-sm-4 form-group mb-4">
                        <label>Company Email ID <span class="text-danger">*</span></label>
                        <input class="form-control" name="email_id" type="text" value="<?php echo isset($data['email_id']) ? $data['email_id'] : ''; ?>" placeholder="Email Id" data-rule-required="0" data-msg-required="This field is required.">
                    </div>

                    <div class="col-sm-4 form-group mb-4">
                        <label>GST No.</label>
                        <input class="form-control" name="gst_no" type="text" value="<?php echo isset($data['gst_no']) ? $data['gst_no'] : ''; ?>" placeholder="GST No." data-rule-required="" data-msg-required="This field is required.">
                    </div>
                    <div class="col-sm-4 form-group mb-4">
                        <label>Company Address</label>
                        <textarea class="form-control" name="company_address" placeholder="Company Address" data-rule-required="" data-msg-required="This field is required."><?php echo isset($data['company_address']) ? $data['company_address'] : ''; ?></textarea>
                    </div>
                    <div class="col-sm-4 form-group mb-4">
                        <label>Favicon</label>
                        <input type="file" name="favicon" class="form-control" />
                    </div>
                    <div class="col-sm-4 form-group mb-4">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control" />
                    </div>
                    <div class="form-group col-sm-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            </form>

            <script type="text/javascript">
                $(document).ready(function() {

                    $("#company-add-form").appForm({
                        onSuccess: function(result) {

                            $('#company-table').DataTable().page.info();
                            $('#company-table').DataTable().ajax.reload(null, false);

                        }
                    });

                    $(".selectpicker").selectpicker({
                        "title": "Select Options"
                    }).selectpicker("render");
                });
            </script>
        </div>
    </div>

</div>