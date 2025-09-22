<?php include('include/head.php'); ?>
<?php require 'data/dbconfig.php';


$AddNewReminder = '';
$ReminderViews = '';
$pid =  $JWT_userID;
$permissionsql = "SELECT * FROM permissions WHERE userID='$pid' ";
$resultPermission = $conn->query($permissionsql);
if ($resultPermission->num_rows > 0) {
    $row = $resultPermission->fetch_assoc();
    $AddNewReminder = htmlspecialchars($row['AddNewReminder']);

    $ReminderViews = htmlspecialchars($row['ReminderViews']);
}


$buttonText = isset($buttonText) ? $buttonText : 'Submit';
$recurring = '';
$custmonAlert = '';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize and validate the ID
    $sql = "SELECT * FROM reminder WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $assignment_name = htmlspecialchars($row['assignment_name']);
        $date = htmlspecialchars($row['date']);
        $alert_duration = $row['alert_duration'];
        $recurring = htmlspecialchars($row['recurring']);
        $tagemployee = $row["tagemployee"];
        $buttonText = 'Update'; // Set button text to 'Update'

        $dateObj = new DateTime($date);
        $editDate = $dateObj->format('d-m-Y');
    }
}
$sessionName = $JWT_adminName;
$sql = "SELECT * FROM reminder where assignedBy = '$sessionName'";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each row to the data array
        $data[] = array(

            'id' => $row['id'],
            'assignment_name' => $row['assignment_name'],
            'date' => $row['date'],
            'alert_duration' => $row['alert_duration'],
            'recurring' => $row['recurring'],
            'isEnable'  => $row['isEnable'],

        );
    }
}
$response = array('data' => $data);
$json = json_encode($response, JSON_PRETTY_PRINT);
//echo json_encode($response, JSON_PRETTY_PRINT);

$file = 'assets/json/reminder.json';


if (file_put_contents($file, $json)) {
    // echo "Data successfully written to $file";
} else {
    echo "Failed to write data to $file";
}


?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- FormValidation -->
            <?php if ($AddNewReminder === 'Enable') : ?>
                <div class="col-12">
                    <div class="card">
                        <div class="row" id="SameNameAlert" style="display:none;">
                            <div class="col-12">
                                <div class="alert alert-danger" role="alert">
                                    <p id="Same_name_alert_para"></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <form id="reminderForm" class="row g-5">
                                <!-- Reminder Form -->
                                <div class="col-12">
                                    <h6>Reminder</h6>
                                    <hr class="mt-0" />
                                </div>
                                <input type="hidden" id="hiddenId" value="<?php echo empty($id) ? '' : htmlspecialchars($id); ?>">
                                <div class="row">
                                    <!-- Assignment Name -->
                                    <div class="col-md-6 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col-12 mb-4">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="formValidationAssignmentName"
                                                            class="form-control" placeholder="Assignment Name"
                                                            name="formValidationAssignmentName" required
                                                            value="<?php echo empty($assignment_name) ? '' : htmlspecialchars($assignment_name); ?>" />
                                                        <label for="formValidationAssignmentName">Assignment Name *</label>
                                                    </div>
                                                </div>

                                                <!-- Date -->
                                                <div class="col-12 mb-6">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="formValidationDate" readonly="readonly"
                                                            style="color: #393939;" required placeholder="DD-MM-YYYY"
                                                            class="form-control flatpickr_date" name="formValidationDate"
                                                            placeholder="Date"
                                                            value="<?php echo empty($editDate) ? '' : htmlspecialchars($editDate); ?>" />
                                                        <label for="formValidationDate">Start Date *</label>
                                                    </div>
                                                </div>


                                                <div class="col-12">
                                                    <div class="form-floating form-floating-outline">
                                                        <select id="formValidationAlertDuration" class="form-control select2 formValidationAlertDuration" required name="formValidationAlertDuration">
                                                            <option value="" disabled selected>Select duration *</option>
                                                            <?php
                                                            // Define the options and their values
                                                            $options = [
                                                                'custom' => 'Custom',
                                                                '30' => '30 minutes',
                                                                '60' => '1 hr',
                                                                '240' => '4 hr',
                                                                '480' => '8 hr',
                                                                '1440' => '1 day',
                                                                '4320' => '3 days',
                                                                '10080' => '7 days',
                                                                '21600' => '15 days',
                                                                '43200' => '30 days',
                                                                '86400' => '60 days',
                                                                '129600' => '90 days',
                                                                '172800' => '120 days',
                                                                '259200' => '180 days',
                                                                '525600' => '365 days'
                                                            ];
                                                            $foundMatch = false;

                                                            // Loop through each option
                                                            foreach ($options as $value => $label) {
                                                                // Check if the option should be selected
                                                                $selected = ($alert_duration == $value) ? 'selected' : '';
                                                                if ($selected) {
                                                                    $foundMatch = true;  // Set the flag if we find a match
                                                                }
                                                                echo "<option value=\"$value\" $selected>$label</option>";
                                                            }
                                                            if (!$foundMatch) {
                                                                $custmonAlert = $alert_duration;
                                                                echo "<option value=\"custom\" selected>Custom</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <label for="formValidationAlertDuration">Alert Duration *</label>
                                                    </div>
                                                </div>
                                                <?php
                                                $sql1 = "SELECT name FROM employee WHERE isenable = 1";
                                                $result = $conn->query($sql1);

                                                $selectedEmployees = [];
                                                if (isset($tagemployee) && !empty($tagemployee)) {
                                                    // Trim whitespace and ensure names are split correctly
                                                    $selectedEmployees = array_map('trim', explode(',', $tagemployee));
                                                }

                                                echo '<div class="form-floating form-floating-outline form-floating-select2 mt-6">';
                                                echo '<div class="position-relative">';
                                                echo '<select   id="selectempoyees" class="select2 form-select" multiple required>';

                                                // Generate the options
                                                if ($result->num_rows > 0) {
                                                    $AllNames = [];
                                                    while ($row = $result->fetch_assoc()) {
                                                        $name = htmlspecialchars($row['name']);
                                                        $AllNames[] = $name;
                                                    }
                                                    sort($AllNames);

                                                    foreach ($AllNames as $name) {
                                                        $isSelected = in_array($name, $selectedEmployees) ? ' selected' : '';
                                                        echo '<option value="' . $name . '"' . $isSelected . '>' . $name . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">No User found.</option>';
                                                }

                                                echo '</select>';
                                                echo '</div>';
                                                echo '<label for="selectempoyees">Tag User *</label>';
                                                echo '</div>';

                                                // Close the connection
                                                $conn->close();
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="col-md mb-md-0 mb-5">
                                                            <div class="form-check custom-option custom-option-icon" style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                                                                <label class="form-check-label custom-option-content" for="customCheckboxIcon1">
                                                                    <span class="custom-option-body">
                                                                        <i class="ri-repeat-2-fill"></i>
                                                                        <span class="custom-option-title mb-2"> Repeat </span>
                                                                        <small class="mb-3">If you want schedule recurring alerts Enable It...</small>
                                                                    </span>
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="customCheckboxIcon1"
                                                                        name="customCheckboxIcon1" <?php echo ($recurring === '1') ? 'checked' : ''; ?> />
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="Hided_recurring_section">
                                                            <div class="Hide_Overlay"></div>
                                                            <div class="row">
                                                                <h6 class="card-header">Custom Recurring Durations</h6>
                                                                <div class="col-12 mb-4">
                                                                    <div class="form-floating form-floating-outline">
                                                                        <input type="number" id="formValidationCustomHours"
                                                                            class="form-control" placeholder="12 (Durations Hours)"
                                                                            name="formValidationCustomHours" pattern="\d*" oninput="validateInput('hours')"
                                                                            value="<?php echo ($custmonAlert && $custmonAlert < 1440) ? $custmonAlert / 60 : ''; ?>" />
                                                                        <label for="formValidationCustomHours">Duration Hours</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">--OR--</div>
                                                                <div class="col-12 mb-4 mt-4">
                                                                    <div class="form-floating form-floating-outline">
                                                                        <input type="number" id="formValidationCustomDays"
                                                                            class="form-control" placeholder="40 (Durations Days)"
                                                                            name="formValidationCustomDays" pattern="[0-9]{2}" oninput="validateInput('days')"
                                                                            value="<?php echo ($custmonAlert && $custmonAlert >= 1440) ? $custmonAlert / 1440 : ''; ?>" />
                                                                        <label for="formValidationCustomDays">Duration Days</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Buttons -->
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end mt-6">
                                        <button type="reset" class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                        <button type="submit" id="submit"
                                            value="<?php echo ($buttonText === 'Update') ? 'Updatereminder' : 'Addreminder'; ?>"
                                            class="btn btn-primary">
                                            <?php echo htmlspecialchars($buttonText); ?>
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>


        <?php if ($ReminderViews === 'Enable') : ?>
            <div class="row" style="margin-top: 50px;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-datatable table-responsive">
                                <table class="datatables-reminder table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>S.No</th>
                                            <th>Assignment Name</th>
                                            <th>Date</th>
                                            <th>Recurring</th>
                                            <th>Alert Duration</th>

                                            <th>Action</th>
                                            <th>Disable</th>
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

<?php include('include/footer.php'); ?><!-- / Content -->
<script src="assets/js/reminder.js"></script>
<script>
    document.getElementById('reminderForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form from submitting the default way


        //Add the Preloader
        $('.event_trigger_loader').addClass('active');

        // Get form values
        const assignmentName = document.getElementById('formValidationAssignmentName').value;
        const date = document.getElementById('formValidationDate').value;
        const alertDuration = document.getElementById('formValidationAlertDuration').value;
        const selectempoyees = $('#selectempoyees').val();
        const recurring = document.getElementById('customCheckboxIcon1').checked ? 1 : 0;
        const submit = $('#submit').val();
        const hiddenId = $('#hiddenId').val();


        let custom_days, custom_hours, params;

        if (alertDuration == "custom") {
            custom_days = document.getElementById('formValidationCustomDays').value;
            custom_hours = document.getElementById('formValidationCustomHours').value;

            if (custom_days) {
                const daysValue = parseInt(custom_days, 10);
                var customMins = daysValue * 24 * 60; // 1 day = 24 hours = 1440 minutes

                // Create the object with converted "customMins" for URL sending
                params = new URLSearchParams({
                    'assignmentName': assignmentName,
                    'date': date,
                    'alertDuration': customMins,
                    'hid': hiddenId,
                    'submit': submit,
                    'recurring': recurring,
                    'selectempoyees': selectempoyees,
                });
            } else if (custom_hours) {
                const hoursValue = parseInt(custom_hours, 10);
                var customMins = hoursValue * 60; // 1 hour = 60 minutes

                // Create the object with converted "customMins" for URL sending
                params = new URLSearchParams({
                    'assignmentName': assignmentName,
                    'date': date,
                    'alertDuration': customMins,
                    'hid': hiddenId,
                    'submit': submit,
                    'recurring': recurring,
                    'selectempoyees': selectempoyees,
                });
            } else {
                $('#SameNameAlert').show();
                $('#Same_name_alert_para').text(
                    `You choosed Custom Duration. So Please fill the mandatory fields .`);

                // Hide After 3 Seconds
                setTimeout(function() {
                    $('#SameNameAlert').hide();
                }, 8000);

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                return;
            }

        } else {
            // Create the object with converted "customMins" for URL sending
            params = new URLSearchParams({
                'assignmentName': assignmentName,
                'date': date,
                'alertDuration': alertDuration,
                'selectempoyees': selectempoyees,
                'hid': hiddenId,
                'submit': submit,
                'recurring': recurring,
                'customMins': customMins // Include the converted minutes
            });
        }

        console.log(params);

        // Send data to PHP script
        fetch('function.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params
            })
            .then(response => response.text())
            .then(result => {
                console.log('Server response:', result);

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                if (result.trim() === 'success') {
                    showModalWithParams(`${assignmentName}  Added`, 'true');


                    //  document.getElementById('reminderForm').reset();
                } else if (result.trim() === 'updated') {
                    showModalWithParams(`${assignmentName}  updated`, 'true');

                } else if (result.trim() === 'Duplicate assignment name') { // fixed comparison here
                    $('#SameNameAlert').show();
                    $('#Same_name_alert_para').html(
                        `${assignmentName} is already exist. Please try another assignment name`);

                    // Hide after 3 seconds
                    setTimeout(function() {
                        $('#SameNameAlert').hide();
                    }, 4000);
                } else {
                    showModalWithParams('Something Wrong', 'false');
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


    // <! -------------- Once I Selected New Default Processing Status Selected  -------------- >

    $('.formValidationAlertDuration').on('select2:select', function(e) {
        var selectedValue = e.params.data.id;

        if (selectedValue == "custom") {
            $('.Hide_Overlay').hide();
            $('.Hided_recurring_section').addClass('open');
        } else {
            $('.Hide_Overlay').show();
            $('.Hided_recurring_section').addClass('close');
        }
    });

    // <! -------------- Once I Selected New Default Processing Status Selected  -------------- >


    function validateInput(type) {
        const hoursInput = document.getElementById('formValidationCustomHours');
        const daysInput = document.getElementById('formValidationCustomDays');
        

        if (type === 'hours') {
            if (hoursInput.value !== '') {
                if (parseInt(hoursInput.value) > 23) {
                    hoursInput.value = 23; // Limit hours to 23
                }
                daysInput.value = ''; // Clear days input
            }
        } else if (type === 'days') {
            if (daysInput.value !== '') {
                if (parseInt(daysInput.value) > 365) {
                    daysInput.value = 365; // Limit days to 365
                }
                hoursInput.value = ''; // Clear hours input
            }
        }
    }

</script>
<script>
    // Update form visibility based on alert duration
    document.getElementById('formValidationAlertDuration').addEventListener('change', function() {
        var alertDuration = this.value;
        var customDurationSection = document.getElementById('customDurationSection');

        if (alertDuration === 'custom') {
            $('.Hide_Overlay').hide();
            $('.Hided_recurring_section').addClass('open');
            customDurationSection.style.display = 'block';
        } else {
            customDurationSection.style.display = 'none';
        }

        // Automatically show custom fields based on selected duration
        if (alertDuration == 'custom') {
            $('.Hide_Overlay').hide();
            $('.Hided_recurring_section').addClass('open');
            document.getElementById('formValidationCustomHours').style.display = 'block';
            document.getElementById('formValidationCustomDays').style.display = 'block';
        } else if (alertDuration < 1440) {
            document.getElementById('formValidationCustomHours').style.display = 'block';
            document.getElementById('formValidationCustomDays').style.display = 'none';
        } else {
            document.getElementById('formValidationCustomHours').style.display = 'none';
            document.getElementById('formValidationCustomDays').style.display = 'block';
        }
    });

    // Initialize display settings on page load
    window.onload = function() {
        var alertDuration = document.getElementById('formValidationAlertDuration').value;
        //var customDurationSection = document.getElementById('customDurationSection');

        if (alertDuration === 'custom') {
            $('.Hide_Overlay').hide();
            $('.Hided_recurring_section').addClass('open');
            //customDurationSection.style.display = 'block';
        } else {
            //customDurationSection.style.display = 'none';
        }
    };


    $(document).ready(function() {
        // Get current date
        var currentDate = new Date();

        // Format current date as DD-MM-YYYY
        var day = String(currentDate.getDate()).padStart(2, '0');
        var month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        var year = currentDate.getFullYear();
        var formattedDate = day + '-' + month + '-' + year;

        // Set current date in the input field
        $('#formValidationDate').val(formattedDate);

        // Optional: Initialize flatpickr if needed
        $(".flatpickr_date").flatpickr({
            dateFormat: "d-m-Y", // Format for flatpickr
            minDate: "today" // Prevent past dates
        });
    });
</script>