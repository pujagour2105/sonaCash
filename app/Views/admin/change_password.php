<div class="content-wrapper">
    <div class="page-heading">
        <div class="row align-items-center">
            <div class="col-sm-8">
                <h1 class="page-title">Change Password</h1>
            </div>
        </div>
    </div>

    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-md-12">
                <form id="changePwd" method="post" action="<?= base_url('admin/action_changepassword'); ?>">
                    <div class="ibox">
                        <div class="ibox-body">

                            <div class="form-group mb-4">
                                <label>Current Password</label>
                                <input name="current_password" class="form-control" type="password" placeholder="Current Password" data-rule-required="1" data-msg-required="This field is required">
                            </div>

                            <div class="form-group mb-4">
                                <label>New Password</label>
                                <input class="form-control" name="new_password" type="password" placeholder="New Password" data-rule-required="1" data-msg-required="This field is required">
                            </div>

                            <div class="form-group mb-4">
                                <label>Confirm Password</label>
                                <input class="form-control" name="confirm_password" type="password" placeholder="Confirm Password" data-rule-required="1" data-msg-required="This field is required">
                            </div>

                            <div class="form-group mb-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script type="text/javascript">
    $(document).ready(function () {

		$("#changePwd").on("submit", function (e) {
			e.preventDefault();

			let form = this;
			let currentPassword = form.current_password.value.trim();
			let newPassword = form.new_password.value.trim();
			let confirmPassword = form.confirm_password.value.trim();

			// Validation
			if (!currentPassword || !newPassword || !confirmPassword) {
				toastr.error("All fields are required.");
				return;
			}

			if (newPassword.length < 6) {
				toastr.error("New password must be at least 6 characters long.");
				return;
			}

			if (newPassword !== confirmPassword) {
				toastr.error("New password and Confirm password do not match.");
				return;
			}

			let formData = new FormData(form);

			$.ajax({
				url: form.action,
				type: 'POST',
				data: formData,
				dataType: 'json',
				beforeSend: function () {
					// Optional: show loading spinner
				},
				success: function (response) {
					if (response.status === 'success') {
						toastr.success(response.message);
						form.reset();
					} else {
						toastr.error(response.message || "An error occurred.");
					}
				},
				error: function (xhr) {
					toastr.error("Server Error: " + xhr.responseText);
				},
				complete: function () {
					// Optional: hide loader
				},
				cache: false,
				contentType: false,
				processData: false
			});
		});

		$(".selectpicker").selectpicker({
			title: "Select Options"
		}).selectpicker("render");
	});

	toastr.options = {
    "positionClass": "toast-top-right",
    "timeOut": "3000",
    "closeButton": true,
};
</script>
