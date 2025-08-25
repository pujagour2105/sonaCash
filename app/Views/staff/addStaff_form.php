<?php echo form_open_multipart("/staff/savStaffData", array("id" => "staff_form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix ibox-body">
    <input class="form-control" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
    
    <div class="row">
        <div class="col-sm-6 form-group mb-4">
            <div class="form-group mb-4">
                <label>Name</label>
                <input class="form-control" name="name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" placeholder="Name" data-rule-required="0" data-msg-required="This field is required.">
            </div>

            <div class="form-group mb-4">
                <label>Email</label>
                <input class="form-control" name="email" type="text" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" placeholder="Personal Email" data-rule-required="0" data-msg-required="This field is required.">
            </div>
            
            <div class="form-group mb-4">
                <label>Mobile</label>
                <input class="form-control" name="mobile" type="text" value="<?php echo isset($data['mobile']) ? $data['mobile'] : ''; ?>" placeholder="Mobile" data-rule-required="0" data-msg-required="This field is required.">
            </div>
            <div class="form-group">
                <label class="d-block">Role</label>
                <select class="selectpicker form-control" data-live-search="true" name="role_id" id="role_id" data-rule-required="0" data-msg-required="This field is required">
                    <?php 
                    echo dropDownStr($roleArray, isset($data['role_id']) ? $data['role_id'] : '', ''); ?>
                </select>
            </div>

            <div class="form-group">
                <label class="d-block">Branch</label>
                <select class="selectpicker form-control branchfund" data-live-search="true" name="branch_id" data-rule-required="0" data-msg-required="This field is required">
                    <?php 
                    echo dropDownStr($branchArray, isset($data['branch_id']) ? $data['branch_id'] : '', ''); ?>
                </select>
            </div>
            
            
            <div class="form-group mb-4">
                <label>DOB</label>
                <input class="form-control" name="dob" type="date" value="<?php echo isset($data['dob']) ? $data['dob'] : ''; ?>" placeholder="DOB" data-rule-required="0" data-msg-required="This field is required.">
            </div>
            <div class="form-group mb-4">
                <label>DOJ</label>
                <input class="form-control" name="doj" type="date" value="<?php echo isset($data['doj']) ? $data['doj'] : ''; ?>" placeholder="DOJ" data-rule-required="0" data-msg-required="This field is required.">
            </div>

            <div class="form-group mb-4">
                <label class="d-block">Gender</label>
                <select class="form-control" name="gender" data-rule-required="0" data-msg-required="This field is required">
                    <?php
                    $gender = '';
                    if( isset($data['gender']) )
                        $gender = $data['gender'];
                    ?>
                    <option value="">Select</option>
                    <option value="male" <?php echo ($gender == 'male') ? 'selected="selected"' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($gender == 'female') ? 'selected="selected"' : ''; ?>>Female</option>
                </select>
            </div>
            <div class="form-group mb-4">
                <label class="d-block">Address</label>
                <textarea name="address" rows="5" class="form-control"><?php echo isset($data['address']) ? $data['address'] : '', ''; ?></textarea>
            </div>
            
            <div class="form-group mb-4">
                <label class="d-block">Status</label>
                <select class="selectpicker form-control" data-live-search="true" name="status" data-rule-required="0" data-msg-required="This field is required">
                    <?php 
                    echo dropDownStr(statusArray, isset($data['status']) ? $data['status'] : '', ''); ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="checkbox checkbox-success">
                    <input type="checkbox" name="administrator" <?php echo isset($data['is_admin']) ?($data['is_admin']== 1)?'checked="checked"':'':0; ?>>
                <span class="input-span"></span>Administrator</label>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" name="password" type="text" placeholder="Password" data-rule-required="<?php echo isset($data['id']) ?'':0; ?>" data-msg-required="This field is required.">
            </div>
        </div>
        
        
        <div class="col-lg-6 role-access">
            <div class="role-heading">Permissions</div>
            <div class="form-group">
				<table class="table table-bordered roles no-margin">
                    <thead>
                        <tr>
                            <th class="bold">
                                
                                <label class="checkbox checkbox-info">
                                    <input type="checkbox" class="ml-3" id="chkall">
                                    <span class="input-span"></span>
                                    <strong>Permission</strong>
                                </label>
                            </th>
                            <th class="text-center bold">View
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="View" data-original-title="Tooltip on top"></i></th>
                            <th class="text-center bold">Create
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Create" data-original-title="Tooltip on top"></i></th>
                            <th class="text-center bold">Edit
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Tooltip on top"></i></th>
                        </tr>
                    </thead>
					<tbody>
                        <?php
                        if($moduleArray) {
                            foreach($moduleArray as $key => $value) {                                
                                $chkRow = $isview = $isadd = $isedit='';
                                if(isset($data['id'])) {                                    
                                    $isadd = check_centerPermission($value["slug"], "is_add", $data['id']);
                                    $isview = check_centerPermission($value["slug"], "is_view", $data['id']);
                                    $isedit = check_centerPermission($value["slug"], "is_edit", $data['id']);
                                    
                                    if($isview && $isadd && $isedit){
                                        $chkRow='checked="checked"';
                                    }
                                }                                
                                ?>
                                <tr class="row_checked">
                                    <td class="bold">
                                        <label class="checkbox checkbox-info">
                                            <input type="checkbox" class="ml-3 chkrow" data-row="<?php echo $value["id"]; ?>" <?php echo $chkRow; ?>>
                                            <span class="input-span"></span>
                                            <?php echo $value["name"]; ?>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-group">
                                            <label class="checkbox checkbox-info">
                                                <input type="checkbox" class="check" name="view[]" value="<?php echo $value["id"]; ?>" <?php echo ($isview)?'checked="checked"':''; ?>>
                                                <span class="input-span"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-group">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" class="check" name="create[]" value="<?php echo $value["id"]; ?>" <?php echo ($isadd)?'checked="checked"':''; ?>>
                                                <span class="input-span"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-group">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" class="check" name="edit[]" value="<?php echo $value["id"]; ?>" <?php echo ($isedit)?'checked="checked"':''; ?>>
                                                <span class="input-span"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            }
                        }
                        ?>                        
					</tbody>
				</table>
			</div>
        </div>
        <!-- End permission-->
    </div>
    
    <div class="row">
        
        

        
    </div>

</div>
    <div class="modal-footer justify-content-start">
            <?php 
                $isEdit = isset($data['id']); // or whatever variable you're using
                $canEdit = $isEdit && checkPermission("staff_management", "is_edit");
                $canAdd = !$isEdit && checkPermission("staff_management", "is_add");
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

        $("#staff_form").appForm({
            onSuccess: function(result) {

                $('#staff_table').DataTable().page.info();
                $('#staff_table').DataTable().ajax.reload(null, false);

            }
        });
        $(".selectpicker").selectpicker({
            "title": "Select Options"
        }).selectpicker("render");
    });
</script>