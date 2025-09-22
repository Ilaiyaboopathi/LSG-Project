<?php include('include/head.php'); ?>

 
 <!-- Content wrapper -->
 <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">

            <!-- -----Data Table assignTask Start------ -->

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <img width="370" src="assets/img/illustrations/PasswordsPndgs.png" alt="password-vector-image">
                      </div>
                      <div class="col-8">
                        <div class="card-body mt-1">
                          <h4 class="mb-1">Reset Password 🔐</h4>
                          <p class="mb-5">Your new password must be different from previously used passwords</p>
            
                          <form id="formReset" class="mb-5" >
                            <div class="mb-5">
                              <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                  <div class="form-floating form-floating-outline">
                                    <input
                                      type="password"
                                      required
                                      id="oldpassword"
                                      class="form-control space_prevent"
                                      name="oldpassword"
                                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                      aria-describedby="password" />
                                    <label for="oldpassword">Old Password</label>
                                  </div>
                                  <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                                </div>
                              </div>
                            </div>
                            <div class="mb-5">
                              <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                  <div class="form-floating form-floating-outline">
                                    <input
                                      type="password"
                                      required
                                      id="password"
                                      class="form-control space_prevent"
                                      name="password"
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
                                </small>
                              </div>
                            </div>
                            <div class="mb-5">
                              <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                  <div class="form-floating form-floating-outline">
                                    <input
                                      type="password"
                                      id="confirmpassword"
                                      required
                                      class="form-control space_prevent"
                                      name="confirmpassword"
                                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                      aria-describedby="password" />
                                    <label for="confirmpassword">Confirm Password</label>
                                  </div>
                                  <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                                </div>
                                <small class="password_guide_text">
                                  <span class="Psd_Confirm"></span>
                                </small>
                              </div>
                            </div>
                            <div class="mb-5">
                              <button class="btn btn-primary d-grid w-100 psd_validate_btn"id ="submit" value="resetpassword" type="submit">Set New Password</button>
                            </div>
                          </form>       
            
                        </div>
                      </div>
                    </div>                      
                  </div>
                </div>
              </div>
            </div>

            <!-- -----Data Table assignTask End------ -->


          </div>
          <!-- / Content -->

          <!-- / Content -->
          <?php include('include/footer.php'); ?>


    <script>


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

    var password = $('#password');
    var cnf_password = $('#confirmpassword');
    var cond_1 = $('.Psd_Cond1'),
            cond_2 = $('.Psd_Cond2'),
            cond_3 = $('.Psd_Cond3'),
            cond_4 = $('.Psd_Cond4');

    password.on('input', function() {
        let pass = password.val();
        checkStrength(pass);
    });

    cnf_password.on('input', function() {
        let cnf_pass = cnf_password.val();
        if(cnf_pass === password.val())
        {
          $('.Psd_Confirm').text('');
          $('.Psd_Confirm').text('Good, Confirm passwords is match');
          $('.Psd_Confirm').addClass('checked');
          $('.psd_validate_btn').addClass('enable');
          $('.psd_validate_btn').removeClass('disable');
        }
        else
        {
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

        if(strength == 4)
        {
          
          $('.psd_validate_btn').addClass('enable');
          $('.psd_validate_btn').removeClass('disable');
        }
        else
        {
          $('.psd_validate_btn').addClass('disable');
          $('.psd_validate_btn').removeClass('enable');
        }
    }



    $('#formReset').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    //Add the Preloader
    $('.event_trigger_loader').addClass('active');


    const oldpassword = $('#oldpassword').val();
    const password = $('#password').val();
    const confirmpassword = $('#confirmpassword').val();

    const submit = $('#submit').val();

    if(password === confirmpassword)
    {
  // Submit the form data using Fetch API
  fetch('function.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({

                'oldpassword': oldpassword,
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
            setTimeout(function() {
               $('.event_trigger_loader').removeClass('active');
            }, 1000);

            const trimmedResult = result.trim();
             if (trimmedResult === 'success') {
                showModalWithParams('Password Changed Successfully', 'true');

             
            } else if (trimmedResult === 'failed') {
                showModalWithParams('Password does not exist', 'true');

            } else {
               // alert('Unexpected response from the server: ' + trimmedResult);
                showModalWithParams(trimmedResult, 'false');

            }
        })
        .catch(error => {

          //Remove the Preloader
          setTimeout(function() {
            $('.event_trigger_loader').removeClass('active');
          }, 1000);

          showModalWithParams(`An error occurred: ${error}`, 'false');
          
        });
    }else
    {
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
</script>