<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
<style>
    @page {
        margin: 130px 30px 80px 30px; /* top | right | bottom | left */
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
    }

    header {
        position: fixed;
        top: -100px;
        left: 0;
        right: 0;
        height: 100px;
        padding-bottom: 5px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .branding {
        display: flex;
        align-items: center;
    }

    .logo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        border: 1px solid #ccc;
    }

    .company-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .company-name {
        font-size: 16px;
        font-weight: bold;
        margin: 0;
    }

    .company-subtitle {
        font-size: 12px;
        color: #666;
        margin: 0;
    }

    .header-line {
        width: 100%;
        border-top: 1px solid #ccc;
        margin-top: 5px;
    }

    footer {
        position: fixed;
        bottom: -60px;
        left: 0;
        right: 0;
        height: 50px;
        font-size: 10px;
    }

    .footer-content {
        border-top: 1px solid #ccc;
        display: flex;
        justify-content: space-between;
        padding-top: 5px;
    }

    .footer-left {
        font-size: 10px;
    }

    .footer-right {
        font-size: 10px;
        text-align: right;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
        font-size: 11px; /* increased from 10px */
        line-height: 1.6; /* added line height */
    }

    th, td {
        border: 1px solid #000;
        padding: 6px 4px; /* increased padding vertically */
        text-align: center;
    }


    th {
        background-color: #f1f1f1;
    }

    .total-row {
        font-weight: bold;
        background-color: #eee;
    }
</style>


</head>
<body>
<header>
    <table width="100%" cellpadding="0" cellspacing="0" style="border: none; margin-bottom: 15px;">
        <tr>
            <!-- Logo + Company Info -->
        <td align="left" style="width: 50%; vertical-align: middle; border: none; border-bottom: 2px solid black; text-align: left;">
            <div style="display: flex; align-items: center;">
                <img src="<?= base_url('/public/assets/images/s_logo.png') ?>" 
                     style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 12px; border: 1px solid #ccc;">
                <div>
                    
                    <!-- Optional subtitle -->
                    <!-- <div style="font-size: 12px; color: #666;">Inventory Report</div> -->
                </div>
            </div>
        </td>
            <!-- Balance Amount -->
              <td style="width: 50%; text-align: right; vertical-align: top;  border: none; border-bottom: 2px solid black;">
                <div style="font-size: 14px; font-weight: bold;">
                    <div style="font-size: 16px; font-weight: bold;"><?= COMPANY_NAME ?></div>
                    <div style="font-size: 12px; color: #666;">Inventory Report</div>
                </div>
            </td>
           
        </tr>
    </table>
</header>

<footer>
    <div class="footer-content">
        <div class="footer-left">
            Signature: ____________________
        </div>
        <div class="footer-right">
            <?= date('d-m-Y h:i A') ?><br>
        </div>
    </div>
</footer>

<?php
$rowsPerPage = 13; // Adjust as needed
$chunks = array_chunk($data, $rowsPerPage);
$pageIndex = 0;
?>

    <main>
        <?php
            $branch_id = session()->get('branch_id');
            $branch_fund = getAvailableFund($branch_id);
        ?>
        <div style="width: 100%; text-align: right; margin-bottom: 8px;">
            <span style="font-size: 14px; font-weight: bold;">
                Balance Amount:
                <span style="color: green;">₹ <?= esc($branch_fund ?? '0.00') ?></span>
            </span>
        </div>
        <?php foreach ($chunks as $chunk): ?>
            <table style="margin-top:8px;">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Gross Wt</th>
                        <th>Diamond</th>
                        <th>Silver</th>
                        <th>Net Wt</th>
                        <th>Per (%)</th>
                        <th>Gold Rate</th>
                        <th>Silver Rate </th>
                        <th>Diamond Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Page totals
                    $pageTotal = [
                        'gross_weight' => 0,
                        'diamond' => 0,
                        'silver' => 0,
                        'net_weight' => 0,
                        'total_amount' => 0
                    ];
                ?>
                <?php foreach ($chunk as $row): ?>
                    <tr>
                        <td><?= $row['branch_name'] ?? '' ?></td>
                        <td><?= date('d-m-Y', strtotime($row['ts'] ?? '')) ?></td>
                        <td><?= $row['item_name'] ?? '' ?></td>
                        <td><?= number_format($row['gross_weight'] ?? 0, 2) ?></td>
                        <td><?= number_format($row['diamond'] ?? 0, 2) ?></td>
                        <td><?= number_format($row['silver'] ?? 0, 2) ?></td>
                        <td><?= number_format($row['net_weight'] ?? 0, 2) ?></td>
                        <td><?= number_format($row['percentage'] ?? 0, 2) ?></td>
                        <td><?= number_format($row['gold_rate'] ?? 0, 2) ?></td>
                        <td><?= number_format($row['silver_rate'] ?? 0, 2) ?></td>
                        <td><?= number_format($row['diamond_rate'] ?? 0, 2) ?></td>
                        <td><?= number_format($row['total_amount'] ?? 0, 2) ?></td>
                    </tr>
                    <?php
                        $pageTotal['gross_weight'] += $row['gross_weight'] ?? 0;
                        $pageTotal['diamond'] += $row['diamond'] ?? 0;
                        $pageTotal['silver'] += $row['silver'] ?? 0;
                        $pageTotal['net_weight'] += $row['net_weight'] ?? 0;
                        $pageTotal['total_amount'] += $row['total_amount'] ?? 0;
                    ?>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3">Page Total</td>
                    <td><?= number_format($pageTotal['gross_weight'], 2) ?></td>
                    <td><?= number_format($pageTotal['diamond'], 2) ?></td>
                    <td><?= number_format($pageTotal['silver'], 2) ?></td>
                    <td><?= number_format($pageTotal['net_weight'], 2) ?></td>
                    <td colspan="4"></td>
                    <td><?= number_format($pageTotal['total_amount'], 2) ?></td>
                </tr>
                </tbody>
            </table>

            <?php if (++$pageIndex < count($chunks)): ?>
                <div style="page-break-after: always;"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </main>

</body>
</html>
