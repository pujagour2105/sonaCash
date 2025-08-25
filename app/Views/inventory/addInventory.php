<?php echo form_open_multipart("/inventory/saveInventoryDetails", array("id" => "inventory_form", "class" => "general-form", "role" => "form")); ?>
<style>
    .card .form-control {
    height: 38px;
}
.inventory-card {
    background: #f9f9f9;
    border-left: 3px solid #007bff;
}
   /* .form-control {
        padding: 0.5rem 0.5rem !important;
    }   */

    /* Limit dropdown height and enable scrolling */
.bootstrap-select .dropdown-menu.inner {
  max-height: 200px !important;
  overflow-y: auto !important;
}
.card {
   overflow: visible !important;
    border: 0;
}
.btn-group .bootstrap-select .form-control .item-select{
    margin-top: -5px !important;
}

/* Style the select box itself */
.bootstrap-select .btn-light {
  background-color: #fff;
  border: 1px solid #ccc;
  height: 50px;
  font-size: 15px;
  padding: 10px;
  text-align: left;
  margin-bottom: 10px !important;
}

/* Adjust placeholder text */
.bootstrap-select .bs-placeholder {
  color: #999 !important;
}
label.error {
    color: red;
    font-size: 13px; /* optional */
    margin-top: 5px;  /* optional */
}
</style>
<div class="modal-body clearfix ibox-body" >
    
    <div class="row">
        <div class="card" >
            <div class="card-body" style="min-height: 500px;" >
                <div id="inventory-body">
                    <?php if (!empty($data)) : ?>
                        <?php foreach ($data as $row): ?>
                            <div class="card border mb-3 inventory-card">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Item Name -->
                                        <div class="col-md-2 mb-2">
                                            <label>Item Name</label>
                                            <select class="form-control selectpicker item-select" name="item_name[]" data-live-search="true" data-rule-required="1" data-msg-required="This field is required">
                                                <?php echo dropDownStr($itemArray, $row['item_id'], 'Select Item'); ?>
                                            </select>
                                            <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="inventory_id[]" value="<?php echo $row['inventory_id']; ?>">
                                        </div>

                                        <!-- Gross Wt -->
                                        <div class="col-md-2 mb-2">
                                            <label>Gross Wt</label>
                                            <input type="text" step="any" name="gross_wt[]" value="<?php echo $row['gross_weight']; ?>" class="form-control gross_wt" data-rule-required="1">
                                        </div>

                                        <!-- Dust -->
                                        <div class="col-md-2 mb-2">
                                            <label>Dust</label>
                                            <input type="text" step="any" name="dust[]" value="<?php echo $row['dust'] ?? 0; ?>" class="form-control dust">
                                        </div>

                                        <!-- Diamond -->
                                        <div class="col-md-2 mb-2">
                                            <label>Diamond</label>
                                            <input type="text" step="any" name="diamond[]" value="<?php echo $row['diamond'] ?? 0; ?>" class="form-control diamond">
                                        </div>

                                        <!-- Silver -->
                                        <div class="col-md-2 mb-2">
                                            <label>Silver</label>
                                            <input type="text" step="any" name="silver[]" value="<?php echo $row['silver'] ?? 0; ?>" class="form-control silver">
                                        </div>

                                        <!-- Net Wt -->
                                        <div class="col-md-1 mb-2">
                                            <label>Net Wt</label>
                                            <input type="text" step="any" name="net_wt[]" value="<?php echo $row['net_weight']; ?>" class="form-control net_wt" readonly>
                                        </div>

                                        <!-- % -->
                                        <div class="col-md-1 mb-2">
                                            <label>Per %</label>
                                            <input type="text" step="any" name="percentage[]" value="<?php echo $row['percentage']; ?>" class="form-control percentage" data-rule-required="1">
                                        </div>

                                        <!-- Gold Rate -->
                                        <div class="col-md-2 mb-2">
                                            <label>Gold Rate(10 gm)</label>
                                            <input type="text" step="any" name="gold_rate[]" value="<?php echo $row['gold_rate']; ?>" class="form-control gold_rate" data-rule-required="1">
                                        </div>

                                        <!-- Dia Rate -->
                                        <div class="col-md-2 mb-2">
                                            <label>Dia. Rate</label>
                                            <input type="text" step="any" name="dia_rate[]" value="<?php echo $row['diamond_rate']; ?>" class="form-control dia_rate">
                                        </div>

                                        <!-- Silver Rate -->
                                        <div class="col-md-2 mb-2">
                                            <label>Silver Rate(1000gm)</label>
                                            <input type="text" step="any" name="silver_rate[]" value="<?php echo $row['silver_rate'] ?? 0; ?>" class="form-control silver_rate">
                                        </div>

                                        <!-- Round Type -->
                                        <div class="col-md-1 mb-2">
                                            <label>Type</label>
                                            <select name="round_type[]" class="form-control round_type">
                                                <option value="1" <?php echo ($row['round_type'] == '1') ? 'selected' : ''; ?>>-</option>
                                                <option value="2" <?php echo ($row['round_type'] == '2') ? 'selected' : ''; ?>>+</option>
                                            </select>
                                        </div>

                                        <!-- Round -->
                                        <div class="col-md-2 mb-2">
                                            <label>Round</label>
                                            <input type="text" step="any" name="round[]" value="<?php echo $row['round'] ?? 0; ?>" class="form-control round_value">
                                        </div>

                                        <!-- Amount -->
                                        <div class="col-md-2 mb-2">
                                            <label>Amount</label>
                                            <input type="text" step="any" name="amount[]" value="<?php echo $row['total_amount']; ?>" class="form-control amount" readonly>
                                        </div>

                                        <!-- Remove Button -->
                                        <div class="col-md-1 text-right mb-2">
                                            <label>&nbsp;</label><br>
                                            <button type="button" class="btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Default empty card if no data -->
                            <div class="card border mb-3 inventory-card">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Item Name -->
                                        <div class="col-md-2 mb-2">
                                            <label>Item Name</label>
                                            <select class="form-control selectpicker item-select"
                                                name="item_name[]" data-live-search="true" 
                                                data-rule-required="1" data-msg-required="This field is required">
                                                <?php echo dropDownStr($itemArray, '', 'Select Item'); ?>
                                            </select>
                                        </div>

                                        <!-- Gross Wt -->
                                        <div class="col-md-2 mb-2">
                                            <label>Gold Gross Wt</label>
                                            <input type="text" name="gross_wt[]" class="form-control gross_wt" step="any" />
                                        </div>

                                        <!-- Dust -->
                                        <div class="col-md-2 mb-2">
                                            <label>Dust</label>
                                            <input type="text" name="dust[]" class="form-control dust " step="any"/>
                                        </div>

                                        <!-- Diamond -->
                                        <div class="col-md-2 mb-2">
                                            <label>Diamond</label>
                                            <input type="text" name="diamond[]" class="form-control diamond" step="any" />
                                        </div>

                                        <!-- Silver -->
                                        <div class="col-md-2 mb-2">
                                            <label>Silver</label>
                                            <input type="text" name="silver[]" class="form-control silver" step="any" onchange="validateNumeric(this)" />
                                        </div>

                                        <!-- Net Wt -->
                                        <div class="col-md-1 mb-2">
                                            <label>Net Wt</label>
                                            <input type="text" name="net_wt[]" class="form-control net_wt" step="any" readonly onchange="validateNumeric(this)" />
                                        </div>

                                        <!-- % -->
                                        <div class="col-md-1 mb-2">
                                            <label>Per %</label>
                                            <input type="text" name="percentage[]" class="form-control percentage" step="any" onchange="validateNumeric(this)" />
                                        </div>

                                        <!-- Gold Rate -->
                                        <div class="col-md-2 mb-2">
                                            <label>Gold Rate (10 gm)</label>
                                            <input type="text" name="gold_rate[]" class="form-control gold_rate" step="any" onchange="validateNumeric(this)"/>
                                        </div>

                                        <!-- Dia Rate -->
                                        <div class="col-md-2 mb-2">
                                            <label>Dia. Rate</label>
                                            <input type="text" name="dia_rate[]" class="form-control dia_rate" step="any" onchange="validateNumeric(this)" />
                                        </div>

                                        <!-- Silver Rate -->
                                        <div class="col-md-2 mb-2">
                                            <label>Silver Rate (1000gm)</label>
                                            <input type="text" name="silver_rate[]" class="form-control silver_rate" step="any"  onchange="validateNumeric(this)"/>
                                        </div>

                                        <!-- Round Type -->
                                        <div class="col-md-1 mb-2">
                                            <label>Type</label>
                                            <select name="round_type[]" class="form-control round_type">
                                                <option value="1">-</option>
                                                <option value="2">+</option>
                                            </select>
                                        </div>

                                        <!-- Round -->
                                        <div class="col-md-2 mb-2">
                                            <label>Round</label>
                                            <input type="text" name="round[]" class="form-control round_value" step="any" onchange="validateNumeric(this)" />
                                        </div>

                                        <!-- Amount -->
                                        <div class="col-md-2 mb-2">
                                            <label>Amount</label>
                                            <input type="text" name="amount[]" class="form-control amount" step="any" readonly />
                                        </div>

                                        <!-- Remove Button -->
                                        <div class="col-md-1 text-right mb-2">
                                            <label>&nbsp;</label><br>
                                            <button type="button" class="btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                </div>


            <!-- <button type="button" class="btn btn-primary" id="add-valuation-row">Add Row</button> -->

                <?php 
                    $isEdit = isset($row['id']); // or whatever variable you're using
                    $canEdit = $isEdit && checkPermission("inventory_management", "is_edit");
                    $canAdd = !$isEdit && checkPermission("inventory_management", "is_add");
                    if ($canEdit || $canAdd) { 
                ?>
                    <button type="submit" class="btn customerd btn-primary">Save</button>    
                <?php } ?>
                <button type="button" class="btn btn-success" id="addRowBtn">Add Row</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-start"> 
       
    </div>
</div>

<?php echo form_close(); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    

    $(document).ready(function () {
       $('.item-select').selectpicker(); 
    
        $('#addRowBtn').on('click', function () {
            let newCard = `
                <div class="card border mb-3 inventory-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-2">
                                <label>Item Name</label>
                                <select class="form-control selectpicker item-select" name="item_name[]" data-live-search="true" data-rule-required="1">
                                    <?php echo dropDownStr($itemArray, '', 'Select Item'); ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2"><label>Gross Wt</label><input type="text" name="gross_wt[]" class="form-control gross_wt" /></div>
                            <div class="col-md-2 mb-2"><label>Dust</label><input type="text" name="dust[]" class="form-control dust" /></div>
                            <div class="col-md-2 mb-2"><label>Diamond</label><input type="text" name="diamond[]" class="form-control diamond" /></div>
                            <div class="col-md-2 mb-2"><label>Silver</label><input type="text" name="silver[]" class="form-control silver" /></div>
                            <div class="col-md-1 mb-2"><label>Net Wt</label><input type="text" name="net_wt[]" class="form-control net_wt" readonly /></div>
                            <div class="col-md-1 mb-2"><label>Per %</label><input type="text" name="percentage[]" class="form-control percentage" /></div>
                            <div class="col-md-2 mb-2"><label>Gold Rate (10 gm)</label><input type="text" name="gold_rate[]" class="form-control gold_rate" /></div>
                            <div class="col-md-2 mb-2"><label>Dia. Rate</label><input type="text" name="dia_rate[]" class="form-control dia_rate" /></div>
                            <div class="col-md-2 mb-2"><label>Silver Rate(1000gm)</label><input type="text" name="silver_rate[]" class="form-control silver_rate" /></div>
                            <div class="col-md-1 mb-2">
                                <label>Type</label>
                                <select name="round_type[]" class="form-control round_type">
                                    <option value="1">-</option>
                                    <option value="2">+</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2"><label>Round</label><input type="number" name="round[]" class="form-control round_value" /></div>
                            <div class="col-md-2 mb-2"><label>Amount</label><input type="number" name="amount[]" class="form-control amount" readonly /></div>
                            <div class="col-md-1 text-right mb-2"><label>&nbsp;</label><br><button type="button" class="btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button></div>
                        </div>
                    </div>
                </div>`;

            const $newCard = $(newCard);
            $('#inventory-body').append($newCard);
            $newCard.find('.item-select').selectpicker('render');
        });



    

    // Function to remove a row
   $('#inventory-body').on('click', '.removeRow', function () {
        $(this).closest('.inventory-card').remove();
    });


    function calculateRow($row1) {
        let gross = parseFloat($row1.find('.gross_wt').val()) || 0;
        let dust = parseFloat($row1.find('.dust').val()) || 0;
        let diamond = parseFloat($row1.find('.diamond').val()) || 0;
        let silver = parseFloat($row1.find('.silver').val()) || 0;
        let netWeight = gross- dust;
        $row1.find('.net_wt').val(netWeight.toFixed(2));


        let percentage = parseFloat($row1.find('.percentage').val()) || 0;
        let goldRate = parseFloat($row1.find('.gold_rate').val()) || 0;

        

        let diaRate = parseFloat($row1.find('.dia_rate').val()) || 0;
        let silverRate = parseFloat($row1.find('.silver_rate').val()) || 0;

        let action = $row1.find('.round_type').val();
        let round_value = parseFloat($row1.find('.round_value').val()) || 0;

        // Validate percentage
        if(dust>gross || dust<0 ){
            alert("Dust cannot be more than gross weight");
            $row1.find('.dust').val('');
            $row1.find('.net_wt').val(gross);
            return;
        }
        if (percentage > 100) {
            alert("Percentage cannot be more than 100");
            $row1.find('.percentage').val('');
            return;
        }

        // Calculate Gold Amount
        let goldAmt = 0;
        let net=(netWeight / 10)
        goldAmt = (net * goldRate * percentage) / 100;
        // goldAmt = (netWeight / 10) * goldRate * (percentage / 100);
        
        // Calculate Silver Amount
        let silverInKg = silver / 1000;
        let silverAmt = (silverInKg * silverRate * percentage) / 100;

        // Calculate Diamond Amount
        let diaAmt = diamond * diaRate;
        console.log(diaAmt, diamond, diaRate,'diamond amount');

        // Total Amount
        let totalAmt = goldAmt + silverAmt + diaAmt;

        // Round calculation
        if (action == '2') {
            totalAmt += round_value;
        } else if (action == '1') {
            totalAmt -= round_value;
        }

        // Set total amount
        $row1.find('.amount').val(totalAmt.toFixed(2));
    }

    // Function to calculate net weight and amount
    $('#inventory-body').on('input change', '.gross_wt, .dust, .gold_rate, .dia_rate, .round_value, .round_type, .percentage, .diamond, .silver, .silver_rate', function () {
        let $row1 = $(this).closest('.inventory-card');
        calculateRow($row1);
    });
  

});

</script>
<script>
    function validateNumeric(input) {
        let value = input.value.trim();

        // Allow: 123, 123.45, .45
        if (!/^(\d+(\.\d{0,2})?|\.\d{1,2})$/.test(value)) {
            toastr.error("Please enter a valid number (up to 2 decimal places).");
            input.value = '';
            input.focus();
        }
    }
</script>

<script>
$(document).ready(function () {

    $(document).on('input change', '.dust,.diamond,.silver,.gross_wt,.round_value,.silver_rate,.gold_rate,.dia_rate', function () {
        validateNumeric(this);
        let val = $(this).val();
        // Allow only up to 2 digits after decimal
        if (val.indexOf('.') >= 0) {
            const parts = val.split('.');
            parts[1] = parts[1].substring(0, 2); // limit to 2 decimal places
            $(this).val(parts.join('.'));
        }
    });

    $('#inventory_form').on('submit', function (e) {
        e.preventDefault();

        let isValid = true;
        let hasAtLeastOneValidRow = false;
        const formData = new FormData();

        // Loop over each row
        $('#inventory-body .inventory-card').each(function (index) {
            const $row = $(this);
            const itemVal = ($row.find("select[name='item_name[]']").val() || '').toString().trim();
            const grossWt = parseFloat($row.find("input[name='gross_wt[]']").val()) || 0;
            const percentage = parseFloat($row.find("input[name='percentage[]']").val()) || 0;
            const goldRate = parseFloat($row.find("input[name='gold_rate[]']").val()) || 0;
            const amount = parseFloat($row.find("input[name='amount[]']").val()) || 0;

            const isRowEmpty = !itemVal && grossWt === 0 && percentage === 0 && goldRate === 0;
            if (isRowEmpty) return true;

            hasAtLeastOneValidRow = true;

            if (!itemVal || percentage <= 0 || amount <= 0) {
                toastr.error(`Please fill all required fields in row ${index + 1}`);
                $row.find('select, input').each(function () {
                    $(this).valid();
                });
                isValid = false;
            }
            // Append only valid rows
            if (itemVal &&  percentage > 0 && amount > 0) {
                formData.append("item_name[]", itemVal);
                formData.append("gross_wt[]", $row.find("input[name='gross_wt[]']").val());
                formData.append("dust[]", $row.find("input[name='dust[]']").val());
                formData.append("diamond[]", $row.find("input[name='diamond[]']").val());
                formData.append("silver[]", $row.find("input[name='silver[]']").val());
                formData.append("net_wt[]", $row.find("input[name='net_wt[]']").val());
                formData.append("percentage[]", $row.find("input[name='percentage[]']").val());
                formData.append("gold_rate[]", $row.find("input[name='gold_rate[]']").val());
                formData.append("dia_rate[]", $row.find("input[name='dia_rate[]']").val());
                formData.append("silver_rate[]", $row.find("input[name='silver_rate[]']").val());
                formData.append("round_type[]", $row.find("select[name='round_type[]']").val());
                formData.append("round[]", $row.find("input[name='round[]']").val());
                formData.append("amount[]", $row.find("input[name='amount[]']").val());

                // Optional hidden fields
                if ($row.find("input[name='id[]']").length)
                    formData.append("id[]", $row.find("input[name='id[]']").val());
                if ($row.find("input[name='inventory_id[]']").length)
                    formData.append("inventory_id[]", $row.find("input[name='inventory_id[]']").val());
            }
        });

        // Handle completely empty form
        if (!hasAtLeastOneValidRow) {
            toastr.error("Please fill at least one row with valid data.");

            $('#inventory-body tr').each(function () {
                $(this).find('select, input').each(function () {
                    $(this).valid();  // trigger validation on each input
                });
            });

            return;
        }

        if (!isValid) return;

        if (!confirm("Are you sure you want to submit this form?")) return;

        $.ajax({
            url: $('#inventory_form').attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    toastr.success("Inventory saved successfully!");
                    $('#inventory_table').DataTable().ajax.reload(null, false);
                    $('#ajaxModal').modal('hide');
                } else {
                    toastr.error(res.message || "Something went wrong.");
                }
            },
            error: function () {
                toastr.error("Server error occurred.");
            }
        });
    });
});

</script>

