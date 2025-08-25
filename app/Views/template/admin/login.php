<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <link rel="shortcut icon" href="/public/assets/images/favicon.png" />
    <title>BMT | <?php echo $title; ?></title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="/public/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/public/libs/line-awesome/css/line-awesome.min.css" rel="stylesheet" />
    <link href="/public/libs/toastr/toastr.min.css" rel="stylesheet" />
    <link href="/public/assets/css/main.min.css" rel="stylesheet" />
    <link href="/public/assets/css/custom.css" rel="stylesheet" />
    <script type="text/javascript">
        var base_url = '<?php echo BASE; ?>';
    </script>
    <style>
        body {background-color: #4c8c9c;}
        .login-content { max-width: 400px; margin: 100px auto 50px; }
        .auth-head-icon { position: relative; height: 100px; width: 100px; display: inline-flex; align-items: center; justify-content: center; font-size: 30px; background-color: #679ba9; border-radius: 50%; transform: translateY(-50%); z-index: 2; }
        .hide { display: none !important; }
    </style>

    <script src="/public/libs/jquery/dist/jquery.min.js"></script>
    <script src="/public/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="/public/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/public/libs/metisMenu/dist/metisMenu.min.js"></script>
    <script src="/public/libs/toastr/toastr.min.js"></script>
    <script src="/public/libs/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="/public/assets/js/app.min.js"></script>
    <!-- <script src="/public/assets/js/common.js"></script> -->

    <script>
        $(function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        });
    </script>
</head>

<body>
    <div class="cover"></div>
    <?php
    try {
        echo view($view);
    } catch (Exception $e) {
        echo "<pre><code>$e</code></pre>";
    } 
    ?>

    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- CORE PLUGINS-->


    <script>
        $(function() {

            // toastr.error(result.message);

            $('#login-form').validate({
                errorClass: "help-block",
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 16
                    }
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error");
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error");
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(data) {
                            result = $.parseJSON(data);
                            if (result.host_code == 0) {
                                toastr.success(result.host_description);
                            } else if (result.host_code == 2) {
                                if (result.t == 2) {
                                    location.href = base_url + '/customer/dashboard';
                                } else {
                                    location.href = base_url + '/admin/dashboard';
                                }
                            } else {
                                toastr.error(result.host_description);
                            }
                        }
                    });
                },
            });


            $('#forgot-form').validate({
                errorClass: "help-block",
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error");
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error");
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(data) {
                            result = $.parseJSON(data);
                            if (result.success) {
                                $(form).trigger("reset");
                                toastr.success(result.message);
                            } else {
                                toastr.error(result.message);
                            }
                        }
                    });
                },
            });




        });
    </script>
</body>

</html>