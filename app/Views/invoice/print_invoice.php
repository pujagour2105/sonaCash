<style>
#print_div {
    background-color: #fff;
    padding: 25px; /* Padding inside the box */
    border: 1px solid #ddd; /* Optional border */
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.1); /* Optional shadow */
    max-width: 850px; /* Control maximum width on screen */
    margin: 0 auto; /* Center the box within its column */
    box-sizing: border-box;
}
.invoice-logo {
    height: 75px;
    width: 75px;
    border-radius: 50%;
    object-fit: contain;
    border: 1px solid #ccc;
    padding: 5px;
    background-color: #fff;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
}
#print_div .header {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: center;
    position: relative;
    padding-top: 10px; /* Reduced from 20px */
    padding-bottom: 25px; /* Reduced from 15px */
    margin-bottom: 20px; /* Slightly reduced */
    border-bottom: 1px solid #000;
    text-align: right;
}

#print_div .header .gstin {
    position: absolute;
    top: 8px;
    left: 10px;
    font-size: 10px;
    font-weight: bold;
    color: #666;
}
.logo-title {
    display: flex;
    align-items: center;
    gap: 15px;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.invoice-logo {
    height: 75px;
    width: 75px;
    border-radius: 50%;
    object-fit: contain;
    border: 1px solid #ccc;
    padding: 5px;
    background-color: #fff;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
}
.company-name h1 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
    color: #222;
    line-height: 1.2;
    text-align: right;
}

.company-name h2 {
    margin: 5px 0 0 0;
    font-size: 14px;
    font-weight: normal;
    color: #555;
    text-align: right;
}


#print_div .details-section {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
    margin-bottom: 20px; 
    padding-bottom: 15px; 
    gap: 20px; 
}
#print_div .party-details {
    flex-basis: 60%;
    font-size: 14px;
    min-width: 0;
    line-height: 1.5; /* Improved readability */
}
 #print_div .invoice-info {
    flex-basis: 35%;
    text-align: right;
    min-width: 0;
    font-size: 14px;
 }
 #print_div .invoice-info div {
    margin-bottom: 5px; /* Spacing */
 }
#print_div .items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px; /* Increased margin */
}
 #print_div .items-table th,
 #print_div .items-table td {
    border: 1px solid #ccc; /* Consistent border */
    padding: 8px 10px; /* Increased padding */
    text-align: left;
    vertical-align: top;
    font-size: 12px;
}
 #print_div .items-table th {
    background-color: #e0e0e0;
    /* background-color:rgb(225, 189, 189); Lighter grey */
    font-weight: bold;
    font-size: 11px;
    white-space: nowrap; /* Prevent header wrapping */
}
/* Column widths - Adjust as needed */
#print_div .items-table th:nth-child(1), #print_div .items-table td:nth-child(1) { width: 5%; text-align: center; }
#print_div .items-table th:nth-child(2), #print_div .items-table td:nth-child(2) { width: 40%; }
#print_div .items-table th:nth-child(3), #print_div .items-table td:nth-child(3) { width: 10%; text-align: right; } /* Adjusted */
#print_div .items-table th:nth-child(4), #print_div .items-table td:nth-child(4) { width: 10%; text-align: right; } /* Adjusted */
#print_div .items-table th:nth-child(5), #print_div .items-table td:nth-child(5) { width: 15%; text-align: right; }
#print_div .items-table th:nth-child(6), #print_div .items-table td:nth-child(6) { width: 20%; text-align: right; font-weight: bold; } /* Adjusted */

/* Ensure empty rows have height */
#print_div .items-table tbody tr td {
    height: 28px; /* Give empty cells some height */
}


 #print_div .summary-section {
     display: flex;
     flex-wrap: nowrap;
     justify-content: space-between;
     border-top: 1px solid #000;
     padding-top: 10px;
     margin-top: 5px;
     min-height: 40px;
     font-weight: bold;
     gap: 20px;
     font-size: 13px; /* Larger total */
}
#print_div .summary-section .payment-info {
    flex-basis: 60%;
     min-width: 0;
     line-height: 1.6;
}
#print_div .summary-section .total-amount {
    flex-basis: 35%;
    text-align: right;
    padding-right: 10px; /* Match table padding */
     min-width: 0;
}
#print_div .footer-details {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
    margin-top: 20px; /* Increased margin */
    padding-top: 15px; /* Increased padding */
    border-top: 1px solid #eee;
    font-size: 11px;
    line-height: 1.5; /* Improved readability */
    gap: 20px;
}
#print_div .footer-details .left-col {
    flex-basis: 48%;
    min-width: 0;
}
#print_div .footer-details .right-col {
    flex-basis: 48%;
    text-align: left;
    min-width: 0;
}
 #print_div .footer-details .right-col div {
    margin-bottom: 5px;
 }
 #print_div .footer-details strong {
    font-weight: bold;
 }

#print_div .declaration {
    margin-top: 20px; /* Increased margin */
    padding-top: 15px; /* Increased padding */
    border-top: 1px solid #eee;
    font-size: 10px;
    text-align: justify;
    line-height: 1.4;
    color: #555; /* Slightly muted text */
}
#print_div .signature-section {
     margin-top: 35px; /* More space before signature */
     padding-top: 15px;
     border-top: 1px solid #eee;
     display: flex;
     flex-wrap: nowrap;
     justify-content: space-between;
     font-size: 11px;
     gap: 20px;
 }
 #print_div .signature-section > div {
     flex-basis: 31%; /* Slightly adjust basis */
     text-align: left;
     min-width: 0;
 }
 #print_div .signature-section > div > .signature-line {
      border-top: 1px solid #000;
      margin-top: 40px; /* Even More space */
      text-align: center;
      padding-top: 5px;
      font-weight: bold;
 }
#print_div .branches {
    text-align: center;
    margin-top: 25px;
    padding-top: 15px;
    border-top: 1px solid #000;
    font-weight: bold;
    font-size: 12px;
    color: #333;
}


/* --- Print Specific Styles --- */
@page {
    margin: 8mm; /* Adjust print margin */
}

@media print {
    /* 1. Set page margins */
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

    /* 2. Hide everything except the print_div */
    body * {
        visibility: hidden !important;
    }

    #print_div, #print_div * {
        visibility: visible !important;
    }

    /* 3. Print area styling */
    #print_div {
        position: relative !important;
        background: white !important;
        margin: -18mm 5mm 5mm -55mm !important;
        padding: 0 !important;
        width: auto !important;
        height: auto !important;
        box-sizing: border-box !important;
        border: none !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
    }

    /* 4. Prevent breaks inside sections */
    #print_div .header,
    #print_div .details-section,
    #print_div .summary-section,
    #print_div .footer-details,
    #print_div .signature-section,
    #print_div .declaration {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    /* 5. Table formatting */
    #print_div .items-table {
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: auto !important;
        page-break-inside: avoid !important;
    }

    #print_div .items-table th,
    #print_div .items-table td {
        border: 1px solid #000 !important;
        padding: 6px 8px !important;
        font-size: 12px !important;
        vertical-align: top !important;
        word-wrap: break-word !important;
    }

    #print_div .items-table th {
        /* background-color: #f0f0f0 !important; */
        background-color: #e0e0e0;
        color: #000 !important;
        font-weight: bold;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* 6. Typography fix */
    #print_div .header h1 {
        font-size: 20pt !important;
        margin: 0 !important;
    }


    #print_div .header h2 {
        font-size: 13pt !important;
        margin: 0 !important;
    }

    #print_div .header .gstin {
        font-size: 9.5pt !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
    }

    #print_div .footer-details,
    #print_div .signature-section,
    #print_div .declaration {
        font-size: 9pt !important;
        line-height: 1.2 !important;
    }

    #print_div .branches {
        font-size: 10pt !important;
        font-weight: bold !important;
        text-align: center !important;
        margin-top: 8px !important;
    }

    /* 7. Remove page footer/header, back buttons, etc. */
    .no-print,
    .page-footer,
    .page-heading,
    .left-side-content,
    .print-button-container {
        display: none !important;
    }
}


</style>

<div class="content-wrapper">
    <div class="page-content">

        <div class="print-button-container no-print" style="text-align: right; padding: 0 15px 10px 0;">
            <button onclick="printInvoice()" class="btn btn-primary btn-sm no-print"> <i class="fa fa-print"></i> Print Invoice</button>
            <button onclick="backToInvoice()" class="btn btn-secondary btn-sm no-print"> <i class="fa fa-arrow-left"></i> Back</button>
        </div>
        <div class="ibox" id="print_div">
                <div class="header">
                    <!-- <div class="gstin">GSTIN: </div> -->
                    <div class="gstin">
                        GSTIN: <?php echo htmlspecialchars($data['gst_no'] ? $data['gst_no']:'37ADCF9538381ZQ'); ?><br>
                        Invoice No: <?php echo $data['invoice_no']; ?><br>
                        Invoice Date: <?php echo isset($data['ts']) ? date('d-m-Y', strtotime($data['ts'])) : date('d-m-Y'); ?>
                    </div>

                    <div class="logo-title">
                        <img src="/public/assets/images/s_logo.png" alt="Company Logo" class="invoice-logo" />
                        <div class="company-name">
                            <h1><?php echo COMPANY_NAME;?></h1>
                            <h2>PURCHASE INVOICE</h2>
                        </div>
                    </div>
                </div>


                <div class="details-section" style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; display: flex; justify-content: space-between; gap: 30px; margin-bottom: 20px; padding-bottom: 10px;">
    
                    <!-- Party Details -->
                    <div class="party-details" style="flex: 1;">
                        <div style="font-weight: bold; font-size: 14px; margin-bottom: 8px;">Customer Details</div>
                        <div><strong>Name:</strong> <?php echo htmlspecialchars($data['customer_name'] ?? 'N/A'); ?></div>
                        <div><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($data['customer_address'] ?? 'N/A')); ?></div>
                        <div><strong>Mobile:</strong> <?php echo nl2br(htmlspecialchars($data['customer_mobile'] ?? 'N/A')); ?></div>
                        <div><strong>Email Id:</strong> <?php echo nl2br(htmlspecialchars($data['customer_email'] ?? 'N/A')); ?></div>
                    </div>

                    <!-- Branch Details (Left Aligned) -->
                    <div class="invoice-info" style="flex: 1;">
                        <div style="font-weight: bold; font-size: 14px; margin-bottom: 8px;">Branch Details</div>
                        <div><strong>Branch:</strong> <?php echo htmlspecialchars($data['branch_name'] ?? 'N/A'); ?></div>
                        <div><strong>Address:</strong> <?php echo htmlspecialchars($data['branch_address'] ?? 'N/A'); ?></div>
                        <div><strong>Mobile:</strong> <?php echo htmlspecialchars($data['branch_number'] ?? 'N/A'); ?></div>
                        <div><strong>Email Id:</strong> <?php echo htmlspecialchars($data['branch_email'] ?? 'N/A'); ?></div>
                    </div>

                </div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Description of Goods</th>
                            <th>Qty.</th>
                            <th>Gross Wt.</th>
                            <th>Price as per 10 gram</th> 
                            <th>Amount(Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $item_sl = 1;
                        $total_amount_calculated = 0;
                        // Loop through the items passed in the 'items' key
                        if (isset($items) && !empty($items)):
                            foreach ($items as $item):
                                $total_amount_calculated += $item['amount'];  // Calculate total amount
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $item_sl++; ?></td>
                            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                            <td><?php echo number_format($item['quantity']); ?></td>
                            <td style="text-align: right;"><?php  echo floatval($item['gross_wt']); ?></td>
                            <td style="text-align: right;"><?php echo number_format($item['rate'], 2); ?></td>
                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($item['amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php 
                        $row_count = isset($items) ? count($items) : 0;
                        for($i = $row_count; $i < 5; $i++): // Add empty rows if needed ?>
                            <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
                        <?php endfor; ?>

                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center;">No items found</td></tr>
                        <?php endif; ?>
                    </tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right; font-weight: bold;">Total Purchase Value:</td>
                            <td style="text-align: right; font-weight: bold;">
                                ₹ <?php echo number_format($data['amount'] ?? $total_amount_calculated, 2); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right;">Cash Amount:</td>
                            <td style="text-align: right;">
                                ₹ <?php echo number_format($data['cash_amount'] ?? 0, 2); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right;">Online Amount:</td>
                            <td style="text-align: right;">
                                ₹ <?php echo number_format($data['online_amount'] ?? 0, 2); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right; font-weight: bold;">Paid Amount:</td>
                            <td style="text-align: right; font-weight: bold;">
                                ₹ <?php echo number_format(($data['cash_amount'] ?? 0) + ($data['online_amount'] ?? 0), 2); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right;">Balance Amount:</td>
                            <td style="text-align: right;">
                                ₹ <?php echo number_format($data['balance_amount'] ?? 0, 2); ?>
                            </td>
                        </tr>
                    </tfoot>


                </table>

                <div style="display: flex; justify-content: space-between; font-size: 13px; margin-top: 5px; padding: 10px 0; border-top: 1px solid #000;">
                    <div style="flex: 1; font-weight: bold;">
                       <?php if($data['payment_mode']!=''){?>
                        Payment Mode: <?php echo strtoupper($data['payment_mode'] ?? ''); ?>
                        <?php if (in_array(strtoupper($data['payment_mode']), ['NEFT', 'RTGS', 'UPI', 'IMPS'])): ?>
                            <br><span style="font-weight: normal; font-style: italic; color: red;">PAYMENT UNDER PROCESS IN NEFT/RTGS</span>
                        <?php endif;
                        } ?>
                    </div>
                </div>


                <div class="footer-details">
                    <div class="left-col">
                        <strong>Terms & Condition</strong><br>
                        E.& O.E.<br>
                        Subject to Delhi Jurisdiction only<br>
                    </div>
                    <div class="right-col">
                        <strong>For <?php echo COMPANY_NAME;?></strong><br>
                        <strong>Billed By :-</strong> <?php echo htmlspecialchars($data['billed_by_name'] ?? ''); ?><br>
                        Name :-
                    </div>
                </div>

                <div class="declaration">
                    <strong>CUSTOMER DECLARATION:</strong><br><br>
                    I <span style="display:inline-block; border-bottom:1px solid #000; width:350px;"></span>
                    &nbsp;&nbsp;&nbsp;&nbsp;S/O, D/O, W/O&nbsp;
                    <span style="display:inline-block; border-bottom:1px solid #000; width:250px;"></span><br><br>
                    HAVE APPROACHED YOUR OUTLET FOR SALE OF MY JEWELLERY WITH DETAILS MENTIONED ABOVE. I DECLARE THAT I AM THE SOLE RIGHTFUL OWNER OF THE JEWELLERY AND NONE OTHER THAN ME HAS ANY OTHER INTEREST IN THE SAME. WITH RESPECT TO THE SAME IT HAS BEEN EXPLAINED TO ME AND I HAVE UNDERSTOOD THAT THE VALUATION OF THE JEWELLERY WILL BE ASSESSED ON THE BASIS OF THE PURITY / CONTENT RECEIVED.
                </div>

                <div class="signature-section" style="border-top: none; padding-top: 10px; margin-top: 50px;">
                    <div><strong>MOBILE NUMBER:-</strong> <?php echo htmlspecialchars($data['branch_number'] ?? ''); ?></div>
            
                    <div style="text-align:center; flex-grow:1;"><strong>CUSTOMER'S SIGNATURE</strong></div>
                </div>


                <div class="branches">
                    OUR BRANCHES <br> <?php
                       echo ucwords("ROHINI, DURGAPURI") ;
                    foreach ($branch_name as $branch) {
                        // echo htmlspecialchars($branch['branch_name'] ?? 'ROHINI, DURGAPURI') . ", ";
                    } ?>
                </div>

            
        </div> 
    </div> 
    <footer class="page-footer">
    </footer>
</div> 

<script>
    function printInvoice() {
        window.print();
    }
    function backToInvoice() {
        // Option 1: Go back in browser history
        window.history.back();

        // Option 2: Go to a specific URL (uncomment and adjust)
        window.location.href = '<?php echo base_url('invoice/index'); /* Or your list page */ ?>';
    }
</script>



