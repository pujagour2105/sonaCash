<?php echo form_open_multipart("/branch/savFundData", array("id" => "fund_form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix ibox-body">
    <div class="row">
        <input class="form-control" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
        <div class="col-sm-6 form-group mb-4">
            <label class="d-block">Branch</label>
            <select class="selectpicker form-control branchfund" data-live-search="true" name="branch_id" data-rule-required="0" data-msg-required="This field is required">
                <?php 
                echo dropDownStr($branchArray, isset($data['branch_id']) ? $data['branch_id'] : '', ''); ?>
            </select>
        </div>
        <div class="col-sm-6 form-group mb-4">
            <label>Balance Amount</label>
            <input class="form-control" name="balance_amount" id="balance_amount" type="text" readonly placeholder="Balance Amount">
        </div>
        <div class="col-sm-6 form-group mb-4">
            <label>Amount</label>
            <input class="form-control amount" name="amount" step="any" type="text" value="<?php echo isset($data['amount']) ? $data['amount'] : ''; ?>" placeholder="Amount" data-rule-required="0" data-msg-required="This field is required." <?php echo isset($data['amount']) ? 'readonly' : ''; ?>>
        </div>
        <div class="col-sm-6 form-group mb-4">
            <label>Description</label>
            <input class="form-control" name="description" type="text" value="<?php echo isset($data['description']) ? $data['description'] : ''; ?>" placeholder="Description" data-rule-required="0" data-msg-required="This field is required.">
        </div>
    </div>


</div>
    <div class="modal-footer justify-content-start">
        <?php 
            $isEdit = isset($data['id']); // or whatever variable you're using
            $canEdit = $isEdit && checkPermission("branch_fund_management", "is_edit");
            $canAdd = !$isEdit && checkPermission("branch_fund_management", "is_add");
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

        $("#fund_form").appForm({
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