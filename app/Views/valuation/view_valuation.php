<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #000;
        background-color: #f4f4f4;
        font-size: 16px; /* Increased from default (~14px) */
    }

    .table-responsive {
        max-height: 100%;
    }

    .print-header,
    .signature-row {
        display: none;
    }
    .print-footer-timestamp {
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
            font-size: 16px !important; /* Ensure larger print text */
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
            padding: 0mm 10mm 10mm 10mm !important;
            width: 100%;
            box-sizing: border-box;
            background: white;
        }

        .content-wrapper,
        .page-content,
        .card,
        .card-body {
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
            font-size: 26px;
            font-weight: bold;
            margin: 0;
        }

        .table-responsive {
            overflow: visible !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #000;
            padding: 10px;
            font-size: 15px;
            text-align: center;
            word-break: break-word;
        }

        th {
            background-color: #f9f9f9;
        }

        .text-end {
            text-align: right !important;
        }

        .signature-row {
            display: flex !important;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signature-box {
            width: 30%;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 15px;
            padding-top: 8px;
        }

        .no-print {
            display: none !important;
        }
    }

    /* Show customer section better in both print and screen */
    .customer-info h5 {
        margin-bottom: 5px;
    }

    .customer-info p {
        margin: 2px 0;
        font-size: 16px;
    }

    /* Optional: Card shadow for screen view */
    .card {
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border-radius: 6px;
        background: #fff;
    }
    .print-footer-timestamp {
        display: block !important;
        position: fixed;
        bottom: 10mm;
        right: 10mm;
        font-size: 14px;
        color: #000;
        text-align: right;
    }
 
</style>

<div class="modal-body clearfix ibox-body">
         
    <div class="page-content" id="print-area">
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
                    <p><strong><?= htmlspecialchars($data[0]['customer_name'].$data[0]['id']) ?>,</strong></p>
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
                                <!-- <th>Rate</th> -->
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
                                    <!-- <td><?= number_format($row['rate'], 2) ?></td> -->
                                    <td><?= number_format($row['amount'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
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
        <div class="print-footer-timestamp">
            Date & Time: <?= date('d-m-Y H:i:s') ?>
        </div>
    </div>
        
</div>

<script>
    
    // Define onafterprint globally
    window.onafterprint = function () {
        const valuationId = <?= json_encode($data[0]['id']) ?>;
        const source = 'uploadImage';
        window.location.href = `/valuation/index/?valuation_id=${valuationId}&source=${source}`;
        // window.location.href = '/valuation/index/';
    };

    // Print button
    document.querySelector('.print-btn').addEventListener('click', function () {
        window.print();
    });

    // Back button
    document.querySelector('.back-btn').addEventListener('click', function () {
        window.location.href = '/valuation/index/';
        
    });
</script>

