<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    /* Limit dropdown height and enable scrolling */
.bootstrap-select .dropdown-menu.inner {
  max-height: 200px !important;
  overflow-y: auto !important;
}
.bootstrap-select.btn-group .dropdown-toggle .filter-option{
    padding-left:15px !important;
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
.item-row .btn {
    width: 100%;
    padding: 8px 0;
    font-size: 16px;
}
label.error {
    color: red !important;
    font-size: 14px;
    margin-top: 4px;
    display: block;
}
input.is-invalid {
    border-color: red;
}
</style>

<?php echo form_open_multipart("/sale/save_sale_Invoice", array("id" => "invoice_form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix ibox-body">
    <div class="row">
        <div class="col-lg-4">
            <input class="form-control" name="id" type="hidden" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
            <label>Select Customer <span class="text-danger">*</span></label>
            <select class="form-control customer-select" data-live-search="true" name="customer_id" id="customer" data-rule-required="1" data-msg-required="This field is required.">
                <?php echo dropDownStr($customerArray, '', 'Select Customer'); ?>
            </select>
        </div>

        <div class="col-sm-4 form-group mb-4">
            <label>Mobile <span class="text-danger">*</span></label>
            <input class="form-control" name="mobile" id="mobile" type="text" readonly data-rule-required="true" data-msg-required="This field is required.">
        </div>

        <div class="col-sm-4 form-group mb-4">
            <label>Address <span class="text-danger">*</span></label>
            <input class="form-control" name="address" id="address" type="text"  readonly data-rule-required="true" data-msg-required="This field is required.">
        </div>

        
    </div>

 
    <!-- Item Rows -->
    <div id="item-rows">
        <div class="row item-row">
            <div class="col-sm-3 form-group">
                <label>Item <span class="text-danger">*</span></label>
                <select class="form-control customer-select" data-live-search="true" name="items[0][item_id]" data-rule-required="true" data-msg-required="This field is required.">
                    <?php echo dropDownStr($itemArray, '', 'Select Item'); ?>
                </select>
            </div>
            <div class="col-sm-2 form-group">
                <label>Quantity <span class="text-danger">*</span></label>
                <input class="form-control quantity" name="items[0][quantity]" type="number" min="1" value="1" data-rule-required="true" data-msg-required="This field is required.">
            </div>
            <div class="col-sm-2 form-group">
                <label>Total Gross Weight <span class="text-danger">*</span></label>
                <input class="form-control gross-wt" name="items[0][gross_wt]" type="number"  step="0.01" data-rule-required="true" data-msg-required="This field is required.">
            </div>
            <div class="col-sm-2 form-group">
                <label>Rate (per 10 grams) <span class="text-danger">*</span></label>
                <input class="form-control rate" name="items[0][rate]" type="number"min="0" 
                step="0.01" 
                data-rule-required="true" data-msg-required="This field is required.">
            </div>
            <div class="col-sm-2 form-group">
                <label>Amount</label>
                <input class="form-control amount" name="items[0][amount]" type="text" value="0" readonly>
            </div>
            <div class="col-sm-1 form-group d-flex align-items-end justify-content-end">
                <button type="button" class="btn btn-success add-item-row mb-2">+</button>
            </div>
        </div>
    </div>


    <div class="row mt-2">
        <div class="col-sm-6 form-group">
            <div class="col-sm-9 form-group mb-4" id="pan-upload-wrapper" style="display: none;">
                <label>Upload PAN Card <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="pan_card" accept=".jpg,.jpeg,.png,.pdf">
            </div>
        </div>
        <div class="col-sm-1 form-group">
           <label>Type</label>
            <select name="round_type[]" class="form-control round_type">
                <option value="1">-</option>
                <option value="2">+</option>
            </select>
        </div>
        <div class="col-sm-2 form-group">
            <label>Round</label>
            <input type="text" name="round[]" class="form-control round_value" step="any" />
        </div>
       
        <div class="col-sm-3 form-group">
            <label>Total Amount</label>
            <input class="form-control" name="total_amount" id="total_amount" type="text" readonly>
        </div>
    </div>


    <div class="row mt-2">
        <div class="col-sm-3 form-group mb-4">
            <label>Cash Payment Amount</label>
            <input class="form-control" name="cash_amount" id="cash_amount" type="text">
        </div>
        <div class="col-sm-3 form-group mb-4" id="online-payment-mode-wrapper">
            <label>Online Payment Mode <span class="text-danger">*</span></label>
            <select class="form-control" name="paymentMode" id="paymentMode">
                <option value="">-- Please Select --</option>
                <option value="NEFT">NEFT</option>
                <option value="UPI">UPI</option>
                <option value="RTGS">RTGS</option>
                <option value="IMPS">IMPS</option>
            </select>
            <span id="paymentMode-error" class="error" style="display:none; color:red;">Please select payment mode.</span>
        </div>


        <div class="col-sm-3 form-group mb-4">
            <label>Online Payment Amount </label>
            <input class="form-control" name="online_amount" id="online_amount" type="text">
        </div>
        
        <div class="col-sm-3 form-group">
            <label>Balance Amount</label>
            <input class="form-control" name="balance_amount" id="balance_amount" type="text" readonly>
        </div>
    </div>

    <div class="col-sm-12 form-group mb-3" id="payment-note" style="display: none; font-size:16px;">
        <strong class="text-danger">PAYMENT UNDER PROCESS IN NEFT/RTGS</strong>
    </div>
</div>

<div class="modal-footer justify-content-start">
    <?php 
        $isEdit = isset($data['id']); // or whatever variable you're using
        $canEdit = $isEdit && checkPermission("invoice_managment", "is_edit");
        $canAdd = !$isEdit && checkPermission("invoice_managment", "is_add");
        if ($canEdit || $canAdd) { 
    ?>
        <button type="submit" class="btn customerd btn-primary">Save</button>    
    <?php } ?>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

</form>

<!-- JavaScript -->
<script>
$(document).ready(function () {
       


    // Calculate Amount = Quantity * Rate and Total

    $(document).on('input', '.rate,.gross-wt,.round_value', function () {
        let val = $(this).val();
        // Allow only up to 2 digits after decimal
        if (val.indexOf('.') >= 0) {
            const parts = val.split('.');
            parts[1] = parts[1].substring(0, 2); // limit to 2 decimal places
            $(this).val(parts.join('.'));
        }
    });

    function updateAmounts() {
        let total = 0;
        const roundType = parseInt($('.round_type').val()) || 1;
        const roundValue = parseFloat($('.round_value').val()) || 0;
        $('#item-rows .item-row').each(function () {
            const qty = parseFloat($(this).find('.quantity').val()) || 0;
            const rate = parseFloat($(this).find('.rate').val()) || 0;
            const gross_wt = parseFloat($(this).find('.gross-wt').val()) || 0;

            const amount = (gross_wt / 10) * rate;
            $(this).find('.amount').val(amount.toFixed(2));
            total += amount;
        });
        if (roundType === 1) {
            total -= roundValue;
        } else {
            total += roundValue;
        }
        $('#total_amount').val(total.toFixed(2));
    
        if (total >= 200000) {
            $('#pan-upload-wrapper').show();
            $('input[name="pan_card"]').attr('required', true);
        } else {
            $('#pan-upload-wrapper').hide();
            $('input[name="pan_card"]').removeAttr('required');
        }
    }

    // Bind quantity or rate change
    $(document).on('input', '.rate,.gross-wt,.round_value,.round_type', function () {
        updateAmounts();
        updateBalanceAmount();
    });

    // Add item row


    
    // Customer autofill

    $('#customer').on('change', function () {
        const customerId = $(this).val();
        if (customerId) {
            $.post('/invoice/getCustomerDetails', { id: customerId }, function (res) {
                if (res.success) {
                    $('#mobile').val(res.data.mobile);
                    $('#address').val(res.data.address);
                }
            }, 'json');
        }
    });

    $('#cash_amount, #online_amount').on('input', function () {
        updateBalanceAmount();
        toggleOnlinePaymentMode();
    });
    // Payment note toggle
    $('#paymentMode').on('change', function () {
        $('#payment-note').toggle($(this).val() !== 'CASH' && $(this).val() !== '');
    }).trigger('change');


    function updateBalanceAmount() {
        const total = parseFloat($('#total_amount').val()) || 0;
        const cash = parseFloat($('#cash_amount').val()) || 0;
        const online = parseFloat($('#online_amount').val()) || 0;

        const paid = cash + online;
        const balance = total - paid;

        $('#balance_amount').val(balance.toFixed(2));

        // Clear any previous error
        $('#balance_amount').removeClass('is-invalid');
        $('#balance-error').remove();

        // Validation
        if (balance < 0 || balance > total) {
            $('#balance_amount').addClass('is-invalid');
            $('#balance_amount').after('<label id="balance-error" class="error">Balance must be between 0 and total amount.</label>');
        }
    }


    function toggleOnlinePaymentMode() {
        const onlineAmount = parseFloat($('#online_amount').val()) || 0;

        if (onlineAmount > 0) {
            $('#paymentMode').closest('.form-group').show(); // Show the field
            $('#paymentMode').attr('required', true); 
            if( $('#paymentMode').val() === '') {
                $('#paymentMode-error').show(); // Show inline error if not selected
            } else {
                $('#paymentMode-error').hide(); // Hide error if selected
            }
        } else {
            $('#paymentMode').closest('.form-group').show(); // Hide the field
            $('#paymentMode').removeAttr('required');        // Remove required
        }
    }

    // Form submit
    $('#invoice_form').on('submit', function (e) {
        e.preventDefault();

        if (!$(this).valid()) return;

        const onlineAmount = parseFloat($('#online_amount').val()) || 0;
        const paymentMode = $('#paymentMode').val();

        if (onlineAmount > 0 && !paymentMode) {
            $('#paymentMode-error').show();  // Show inline error
            $('#paymentMode').focus();
            return false;
        }


        const total = parseFloat($('#total_amount').val()) || 0;
        if (total >= 200000 && !$('input[name="pan_card"]').val()) {
            alert("PAN Card is required for amount ≥ 200000");
            return false;
        }

        if (!confirm("Are you sure you want to submit this form?")) return false;

        const formData = new FormData(this);
        $.ajax({
            url: '/sale/save_sale_Invoice',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                if (res.success && res.redirect_url) {
                    window.location.href = res.redirect_url;
                } else {
                    alert(res.message || "Something went wrong.");
                }
            },
            error: function () {
                alert("Submission error.");
            }
        });
    });

    updateAmounts(); // initial run
    toggleOnlinePaymentMode();
    $('.customer-select').selectpicker();

    
});
</script>
<script>
$(document).ready(function () {
    let rowIndex = 1;
    $(document).off('click', '.add-item-row').on('click', '.add-item-row', function () {
        const newRow = `
        <div class="row item-row">
            <div class="col-sm-3 form-group">
                <select class="form-control customer-select selectpicker" name="items[${rowIndex}][item_id]" data-live-search="true" data-rule-required="true" data-msg-required="This field is required.">
                    <?php echo dropDownStr($itemArray, '', 'Select Item'); ?>
                </select>
            </div>
            <div class="col-sm-2 form-group">
                <input class="form-control quantity" name="items[${rowIndex}][quantity]" type="number" min="1" value="1" data-rule-required="true" data-msg-required="This field is required.">
            </div>
            <div class="col-sm-2 form-group">
                <input class="form-control gross-wt" name="items[${rowIndex}][gross_wt]" type="number"  step="0.01"  data-rule-required="true" data-msg-required="This field is required.">
            </div>
            <div class="col-sm-2 form-group">
                <input class="form-control rate" name="items[${rowIndex}][rate]" type="number"
                 min="0" step="0.01" data-rule-required="true" data-msg-required="This field is required.">
            </div>
            <div class="col-sm-2 form-group">
                <input class="form-control amount" name="items[${rowIndex}][amount]" type="text" value="0" readonly>
            </div>
            <div class="col-sm-1 form-group d-flex align-items-end justify-content-end">
                <button type="button" class="btn btn-danger remove-item-row mb-2">-</button>
            </div>
        </div>`;
    
        $('#item-rows').append(newRow);
        rowIndex++;
        $('.selectpicker').selectpicker('refresh');
    });

    // Remove item row
    $(document).on('click', '.remove-item-row', function () {
        $(this).closest('.item-row').remove();
        updateAmounts();
        updateBalanceAmount();
    });
});
</script>