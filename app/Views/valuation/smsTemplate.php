<style>
    #otpModal .modal-dialog {
    margin-top: 10vh; /* So it's vertically centered-ish */
    }

#otpModal.modal-full .modal-content {
    min-height: 50vh;
}
#otpModal .modal-content {
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    padding: 1.5rem;
}

#otpModal .modal-header {
    background-color: #007bff;
    color: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

#otpModal .modal-body {
    padding: 1.5rem;
}

#otpModal .otp-container {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    width: 100%;
}

#otpModal input[type="text"] {
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    width: 100%;
    margin-bottom: 1rem;
}

#otpModal button{
   
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
}
#otpModal .verifybtn{
 background-color: #28a745;
}

#otpModal button:hover {
    background-color: #218838;
}

</style>
<!-- OTP Template HTML -->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">OTP Verification</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <form id="otpForm">
                <div class="form-group">
                    <label for="otpInput">Enter OTP (6 Digit)</label>
                    <input type="hidden" id="valuationId" value="<?php echo $data['id']; ?>">
                    <input type="text" class="form-control" id="otpInput" placeholder="Enter the OTP sent to your phone" maxlength="6" pattern="\d*" inputmode="numeric" required>
                    
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success verifybtn">Verify OTP</button>
                    <button type="button" class="btn btn-warning" id="resendOtpBtn">Resend OTP</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>

<script>
    $(document).ready(function() {
        // Handle OTP form submission
        $('#otpForm').on('submit', function(e) {
            e.preventDefault();
            
            var otp = $('#otpInput').val();

            if (otp.length !== 6 || isNaN(otp)) {
                alert('Please enter a valid 6-digit OTP.');
                return false;
            }
            if (otp === '') {
                alert('Please enter OTP');
                return;
            }

            $.ajax({
                url: '/valuation/verifyOtp',
                type: 'POST',
                data: { otp: otp },
                success: function(response) {
                  response = JSON.parse(response);
                    if (response.success) {
                        toastr.success('OTP verified successfully!');
                        $('#otpModal').modal('hide');
                        const valuationId =response.valuation_id;
                        const source = 'viewValuation';
                        window.location.href = `/valuation/index/?valuation_id=${valuationId}&source=${source}`;
                        $('#valuation_table').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error('Invalid OTP. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred during verification.');
                }
            });
        });

        // Handle Resend OTP button
        $('#resendOtpBtn').on('click', function() {
            $.ajax({
                url: '/valuation/resendOtp',
                type: 'POST',
                data: { valuationId: $('#valuationId').val() },
                success: function(response) {
                  response = JSON.parse(response);
                    if (response.success) {
                      $('#valuation_table').DataTable().ajax.reload(null, false);
                      toastr.success('OTP sent successfully!');
                    } else {
                        toastr.error('Failed to resend OTP.');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred while resending OTP.');
                }
            });
        });
    });
</script>
