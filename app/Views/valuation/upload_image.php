<?php echo form_open_multipart("/valuation/saveValuationImage", array("id" => "valuation_form1", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix ibox-body" >
    <div class="row">
        <div class="card" >
            <div class="card-header" ><strong>Valuation Details</strong></div>
            <div class="card-body" >
                <table class="table table-bordered" id="valuationTable">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Item Name</th>
                            <th>Gross Wt.</th>
                            <th>Dust</th>
                            <th>Net Weight</th>
                            <th>Rate</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)) : ?>
                            <input class="form-control" name="id" type="hidden" value="<?php echo isset($data[0]['id']) ? $data[0]['id'] : ''; ?>">
                            <input type="hidden" name="customer_id" class="form-control" value="<?php echo $data[0]['customer_id']; ?>" readonly />
                            <?php foreach ($data as $row) : ?>
                                <tr class="valuation-row">
                                    <td>
                                        
                                        <input type="text"  class="form-control" value="<?php echo $row['customer_name']; ?>" readonly />
                                    </td>
                                    <td><input type="text"  class="form-control" value="<?php echo $row['item_name']; ?>" readonly /></td>
                                    <td><input type="number" step="any" class="form-control gross" value="<?php echo $row['gross_weight']; ?>"  readonly/></td>
                                    <td><input type="number" step="any"  class="form-control dust"  value="<?php echo $row['dust']; ?>" readonly /></td>
                                    <td><input type="number" step="any"  class="form-control net" value="<?php echo $row['net_weight']; ?>" readonly  /></td>
                                    <td><input type="number"  class="form-control rate" value="<?php echo $row['rate']; ?>" readonly /></td>
                                    <td><input type="text" class="form-control amount" value="<?php echo $row['amount']; ?>" readonly /></td>
                                    
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- Image Upload Section -->
            
                <div class="row mt-4 mb-4" id="image-upload-section">
                    <div class="col-md-6">
                        <?php if ($data[0]['status']==1) :?>
                            <label for="image">Upload Valuation Document</label>
                            <input type="file" name="image" id="image" accept="image/*,application/pdf" class="form-control">
                        <?php endif; ?>

                        <?php if (!empty($data[0]['image'])): ?>
                            <?php 
                                $file = $data[0]['image'];
                                $file_path = base_url('uploads/' . $file);
                                $file_full_path = FCPATH . 'uploads/' . $file;
                                $file_ext = pathinfo($file, PATHINFO_EXTENSION);
                            ?>
                            <div class="mt-2">
                                <?php if (file_exists($file_full_path)): ?>
                                    <?php if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])): ?>
                                        <img src="<?= $file_path ?>" 
                                            alt="Uploaded Image" 
                                            class="img-thumbnail" 
                                            style="max-height: 150px;">
                                    <?php elseif (strtolower($file_ext) == 'pdf'): ?>
                                        <a href="<?= $file_path ?>" target="_blank">View PDF File</a>
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

                    <div class="col-md-6">
                        <?php if ($data[0]['status']==1) :?>
                            <label for="image2">Aadhar Document (Comming from Customer Profile)</label>
                            <!-- <input type="file" name="image2" id="image2" accept="image/*,application/pdf" class="form-control"> -->
                        <?php endif; ?>
                        <?php  ?>
                        <?php
                            $image_to_check = !empty($data[0]['cust_image']) ? $data[0]['cust_image'] : $data[0]['image2'];

                            if (!empty($image_to_check)):
                                $file = $image_to_check;
                                $file_path = base_url('uploads/' . $file);
                                $file_full_path = FCPATH . 'uploads/' . $file;
                                $file_ext = pathinfo($file, PATHINFO_EXTENSION);
                            ?>
                                <div class="mt-2">
                                    <?php if (file_exists($file_full_path)): ?>
                                        <?php if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])): ?>
                                            <img src="<?= $file_path ?>" 
                                                alt="Uploaded Image" 
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

                      
                    <!-- Jewellery Image -->
                    <div class="col-md-6 mt-4">
                        <label for="jewellery_image">Jewellery Image</label>
                        <input type="file" name="jewellery_image" id="jewellery_image" accept="image/*" class="form-control" onchange="previewImage(this, '#jewellery_preview')">
                            
                        <div id="jewellery_preview" class="mt-2">
                            <?php if (!empty($data[0]['jewellery_image'])):
                                $file = $data[0]['jewellery_image'];
                                $file_path = base_url('uploads/' . $file);
                                $file_full_path = FCPATH . 'uploads/' . $file;
                                if (file_exists($file_full_path)): ?>
                                    <div class="position-relative d-inline-block">
                                        <img src="<?= $file_path ?>" alt="Jewellery Image" class="img-thumbnail" style="max-height: 150px;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px;"
                                            onclick="deleteImage('<?= $file ?>', 'jewellery_image')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endif;
                            endif; ?>
                        </div>
                    </div>
                    <!-- Client with Jewellery Image -->
                    <div class="col-md-6 mt-4">
                        <label for="client_image">Client with Jewellery Image</label>
                        <input type="file" name="client_image" id="client_image" accept="image/*" class="form-control" onchange="previewImage(this, '#client_jewellery_preview')">
                            
                        <div id="client_jewellery_preview" class="mt-2">
                            <?php if (!empty($data[0]['client_image'])):
                                $file = $data[0]['client_image'];
                                $file_path = base_url('uploads/' . $file);
                                $file_full_path = FCPATH . 'uploads/' . $file;
                                if (file_exists($file_full_path)): ?>
                                    <div class="position-relative d-inline-block">
                                        <img src="<?= $file_path ?>" alt="Client Image with Jewellery" class="img-thumbnail" style="max-height: 150px;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px;"
                                            onclick="deleteImage('<?= $file ?>', 'client_image')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endif;
                            endif; ?>
                        </div>
                    </div>
                  

                </div>


                <div class="form-group mt-3">
                    <?php if($data[0]['status']==1){?>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <?php } ?>
                
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>

                
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-start"> 
       
    </div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {

        // Submit form via appForm plugin
        $("#valuation_form1").appForm({
            onSuccess: function (result) {
                console.log(result, 'result');
                // You can reload DataTable here if needed
                // $('#valuation_table').DataTable().ajax.reload(null, false);
            }
        });

    });

    // Dynamically preview image before upload
    function previewImage(input, previewTarget) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $(previewTarget).html(`
                    <div class="position-relative d-inline-block">
                        <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                `);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Delete image via AJAX and remove preview block
    function deleteImage(filename, field) {
        const id = $("input[name='id']").val(); // valuation ID

        if (!confirm("Are you sure you want to delete this image?")) return;

        $.ajax({
            url: "<?= base_url('valuation/deleteImage') ?>",
            method: "POST",
            data: {
                filename: filename,
                field: field,
                id: id
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Image deleted successfully.");

                    // Remove only the relevant preview block
                    if (field === 'jewellery_image') {
                        $('#jewellery_preview').empty();
                    } else if (field === 'client_image') {
                        $('#client_jewellery_preview').empty();
                    }
                } else {
                    toastr.error(response.message, "Error");
                }
            },
            error: function () {
                toastr.error("Something went wrong.", "Error");
            }
        });
    }
</script>

