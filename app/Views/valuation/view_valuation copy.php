<!DOCTYPE html>
<html>
<head>
    <title>Valuation Details - SS GOLD</title>
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
            size: A4;
            margin: 0;
        }

        @media print {
            @page {
                size: A4;
                margin: 0; 
            }
            html, body {
                width: 100%;
                height: 100%;
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
                margin: 0 auto !important;
                padding: 20mm 10mm 10mm 10mm !important; /* safe area */
                width: 100% !important;
                box-sizing: border-box;
                background: white;
                page-break-after: avoid;
            }

            .content-wrapper, .page-content, .card, .card-body {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box;
                page-break-inside: avoid;
            }

            .print-header {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid #000;
                margin-bottom: 20px;
                padding-bottom: 10px;
            }

            .print-logo {
                height: 60px;
                width: 60px;
                object-fit: contain;
                border-radius: 50%;
            }

            .company-name {
                font-size: 24px;
                font-weight: bold;
                margin: 0;
            }

            .company-details {
                font-size: 14px;
                margin: 0;
                color: #333;
            }

            .table-responsive {
                overflow: visible !important;
                max-width: 100% !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse;
                table-layout: auto; /* makes columns flexible */
                page-break-inside: auto;
            }

            th, td {
                border: 1px solid #000;
                padding: 8px;
                font-size: 13px;
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

                <!-- Print Header -->
                <div class="print-header">
                    <div class="print-header-left">
                        <img src="<?= base_url('/public/assets/images/s_logo.png') ?>" alt="SS GOLD Logo" class="print-logo">
                    </div>
                    <div class="print-header-right">
                        <h2 class="company-name"><?php echo COMPANY_NAME;?></h2>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="mb-3">
                    <h5>To,</h5>
                    <p><strong><?= htmlspecialchars($data[0]['customer_name']) ?>,</strong></p>
                    <p><?= nl2br(htmlspecialchars($data[0]['address'])) ?></p>
                    <p><?= htmlspecialchars($data[0]['email']) ?></p>
                    <p><?= htmlspecialchars($data[0]['mobile']) ?></p>
                </div>

                <!-- Table Content -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Gross Weight</th>
                                <th>Dust</th>
                                <th>Net Weight</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $totalAmount = 0;
                                foreach ($data as $row): 
                                    $totalAmount += $row['amount'];
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                                    <td><?= number_format($row['gross_weight'], 2) ?></td>
                                    <td><?= number_format($row['dust'], 2) ?></td>
                                    <td><?= number_format($row['net_weight'], 2) ?></td>
                                    <td><?= number_format($row['rate'], 2) ?></td>
                                    <td><?= number_format($row['amount'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Amount:</strong></td>
                                <td><strong><?= number_format($totalAmount, 2) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Signature Section -->
                <div class="signature-row" id="signatureRow">
                    <div class="signature-box">Purchased by</div>
                    <div class="signature-box">Customer mobile</div>
                    <div class="signature-box">Signature</div>
                </div>
            </div>
        </div>

        <!-- ✅ Print Button (not included in print) -->
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
        window.location.href = '/valuation/index/';
    });
</script>

</body>
</html>
