<?php echo form_open_multipart("/distribution/saveAmtDistribution", array("id" => "distribution_form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix ibox-body">
    <input class="form-control" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
    
    <div class="row">
        <div class="col-md-6 form-group mb-4">
            <label>Name <span class="text-danger">*</span></label>
            <input class="form-control" name="name" type="text"  pattern="[A-Za-z\s]+" title="Only letters are allowed" value="<?php echo isset($data['customer_name']) ? $data['customer_name'] : ''; ?>" placeholder="Customer Name" data-rule-required="true" data-msg-required="Name is required.">
        </div>
        <div class="col-md-6 form-group mb-4">
            <label>Amount <span class="text-danger">*</span></label>
            <input class="form-control amount" name="amount" type="text"  step="any" data-rule-number="true"  value="<?php echo isset($data['amount']) ? $data['amount'] : ''; ?>" data-rule-required="true" placeholder="amount" data-msg-required="Amount is required.">
        </div>
        <div class="col-md-6 form-group mb-4">
            <label>Duration (In Month)<span class="text-danger">*</span></label>
            <input class="form-control" name="duration" type="text"
            data-rule-digits="true"
            value="<?php echo isset($data['duration']) ? $data['duration'] : ''; ?>" placeholder="Duration" data-rule-required="true" data-msg-required="Duration is required.">
        </div>
        <!-- <div class="col-md-3 form-group mb-4">
            <label>Status <span class="text-danger">*</span></label>
            <select class="form-control selectpicker" name="status" data-rule-required="true"
                data-msg-required="Status is required.">
                <option value="1" <?php echo (isset($data['status']) && $data['status'] == '1') ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo (isset($data['status']) && $data['status'] == '0') ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div> -->
        <div class="col-md-6 form-group mb-4">
            <label>Description</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Description"><?php echo isset($data['description']) ? $data['description'] : ''; ?></textarea>
        </div>
        
    </div>

</div>
    <div class="modal-footer justify-content-start">
        
        <?php 
            $isEdit = isset($data['id']); // or whatever variable you're using
            $canEdit = $isEdit && checkPermission("amt_distribution_management", "is_edit");
            $canAdd = !$isEdit && checkPermission("amt_distribution_management", "is_add");
            if ($canEdit || $canAdd) { 
        ?>
            <button type="submit" class="btn customerd btn-primary">Save</button>    
        <?php } ?> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('input', '.amount', function () {
            let val = $(this).val();
            // Allow only up to 2 digits after decimal
            if (val.indexOf('.') >= 0) {
                const parts = val.split('.');
                parts[1] = parts[1].substring(0, 2); // limit to 2 decimal places
                $(this).val(parts.join('.'));
            }
        });

        $("#distribution_form").appForm({
            onSuccess: function(result) {

                $('#amount_distribution_table').DataTable().page.info();
                $('#amount_distribution_table').DataTable().ajax.reload(null, false);

            }
        });
        $(".selectpicker").selectpicker({
            "title": "Select Options"
        }).selectpicker("render");
    });
</script>