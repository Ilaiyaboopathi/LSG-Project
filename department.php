<?php include('include/head.php');


require 'data/dbconfig.php';


$AddDepartment = '';
$DepartmentViews = '';
$pid =  $JWT_userID;
$permissionsql = "SELECT * FROM permissions WHERE userID='$pid' ";
$resultPermission = $conn->query($permissionsql);
if ($resultPermission->num_rows > 0) {
    $row = $resultPermission->fetch_assoc();
    $AddDepartment = htmlspecialchars($row['SettingAddDepartment']);
    $DepartmentViews = htmlspecialchars($row['SettingDepartmentViews']);
}



$buttonText = isset($buttonText) ? $buttonText : 'Submit';
$sql = "SELECT * FROM department";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each row to the data array
        $data[] = array(

            'id' => $row['id'],
            'name' => $row['name']
        );
    }
}
$response = array('data' => $data);
$json = json_encode($response, JSON_PRETTY_PRINT);
//echo json_encode($response, JSON_PRETTY_PRINT);

$file = 'assets/json/department.json';


if (file_put_contents($file, $json)) {
    // echo "Data successfully written to $file";
} else {
    echo "Failed to write data to $file";
}



if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize and validate the ID
    $sql = "SELECT * FROM department WHERE id='$id' ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);

        $buttonText = 'Update'; // Set button text to 'Update'
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
                        <?php if ($AddDepartment === 'Enable') : ?>
                            <form id="department_add" class="row g-5">
                                <div class="row" id="SameNameAlert" style="display:none;">
                                    <div class="col-12">
                                        <div class="alert alert-danger" role="alert">
                                            <p id="Same_name_alert_para"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Account Details -->
                                <div class="col-12">
                                    <h6>Add & Edit Department </h6>
                                    <hr class="mt-0" />
                                </div>



                                <div class="row">
                                    <!-- Project Name -->


                                    <!-- Project Type -->
                                    <div class="col-12">
                                        <div class="col-md-6 mb-4">
                                            <div class="form-floating form-floating-outline">
                                                <input type="hidden" id="hiddenId"
                                                    value="<?php echo empty($id) ? '' : htmlspecialchars($id); ?>">


                                                <div class="form-floating form-floating-outline mb-3">
                                                    <input type="text" name="name_dep" id="name_dep"
                                                        value="<?php echo empty($name) ? '' : htmlspecialchars($name); ?>"
                                                        required placeholder="Digital Marketing" class="form-control"
                                                        maxlength="50" pattern="[A-Za-z\s]+"
                                                        message=" Only alphabetic characters and spaces are allowed" />
                                                    <label for="name_dep">Department Name *</label>

                                                </div>
                                                <!-- <label for="task">Web design</label> -->
                                            </div>
                                        </div>





                                        <!-- Buttons -->
                                        <div class="col-6 d-flex justify-content-end mt-12">
                                            <button type="reset"
                                                class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                            <button type="submit" id="submit"
                                                value="<?php echo ($buttonText === 'Update') ? 'UpdateDepartment' : 'AddDepartment'; ?>"
                                                class="btn btn-primary">
                                                <?php echo htmlspecialchars($buttonText); ?>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        <?php endif; ?>


                    </div>
                    <!-- / Content -->



                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>




        <?php if ($DepartmentViews === 'Enable') : ?>
            <div class="row" style="margin-top: 50px;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-datatable table-responsive">
                                <table class="data_tables_department table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>S.No</th>
                                            <th>Department Name</th>

                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>



<?php include('include/footer.php'); ?>

<script src="assets/js/department.js"></script>

<script>
    $('#department_add').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        //Add the Preloader
        $('.event_trigger_loader').addClass('active');


        // Get form values
        const name = $('#name_dep').val();


        const submit = $('#submit').val();
        const hiddenId = $('#hiddenId').val(); // Correctly retrieve hiddenId value



        // Submit the form data using Fetch API
        fetch('function.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'name': name,

                    'hid': hiddenId,
                    'submit': submit,

                })
            })
            .then(response => response.text())
            .then(result => {
                // Log the response from PHP
                console.log('Server response:', result);

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                // Trim any extra whitespace from the result
                const trimmedResult = result.trim();

                // Handle the response text
                if (trimmedResult === 'Success') {

                    showModalWithParams(`${name} Added`, 'true');
                } else if (trimmedResult === 'updated') {
                    showModalWithParams(`${name} Updated`, 'true');
                } 
                else if (trimmedResult === 'duplicateName') {
                        $('#SameNameAlert').show();
                        $('#Same_name_alert_para').html('Department name is already exist');

                        // Hide after 3 seconds
                        setTimeout(function() {
                            $('#SameNameAlert').hide();
                        }, 4000);
                }
                else {
                    alert('Unexpected response from the server: ' + trimmedResult);
                    showModalWithParams(`${trimmedResult}`, 'false');
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