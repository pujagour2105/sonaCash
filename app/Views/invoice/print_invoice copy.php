<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Invoice - SS Jewellers</title>
    <style>
         body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      line-height: 1.4;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    /* body {
            font-family: Arial, sans-serif;
            font-size: 12px; 
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
        } */

    .print-button-container {
      margin: 10px 0;
    }

    .no-print {
      padding: 6px 12px;
      font-size: 14px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    @media print {
      .no-print {
        display: none;
      }
    }

    /* .invoice-box {
      width: 800px;
      padding: 25px;
      border: 1px solid #eee;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
      position: relative;
    } */

       
        .invoice-box {
            width: 800px; /* Adjust width as needed */
            margin: 10px;
            padding: 25px;
            border: 1px solid #eee;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            position: relative; /* Needed for absolute positioning inside */
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            position: relative; /* Context for GSTIN */
        }
         .header .gstin {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 9px;
            text-align: left;
         }
        .header h1 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }
        .details-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .party-details {
            flex-basis: 65%; /* Take up more space */
            font-size: 13px;
        }
         .invoice-info {
            flex-basis: 30%;
            text-align: right;
         }
         .invoice-info div {
            margin-bottom: 3px;
         }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 9px;
        }
        /* Adjust column widths */
        .items-table th:nth-child(1), .items-table td:nth-child(1) { width: 5%; text-align: center; } /* Sl No. */
        .items-table th:nth-child(2), .items-table td:nth-child(2) { width: 40%; } /* Description */
        .items-table th:nth-child(3), .items-table td:nth-child(3) { width: 8%; text-align: right; } /* Qty */
        .items-table th:nth-child(4), .items-table td:nth-child(4) { width: 8%; text-align: right; } /* Unit - Assuming this is unit price?*/
        .items-table th:nth-child(5), .items-table td:nth-child(5) { width: 15%; text-align: right; } /* Price - Confusing with Unit, maybe total price? */
        .items-table th:nth-child(6), .items-table td:nth-child(6) { width: 15%; text-align: right; font-weight: bold; } /* Amount */

        .summary-section {
             display: flex;
             justify-content: space-between;
             border-top: 1px solid #000; /* Line above total */
             padding-top: 10px;
             margin-top: 0px; /* Reduced margin */
             min-height: 50px; /* Ensure space */
             font-weight: bold;
        }
        .summary-section .payment-info {
            flex-basis: 60%;
        }
        .summary-section .total-amount {
            flex-basis: 35%;
            text-align: right;
            padding-right: 6px; /* Align with table padding */
        }
        .footer-details {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 12px;
            line-height: 1.3;
        }
        .footer-details .left-col {
            flex-basis: 48%;
        }
        .footer-details .right-col {
            flex-basis: 48%;
            text-align: left; /* Default left for labels */
        }
         .footer-details .right-col div {
            margin-bottom: 5px;
         }
         .footer-details strong {
            font-weight: bold;
         }

        .declaration {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 10px; /* Very small text */
            text-align: justify;
            line-height: 1.2;
        }
        .signature-section {
            margin-top: 30px; /* More space before signature */
            padding-top: 10px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
        }
        .signature-section div {
            flex-basis: 30%;
            text-align: left;
        }
        .signature-section .signature-line {
             border-top: 1px solid #000;
             margin-top: 30px; /* Space for signature */
             text-align: center;
             padding-top: 5px;
        }
        .branches {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            font-weight: bold;
            font-size: 11px;
        }
        .print-button-container {
      margin: 10px 0;
    }
    </style>
</head>
<body>
<!-- ✅ Properly positioned print button -->
<div class="print-button-container">
    <button onclick="printInvoice()" class="no-print">Print Invoice</button>
    <button onclick="backToInvoice()" class="no-print">Back to Invoice</button>
  </div>
    <div class="invoice-box">
        <div class="header">
            <div class="gstin">GSTIN: 37ADCF9538381ZQ</div> <h1>PURCHASE INVOICE</h1>
            <h2>SS JEWELLERS</h2>
        </div>

        <div class="details-section">
            <div class="party-details">
                <strong>Party Detail:</strong><br>
                Name: <?php  echo $data['customer_name'];?><br>
                Address: <?php echo $data['customer_address']; ?><br>
                <!-- DELHI-110085 -->
            </div>
            <div class="invoice-info">
                <div><?php echo $data['branch_name']; ?></div>
                <div><strong>281</strong></div> <div></div><?= date('d-m-Y', strtotime($data['ts'])) ?> </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Description of Goods</th>
                    <th>Qty.</th>
                    <th>Unit</th> <th>Price</th> <th>Amount(Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>BALI</td>
                    <td style="text-align: right;">1.5</td> <td style="text-align: right;">?</td> <td style="text-align: right;">6200.00</td> <td style="text-align: right;">9300</td> </tr>
                <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
                 <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
                 <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
                 <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
            </tbody>
        </table>

        <div class="summary-section">
            <div class="payment-info">
                Cash Payment :- <?= esc($data['payment_mode'] ?? '-') ?> Mode <br> NEFT:-
            </div>
            <div class="total-amount">
                 <?php echo $data['amount'] ; ?>
            </div>
        </div>


        <div class="footer-details">
            <div class="left-col">
                <strong>Terms & Condition</strong><br>
                E.& O.E.<br>
                Subject to Delhi Jurisdiction only<br>
                <strong>OFFICE ADDRESS :-</strong> G-8/1,ROHINI,SCTOR-7,DELHI-110085<br>
                <strong>MOB:</strong> 9891011250/9891013646
            </div>
            <div class="right-col">
                <strong>For SS JEWELLERS</strong><br>
                <strong>Billed By :-</strong><br>
                Name :-<br><br> S/O,D/O,W/O :- <br>
                </div>
        </div>

        <div class="declaration">
            HAVE APPROACHED YOUR OUTLET FOR SALE OF MY JEWELLERY WITH DETAILS MENTIONED ABOVE. I DECLARE THAT I AM THE SOLE RIGHTFUL OWNER OF THE JEWELLERY AND NONE OTHER THAN ME HAS ANY OTHER INTEREST IN THE SAME. WITH RESPECT TO THE SAME IT HAS BEEN EXPLAINED TO ME AND I HAVE UNDERSTOOD THAT THE VALUATION OF THE JEWELLERY WILL BE ASSESSED ON THE BASIS OF THE PURITY / CONTENT RECEIVED.
        </div>

        <div class="signature-section">
             <div>
                 MOBILE NUMBER:- <br>
                 </div>
             <div>
                 LANDLINE NUMBER:- <br>
                 </div>
             <div>
                <div class="signature-line">CUSTOMER'S SIGNATURE</div>
            </div>
        </div>

        <div class="branches">
            OUR BRANCHES <br> ROHINI, DURGAPURI
        </div>

    </div>
</body>

<script>
    function printInvoice() {
      window.print();
    }
    function backToInvoice() {
    window.location.href = '/invoice/index'; // Adjust this path if needed
    }
  </script>
</html>