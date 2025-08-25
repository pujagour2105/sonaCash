<!DOCTYPE html>
<html lang="en">


<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width initial-scale=1.0">
     <link rel="shortcut icon" href="/public/assets/images/favicon.ico" />
     <title>SONA Cash For Gold| <?php echo $title; ?></title>
 
     <!-- GLOBAL MAINLY STYLES-->
     <link href="/public/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
     <link href="/public/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
     <link href="/public/libs/line-awesome/css/line-awesome.min.css" rel="stylesheet" />
     <link href="/public/libs/themify-icons/css/themify-icons.css" rel="stylesheet" />
     <link href="/public/libs/animate.css/animate.min.css" rel="stylesheet" />
     <link href="/public/libs/toastr/toastr.min.css" rel="stylesheet" />
     <link href="/public/libs/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
     <link href="/public/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
     <!-- PLUGINS STYLES-->
 
 
     <link href="/public/datatables/responsive.dataTables.min.css" rel="stylesheet" />
     <link href="/public/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" />
 
 
 
     <!-- THEME STYLES-->
     <link href="/public/assets/css/main.min.css" rel="stylesheet" />
     <link href="/public/assets/css/custom.css" rel="stylesheet" />
     <!-- PAGE LEVEL STYLES-->
 
     <!-- CORE PLUGINS-->
     <script src="/public/libs/jquery/dist/jquery.min.js"></script>
     <script src="/public/libs/popper.js/dist/umd/popper.min.js"></script>
     <script src="/public/libs/bootstrap/dist/js/bootstrap.min.js"></script>
     <script src="/public/libs/metisMenu/dist/metisMenu.min.js"></script>
     <script src="/public/libs/jquery-slimscroll/jquery.slimscroll.min.js"></script>
     <script src="/public/libs/toastr/toastr.min.js"></script>
 
     <script src="/public/libs/jquery-validation/dist/jquery.validate.min.js"></script>
     <script src="/public/assets/js/jquery.form.js"></script>
     <script src="/public/libs/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
 
     <script src="/public/datatables/jquery.dataTables.min.js"></script>
     <script src="/public/datatables/dataTables.bootstrap5.min.js"></script>
     <script src="/public/datatables/dataTables.responsive.min.js"></script>
 
     <link href="/public/libs/bootstrap-sweetalert/dist/sweetalert.css" rel="stylesheet" />
     <script src="/public/libs/bootstrap-sweetalert/dist/sweetalert.min.js"></script>
     <script src="/public/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
 
     <link href="<?php echo base_url('public/libs/wysiwyag/richtext.css'); ?>" rel="stylesheet"/>
     <script src="<?php echo base_url('public/libs/wysiwyag/jquery.richtext.js'); ?>"></script>
 
     <link href="/public/libs/smalot-bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
     <script src="/public/libs/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
     <!-- PAGE LEVEL PLUGINS-->
     <!-- CORE SCRIPTS-->
     <script src="/public/assets/js/app.min.js"></script>
     <script src="/public/assets/js/common.js"></script>
     <script src="/public/assets/js/admin.js"></script>
     <!-- PAGE LEVEL SCRIPTS-->
 
     <script type="text/javascript"> var base_url = '<?php echo base_url(); ?>';</script>
 </head>

<body class="fixed-navbar">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <header class="header">
            <div class="page-brand">
                <a href="/">
                    <span class="brand">
                        <img src="/public/assets/images/s_logo.png" style="height:60px;width:60px; border-radius: 50%;">
                        <span style="font-size:10px;"><?php echo COMPANY_NAME;?></span>
                    </span>
                    <span class="brand-mini">
                        <img src="/public/assets/images/favicon.ico">
                    </span>
                </a>
            </div>
            <div class="flexbox flex-1">
                <!-- START TOP-LEFT TOOLBAR--> 
                <ul class="nav navbar-toolbar">
                    <li>
                        <a class="nav-link sidebar-toggler js-sidebar-toggler" href="javascript:;">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                    </li>
                    <li>
                        <i title="Lead shared by" class="fa fa-arrow-right"></i>
                        <i title="Lead shared to" class="fa fa-arrow-left"></i>
                    </li>
                    <li>
                        <?php
                        $branch_id = session()->get('branch_id');
                        $branch_fund = getAvailableFund($branch_id);
                         ?>
                        <span title="Branch Fund" style="color: green; font-weight: bold; margin-left:20px;">
                            ₹ <?= esc($branch_fund ?? '0.00') ?>
                        </span>
                    </li>
                </ul>
                <!-- END TOP-LEFT TOOLBAR-->
                <!-- START TOP-RIGHT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    

                    <li class="dropdown dropdown-inbox hide">
                        <a class="nav-link dropdown-toggle toolbar-icon" data-toggle="dropdown" href="javascript:;"><i class="ti-email"></i>
                            <span class="envelope-badge">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-media">
                            <div class="dropdown-arrow"></div>
                            <div class="dropdown-header text-center">
                                <div>
                                    <span class="font-18"><strong>0 New</strong> Messages</span>
                                </div>
                                <a class="text-muted font-13" href="javascript:;">view all</a>
                            </div>
                            <div class="p-3 hide">
                                <div class="media-list media-list-divider scroller" data-height="350px" data-color="#71808f">
                                    <a class="media p-3">
                                        <div class="media-img">
                                            <img class="img-circle" src="/public/assets/images/u1.jpeg" alt="image" />
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">Lynn Weaver<small class="text-muted float-right">Just now</small></div>
                                            <div class="font-13 text-muted">Your proposal interested me.</div>
                                            <div class="font-13 mt-1">
                                                <span class="d-inline-flex align-items-center text-primary"><i class="ti-support mr-2"></i>Support</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="dropdown dropdown-notification">
                        <a class="nav-link dropdown-toggle toolbar-icon" data-toggle="dropdown" href="javascript:;"><i class="ti-bell rel"></i>
                            <span class="envelope-badge">0<?php //echo get_fresh_leads(); ?></span>
                        </a>
                    </li>


                    <?php
                    $image_dir = DOWNLOAD_IMAGES . 'users/';
                    if (get_session('image')) {
                        $image = $image_dir . get_session('id') . '/' . get_session('image');
                    } else {
                        $image = '/public/assets/images/profile.jpeg';
                    }
                    ?>
                    <li class="dropdown dropdown-user">
                        <a class="dropdown-toggle dropdown-arrow" data-toggle="dropdown" aria-expanded="false">
                            <span><?php echo get_session('name'); ?></span>
                            <img id="userImage" src="<?php echo $image; ?>" alt="image" />
                        </a>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?php echo base_url('admin/profile'); ?>"><i class="ti-user"></i>Profile</a>
                            <a class="dropdown-item" href="<?php echo base_url('admin/changepassword'); ?>"><i class="ti-user"></i>Change Password</a>
                            <!-- <a class="dropdown-item" href="javascript:;"><i class="fa fa-wrench"></i>Settings</a> -->
                            <a class="dropdown-item" href="<?php echo base_url('admin/logout'); ?>"><i class="fa fa-sign-out"></i>Sign out</a>
                        </div>

                    </li>
                </ul>
                <!-- END TOP-RIGHT TOOLBAR-->
            </div>
        </header>
        <!-- END HEADER-->




        <?php echo view('template/admin/admin_leftmenu'); ?>

        <?php
        try {
            echo view($view);
        } catch (Exception $e) {
            echo "<pre><code>$e</code></pre>";
        }
        ?>

        <?php echo view('template/admin/admin_footer'); ?>
    </div>

    <?php echo view('modal/index'); ?>
    <?php echo view('modal/confirmation'); ?>


    
    <!-- PAGE LEVEL SCRIPTS-->


    <div style='display: none;'>

        <script type='text/javascript'>
            //feather.replace();

            <?php
            $session = \Config\Services::session();
            //		p($session->get());

            $error_message = $session->getFlashdata("error_message");
            $success_message = $session->getFlashdata("success_message");
            //echo ' ============ '.$error_message.' ---------- '.$success_message;;
            //$error_message="Hello000000000000";


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


    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php 
    if(get_session("role_id") == 4) {
    ?>
<script>
    
    $(document).ready(function(){
        $(".open_notification").click(function(){
            checkNotifications();
        });
        $("#ajaxModalContent").click(function(){
            $('#modalNotificationAlert').modal('hide');
        });
        setInterval(checkNotifications, 300000); // 1 min == 60000
        function checkNotifications() {
            $('#modalNotificationAlert').modal('hide');
            $.ajax({
                url: '/admin/checknotification',
                method: 'post',
                data: {},
                success: function(data) {
                    result = $.parseJSON(data);
                    if (result.host_code == 1) { 
                        $(".pending_notifi_cnt").text(result.cnt);
                        $(".hot_notifi_cnt").text(result.hot);
                        $("#nofiIDmodal").html(result.result);  
                        
                        $('#modalNotificationAlert').modal('show');
                        if( $("#modalNotificationAlert").attr("data-isopen") == '0' ) {
                            $("#modalNotificationAlert").attr("data-isopen", 1);
                        }

                    }
                }
            });
        }

        $(document).on("click", ".notiremark1", function() {
            $(this).remove();
            _leadid = $(this).attr("data-leadid");
            $(".notiremark"+_leadid).trigger("click");
        });
        
    });
    
</script>

    <!-- Callback alert modal -->
    <div class="modal fade" id="modalNotificationAlert" data-isopen="0" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Friendly Reminders <small>(<span class="pending_notifi_cnt"></span>)</small></h5>
                </div>

                <div class="modal-body-">
                    <ul class="media-list media-list-divider scroller mr-2" data-height="470px" id="nofiIDmodal">            
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php 
} ?>

