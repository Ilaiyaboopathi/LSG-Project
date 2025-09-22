<?php
ob_start();
session_start();
require 'data/dbconfig.php';
?>
<!doctype html>
<html
    lang="en"
    class="light-style layout-wide customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
    data-template="vertical-menu-template-no-customizer"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Task Engine | Admin Login</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
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
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
</head>

<body>
    <!-- Content -->
    <?php
    $sql = "SELECT * FROM logo WHERE id= 1 ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        //$name_of_file = htmlspecialchars($row['name']);
        $file_url = htmlspecialchars($row['file_url']);
    }


    ?>
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
            <div class="authentication-inner py-6">
                <!-- Login -->
                <div class="card p-md-7 p-1">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="index.php" class="app-brand-link gap-2">
                            <img width="150" src="<?php echo htmlspecialchars($file_url); ?>">
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-1">
                        <h4 class="mb-1">Welcome to Task Engine 👋</h4>
                        <p class="mb-5">Please sign-in to your account and update</p>

                        <form id="formAuthentication" class="mb-5" method="POST">
                            <div class="form-floating form-floating-outline mb-5">
                                <input
                                    type="text"
                                    class="form-control space_prevent"
                                    id="username"
                                    name="username"
                                    placeholder="Enter your email or username"
                                    autofocus
                                    value="<?php if (isset($_COOKIE["user_login"])) {
                                                echo htmlspecialchars($_COOKIE["user_login"]);
                                            } ?>" />
                                <label for="email">Email or Username</label>
                            </div>
                            <div class="mb-5">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input
                                                type="password"
                                                id="password"
                                                class="form-control space_prevent"
                                                name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password"
                                                value="<?php if (isset($_COOKIE["userpassword"])) {
                                                            echo htmlspecialchars($_COOKIE["userpassword"]);
                                                        } ?>" />
                                            <label for="password">Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-5 d-flex justify-content-between mt-5">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember" <?php if (isset($_COOKIE["user_login"])) { ?> checked <?php } ?> />
                                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                                </div>
                                <a href="ForgotPsd.php" class="float-end mb-1 mt-2">
                                    <span>Forgot Password?</span>
                                </a>
                            </div>
                            <div class="mb-5">
                                <button class="btn btn-primary d-grid w-100" id="submit" value="Login" name="btnSubmitLogin" type="submit">Log In</button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Login -->
                <img
                    alt="mask"
                    src="assets/img/illustrations/auth-basic-login-mask-light.png"
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
</body>

</html>


<script>
    //Space Prevent Code Start


    $(document).ready(function() {
        $('.space_prevent').on('input', function() {
            // Remove leading and trailing spaces
            let value = $(this).val().trim();

            // Replace multiple spaces with a single space
            value = value.replace(/\s+/g, ' ');

            // Update the input value
            $(this).val(value);
        });
    });

    //Space Prevent Code End



    $('#formAuthentication').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const username = $('#username').val();
        const password = $('#password').val();
        const remember = $('#remember').val();

        const submit = $('#submit').val();



        // Submit the form data using Fetch API
        fetch('loginhandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({

                    'username': username,
                    'password': password,
                    'remember': remember,
                    'submit': submit,

                })
            })
            .then(response => response.text())
            .then(result => {
                // Log the response from PHP
                console.log('Server response:', result);


                const trimmedResult = result.trim();
                if (trimmedResult === 'employee') {
                   

                   //window.location.href = 'https://demo.mbwapps.in/index.php?page=dashboard';

            window.location.href = 'https://localhost/LSG_Final_12_09/index.php?page=dashboard';

                   
                } else if (trimmedResult === 'admin') {
                   
              //window.location.href = 'https://demo.mbwapps.in/index.php?page=dashboard';

               window.location.href = 'https://localhost/LSG_Final_12_09/index.php?page=dashboard';
                  

                }
             else if (trimmedResult === 'client') {
                   
              //window.location.href = 'https://demo.mbwapps.in/assignjob.php?page=assignjob';

               window.location.href = 'https://localhost/LSG_Final_12_09/assignjob.php?page=assignJob';
                   
                }
                // Handle the response text
                else if (trimmedResult === 'InActive') {
                    showModalWithParams('Account is InActive', 'false');


                } else if (trimmedResult === 'Wrong') {
                    showModalWithParams('Invalid UserName or Password', 'false');

                } else {
                    // alert('Unexpected response from the server: ' + trimmedResult);
                    showModalWithParams(trimmedResult, 'false');

                }
            })
            .catch(error => console.error('Error:', error));
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
</script>