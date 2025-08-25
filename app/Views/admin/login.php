<div class="ibox login-content">
        <div class="text-center">
            <span class="auth-head-icon">
                <!-- <i class="la la-user"></i> -->
                <img src="/public/assets/images/logo.png" style="max-width: 70%;">
            </span>
        </div>
        <form class="ibox-body" id="login-form" action="/admin/checkAdmin<?php //echo base_url('admin/checkAdmin'); ?>" method="post">
            <h4 class="font-strong text-center mb-3">LOG IN</h4>
            <?php
					$admin_selected=$user=$pw="";
					if(isset($_COOKIE["ciuid"])) {
						$admin_selected='checked="checked"';	
                        $cookie_data=explode('|||',base64_decode($_COOKIE["ciuid"]));
                        if($cookie_data) {
                            $user=$cookie_data[0];
                            $pw=$cookie_data[1];
                        }
                    }
                    ?>

            <div class="form-group mb-4">
                <label>Email</label>
                <input class="form-control" type="text" name="email" value="<?php echo $user; ?>" id="email" placeholder="Email">
            </div>
            <div class="form-group mb-4">
                <label><span id="idpw">Password</span></label>
                <input class="form-control" type="password" name="password" value="<?php echo $pw; ?>" placeholder="Password">
            </div>
            
            <div class="flexbox mb-5 hidepw">
                <span class="">
                    <label class="ui-switch switch-icon mr-2 mb-0">
                        <input type="checkbox" name="remember" <?php echo $admin_selected; ?>>
                        <span></span>
                    </label>Remember</span>
                <a class="text-primary" href="<?php echo base_url('admin/forgot');?>">Forgot password?</a>
            </div>
            
            <div class="text-center mb-4">
                <button class="btn btn-primary btn-block" type="submit">LOGIN</button>
            </div>
        </form>
    </div>

    <?php
$session = \Config\Services::session();
//		p($session->get());
		
$error_message = $session->getFlashdata("error_message");
// echo '==============='.$error_message; 
if($error_message) {
    ?><script>
    $(function() {
toastr.error("<?php echo $error_message; ?>");
    });
</script><?php 
}
?>
