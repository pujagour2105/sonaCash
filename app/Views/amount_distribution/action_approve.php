<?php echo form_open_multipart("/distribution/action_approveDistribution", array("id" => "distribution_form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix ibox-body">
    <input class="form-control" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
    
    <div class="row">

        <div class="col-md-6 form-group mb-4">
            <label>Action <span class="text-danger">*</span></label>
            <select class="form-control selectpicker" name="is_approved" data-rule-required="true"
                data-msg-required="Action is required.">
                <option value="">Please Select Action</option>
                <option value="1" <?php echo (isset($data['is_approved']) && $data['is_approved'] == '1') ? 'selected' : ''; ?>>Approve</option>
                <option value="2" <?php echo (isset($data['is_approved']) && $data['is_approved'] == '2') ? 'selected' : ''; ?>>Reject</option>
            </select>
        </div>
        <div class="col-md-6 form-group mb-4">
            <label>Remark <span class="text-danger">*</span></label>
            <textarea name="remark" rows="3" class="form-control" placeholder="remark"><?php echo isset($data['remarks']) ? $data['remarks'] : ''; ?></textarea>
        </div>
        
    </div>

</div>
    <div class="modal-footer justify-content-start">
        <button type="submit" class="btn customerd btn-primary">Save</button>    
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {

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