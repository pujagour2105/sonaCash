<?php echo form_open_multipart("/item/saveItem", array("id" => "item_form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix ibox-body">
    <div class="row">
        <input class="form-control" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
        
        <div class="col-sm-6 form-group mb-4">
            <label>Item Name</label>
            <input class="form-control" name="item_name" type="text" value="<?php echo isset($data['item_name']) ? $data['item_name'] : ''; ?>" placeholder="Item name" data-rule-required="0" data-msg-required="This field is required.">
        </div>
        
    </div>


</div>
    <div class="modal-footer justify-content-start">
        
         <?php 
            $isEdit = isset($data['id']); // or whatever variable you're using
            $canEdit = $isEdit && checkPermission("item_management", "is_edit");
            $canAdd = !$isEdit && checkPermission("item_management", "is_add");
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

        $("#item_form").appForm({
            onSuccess: function(result) {

                $('#item_table').DataTable().page.info();
                $('#item_table').DataTable().ajax.reload(null, false);

            }
        });
        $(".selectpicker").selectpicker({
            "title": "Select Options"
        }).selectpicker("render");
    });
</script>