<?php echo form_open_multipart("/branch/savData", array("id" => "branch_form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix ibox-body">
    <div class="row">
        <input class="form-control" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
        
        <div class="col-sm-6 form-group mb-4">
            <label>Name</label>
            <input class="form-control" name="branch_name" type="text" value="<?php echo isset($data['branch_name']) ? $data['branch_name'] : ''; ?>" placeholder="Branch name" data-rule-required="0" data-msg-required="This field is required.">
        </div>
        <div class="col-sm-6 form-group mb-4">
            <label>Contact Person</label>
            <input class="form-control" name="contact_person" type="text" value="<?php echo isset($data['contact_person']) ? $data['contact_person'] : ''; ?>" placeholder="Contact Person" data-rule-required="0" data-msg-required="This field is required.">
        </div>
        <div class="col-sm-6 form-group mb-4">
            <label>Contact Number</label>
            <input class="form-control" name="contact_no" type="text" value="<?php echo isset($data['contact_no']) ? $data['contact_no'] : ''; ?>" placeholder="Contact Number" data-rule-required="0" data-msg-required="This field is required.">
        </div>
        <div class="col-sm-6 form-group mb-4">
            <label>Email Id</label>
            <input class="form-control" name="email_id" type="email" value="<?php echo isset($data['email_id']) ? $data['email_id'] : ''; ?>" placeholder="Email Id" >
        </div>
        <div class="col-sm-6 form-group mb-4">
            <label>Address</label>
            <input class="form-control" name="address" type="text" value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>" placeholder="Address" data-rule-required="0" data-msg-required="This field is required.">
        </div>
        <div class="col-sm-6 form-group mb-4">
            <label>GST. No</label>
            <input class="form-control" name="gst_no" type="text" value="<?php echo isset($data['gst_no']) ? $data['gst_no'] : ''; ?>" placeholder="GST" data-rule-required="0" data-msg-required="This field is required.">
        </div>

        <div class="col-sm-4 form-group mb-4">
            <label class="d-block">Status</label>
            <select class="selectpicker form-control" data-live-search="true" name="status" data-rule-required="0" data-msg-required="This field is required">
                <?php 
                echo dropDownStr(statusArray, isset($data['status']) ? $data['status'] : '', ''); ?>
            </select>
        </div>
        <div class="col-sm-4 form-group mb-4">
            <label class="d-block">Sale Invoice Serial No</label>
            <input class="form-control sale_invoice_no" name="sale_invoice_no" type="number"  step="1"  value="<?php echo isset($data['sale_invoice_no']) ? $data['sale_invoice_no'] : ''; ?>" placeholder="serial no" <?php echo !empty($data['sale_invoice_no']) ? 'readonly' : ''; ?>>
        </div>
        <div class="col-sm-4 form-group mb-4">
            <label class="d-block">Purchase Invoice Serial No</label>
            <input class="form-control purchase_invoice_no" name="purchase_invoice_no" type="number"  step="1"  value="<?php echo isset($data['purchase_invoice_no']) ? $data['purchase_invoice_no'] : ''; ?>" placeholder="serial no" <?php echo !empty($data['purchase_invoice_no']) ? 'readonly' : ''; ?>>
        </div>
    </div>


</div>
    <div class="modal-footer justify-content-start">
        <?php 
            $isEdit = isset($data['id']); // or whatever variable you're using
            $canEdit = $isEdit && checkPermission("branch_management", "is_edit");
            $canAdd = !$isEdit && checkPermission("branch_management", "is_add");
            if ($canEdit || $canAdd) { 
        ?>
            <button type="submit" class="btn customerd btn-primary">Save</button>    
        <?php } ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('input', '.sale_invoice_no,.purchase_invoice_no', function () {
            let val = $(this).val();
            // Remove anything that's not a digit (0-9)
            val = val.replace(/[^0-9]/g, '');

            $(this).val(val);
        });

        $("#branch_form").appForm({
            onSuccess: function(result) {

                $('#branch_table').DataTable().page.info();
                $('#branch_table').DataTable().ajax.reload(null, false);

            }
        });
        $(".selectpicker").selectpicker({
            "title": "Select Options"
        }).selectpicker("render");
    });
</script>