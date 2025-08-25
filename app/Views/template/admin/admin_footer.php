    <!-- END PAGE CONTENT-->
    <div class="footer-wrapper">
            <footer class="page-footer">
                <div class="font-13"><?php echo date("Y")?> © <b>eDarpan</b></div>
                <div>
                    <a class="px-3" href="http://edarpan.in" target="_blank">Edarpan.in</a>
                </div>
                <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
            </footer>
        </div>
</div>
    
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>

    <?php 
    if(get_session("role_id") == 4) {
    ?>
    <div class="fixed-icon ">
        <img src="/public/assets/images/miss_callback.jpg" class="open_notification" alt="Fixed Icon">
    </div>
    <?php } ?>
    
    
</body>


<!-- Mirrored from admincast.com/adminca/preview/admin_1/html/form_layouts.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 23 May 2019 05:55:25 GMT -->
</html>