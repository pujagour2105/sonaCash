<div class="ibox login-content">
        <div class="text-center">
            <span class="auth-head-icon">
                <!-- <i class="la la-user"></i> -->
                <img src="/public/assets/images/logo.png" style="max-width: 70%;">
            </span>
        </div>
        <form class="ibox-body" id="forgot-form" action="<?php echo base_url('admin/forgotpw'); ?>" method="post">
            <h4 class="font-strong text-center mb-3">Forgot Password</h4>
            <div class="form-group mb-4">
                <label>Email</label>
                <input class="form-control" type="text" name="email" id="email" placeholder="Email">
            </div>
            
            <div class="flexbox mb-5 hidepw">
                <a class="text-primary" href="<?php echo base_url('/'); ?>">Login</a>
            </div>
            
            <div class="text-center mb-4">
                <button class="btn btn-primary btn-block" type="submit">Forgot</button>
            </div>
        </form>
    </div>