<?php echo form_open_multipart("/valuation/saveValuationData", array("id" => "valuation_form", "class" => "general-form", "role" => "form")); ?>

<style>
    /* Limit dropdown height and enable scrolling */
.bootstrap-select .dropdown-menu.inner {
  max-height: 180px !important;
  overflow-y: auto !important;
}
.card {
   overflow: visible !important;
    border: 0;
}

/* Style the select box itself */
.bootstrap-select .btn-light {
  background-color: #fff;
  border: 1px solid #ccc;
  height: 45px;
  font-size: 15px;
  padding: 10px;
  text-align: left;
}

/* Adjust placeholder text */
.bootstrap-select .bs-placeholder {
  color: #999 !important;
}
</style>
<div class="modal-body clearfix ibox-body">
    <div class="row">
        <div class="card">
            <div class="card-header"><strong>Valuation Details</strong></div>
            <div class="card-body">
                
                <!-- Enhanced Customer Field -->
                <div class="form-group row align-items-center">
                    <label for="customer" class="col-md-2 col-form-label font-weight-bold">Customer</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control selectpicker customer-select"
                                    data-live-search="true"
                                    name="customer_id"
                                    id="customer"
                                    data-rule-required="1"
                                    data-msg-required="This field is required"
                                    title="Select Customer">
                                <?php echo dropDownStr($customerArray, isset($customer_id) ? $customer_id: '', 'Select Customer'); ?>
                            </select>
                            <input type="hidden" name="id" value="<?php echo isset($data[0]['id']) ? $data[0]['id'] : ''; ?>">
                        </div>
                        <small class="form-text text-muted">Choose the customer associated with this invoice.</small>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table table-bordered" id="valuationTable">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Gross Wt.</th>
                                <th>Dust</th>
                                <th>Net Weight</th>
                                <th>Rate</th>
                                <th>Action</th>
                                <th>Round of</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data)) : ?>
                                <?php foreach ($data as $row) : ?>
                                    <tr class="valuation-row">
                                        <td>
                                            <input type="hidden" name="val_id[]" value="<?php echo isset($row['val_id']) ? $row['val_id'] : ''; ?>">
                                            <select class="form-control selectpicker item-select" 
                                                    data-live-search="true"
                                                    name="item_id[]"
                                                    id="item_id"
                                                    data-rule-required="1"
                                                    data-msg-required="This field is required">
                                                
                                                <?php echo dropDownStr($itemArray, isset($row['item_id']) ?$row['item_id'] : '', 'Select Item'); ?>
                                            </select>
                                        </td>
                                        <td><input type="number" step="any" name="gross_wt[]" data-rule-required="1"  data-msg-required="This field is required" class="form-control gross" value="<?php echo $row['gross_weight']; ?>" /></td>
                                        <td><input type="number" step="any" name="dust[]" class="form-control dust" value="<?php echo $row['dust']; ?>" /></td>
                                        <td><input type="number" step="any" name="net_wt[]" class="form-control net" value="<?php echo $row['net_weight']; ?>" readonly /></td>
                                        <td><input type="number" step="any" name="rate[]" class="form-control rate" value="<?php echo $row['rate']; ?>" /></td>
                                        <td>
                                            <select name="round_type[]" class="form-control action-select">
                                                <option value="">Select</option>
                                                <option value="1" <?php echo ($row['round_type'] == 1) ? 'selected' : ''; ?>>-</option>
                                                <option value="2" <?php echo ($row['round_type'] == 2) ? 'selected' : ''; ?>>+</option>
                                            </select>
                                        </td>
                                        <td><input type="number" step="any" name="round[]" class="form-control round_value" value="<?php echo $row['round_value']; ?>"/></td>
                                        <td><input type="text" step="any" name="amount[]" class="form-control amount" value="<?php echo $row['amount']; ?>" readonly /></td>
                                        <td>
                                            <?php if (empty($row['id'])) : ?>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-row">Delete</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr class="valuation-row">
                                    <td>
                                        <select class="form-control selectpicker item-select" 
                                                data-live-search="true"
                                                name="item_id[]"
                                                id="item_id"
                                                data-rule-required="1"
                                                data-msg-required="This field is required">
                                            
                                            <?php echo dropDownStr($itemArray, '', 'Select Item'); ?>
                                        </select>
                                    </td>
                                    <td><input type="number" step="any" name="gross_wt[]" data-rule-required="1"  data-msg-required="This field is required" class="form-control gross" /></td>
                                    <td><input type="number" step="any" name="dust[]" class="form-control dust" /></td>
                                    <td><input type="number" step="any" name="net_wt[]" class="form-control net" readonly /></td>
                                    <td><input type="number" step="any" name="rate[]" class="form-control rate" value="" /></td>
                                    <td>
                                        <select name="round_type[]" class="form-control action-select">
                                            <option value="">Select</option>
                                            <option value="1">-</option>
                                            <option value="2">+</option>
                                        </select>
                                    </td>
                                    <td><input type="number" step="any" name="round[]" class="form-control round_value" value="" /></td>
                                    <td><input type="text" step="any" name="amount[]" class="form-control amount" readonly /></td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                    <template id="valuationRowTemplate">
                        <tr class="valuation-row">
                            <td>
                                <select class="form-control selectpicker item-select" 
                                        data-live-search="true"
                                        name="item_id[]"
                                        data-rule-required="1"
                                        data-msg-required="This field is required">
                                    <?php echo dropDownStr($itemArray, '', 'Select Item'); ?>
                                </select>
                            </td>
                            <td><input type="number" step="any" name="gross_wt[]" class="form-control gross" /></td>
                            <td><input type="number" step="any" name="dust[]" class="form-control dust" /></td>
                            <td><input type="number" step="any" name="net_wt[]" class="form-control net" readonly /></td>
                            <td><input type="number" step="any" name="rate[]" class="form-control rate" /></td>
                            <td>
                                <select name="round_type[]" class="form-control action-select">
                                    <option value="">Select</option>
                                    <option value="1">-</option>
                                    <option value="2">+</option>
                                </select>
                            </td>
                            <td><input type="number" step="any" name="round[]" class="form-control round_value" /></td>
                            <td><input type="text" step="any" name="amount[]" class="form-control amount" readonly /></td>
                            <td><a href="javascript:void(0);" class="btn btn-danger btn-sm delete-row">Delete</a></td>
                        </tr>
                    </template>

                </div>

                <div class="form-group mt-3">
                    <?php 
                        $isEdit = isset($data[0]['id']); // or whatever variable you're using
                        $canEdit = $isEdit && checkPermission("valuation_management", "is_edit");
                        $canAdd = !$isEdit && checkPermission("valuation_management", "is_add");
                        if ($canEdit || $canAdd) { 
                    ?>
                        <button type="submit" class="btn customerd btn-primary">Submit</button>    
                    <?php } ?>
                    <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                    <button type="button" class="btn btn-success" id="addRow">Add More Fields</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
    <!-- OTP Modal -->
    

<?php echo form_close(); ?>

<div id="otpModal" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
			<div class="modal-content" id="otpModalContent">
				<!-- Content will be loaded here -->
			</div>
		</div>
	</div>

<script type="text/javascript">
$(document).ready(function () {

    $(document).on('input', '.gross,.dust,.rate,.round_value', function () {
        let val = $(this).val();
        // Allow only up to 2 digits after decimal
        if (val.indexOf('.') >= 0) {
            const parts = val.split('.');
            parts[1] = parts[1].substring(0, 2); // limit to 2 decimal places
            $(this).val(parts.join('.'));
        }
    });
   
    // Calculate row values
    function calculateRow($row) {
        let gross = parseFloat($row.find('.gross').val()) || 0;
        let dust = parseFloat($row.find('.dust').val()) || 0;
        let rate = parseFloat($row.find('.rate').val()) || 0;
        let action = $row.find('.action-select').val();
        let round_value = parseFloat($row.find('.round_value').val()) || 0;

        let netWeight = gross - dust;
        $row.find('.net').val(netWeight.toFixed(2));

        let amount = (netWeight/10) * rate;
        if (action == '2') {
            amount = parseFloat(amount + round_value);
        } else if (action == '1') {
            amount = parseFloat(amount - round_value);
        }
        
        $row.find('.amount').val(amount.toFixed(2));
    }

    // Event handlers
    $('#valuationTable').on('input change', '.gross, .dust, .rate, .round_value, .action-select', function () {
        calculateRow($(this).closest('tr'));
    });
  
    $('#addRow').on('click', function () {
        let template = document.querySelector('#valuationRowTemplate');
        let clone = template.content.cloneNode(true);
        $('#valuationTable tbody').append(clone);
        
        // Reapply selectpicker to the new select
        $('.item-select').selectpicker('refresh');
    });


    $('#valuationTable').on('click', '.delete-row', function () {
        if ($('#valuationTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert("You must have at least one item row");
        }
    });

    $("#valuation_form").appForm({
        isModal: true, // Important: Prevent parent modal auto-close
        onSuccess: function (result) {
            $('#valuation_table').DataTable().ajax.reload(null, false);
            $("#otpModalContent").load("/valuation/loadOtpModel/", function () {
				$("#otpModal").modal("show");
			});
        }
    });

    $('#verifyOtpBtn').on('click', function () {
        var otp = $('#otp').val();
        
        $.post('/valuation/verifyOtp', { otp: otp}, function (response) {

            response = JSON.parse(response);
            if (response.success) {
                $('#otpModal').modal('hide');
                $('.modal').modal('hide'); 
                
                // $('#valuation_table').DataTable().ajax.reload(null, false);
                toastr.success(response.message);
                console.log(response,'response');
                const valuationId =response.valuation_id;
                const source = 'viewValuation';
                window.location.href = `/valuation/index/?valuation_id=${valuationId}&source=${source}`;
                // window.location.href = '/valuation/index/';
            } else {
                $('#otpError').show();
                toastr.error(response.message);
                $('#valuation_table').DataTable().ajax.reload(null, false);
            }
        });
    });
     // Initialize select picker
    $('.customer-select').selectpicker();
    $('.item-select').selectpicker(); 

    
});
$(document).ready(function () {
    $('#otpModal').on('hidden.bs.modal', function () {
        $('body').removeClass('otp-modal-open');
    });
});
</script>