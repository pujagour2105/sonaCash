<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <ul class="side-menu metismenu">
            <li class="_dash">
                <a href="/"><i class="sidebar-item-icon ti-home"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <?php 
            if (checkPermission("branch_management", "is_add") || checkPermission("branch_management", "is_view") || checkPermission("branch_management", "is_edit")) { ?>
                <li><a href="/branch/index"><i class="sidebar-item-icon fa fa-university"></i><span class="nav-label">Branches</span></a></li>
                <?php
            }  
            if (checkPermission("branch_fund_management", "is_add") || checkPermission("branch_fund_management", "is_view") || checkPermission("branch_fund_management", "is_edit")) { ?>
                <li><a href="/branch/funds"><i class="sidebar-item-icon fa fa-money"></i><span class="nav-label">Branch Funds</span></a></li>
                <?php 
            } 
            if (checkPermission("staff_management", "is_add") || checkPermission("staff_management", "is_view") || checkPermission("staff_management", "is_edit")) { ?>
                <li><a href="/staff/index"><i class="sidebar-item-icon fa fa-id-badge "></i><span class="nav-label">Users</span></a></li>
                <?php 
            } 
            if (checkPermission("customer_management", "is_add") || checkPermission("customer_management", "is_view") || checkPermission("customer_management", "is_edit")) { ?>
                <li><a href="/customer/index"><i class="sidebar-item-icon fa fa-users"></i><span class="nav-label">Customer</span></a></li>
                <?php 
            } 
            if (checkPermission("item_management", "is_add") || checkPermission("item_management", "is_view") || checkPermission("item_management", "is_edit")) { ?>
                <li><a href="/item/index"><i class="sidebar-item-icon fa fa-list-alt"></i><span class="nav-label">Item</span></a></li>
                <?php 
            } 
            if (checkPermission("valuation_management", "is_add") || checkPermission("valuation_management", "is_view") || checkPermission("valuation_management", "is_edit")) { ?>
                <li><a href="/valuation/index"><i class="sidebar-item-icon fa fa-bar-chart"></i><span class="nav-label">Valuation</span></a></li>
                <?php 
            } 
            if (checkPermission("inventory_management", "is_add") || checkPermission("inventory_management", "is_view") || checkPermission("inventory_management", "is_edit")) { ?>
                <li><a href="/inventory/index"><i class="sidebar-item-icon fa fa-archive"></i><span class="nav-label">Inventory</span></a></li>
                <?php 
            }
            if (checkPermission("amt_distribution_management", "is_add") || checkPermission("amt_distribution_management", "is_view") || checkPermission("amt_distribution_management", "is_edit")) { ?>
                <li><a href="/distribution/index"><i class="sidebar-item-icon fa fa-money"></i><span class="nav-label">Amount Distribution</span></a></li>
                <?php 
            }
            if (checkPermission("invoice_managment", "is_add") || checkPermission("invoice_managment", "is_view") || checkPermission("invoice_managment", "is_edit")) { ?>
                <li><a href="/invoice/index"><i class="sidebar-item-icon fa fa-file-text"></i><span class="nav-label"> Purchase Invoice</span></a></li>
                <li><a href="/sale/index"><i class="sidebar-item-icon fa fa-file-text"></i><span class="nav-label"> Sale Invoice</span></a></li>
                <?php 
            }
            if (checkPermission("report_managment", "is_add") || checkPermission("report_managment", "is_view") || checkPermission("report_managment", "is_edit")) { ?>
                <li><a href="#"><i class="sidebar-item-icon fa fa-line-chart"></i><span class="nav-label">Reports</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="/report/branch_valuation_report">Branch Valuation Report</a>
                        </li>
                        <li>
                            <a href="/report/branch_report">Branch Report</a>
                        </li>
                        <li>
                            <a href="/report/invoice_report">Purchase Invoice Report</a>
                        </li>

                        <li>
                            <a href="/report/sale_invoice_report">Sale Invoice Report</a>
                        </li>
                    </ul>
                </li>
                <?php 
            }
            ?>
            <?php /* ?>
            <li class="_is_master d-none d-sm-block inner_menu">
                <a href="javascript:;" class="dropdown-toggle"><i class="sidebar-item-icon ti-paint-roller"></i>
                    <span class="nav-label">Master Data</span><i class="fa fa-angle-left arrow"></i></a>
                <?php $is_master = 1; ?>
                <ul class="nav-2-level collapse">
                    <?php if (checkPermission("city_mapping", "is_add") || checkPermission("city_mapping", "is_view") || checkPermission("city_mapping", "is_edit")) { ?>
                        <li><a href="<?php echo base_url('master/cities'); ?>" class="">City Mapping</a></li>
                    <?php }  ?> 
                    <?php if (checkPermission("course_common", "is_add") || checkPermission("course_common", "is_view") || checkPermission("course_common", "is_edit")) { ?>
                        <li><a href="/master/course_fields">Course Common Fields</a></li>
                    <?php } ?>
                    
                    <?php if (checkPermission("emailtemplate", "is_add") || checkPermission("emailtemplate", "is_view") || checkPermission("emailtemplate", "is_edit")) { ?>
                        <li><a href="<?php echo base_url('emailtemplate/index'); ?>" class="">Email Template</a></li>
                    <?php } ?>
                    
                    <?php if (checkPermission("staff", "is_add") || checkPermission("staff", "is_view") || checkPermission("staff", "is_edit")) { ?>
                        <li><a href="<?php echo base_url('staff/index'); ?>" class="">Staff management</a></li>
                    <?php } ?>
                    <?php if (checkPermission("lead_source", "is_add") || checkPermission("lead_source", "is_view") || checkPermission("lead_source", "is_edit")) { ?>
                        <li><a href="<?php echo base_url('master/leadsource'); ?>" class="">Lead Sources</a></li>
                    <?php } ?>
                    
                    <?php if (checkPermission("level", "is_add") || checkPermission("level", "is_view") || checkPermission("level", "is_edit")) { ?>
                        <li><a href="<?php echo base_url('master/level'); ?>" class="">Level</a></li>
                    <?php } ?>
                    <?php if (checkPermission("entrance_exams", "is_add") || checkPermission("entrance_exams", "is_view") || checkPermission("entrance_exams", "is_edit")) { ?>
                        <li><a href="<?php echo base_url('master/entrance_exams'); ?>" class="">Entrance Exams</a></li>
                    <?php } ?>
                    <?php if (checkPermission("amenities", "is_add") || checkPermission("amenities", "is_view") || checkPermission("amenities", "is_edit")) { ?>
                        <li><a href="<?php echo base_url('master/amenities'); ?>" class="">Amenities</a></li>
                    <?php } ?>
                    <?php if (checkPermission("staff_mapping", "is_add") || checkPermission("staff_mapping", "is_view") || checkPermission("staff_mapping", "is_edit")) { ?>
                        <li><a href="<?php echo base_url('master/staff_mapping'); ?>" class="">Staff University mapping</a></li>
                    <?php } ?>

                </ul>
            </li>
            <?php */ ?>


            



            

        </ul>
        
    </div>
</nav>
<!-- END SIDEBAR-->

<script>
    $(document).ready(function() {
        var is_master = "<?php echo isset($is_master) ? $is_master : ''; ?>";
        if (!is_master) {
            // $("._is_master").remove();
            // $("._is_master").addClass('active');
        }
    });
</script>