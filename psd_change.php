<?php

session_start();
require 'vendor/autoload.php';
require 'data/dbconfig.php';

// Check if the token is present in the URL
if (isset($_GET['token'])) {
  $token = $_GET['token'];

  // Prepare a SQL statement to check the token
  $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires >= NOW()");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if the token is valid
  if ($result->num_rows > 0) {
    // Token is valid
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $validToken = true;
  } else {
    // Token is invalid or expired
    $validToken = false;
  }
} else {
  // No token provided
  $validToken = false;
}

?>


<!doctype html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
  data-assets-path="assets/" data-template="vertical-menu-template-no-customizer" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>MBW | New Password</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
    rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="assets/vendor/fonts/remixicon/remixicon.css" />
  <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

  <!-- Menu waves for no-customizer fix -->
  <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" />
  <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" />
  <link rel="stylesheet" href="assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
  <!-- Vendor -->
  <link rel="stylesheet" href="assets/vendor/libs/@form-validation/form-validation.css" />

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

  <!-- Helpers -->
  <script src="assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="assets/js/config.js"></script>

  <!-- ---Alert_show---- -->
  <script>
    function showModalWithParams(message, status) {
      var content = $("#custom_alert_side");
      var content_para = $("#custom_alert_side .msg_content");

      // Remove any existing success or error classes
      content.removeClass("success_my_alert");
      content.removeClass("error_my_alert");

      // Add the appropriate class based on status
      if (status === 'false') {
        content.addClass("error_my_alert");
        content_para.html(message);
      } else {
        content.addClass("success_my_alert");
        content_para.html(message);
      }


      var modal = new bootstrap.Modal(document.getElementById('alertCardModal'), {
        keyboard: false
      });
      modal.show();


      // setTimeout(() => {
      //     modal.hide();


      //     var currentUrl = new URL(window.location.href);


      //         currentUrl.searchParams.delete('id');

      //         window.location.href = currentUrl.toString();

      // }, 2000); 
    }

  </script>
  <!-- ---Alert_show---- -->


</head>

<body>
  <!-- Content -->

  

    <div class="position-relative">
      <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
        <div class="authentication-inner py-6">
          <?php if (!empty($ErrorMsg)): ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $ErrorMsg; ?>
            </div>
          <?php endif; ?>
          <!-- Login -->
          <div class="card p-md-7 p-1">
            <!-- Logo -->
            <div class="app-brand justify-content-center mt-5">
              <a href="login.php" class="app-brand-link gap-2">
                <img width="150" src="assets/img/logos/mbw-logo.webp">
              </a>
            </div>
            <!-- /Logo -->

            <div class="card-body mt-1">
              <h4 class="mb-1">Change New Password</h4>
              <p class="mb-5">Change Your Password to Access Your Account Securely</p>

              <form id="formReset" class="mb-5" action="" method="POST">
                <input type="hidden" id="hiddenToken"
                  value="<?php echo empty($token) ? '' : htmlspecialchars($token); ?>">
                <div class="mb-5">
                  <div class="form-password-toggle">
                    <div class="input-group input-group-merge">
                      <div class="form-floating form-floating-outline">

                        <!-- <input type="password" id="password1" class="form-control" name="New_password"
                          placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                          aria-describedby="password1" required /> -->
                        <input type="password" required id="password" class="form-control space_prevent" name="password"
                          placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                          aria-describedby="password" />
                        <label for="password">New Password</label>
                      </div>
                      <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                    </div>
                    <small class="password_guide_text">
                      <span class="Psd_Cond1">Lowercase & Uppercase |</span>
                      <span class="Psd_Cond2">Number (0-9) |</span>
                      <span class="Psd_Cond3">Special Character (!@#$%^&*) |</span>
                      <span class="Psd_Cond4">Atleast 8 Character</span>
                      <span class="Psd_Error" style="color: red;"></span>
                    </small>
                  </div>
                </div>
                <div class="mb-5">
                  <div class="form-password-toggle">
                    <div class="input-group input-group-merge">
                      <div class="form-floating form-floating-outline">
                        <!-- <input type="password" id="password" class="form-control" name="confirm_password"
                          placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                          aria-describedby="password" required /> -->
                        <input type="password" id="confirmpassword" required class="form-control space_prevent"
                          name="confirmpassword"
                          placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                          aria-describedby="password" />
                        <label for="confirmpassword">Confirm Password</label>
                      </div>
                        <input type="hidden" id="hiddenToken" value="<?= $_GET['token'] ?>">
                          <input type="hidden" id="submit" value="resetpassword">
                      <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                    </div>
                  </div>
                </div>

                <div class="mb-5">
                  <button class="btn btn-primary d-grid w-100 psd_validate_btn" id="submit" value="resetForgetPassword"
                    type="submit">Set New Password</button>
                </div>
              </form>

            </div>
          </div>
          <!-- /Login -->
          <img alt="mask" src="assets/img/illustrations/auth-basic-login-mask-light.png"
            class="authentication-image d-none d-lg-block"
            data-app-light-img="illustrations/auth-basic-login-mask-light.png"
            data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
        </div>
      </div>
    </div>



    <div class="position-relative">
      <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
        <div class="authentication-inner py-6">
          <!-- Login -->
          <div class="card p-md-7 p-1">
            <!-- Logo -->
            <div class="app-brand justify-content-center mt-5">
              <a href="index.php" class="app-brand-link gap-2">
                <img width="150" src="assets/img/logos/mbw-logo.webp">
              </a>
            </div>
            <!-- /Logo -->

            <div class="card-body mt-1">
              <h4 class="mb-1 text-center">Password Reset Link Expired</h4>
              <p class="mb-5 text-center">Please request a new password reset link to regain access to your account. If
                you need further assistance, don’t hesitate to reach out to our support team!</p>
              <div class="mb-5 d-flex justify-content-between mt-5">
                <img width="150" class="rounded mx-auto d-block" style="transform: rotate(90deg);"
                  src="assets/img/illustrations/link_expiry.png">
              </div>
              <div class="mb-5">
                <a href="ForgotPsd.php" class="btn btn-primary d-grid w-100" value="btnForgetPassword">Back To Send Link
                  Again</a>
              </div>
            </div>
          </div>
          <!-- /Login -->
          <img alt="mask" src="assets/img/illustrations/auth-basic-login-mask-light.png"
            class="authentication-image d-none d-lg-block"
            data-app-light-img="illustrations/auth-basic-login-mask-light.png"
            data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
        </div>
        <!-- Alert Card Modal -->
        <div class="modal fade alert_fade" id="alertCardModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered1 modal-simple my_modern_alert modal-add-new-cc">
            <div class="modal-content custom_slide_model" id="custom_alert_side">
              <button type="button" class="my_modern_alert_button" data-bs-dismiss="modal" aria-label="Close">x</button>
              <div class="modal-body p-0">
                <div class="text-left">
                  <p class="msg_content"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--/ Alert Card Modal -->
      </div>
    </div>

 

  <!-- Alert Card Modal -->
  <div class="modal fade alert_fade" id="alertCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple my_modern_alert modal-add-new-cc">
      <div class="modal-content custom_slide_model" id="custom_alert_side">
        <button type="button" id="modalCloseButton" class="my_modern_alert_button" data-bs-dismiss="modal"
          aria-label="Close">x</button>
        <div class="modal-body p-0">
          <div class="text-left">
            <p class="msg_content"></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Alert Card Modal -->


  <!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>
  <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
  <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="assets/vendor/libs/hammer/hammer.js"></script>
  <script src="assets/vendor/libs/i18n/i18n.js"></script>
  <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
  <script src="assets/vendor/js/menu.js"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="assets/vendor/libs/@form-validation/popular.js"></script>
  <script src="assets/vendor/libs/@form-validation/bootstrap5.js"></script>
  <script src="assets/vendor/libs/@form-validation/auto-focus.js"></script>

  <!-- Main JS -->
  <script src="assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="assets/js/pages-auth.js"></script>





  <script>

    $(document).ready(function () {
      $('.space_prevent').on('input', function () {
        // Remove leading and trailing spaces
        let value = $(this).val().trim();

        // Replace multiple spaces with a single space
        value = value.replace(/\s+/g, ' ');

        // Update the input value
        $(this).val(value);
      });
    });

    var password = $('#password');
    var cnf_password = $('#confirmpassword');
    var cond_1 = $('.Psd_Cond1'),
      cond_2 = $('.Psd_Cond2'),
      cond_3 = $('.Psd_Cond3'),
      cond_4 = $('.Psd_Cond4');

    password.on('input', function () {
      let pass = password.val();
      checkStrength(pass);
    });

    cnf_password.on('input', function () {
      let cnf_pass = cnf_password.val();
      if (cnf_pass === password.val()) {
        $('.Psd_Confirm').text('');
        $('.Psd_Confirm').text('Good, Confirm passwords is match');
        $('.Psd_Confirm').addClass('checked');
        $('.psd_validate_btn').addClass('enable');
        $('.psd_validate_btn').removeClass('disable');
      }
      else {
        $('.Psd_Confirm').text('');
        $('.Psd_Confirm').text('Sorry, Please check confirm passwords does not match');
        $('.Psd_Confirm').removeClass('checked');
        $('.psd_validate_btn').addClass('disable');
        $('.psd_validate_btn').removeClass('enable');
      }
    });


    function checkStrength(get_password) {
      let strength = 0;

      var Cond1_isValid = /([a-z].*[A-Z])|([A-Z].*[a-z])/.test(get_password);
      var Cond2_isValid = /([0-9])/.test(get_password);
      var Cond3_isValid = /([!,%,&,@,#,$,^,*,?,_,~])/.test(get_password);

      //If password contains both lower and uppercase characters
      if (Cond1_isValid) {
        strength += 1;
        cond_1.addClass('checked');
      } else {
        cond_1.removeClass('checked');
      }
      //If it has numbers and characters
      if (Cond2_isValid) {
        strength += 1;
        cond_2.addClass('checked');
      } else {
        cond_2.removeClass('checked');
      }
      //If it has one special character
      if (Cond3_isValid) {
        strength += 1;
        cond_3.addClass('checked');
      } else {
        cond_3.removeClass('checked');
      }
      //If password is greater than 7
      if (get_password.length > 7) {
        strength += 1;
        cond_4.addClass('checked');
      } else {
        cond_4.removeClass('checked');
      }

      if (strength == 4) {

        $('.psd_validate_btn').addClass('enable');
        $('.psd_validate_btn').removeClass('disable');
      }
      else {
        $('.psd_validate_btn').addClass('disable');
        $('.psd_validate_btn').removeClass('enable');
      }
    }



    $('#formReset').on('submit', function (e) {
      e.preventDefault(); // Prevent the default form submission
      // if (!validatePassword()) {
      //   return; // If validation fails, do not proceed
      // }

      //Add the Preloader
      $('.event_trigger_loader').addClass('active');


      const password = $('#password').val();
      const confirmpassword = $('#confirmpassword').val();
      const hiddenToken = $('#hiddenToken').val();
      const submit = $('#submit').val();

      if (password === confirmpassword) {
        // Submit the form data using Fetch API
        fetch('function.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({

            'hiddenToken': hiddenToken,
            'password': password,
            'confirmpassword': confirmpassword,

            'submit': submit,

          })
        })
          .then(response => response.text())
          .then(result => {
            // Log the response from PHP
            //console.log('Server response:', result);

            //Remove the Preloader
            setTimeout(function () {
              $('.event_trigger_loader').removeClass('active');
            }, 1000);

            const trimmedResult = result.trim();
            if (trimmedResult === 'success') {
              showModalWithParamsPass('Password Changed Successfully', 'true');


            } else if (trimmedResult === 'failed') {
              showModalWithParamsPass('Password does not exist', 'true');

            } else {
              // alert('Unexpected response from the server: ' + trimmedResult);
              showModalWithParamsPass(trimmedResult, 'false');

            }
          })
          .catch(error => {

            //Remove the Preloader
            setTimeout(function () {
              $('.event_trigger_loader').removeClass('active');
            }, 1000);

            showModalWithParams(`An error occurred: ${error}`, 'false');

          });
      } else {
        showModalWithParams('Password MisMatch', 'false');
      }



    });


    function showModalWithParams(message, status) {

      var content = $("#custom_alert_side");
      var content_para = $("#custom_alert_side .msg_content");

      content.removeClass("success_my_alert");
      content.removeClass("error_my_alert");

      if (status == 'false') {
        content.addClass("error_my_alert");
        content_para.html(message);
      } else {
        content.addClass("success_my_alert");
        content_para.html(message);
      }

      // Initialize and show the modal
      var modal = new bootstrap.Modal(document.getElementById('alertCardModal'), {
        keyboard: false
      });
      modal.show();
    }

    function showModalWithParamsPass(message, status) {
      var content = $("#custom_alert_side");
      var content_para = $("#custom_alert_side .msg_content");

      // Remove any existing success or error classes
      content.removeClass("success_my_alert");
      content.removeClass("error_my_alert");

      // Add the appropriate class based on status
      if (status === 'false') {
        content.addClass("error_my_alert");
        content_para.html(message);
      } else {
        content.addClass("success_my_alert");
        content_para.html(message);

        // Redirect to login.php after showing the success message
        setTimeout(function () {
          window.location.href = 'login.php';
        }, 3000); // Redirect after 3 seconds (3000 milliseconds)
      }

      var modal = new bootstrap.Modal(document.getElementById('alertCardModal'), {
        keyboard: false
      });
      modal.show();
    }

    // function validatePassword() {
    //   const password = document.getElementById('password').value;
    //   let errorMessages = [];
    //   const errorContainer = document.querySelector('.Psd_Error');

    //   if (!/[a-z]/.test(password) || !/[A-Z]/.test(password)) {
    //     errorMessages.push('Password must contain both lowercase and uppercase letters.');
    //   }
    //   if (!/\d/.test(password)) {
    //     errorMessages.push('Password must contain at least one number.');
    //   }
    //   if (!/[!@#$%^&*]/.test(password)) {
    //     errorMessages.push('Password must contain at least one special character.');
    //   }
    //   if (password.length < 8) {
    //     errorMessages.push('Password must be at least 8 characters long.');
    //   }

    //   errorContainer.textContent = errorMessages.join(' ');

    //   return errorMessages.length === 0;
    // }


  </script>

</body>

</html>