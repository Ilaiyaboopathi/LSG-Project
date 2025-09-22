<?php
ob_start();
require 'data/dbconfig.php'; ?>
<!doctype html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
  data-assets-path="assets/" data-template="vertical-menu-template-no-customizer" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>MBW | Admin Login</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.png" />

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
  <link rel="stylesheet" href="assets/css/preloader.css" />

  <!-- Helpers -->
  <script src="assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="assets/js/config.js"></script>
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
</head>

<body>
  <!-- Content -->

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
            <h4 class="mb-1">Forgot your password? 🔐</h4>
            <p class="mb-5">We've sent your credentials to your email to regain access.</p>

            <form id="formForgetPassword" class="mb-5" method="POST">
              <div class="form-floating form-floating-outline mb-5">
                <input type="text" class="form-control" id="email" name="email"
                  placeholder="Enter your registered email" autofocus />
                <label for="email">Registered Email</label>
              </div>

              <div class="mb-5">
                <button class="btn btn-primary d-grid w-100" id="submit" value="btnForgetPassword" type="submit">Send
                  Now</button>
              </div>
              <div class="mb-5 d-flex justify-content-between mt-5">
                <a href="login.php" class="float-end mb-1 mt-2">
                  <span><-- Back To Login</span>
                </a>
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

  
  <!-- Submit Pre loader -->
  <div class="event_trigger_loader">
    <div class="cube-folding">
      <span class="leaf1"></span>
      <span class="leaf2"></span>
      <span class="leaf3"></span>
      <span class="leaf4"></span>
    </div>
    <div class="text_close_caution">
      Please wait few seconds....
    </div>
  </div>
  <!-- Submit Pre loader -->

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
 $('#formForgetPassword').on('submit', function (e) {
  e.preventDefault(); // Prevent the default form submission

  $('.event_trigger_loader').addClass('active');

  const email = $('#email').val();

 fetch('function.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded'
  },
  body: new URLSearchParams({
    email: $('#email').val(),
    submit: 'btnForgetPassword',
    action: 'forgot_password'
  })
})
    .then(response => response.text())
    .then(result => {
      console.log('Server response:', result);
      const trimmedResult = result.trim();

      if (trimmedResult === 'success') {
        showModalWithParams('Check your Email...', 'true');
      } else if (trimmedResult === 'Failed') {
        showModalWithParams('Invalid Email...', 'false');
      } else {
        alert('Unexpected response from the server: ' + trimmedResult);
      }

      setTimeout(() => $('.event_trigger_loader').removeClass('active'), 1000);
    })
    .catch(error => {
      setTimeout(() => $('.event_trigger_loader').removeClass('active'), 1000);
      showModalWithParams(`An error occurred: ${error}`, 'false');
    });
});


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

<script src="assets/js/preloader.js"></script>

</body>

</html>