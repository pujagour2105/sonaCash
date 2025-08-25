<?php echo form_open_multipart("/customer/saveCustomerData", array("id" => "customer_form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix ibox-body">
    <input class="form-control" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
    
    <div class="row">
        <div class="col-md-3 form-group mb-4">
            <label>Name <span class="text-danger">*</span></label>
            <input class="form-control" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" placeholder="Customer Name" data-rule-required="true" data-msg-required="Name is required.">
        </div>
        <div class="col-md-3 form-group mb-4">
            <label>Email</label>
            <input class="form-control" name="email" type="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" placeholder="Email (optional)">
        </div>
        <div class="col-md-3 form-group mb-4">
            <label>Mobile <span class="text-danger">*</span></label>
            <input class="form-control" name="mobile" type="text"
            data-rule-digits="true"
            data-rule-minlength="10"
            data-rule-maxlength="10"
            data-rule-mobileIND="true"
            data-msg-mobileIND="Please enter a valid 10-digit mobile number." 
            value="<?php echo isset($data['mobile']) ? $data['mobile'] : ''; ?>" placeholder="Mobile Number" data-rule-required="true" data-msg-required="Mobile number is required.">
        </div>
        <div class="col-md-3 form-group mb-4">
            <label>Status <span class="text-danger">*</span></label>
            <select class="form-control selectpicker" name="status" data-rule-required="true"
                data-msg-required="Status is required.">
                <option value="1" <?php echo (isset($data['status']) && $data['status'] == '1') ? 'selected' : ''; ?> selected>Active</option>
                <option value="0" <?php echo (isset($data['status']) && $data['status'] == '0') ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-4">
            <label>Address</label>
            <textarea name="address" rows="3" class="form-control" placeholder="Customer Address (optional)"><?php echo isset($data['address']) ? $data['address'] : ''; ?></textarea>
        </div>
        <div class="col-md-6">
            
            <label for="image">Upload Aadhar</label>
            <input type="file" name="image" id="image" accept="image/*,application/pdf" class="form-control">
        
                        
            <?php if (!empty($data['image'])): ?>
                <?php 
                    $file = $data['image'];
                    $file_path = base_url('uploads/' . $file);
                    $file_full_path = FCPATH . 'uploads/' . $file;
                    $file_ext = pathinfo($file, PATHINFO_EXTENSION);
                ?>
                <div class="mt-2">
                    <?php if (file_exists($file_full_path)): ?>
                        <?php if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])): ?>
                            <img src="<?= $file_path ?>" 
                                alt="Aadhar Image" 
                                class="img-thumbnail" 
                                style="max-height: 150px;">
                                <?php elseif (strtolower($file_ext) == 'pdf'): ?>
                                <div style="border: 1px dashed #ccc; padding: 10px; background-color: #f9f9f9; color: #555; border-radius: 6px; text-align: center;">
                                <a href="<?= $file_path ?>" target="_blank"><i class="fa fa-info-circle" style="margin-right: 5px; color: #888;"></i>View PDF File</a>
                                </div>
                            <?php else: ?>
                                <p>Unsupported file type.</p>
                            <?php endif; ?>
                            <?php else: ?>
                                <div style="border: 1px dashed #ccc; padding: 10px; background-color: #f9f9f9; color: #555; border-radius: 6px; text-align: center;">
                                <i class="fa fa-info-circle" style="margin-right: 5px; color: #888;"></i> File not found.
                            </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>   
    </div>

    <div class="row">
        
        

        
    </div>

</div>
    <div class="modal-footer justify-content-start">
        <?php 
            $isEdit = isset($data['id']); // or whatever variable you're using
            $canEdit = $isEdit && checkPermission("customer_management", "is_edit");
            $canAdd = !$isEdit && checkPermission("customer_management", "is_add");
            if ($canEdit || $canAdd) { 
        ?>
            <button type="submit" class="btn customerd btn-primary">Save</button>    
        <?php } ?>
        <!-- <button type="submit" class="btn customerd btn-primary">Save</button>     -->
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {

        $("#customer_form").appForm({
            onSuccess: function(result) {

                $('#customer_table').DataTable().page.info();
                $('#customer_table').DataTable().ajax.reload(null, false);
                if (result.redirect) {
                    window.location.href = result.redirect;
                }

            }
        });
        $(".selectpicker").selectpicker({
            "title": "Select Options"
        }).selectpicker("render");
    });
</script>