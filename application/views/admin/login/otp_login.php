<style type="text/css">
     #img-loader-data{
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('../../../assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
    }
</style>
<section id="wrapper">
    <div class="loader" id="img-loader-data" style="display:none;"></div>
    <div class="login-register" style="background-image:url(<?php echo base_url();?>/assets/images/background/login.jpeg);">        
        <div class="login-box card">
            <div class="card-body">
                <div align="center" class="alert alert-error">
                    <?php if (isset($error_status)): ?>
                        <p class="success-msg otp_error_alert" id="success-alert" style="color: red; text-align: center;">
                            <?php
                            if ($error_status == 1) {
                                echo "OTP has expired. Please request a new OTP.";
                            } else {
                                echo "Invalid OTP. Please try again.";
                            }
                            ?>
                        </p>
                    <?php endif; ?>
                    <div class="alert alert-success" id="otp_alert">
                    </div>
                </div>
                <form method="POST" class="form-horizontal form-material" id="loginform" action="<?php echo base_url();?>users/verify_otp">
                    <h3 class="box-title mb-3">Two-Step Verification</h3>
                    <p class="mb-4 text-muted">Enter the verification code we sent to <span class="text-dark text-3"><?php echo $otp_mobilenumber; ?></span></p>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" name="otp" id="otp" type="text" required="" placeholder="Enter otp" value="<?php echo $stored_otp; ?>">
                            <input class="form-control" name="user_id" id="user_id" type="hidden" value="<?php echo $user_id; ?>">
                             </div>
                    </div>
                    <div class="form-group text-center mt-3">
                        <div class="col-xs-12">
                            <div id="timer">OTP will expire in: 02:00</div>
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                            <a href="javascript:void(0)" id="resend_otp">Resend OTP</a><br>
                            <a href="<?php echo base_url('adminlogin'); ?>" id="resend_otp">Back To Login</a>
                        </div>
                    </div>  
                </form> 
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        // Countdown timer function
        // Countdown timer function
        function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            var intervalId = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = "OTP will expire in: " + minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(intervalId); // Clear interval when timer reaches zero
                }
            }, 1000);

            return intervalId; // Return the interval ID
        }

        var display = document.querySelector('#timer');
        var intervalId = startTimer(120, display); // Start timer with 2 minutes (120 seconds) duration


        setTimeout(function() {
            $('.otp_error_alert').fadeOut('fast');
        }, 10000);

        setTimeout(function() {
            $('#otp_alert').fadeOut('fast');
        }, 10000);
        $('#resend_otp').click(function() {
            $("#img-loader-data").show();
            $.ajax({
                type: "POST",
                async : false,
                url: '<?php echo base_url('users/resendOtp'); ?>',
                data : {
                    'user_id': $("#user_id").val()
                },
                success: function(response) {
                    var jsonObject = JSON.parse(response);
                    $('.otp_error_alert').html('');
                    $("#img-loader-data").hide();
                    $("#otp_alert").show().html('OTP resent successfully. Please check your mobile device for the new OTP and use it to login');
                    clearInterval(intervalId); // Clear the current timer
                    intervalId = startTimer(120, display); // Start a new timer with 2 minutes duration
                },
                error: function(xhr, status, error) {
                    $("#img-loader-data").hide();
                }
            });
        });
    });
</script>