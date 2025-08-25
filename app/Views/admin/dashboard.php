<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Dashboard</h1>
    </div>
<?php ?>
    <div class="page-content fade-in-up">

        <!-- <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card mb-4">
                    <div class="card-body flexbox-b">
                        <div class="easypie mr-4" data-percent="73" data-bar-color="#18C5A9" data-size="80" data-line-width="8">
                            <span class="easypie-data text-success" style="font-size:32px;"><i class="la la-users"></i></span>
                        </div>
                        <div>
                            <h3 class="font-strong text-success">
                                <a href="/lead"><?php //echo get_total_leads_count(get_session("id")); ?></a>
                            </h3>
                            <div class="text-muted">All Time Leads</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card mb-4">
                    <a href="/status-report/<?php echo base64_encode(2); ?>">
                        <div class="card-body flexbox-b">
                            <div class="easypie mr-4" data-percent="42" data-bar-color="#5c6bc0" data-size="80" data-line-width="8">
                                <span class="easypie-data font-26 text-primary"><i class="ti-world"></i></span>
                            </div>
                            <div>
                                <h3 class="font-strong text-primary"><?php //echo get_completed_leads_count(get_session("id")); ?></h3>
                                <div class="text-muted">Taken Admission Leads</div>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card mb-4">
                    <a href="/status-report/<?php echo base64_encode(5); ?>">
                        <div class="card-body flexbox-b">
                            <div class="easypie mr-4" data-percent="70" data-bar-color="#ff4081" data-size="80" data-line-width="8">
                                <span class="easypie-data text-pink" style="font-size:32px;"><i class="la la-tags"></i></span>
                            </div>
                            <div>
                                <h3 class="font-strong text-pink"><?php // echo get_callback_leads_count(get_session("id")); ?></h3>
                                <div class="text-muted">Callback Leads</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="/status-report/<?php echo base64_encode(3); ?>">
                    <div class="card mb-4">
                        <div class="card-body flexbox-b">
                            <div class="easypie mr-4" data-percent="70" data-bar-color="#f39c12" data-size="80" data-line-width="8">
                                <span class="easypie-data text-warning" style="font-size:32px;"><i class="fa fa-thumbs-up"></i></span>
                            </div>
                            <div>
                                <h3 class="font-strong text-warning"><?php //echo get_interested_leads_count(get_session("id")); ?></h3>
                                <div class="text-muted">Interested Leads</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div> -->
        <div class="row mb-4">
            <div class="col-lg-6 col-md-6">
                    <div class="ibox addcsv">
                        
                        <div class="ibox-head">
                            <div class="ibox-title">Branch Fund</div>
                            <!-- <div class="ibox-tools">
                                <a class="download_csv" title="Download CSV"><i class="fa fa-download"></i></a>
                            </div> -->
                        </div>

                        <div class="ibox-body barbox">
                            <div class="clsbar"></div>
                            <div class="scroller" data-height="350px">
                                <div id="">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Branch Name</th>
                                                <th>Available Fund (₹)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = 0;
                                            if (!empty($branch_fund)) {
                                                foreach ($branch_fund as $row) {
                                                    $total += (float)$row->available_balance;
                                                    echo "<tr>
                                                        <td>" . esc($row->branch_name) . "</td>
                                                        <td>" . number_format($row->available_balance, 2, '.', '') . "</td>
                                                    </tr>";
                                                }
                                                echo "<tr>
                                                    <th>Total</th>
                                                    <th>" . number_format($total, 2, '.', '') . "</th>
                                                </tr>";
                                            } else {
                                                echo "<tr><td colspan='2'>No data found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="ibox addcsv">
                    <div class="ibox-head">
                        <div class="ibox-title">Amount Distribution
                            <small style="font-size: 14px; color: #666;">
                                (<?php echo date('F Y'); ?>)
                            </small>
                        </div>
                        <!-- <div class="ibox-tools">
                                <a class="download_csv" title="Download CSV"><i class="fa fa-download"></i></a>
                            </div> -->
                    </div>
                    <div class="ibox-body barbox">
                        <div class="clsbar"></div>
                        <div class="scroller" data-height="350px">
                            <div id="">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Branch Name</th>
                                            <th>Customer Name</th>
                                            <th>Date</th>
                                            <th>Amount (₹)</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        if (!empty($distribution)) {
                                            foreach ($distribution as $row) {
                                                $total += (float)$row->amount;
                                                echo "<tr>
                                                    <td>" . esc($row->branch_name) . "</td>
                                                    <td>" . esc($row->customer_name) . "</td>
                                                    <td>" . date('d-m-Y', strtotime($row->date_only)) . "</td> 
                                                    <td>" . number_format($row->amount, 2, '.', '') . "</td>
                                                </tr>";
                                            }
                                            echo "<tr>
                                                <th colspan='3'>Total</th>
                                                <th>" . number_format($total, 2, '.', '') . "</th>
                                            </tr>";
                                        } else {
                                            echo "<tr><td colspan='4'>No data found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


            <!-- <div class="row mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="ibox">
                        <div class="ibox-head">
                            <div class="ibox-title">Source wise lead status</div>
                            <div class="ibox-tools">
                                
                                    <a class="downloadStatus" ><i class="fa fa-download"></i></a>
                                    
                                </div>
                        </div>
                        <div class="ibox-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label class="mb-0 mr-2">Lead Source</label>
                                    <select id="source" id="source" class="form-control statusChart" data-live-search="true">
                                        <?php 
                                        echo dropDownStr($sourceArray, '', "");
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-4 hide">
                                    <label class="mb-0 mr-2">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control statusChart">
                                </div>
                                <div class="col-lg-4 hide">
                                    <label class="mb-0 mr-2">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control statusChart">
                                </div>
                            </div>
                        </div>
                        <div class="ibox-body">
                            <div class=" scroller" data-height="420px">
                            <div id="source_pie_chart"></div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8 col-md-6">
                    <div class="ibox">
                        <div class="ibox-head">
                            <div class="ibox-title">Lead status</div>
                            <div class="ibox-tools">
                                <a class="downloadLeadStatus" ><i class="fa fa-download"></i></a>
                            </div>
                        </div>
                        <div class="ibox-body clsStf">
                            <div class="row">
                                <div class="col-lg-4 ">
                                    <label class="mb-0 mr-2">Staff</label>
                                    <select id="list_staff" class="form-control" data-live-search="true">
                                        <?php 
                                        echo dropDownStr($salesArray, 11, "");
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label class="mb-0 mr-2">From Date</label>
                                    <input type="date" id="list_from" class="form-control">
                                </div>
                                <div class="col-lg-4">
                                    <label class="mb-0 mr-2">To Date</label>
                                    <input type="date" id="list_to" class="form-control">
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-primary mt-3" id="getList">Search</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-body">
                            <div class=" scroller" data-height="420px">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Name</th>
                                            <th>Activity count</th>
                                            <th>Assign Leads</th>
                                            <th>Pending Leads</th>

                                            <th>Admission Taken</th>
                                            <th>Pending follow-ups</th>
                                            <th>today follow-ups</th>
                                            <th>Hot Pending follow-ups</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lead_status_data">
                                        <tr><td colspan="5">Loading...</td></tr>
                                    </tbody>
                                </table>
                            
                            </div>

                        </div>
                    </div>
                </div>

            </div> -->

            
            <!-- <div class="ibox">
                <div class="ibox-head">
                    
                    <ul class="nav nav-tabs tabs-line tabs-line-2x">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tab-10-1" data-toggle="tab"></i>Lead Source</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="#tab-10-3" data-toggle="tab"></i>Admin</a>
                        </li>
                    </ul>
                </div>
                <div class="ibox-body">
                    <div class="tab-content">
                        
                    
                        <div class="tab-pane fade show active text-center" id="tab-10-1">
                        
                            <div class="chart" data-type="source_pie" data-title="Source wise status">
                                <?php echo view('admin/sales/staff_graph_report_filter', [ "date"=>'', "status"=>'', "course"=>'hide' ]); ?>
                                <div class="row">
                                    <div class="col-lg-12 ">
                                        <div id="chartContainer" class="div-loader"></div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="tab-pane fade text-center" id="tab-10-3">

                            <div class="chart" data-type="course_bar" data-title="Source wise admission taken">
                                <?php echo view('admin/sales/staff_graph_report_filter', [ "date"=>'', "status"=>'hide', "course"=>'' ]); ?>
                                <div id="leads_bar_course"></div>
                            </div>

                        </div>    

                    </div>
                </div>
            </div> -->
            <hr>
            
            


            



    </div>
</div>

 
