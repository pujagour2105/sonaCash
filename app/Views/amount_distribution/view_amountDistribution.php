<style>

.table-responsive {
    max-height: 100%;
}

.signature-row {
    display: none;
}

.print-header {
    display: none;
}

@media print {
    @page {
        size: A4;
        margin: 0; 
    }
    html, body {
        margin: 0 !important;
        padding: 0 !important;
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
        left: 0;
        top: 0;
        width: 100% !important;
        margin: 0 auto !important;
        padding: 0 !important;
        background: white;
        z-index: 9999;
        page-break-inside: avoid;
    }

    .no-print {
        display: none !important;
    }
    .print:last-child {
     page-break-after: auto;
    }
    /* Clean header display during print */
    .print-header {
        display: flex !important;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .print-logo {
        height: 60px;
        border-radius: 50%;
    }

    .company-name {
        font-size: 24px;
        font-weight: bold;
    }

    .company-details {
        font-size: 14px;
        color: #333;
    }

    .signature-row {
        display: flex !important;
        justify-content: space-between;
        margin-top: 100px;
    }

    .signature-box {
        width: 30%;
        border-top: 1px solid #000;
        text-align: center;
        font-size: 14px;
        padding-top: 5px;
    }

    table, tr, td, th {
        page-break-inside: avoid !important;
    }
}


</style>
<div class="modal-body clearfix ibox-body" id="print-area">

    <div class="card">
        <div class="card-body">

            <!-- Print Header -->
            <div class="print-header">
                <div class="print-header-left">
                    <img src="<?= base_url('/public/assets/images/s_logo.png') ?>" alt="SS GOLD Logo" class="print-logo">
                </div>
                <div class="print-header-right">
                    <h2 class="company-name">SS GOLD</h2>
                    <p class="company-details">Purity in Trust</p>
                </div>
            </div>
            <!-- Table Content -->
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Customer Name</th>
                            <th>Amount</th>
                            <th>Duration</th>
                            <th>Is Approved</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)): ?>
                            <tr>
                                <td><?= htmlspecialchars($data['customer_name']) ?></td>
                                <td><?= number_format($data['amount'], 2) ?></td>
                                <td><?= htmlspecialchars($data['duration']) ?></td>
                                <td>
                                    <?php
                                        if (isset($data['is_approved'])) {
                                            if ($data['is_approved'] === '1') {
                                                echo 'Approved';
                                            } elseif ($data['is_approved'] === '2') {
                                                echo 'Rejected';
                                            } else {
                                                echo 'Pending';
                                            }
                                        }

                                    ?>
                                </td>
                                <td><?= htmlspecialchars($data['description']) ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Print Button -->
            <button class="btn btn-primary print-btn no-print">Print</button>

        </div>
    </div>
</div>

<script>
document.addEventListener('click', function (e) {
  if (e.target && e.target.classList.contains('print-btn')) {
    // 🧽 Remove duplicate #print-area before printing
    const areas = document.querySelectorAll('#print-area');
    for (let i = 1; i < areas.length; i++) {
      areas[i].remove();
    }

    setTimeout(() => {
      window.print();
    }, 100);
  }
});
</script>


