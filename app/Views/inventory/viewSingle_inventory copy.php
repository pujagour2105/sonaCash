<!DOCTYPE html>
<html>
<head>
    <title>Valuation Details - <?php echo COMPANY_NAME;?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #000;
        }

        .table-responsive {
            max-height: 100%;
        }

        .print-header {
            display: none;
        }

        .signature-row {
            display: none;
        }

        @page {
            margin: 0;
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            html, body {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: 100% !important;
                overflow: hidden;
            }

            body * {
                visibility: hidden !important;
            }

            #print-area, #print-area * {
                visibility: visible !important;
            }

            #print-area {
                position: relative !important;
                margin: 0 !important;
                padding: 10mm 10mm 10mm 10mm !important; /* Safe content area */
                width: 100% !important;
                box-sizing: border-box;
                background: #fff;
            }

            .content-wrapper, .page-content, .card, .card-body {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box;
            }

            .print-header {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid #000;
                padding: 10px 0;
                margin-bottom: 10px;
            }

            .print-logo {
                height: 60px;
                width: 60px;
                object-fit: contain;
                border-radius: 50%;
            }

            .company-info {
                text-align: right;
            }

            .company-name {
                font-size: 20px;
                font-weight: bold;
                margin: 0;
                color: #000;
            }

            .table-responsive {
                width: 100% !important;
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse;
                table-layout: auto;
            }

            th, td {
                border: 1px solid #000;
                padding: 6px;
                font-size: 12px;
                word-break: break-word;
                text-align: center;
            }

            th {
                background-color: #f0f0f0;
            }

            .text-end {
                text-align: right;
            }

            .signature-row {
                display: flex !important;
                justify-content: space-between;
                margin-top: 40px;
            }

            .signature-box {
                width: 30%;
                border-top: 1px solid #000;
                text-align: center;
                font-size: 12px;
                padding-top: 5px;
            }

            .no-print {
                display: none !important;
            }
        }


    </style>
</head>
<body>

<!-- ✅ Printable Content Starts Here -->
<div class="content-wrapper" id="print-area">
    <div class="page-content">
        <div class="card">
            <div class="card-body">

                <div class="print-header">
                    <img src="<?= base_url('/public/assets/images/s_logo.png') ?>" alt="SS GOLD Logo" class="print-logo">
                    <div class="company-info">
                        <h1 class="company-name"><?php echo COMPANY_NAME;?></h1>
                        <!-- <p class="company-tagline">Purity in Trust</p> -->
                    </div>
                </div>

                <!-- Table Content -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="thead-light">
                            <tr>
                                <!-- <th>Branch Name</th> -->
                                <th>Date</th>
                                <th>Item</th>
                                <th>Gross wt</th>
                                <th>Diamond</th>
                                <th>Silver</th>
                                <th>Net Wt.</th>
                                <th>Per(%)</th>
                                <th>Gold</th>
                                <th>Silver</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $row): ?>
                                <tr>
                                    <!-- <td><?= htmlspecialchars($row['branch_name'] ?? '-') ?></td> -->
                                    <td><?= isset($row['ts']) ? date('d-m-Y', strtotime($row['ts'])) : '-' ?></td>
                                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                                    <td><?= number_format($row['gross_weight'], 2) ?></td>
                                    <td><?= number_format($row['diamond'], 2) ?></td>
                                    <td><?= number_format($row['silver'], 2) ?></td>
                                    <td><?= number_format($row['net_weight'], 2) ?></td>
                                    <td><?= number_format($row['percentage'], 2) ?></td>
                                    <td><?= number_format($row['gold_rate'], 2) ?></td>
                                    <td><?= number_format($row['silver_rate'], 2) ?></td>
                                    <td><?= number_format($row['total_amount'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- Print Button (not printed) -->
        <div class="text-right mt-3 mb-4 no-print">
            <button class="btn btn-primary print-btn">Print</button>
            <button class="btn btn-warning back-btn">Back</button>
        </div>
    </div>
</div>

<script>
    document.querySelector('.print-btn').addEventListener('click', function () {
        window.print();
    });
    document.querySelector('.back-btn').addEventListener('click', function () {
        window.location.href = '/inventory/index/';
    });
</script>

</body>
</html>
