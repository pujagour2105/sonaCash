<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #000;
    }

    .table-responsive {
        max-height: 100%;
    }

    .print-header,
    .signature-row {
        display: none;
    }

    @page {
        size: A4 landscape;
        margin: 5mm; /* Only 0.5mm margin */
    }

    @media print {
        html, body {
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden;
            font-size: 10px !important;
        }

        body * {
            visibility: hidden !important;
        }

        #print-area, #print-area * {
            visibility: visible !important;
        }

        #print-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 0 !important;
            margin: 0 !important;
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
            padding: 6px 0;
            margin-bottom: 6px;
        }

        .print-logo {
            height: 50px;
            width: 50px;
            object-fit: contain;
            border-radius: 50%;
        }

        .company-info {
            text-align: right;
        }

        .company-name {
            font-size: 16px !important;
            font-weight: bold;
            margin: 0;
            color: #000;
        }

        .company-subtitle {
            font-size: 12px !important;
            margin: 0;
        }

        .balance-info {
            font-size: 12px !important;
            margin-bottom: 8px;
        }

        .table-responsive {
            width: 100% !important;
            overflow: visible !important;
            page-break-after: always;
        }

        table {
            width: 100% !important;
            border-collapse: collapse;
            table-layout: fixed;
            word-break: break-word;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px !important;
            font-size: 9.5px !important;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        tr, td, th {
            page-break-inside: avoid !important;
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
            font-size: 11px;
            padding-top: 5px;
        }

        .no-print {
            display: none !important;
        }
    }
</style>




<!-- ✅ Printable Content Starts Here -->
 <div class="modal-body clearfix ibox-body">
    <div class="page-content"  id="print-area">
        <div class="card">
            <div class="card-body">

                <div class="balance-info" style="text-align: right; margin-bottom: 10px; font-size: 14px; font-weight: bold;">
                    <?php
                    $branch_id = session()->get('branch_id');
                    $branch_fund = getAvailableFund($branch_id);
                     ?>
                    Balance Amount: ₹<span title="Branch Fund" style="color: green; font-weight: bold; margin-left:20px;">
                        ₹ <?= esc($branch_fund ?? '0.00') ?>
                    </span>
                </div>

                <div class="print-header">

                    <img src="<?= base_url('/public/assets/images/s_logo.png') ?>" alt="SS GOLD Logo" class="print-logo">
                    <div class="company-info">
                        <h1 class="company-name"><?php echo COMPANY_NAME;?></h1>
                        <h2 class="company-subtitle" style="margin: 0; font-size: 16px;">Inventory List</h2>
                        <!-- <p class="company-tagline">Purity in Trust</p> -->
                    </div>
                </div>


                <!-- Table Content -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Branch Name</th>
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
                                    <td><?= htmlspecialchars($row['branch_name'] ?? '-') ?></td>
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
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th><?= round(array_sum(array_column($data, 'gross_weight'))) ?></th>
                                <th><?= round(array_sum(array_column($data, 'diamond'))) ?></th>
                                <th><?= round(array_sum(array_column($data, 'silver'))) ?></th>
                                <th><?= round(array_sum(array_column($data, 'net_weight'))) ?></th>
                                <th><?= ""; ?></th>
                                <th><?= "" ?></th>
                                <th><?= "" ?></th>
                                <th><?= round(array_sum(array_column($data, 'total_amount'))) ?></th>
                            </tr>
                        </tfoot>

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
    (function () {
        let printTriggered = false; // ✅ Guard against multiple popups

        const printBtn = document.querySelector('.print-btn');
        const backBtn = document.querySelector('.back-btn');

        if (printBtn) {
            // First remove any existing listener
            printBtn.replaceWith(printBtn.cloneNode(true));
            const newPrintBtn = document.querySelector('.print-btn');

            newPrintBtn.addEventListener('click', function () {
                if (printTriggered) return; // ✅ prevent second click
                printTriggered = true;

                window.print();
                window.location.href = '/inventory/index/';
                setTimeout(() => {
                    printTriggered = false; // reset for next open
                   
                }, 3000); // delay long enough to allow PDF to generate
            });
        }

        if (backBtn) {
            backBtn.addEventListener('click', function () {
                window.location.href = '/inventory/index/';
            });
        }
    })();
</script>

<!--<script>-->
<!--    document.querySelector('.print-btn').addEventListener('click', function () {-->
<!--        window.print();-->
<!--    }, { once: true });-->
<!--    document.querySelector('.back-btn').addEventListener('click', function () {-->
<!--        window.location.href = '/inventory/index/';-->
<!--    });-->
<!--</script>-->