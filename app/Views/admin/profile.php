<?php
$image_dir = DOWNLOAD_IMAGES . 'users/';
$name = get_session('name');
if (get_session('image')) {
	$image = $image_dir . get_session('id') . '/' . get_session('image');
} else {
	$image = '/public/assets/images/profile.jpeg';
}
?>
<div class="content-wrapper">
	<!-- START PAGE CONTENT-->
	<div class="page-heading">
		<div class="row align-items-center">
			<div class="col-sm-8">
				<h1 class="page-title">Profile</h1>
			</div>
		</div>
	</div>
	<div class="page-content fade-in-up">

		<div class="row">
			<div class="col-lg-5">
				<div class="ibox">
					<div class="text-center centered mb-4" style="max-width:320px;">
						<div class="card-body">
							<div class="card-avatar mt-3 mb-4">
								<img class="img-circle" id="main_img" src="<?php echo $image; ?>" alt="image" />
								<div class="img_preview">
									<div class="im_progress">
										<img class="loader_img" src="<?php echo base_url('public/assets/images/ajax-loader.gif'); ?>">
									</div>
									<img src="" class="profile-user-img img-responsive img-circle" id="img_preview" alt="User profile picture">
								</div>
							</div>
							<h4 class="card-title mb-1"><?php echo $name; ?></h4>
							<br>
							<div class="d-flex justify-content-around align-items-center">

								<form id="Ajaxform">
									<div class="file-input-plus file-input"><i class="la la-plus-circle" title="Update image"></i>
										<input type="file" name="ajax_file" id="Fileinput" accept="image/png, image/jpg, image/jpeg">
									</div>
								</form>

							</div>
						</div>
					</div>
				</div>				
			</div>
			<div class="col-md-7">
				<?php echo form_open("/admin/profile", array("id" => "formProfile", "class" => "general-form", "role" => "form")); ?>

				<div class="ibox">
					<div class="ibox-body">

						<div class="form-group mb-4">
							<label>Name</label>
							<input name="name" class="form-control" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" placeholder="Name" data-rule-required="0" data-msg-required="This field is required.">
						</div>
						<div class="form-group mb-4">
							<label>Official Email</label>
							<input class="form-control" name="official_email" type="text" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" placeholder="First Name" data-rule-required="0" data-msg-required="This field is required." data-rule-email="1">
						</div>
						<div class="form-group mb-4">
							<label>Mobile</label>
							<input name="mobile" class="form-control" type="text" value="<?php echo isset($data['mobile']) ? $data['mobile'] : ''; ?>" placeholder="Mobile" data-rule-required="0" data-msg-required="This field is required." data-rule-mobile="1">
						</div>
						
						<div class="form-group mb-4">
							<label>DOB</label>
							<input name="dob" class="form-control" type="date" value="<?php echo isset($data['dob']) ? $data['dob'] : ''; ?>" placeholder="DOB" data-rule-required="0" data-msg-required="This field is required.">
						</div>

						<div class="form-group mb-4">
							<label>Gender</label>
							<div>
								<label class="radio radio-inline radio-info">
									<input type="radio" name="gender" value="male" <?php echo ($data['gender'] == 'male') ? 'checked' : ''; ?>>
									<span class="input-span"></span>Male</label>
								<label class="radio radio-inline radio-info">
									<input type="radio" name="gender" value="female" <?php echo ($data['gender'] == 'female') ? 'checked' : ''; ?>>
									<span class="input-span"></span>Female</label>
							</div>
						</div>
						
						<div class="form-group mb-4">
						<button type="submit" class="btn btn-primary">Update</button>
					</div>	
					</div>
				</div>
				</form>


			</div>

			


			
			
		</div>


	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$("#formProfile").appForm({
			onSuccess: function(result) {

			}
		});
		$(".selectpicker").selectpicker({
			"title": "Select Options"
		}).selectpicker("render");
	});


	// update profile image
	$(document).ready(function() {
		$(document).on('change', '#Fileinput', function() {
			var imgpreview = DisplayImagePreview(this);
			$(".img_preview").show();
			$("#main_img").hide();

			var url = "/admin/uploadImage";
			ajaxFormSubmit(url, '#Ajaxform', function(output) {
				var data = JSON.parse(output);
				if (data.status == 'success') {
					$('.im_progress').fadeOut();
				} else {
					alert("Something went wrong.Please try again.");
					$(".img_preview").hide();
				}
			});
		});

		function DisplayImagePreview(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('#img_preview, #userImage').attr('src', e.target.result);
				};
				reader.readAsDataURL(input.files[0]);
			}
		}

		function ajaxFormSubmit(url, form, callback) {
			var formData = new FormData($(form)[0]);
			$.ajax({
				url: url,
				type: 'POST',
				data: formData,
				datatype: 'json',
				beforeSend: function() {
					// do some loading options
				},
				success: function(data) {
					callback(data);
				},

				complete: function() {
					// success alerts
				},

				error: function(xhr, status, error) {
					alert(xhr.responseText);
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}
	});
</script>