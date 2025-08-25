


	<?php
	
	if(get_session("id") == '') {
		app_redirect('/');
	}

	try {
		echo view($view);
	} catch (Exception $e) {
		echo "<pre><code>$e</code></pre>";
	}
	?>
	<?php
	// $userData = user_name_byid(get_session("id"));
	// if ($userData["player_name"] == "") {
	// 	$account_link = base_url('home/dashboard/1');
	// } else {
	// 	$account_link = base_url('user/account');
	// }
	?>


	<?php echo view('modal/app_modal'); ?>
	<?php echo view('modal/confirmation'); ?>

	<div style='display: none;'>
		<script type='text/javascript'>
			<?php
			$session = \Config\Services::session();
			$error_message = $session->getFlashdata("error_message");
			$success_message = $session->getFlashdata("success_message");

			if (isset($error)) {
				echo 'appAlert.error("' . $error . '");';
			}
			if (isset($error_message)) {
				echo 'appAlert.error("' . $error_message . '");';
			}
			if (isset($success_message)) {
				echo 'appAlert.success("' . $success_message . '", {duration: 10000});';
			}
			?>
		</script>
	</div>



<script>
	var cartCount = localStorage.getItem('cartCount');
	cartCount = !isNaN(cartCount) ? cartCount : 0;
	if ((cartCount) && (cartCount != 0)) {
		$('#cartCount').html('(' + cartCount + ')');
	}
</script>
