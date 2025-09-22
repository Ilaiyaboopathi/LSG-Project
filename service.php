<?php include('include/head.php'); 


require 'data/dbconfig.php'; 



$ServiceReq= '';
$ServiceViews = '';
// $pid =  $JWT_userID ;
// $permissionsql = "SELECT * FROM permissions WHERE userID='$pid' ";
// $resultPermission = $conn->query($permissionsql);
// if ($resultPermission->num_rows > 0) {
//   $row = $resultPermission->fetch_assoc();
//   $ServiceReq = htmlspecialchars($row['SettingSServiceReq']);

//   $ServiceViews = htmlspecialchars($row['SettingServiceViews']);
// }
$buttonText = isset($buttonText) ? $buttonText : 'Submit'; 
$sql = "SELECT * FROM service_req";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Add each row to the data array
        $data[] = array(
          
            'id' => $row['Id'],
            'service' => $row['service']
        );
    }
}
$response = array('data' => $data);
$json = json_encode($response, JSON_PRETTY_PRINT);
//echo json_encode($response, JSON_PRETTY_PRINT);

$file = 'assets/json/service.json';


if (file_put_contents($file, $json)) {
  // echo "Data successfully written to $file";
} else {
    echo "Failed to write data to $file";
}



// if (isset($_GET['id'])) {
//   $id = intval($_GET['id']); // Sanitize and validate the ID
//   $sql = "SELECT * FROM service_req WHERE Id='$id' ";
//   $result = $conn->query($sql);

//   if ($result->num_rows > 0) {
//       $row = $result->fetch_assoc();
//       $service = htmlspecialchars($row['service']);

//       $buttonText = 'Update'; // Set button text to 'Update'
//   }
// }
// Check if the ID is passed in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize and validate the ID
    $sql = "SELECT * FROM service_req WHERE Id = '$id'"; // Query to get the service based on the ID
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $service = htmlspecialchars($row['service']); // Assign the service to the variable

        $buttonText = 'Update'; // Set the button text to 'Update'
    } else {
        // If no record found, redirect or show an error
        echo "Service not found";
        exit;
    }
}

?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <!-- FormValidation -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!--?php if ( $ServiceReq === 'Enable') : ?-->
                        <form id="Service_Request" class="row g-5">
                            <div class="row" id="SameNameAlert" style="display:none;">
                                    <div class="col-12">
                                        <div class="alert alert-danger" role="alert">
                                        <p id="Same_name_alert_para"></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Account Details -->
                            <div class="col-12">
                                <h6>Add New Service Request</h6>
                                <hr class="mt-0" />
                            </div>


                            <div class="row">
                                <!-- Project Name -->


                                <!-- Project Type -->
                                <div class="col-12">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-floating form-floating-outline">
                                        <input type="hidden" id="hiddenId" value="<?php echo isset($id) ? htmlspecialchars($id) : ''; ?>">

                                        <div class="form-floating mb-3">
                                            <input type="text" name="service" id="service" value="<?php echo isset($service) ? htmlspecialchars($service) : ''; ?>" required placeholder="Web design" class="form-control" />
                                            <label for="service">Service Request *</label>
                                        </div>
                                            <!-- <label for="task">Web design</label> -->
                                        </div>
                                    </div>


                                    <!-- Buttons -->
                                    <div class="col-6 d-flex justify-content-end mt-12">
                                        <button type="button" id="cancelbtn"
                                            class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                        <button type="submit" id="submit"
                                            value="<?php echo ($buttonText === 'Update') ? 'Updateservice' : 'Addservice'; ?>"
                                            class="btn btn-primary">
                                            <?php echo htmlspecialchars($buttonText); ?>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                        <!--?php endif; ?-->

                    </div>
                    <!-- / Content -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        

        <!--?php if ( $ServiceViews === 'Enable') : ?-->
        <div class="row" style="margin-top: 50px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-datatable table-responsive">
                            <table class="datatables-tasktype table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>S.No</th>
                                        <th>Service Request</th>

                                        <th>Actions</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--?php endif; ?-->

    </div>
</div>



    <?php include('include/footer.php'); ?>
    <script src="assets/js/service.js"></script>
    <script>
    $('#Service_Request').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        //Add the Preloader
        $('.event_trigger_loader').addClass('active');


        // Get form values
        const service = $('#service').val();


        const submit = $('#submit').val();
        const hiddenId = $('#hiddenId').val(); // Correctly retrieve hiddenId value



        // Submit the form data using Fetch API
        fetch('function.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'service': service,
                    'hid': hiddenId,
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

                // Trim any extra whitespace from the result
                const trimmedResult = result.trim();

                // Handle the response text
                if (trimmedResult === 'success') {
                    showModalWithParams(`${service} Added`, 'true');
                } 
                else if (trimmedResult === 'updated') {
                    showModalWithParams(`${service} Updated`, 'true')
                } 
                else if (trimmedResult === 'duplicate') {
                    $('#SameNameAlert').show();

                    $('#Same_name_alert_para').html('Type is already exist');

                    // Hide after 3 seconds
                    setTimeout(function() {
                      $('#SameNameAlert').hide();
                    }, 4000);
                } 
                 
                else {
                    showModalWithParams(trimmedResult, 'fasle');
                }
            })
            .catch(error => {

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                showModalWithParams(`An error occurred: ${error}`, 'false');
            });
    });
    </script>
    </body>

    </html>