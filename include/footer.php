<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
            <div class="text-body mb-2 mb-md-0">
                Copyright ©
                <script>
                    document.write(new Date().getFullYear());
                </script>
                . All rights reserved.<span class="text-danger"><i class="tf-icons ri-heart-fill"></i></span> by
                <a href="https://mbwit.net/" target="_blank" class="footer-link">MBW</a>
            </div>
            <div class="d-none d-lg-inline-block">
                <span class="text-body">Powered By</span> <a href="https://mbwit.net/" target="_blank"
                    class="footer-link me-4">MBW</a>
            </div>
        </div>
    </div>
</footer>
<!-- / Footer -->


<div class="content-backdrop fade"></div>
</div>
<!------------------ Content wrapper ---------------------------->
</div>

<!-- / Layout page -->
</div>

<!-------------------------- Alert Card Modal Start-------------------------->
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
<!--------------------------/ Alert Card Modal End--------------------->

<!-------------------------- Overlay ---------------------------------->
<div class="layout-overlay layout-menu-toggle"></div>


<!-------------------------- Pre loader Start------------------------>
<div id="custom-preloader">
    <div class="preloader-content">
        <img class="pre_img" src="assets/img/logos/mbw-logo.webp">
        <div class="col d-flex justify-content-center mt-10">
            <!-- Wave -->
            <div class="sk-wave sk-primary">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
            </div>
        </div>
    </div>
</div>
<!-------------------------- Pre loader End-------------------------->


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



<!-- Drag Target Area To SlideIn Menu On Small Screens -->
<div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->

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
<script src="assets/vendor/libs/dropzone/dropzone.js"></script>
<script src="assets/js/forms-file-upload.js"></script>
<!-- endbuild -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<!-- Vendors JS -->
<!-- <script src="assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script> -->
<script src="assets/vendor/libs/cleavejs/cleave.js"></script>
<script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
<script src="assets/vendor/libs/moment/moment.js"></script>

<!-- ---Chart For Dashboard---- -->
<script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

<!-- <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script> -->
<!-- <script src="assets/vendor/libs/tagify/tagify.js"></script> -->
<!-- <script src="assets/vendor/libs/@form-validation/popular.js"></script> -->
<script src="assets/vendor/libs/select2/select2.js"></script>
<!-- <script src="assets/vendor/libs/@form-validation/bootstrap5.js"></script>
  <script src="assets/vendor/libs/@form-validation/auto-focus.js"></script> -->

<!-- ---Form Validation--- -->
<!-- <script src="assets/vendor/libs/@form-validation/popular.js"></script>
  <script src="assets/vendor/libs/@form-validation/bootstrap5.js"></script>
  <script src="assets/vendor/libs/@form-validation/auto-focus.js"></script> -->

<!-- ---Data table--- -->
<script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

<!-- Main JS -->
<script src="assets/js/main.js"></script>

<!-- Preloader JS -->
<script src="assets/js/preloader.js"></script>
<script src="assets/js/client.js"></script>
<!-- <script src="assets/js/projectweb.js"></script> -->
<!-- --Data tables-- -->
<!-- <script src="assets/js/clients-data-table.js"></script> -->
<script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
<script src="assets/js/forms-pickers.js"></script>

<!-- Form Js Cnd -->
<script src="assets/js/form-layouts.js"></script>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script> -->


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var closeButton = document.getElementById('modalCloseButton');

        var cancelbtn = document.getElementById('cancelbtn');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                // Get the current URL
                var currentUrl = new URL(window.location.href);

                // Remove the 'id' parameter from the URL
                currentUrl.searchParams.delete('id');

                // Reload the page with the modified URL
                window.location.href = currentUrl.toString();
            });
        }
        if (cancelbtn) {
            cancelbtn.addEventListener('click', function() {
                // Get the current URL
                var currentUrl = new URL(window.location.href);

                // Remove the 'id' parameter from the URL
                currentUrl.searchParams.delete('id');

                // Reload the page with the modified URL
                window.location.href = currentUrl.toString();
            });
        }
    });


    // Global function to append dynamic content and render KaTeX
    function appendContentToEditor(editorSelector, content) {
        const editor = document.querySelector(editorSelector);
        if (editor) {
            const quillEditor = Quill.find(editor); // Find the Quill editor instance

            // Clear existing content
            quillEditor.root.innerHTML = ''; 

            // Append content to the editor's root (HTML content)
            quillEditor.root.innerHTML += content;
        }
    }
    function addtagusersinTogify(tagDataArray)
    {
        if (TagifyUserList) {
            TagifyUserList.addTags(tagDataArray);
        }

    }

    function addtagusersinTogify(tagDataArray)
    {
        if (TagifyUserList) {
            TagifyUserList.addTags(tagDataArray);
        }

    }


    function isQuillEmpty(quillInstance) {
        if (!quillInstance) return true; // Ensure the instance exists
        return quillInstance.getText().trim() === "";
    }


    //initialize Tooltips for all Extra Requirements
    function initializeTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }


    const numericInputs = document.querySelectorAll('input[type="number"]');
    numericInputs.forEach(input => {
        input.addEventListener('keydown', function(event) {
            if (!['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete'].includes(event.key) &&
                (event.key < '0' || event.key > '9')) {
                event.preventDefault();
            }
        });
    });


</script>


<?php


// Assuming you have stored user information in session
$loggedInUserEmail = $JWT_userEmail;
$loggedInUserId = $JWT_userID;
$loggedInUserName = $JWT_adminName;
$loggedInUserRole = $JWT_userRole;


?>


<script>


    window.loggedInUserEmail = "<?php echo $loggedInUserEmail; ?>";
    window.loggedInUserName = "<?php echo $loggedInUserName; ?>";
    window.loggedInUserRole = "<?php echo $loggedInUserRole; ?>";
    window.loggedInUserId = "<?php echo $loggedInUserId; ?>";

    let taskCount = 0;
    let reminderCount = 0;
    let projectCount = 0;


    let lastUpdate = 0;
    const updateInterval = 5000; // 5 seconds
    const scheduledNotifications = new Map();
    const processedReminders = new Set();



    document.addEventListener('DOMContentLoaded', (event) => {
        fetchNotifications();
        fetchReminderNotifications();
    });




    //let conn = new WebSocket('ws://127.0.0.1:61200');
    // // let conn = new WebSocket('wss://testing.mbwapps.in/ws/');
    // let conn = new WebSocket('wss://mbwapps.in/ws/');
  
  let conn = new WebSocket('wss://medchrono.lsghealthcare.com/ws/');


    // When WebSocket connection opens
    conn.onopen = function() {
        console.log("WebSocket connection opened!");
    };


    setInterval(() => {
        if (conn.readyState === WebSocket.OPEN) {
            console.log('Sending heartbeat...');
            conn.send('ping');
        } else {
            console.warn('Cannot send heartbeat, WebSocket is not open.');
        }
    }, 10000);

    
    
    $(document).ready(function() {


        if (Notification.permission !== "granted") {
            Notification.requestPermission().then(function(permission) {
                if (permission === "granted") {
                    console.log("Notification permission granted.");
                } else {
                    console.log("Notification permission denied.");
                }
            });
        }


        //   =========== Fetch Users when choose type in Empolyee Report Start ===============

        $('#selectEmployee').change(function() {
                const selectedType = $(this).val();
                $('#SelectedEmployee').empty().append('<option value="">All</option>'); // Clear previous options

                if (selectedType) {
                    $.ajax({
                        url: 'include/handlers/GeneralHandlers.php', // Your backend endpoint
                        type: 'POST',
                        data: {
                            type: selectedType,
                            action: 'FetchEmployee'
                        },
                        dataType: 'json',
                        success: function(response) {
                            response.sort(function(a, b) {
                                return a.name.localeCompare(b.name);
                            });
                            response.forEach(function(employee) {
                                $('#SelectedEmployee').append(`<option value="${employee.id}">${employee.name}</option>`);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching employee data:', error);
                    }
                });
            }
        });

        //   =========== Fetch Users when choose type in Empolyee Report End ===============

    });


    //  =========================== On Message from Websocket Start====================================

    conn.onmessage = function(e) {
        const message = JSON.parse(e.data);

        if (message.type === 'salesTask') {
            const task = message.data;
            const Employee = task.employeeName;


            if (Employee.includes(window.loggedInUserName)) {
                playNotificationSound();
                showNotification(`Task Assign: ${task.employeeName}'s${task.taskName}  ${task.platform}`, {
                    body: `Details: ${task.taskDetails}\nDate: ${task.date}\nTime: ${task.time}`,
                    icon: 'notification-icon.png'
                });

                insertNotificationIntoDatabase(task);
            }
        } 
        
        else if (message.type === 'HeartBeat') {
            console.log('Received Beat:', message.data);
        } 
        
        else if (message.type === 'recurringReminders') {
            const task = message.data;
            const tagname = task.assignedTo;

            console.log('message.data:', message.data);
            console.log('assignedTo:', tagname);

            if (tagname.includes(window.loggedInUserName)) {
                playNotificationSound();
                showNotification(`Reminder: ${task.assignedTo}'s ${task.name}`, {
                    body: `Details: ${task.name}\nDate: ${task.date}`,
                    icon: 'notification-icon.png'
                });

                insertReminderIntoDatabase(task);
            }

        } 
        
        else if (message.type === 'DeadlineProject') {

            const task = message.data;

            const Name = task.Name;


            if (Name.includes(window.loggedInUserName)) {
                playNotificationSound();
                showNotification(`Project Deadline: ${task.Name}'s ${task.ProjectName}`, {
                    body: `Details: ${task.Information}\nDate: ${task.DeadlineDate}\nTime: ${task.DeadlineTime}`,
                    icon: 'notification-icon.png'
                });

                insertprojectDeadlineNotificationIntoDatabase(task);
            }
        } 
        
        else if (message.type === 'ProjectDetails') {
            const Project_obj = message.data;
            console.log('Project in Socket', Project_obj);
            const taggedEmployees = Project_obj.Name;
            //console.log('logging project id :',Project_obj.NewProjectId);
            if (taggedEmployees.includes(window.loggedInUserName)) {
                playNotificationSound();
                showNotification(`Project:Hi ${window.loggedInUserName}, ${Project_obj.ProjectName}'s ${Project_obj.Platform}`, {
                    body: `Details: ${Project_obj.Information}\nDate: ${Project_obj.NotifiedDate}\nTime: ${Project_obj.NotifiedTime}`,
                    icon: 'notification-icon.png'
                });
                insertprojectNotificationIntoDatabase(Project_obj);
            }
        }

        else if (message.type === 'NotifyClientJob') {
            const ClientJob_obj = message.data;
            console.log('Client Job in Socket', ClientJob_obj);


            if (window.loggedInUserRole == "admin") {
                var jobInfo = "New Job Added By "+ ClientJob_obj.name +"";
                playNotificationSound();
                showNotification(`Project:Hi ${window.loggedInUserName}, ${ClientJob_obj.job_name}'s ${ClientJob_obj.job_type}`, {
                    body: `Details: ${jobInfo}\nCreated At: ${ClientJob_obj.created_at}`,
                    icon: 'notification-icon.png'
                });

                ClientJob_obj.action = "save_notify_new_job_assigned";

                // Create FormData object
                var formData = new FormData();
                formData.append("id", ClientJob_obj.id);
                formData.append("job_name", ClientJob_obj.job_name);
                formData.append("job_type", ClientJob_obj.job_type);
                formData.append("added_by_name", ClientJob_obj.employee_name);
                formData.append("action", "save_notify_new_job_assigned");

                // Insert notification into database using FormData
                $.ajax({
                    url: 'include/handlers/ClientJobHandler.php',
                    type: 'POST',
                    data: formData,
                    processData: false,  // Prevent jQuery from automatically processing data
                    contentType: false,  // Prevent jQuery from setting content type
                    dataType: 'json',
                    success: function(data) {
                        //console.log("Notification saved:", data);
                        console.log("Notification saved");
                        fetch_gs_Notifications();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error saving notification:", error);
                    }
                });

            }
        }



        else if (message.type === 'NotifyClientProject') {
            const ClientProject_obj = message.data;
            console.log('Client Project in Socket', ClientProject_obj);


            // Fetch assigned users (example: assuming you have this data available in your frontend)
            var taggedUserId = ClientProject_obj.assignees.map(assignee => assignee.assignee_id);

            console.log(taggedUserId);
            console.log(window.loggedInUserId);


            if (taggedUserId.includes(window.loggedInUserId)) {
                var projectInfo = "New Project Added By " + ClientProject_obj.creator_name;
                playNotificationSound();
                showNotification(`Project: Hi ${window.loggedInUserName}, ${ClientProject_obj.name} (${ClientProject_obj.type})`, {
                    body: `Details: ${projectInfo}\nCreated At: ${ClientProject_obj.created_at}`,
                    icon: 'notification-icon.png'
                });

                ClientProject_obj.action = "save_notify_new_project_assigned";

                // Create FormData object
                var formData = new FormData();
                formData.append("id", ClientProject_obj.id);
                formData.append("project_name", ClientProject_obj.name);
                formData.append("project_type", ClientProject_obj.type);
                formData.append("added_by_name", ClientProject_obj.creator_name);
                formData.append("action", "save_notify_new_project_assigned");

                // Insert notification into database using FormData
                $.ajax({
                    url: 'include/handlers/ClientProjectHandler.php',
                    type: 'POST',
                    data: formData,
                    processData: false,  // Prevent jQuery from automatically processing data
                    contentType: false,  // Prevent jQuery from setting content type
                    dataType: 'json',
                    success: function(data) {
                        console.log("Project Notification saved");
                        fetch_gs_Notifications();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error saving project notification:", error);
                    }
                });
            }
        }


    //  =========================== On Message from Websocket Start====================================

    };



    function sendSubmittedDataToSocket(PayloadData) {


        if (conn.readyState === WebSocket.OPEN) {
            const formData = {
                action: 'SendProjectToSocket',
                data: JSON.stringify(PayloadData)
            };
            try {
                conn.send(JSON.stringify(formData));
                console.log('Data sent:', PayloadData);
            } catch (e) {
                console.error('Error sending data:', e);
            }
        }
    }





    $('#eventForm').on('submit', function(e) {
        e.preventDefault();

        //Add the Preloader
        $('.event_trigger_loader').addClass('active');


        const name = $('#name').val();
        const customertype = $('#customertype').val();
        const phone = $('#phone').val();
        const platform = $('#platform').val();
        const details = $('#details').val();
        const date = $('#date').val();
        const time = $('#time').val();
        const selectempoyees = $('#selectempoyees').val();
        //const status = $('#status').val();
        const submit = $('#submit').val();
        const hiddenId = $('#hiddenId').val();
        const hiddenEmail = $('#hiddenEmail').val();
        const hguid = $('#hguid').val();
        const status = $('input[name="customRadioIcon-01"]:checked').val();
        // Calculate the delay for the notification
        const dateTime = new Date(`${date}T${time}`);
        const now = new Date();
        const delay = dateTime - now;

        // Check if date or time is empty
        if (!date || !time || !platform) {
            // If either is empty, show the alert message and prevent submission
            $('#SameDateAlert').show();
            $('#Same_Date_alert_para').text(
                `Please fill the mandatory fields .`);

            // Hide After 3 Seconds
            setTimeout(function() {
                $('#SameEmailAlert').hide();
            }, 4000);

            //Remove the Preloader
            setTimeout(function() {
                $('.event_trigger_loader').removeClass('active');
            }, 1000);
            return;
        }


        // Submit the form data using Fetch API
        fetch('function.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'name': name,
                    'customertype': customertype,
                    'phone': phone,
                    'platform': platform,
                    'details': details,
                    'date': date,
                    'time': time,
                    'hid': hiddenId,
                    'hemail': hiddenEmail,
                    'hguid': hguid,
                    'selectempoyees': selectempoyees,
                    'status': status,
                    'submit': submit
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
                    showModalWithParams(`${name}'s ${platform} Added`, 'true');



                } else if (trimmedResult === 'updated') {
                    showModalWithParams(`${name} Updated`, 'true');

                } else {
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
    });



    $('#addProject').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        let isValid = true; // Flag

        const date = $('#date').val();
        const time = $('#time').val();

        $('.project_cloned_rpt').each(function() {
            const empName = $(this).find('.emp_name_readonly').val(); // Employee name
            const projectInfo = $(this).find('textarea').val(); // Project info for the specific employee
            const deadlineDate = $(this).find('.empdeadlinedate').val(); // Deadline date
            const deadlineTime = $(this).find('.empdeadlinetime').val(); // Deadline time

            // Check if required fields are empty
            if (!empName || !projectInfo || !deadlineDate || !deadlineTime || !date || !time) {
                console.log(empName);
                console.log(projectInfo);
                console.log(deadlineDate);
                console.log(deadlineTime);
                console.log(date);
                console.log(time);
                isValid = false;
            }
        });

        if (isValid === true) {
            
            // Add the Preloader
            $('.event_trigger_loader').addClass('active');

            // Collect form data
            const formData = prepareFormData();

            // Send data to function.php first
            sendFormDataToFunction(formData)
                .then(result => {
                    console.log(result.status);
                    if (result.status === 'success' || result.status === 'updated') {
                        // Handle the response from function.php
                        handleFunctionResponse(result, formData);

                        // Now send data to sendmail.php
                        //return sendFormDataToSendMail(formData);
                    } 
                    else {
                        throw new Error(result.message); // Stop execution if not successful
                    }
                })
                // .then(result => {
                //     // Handle the response from sendmail.php
                //     if (result.status === 'success') {
                //         console.log('Email sent successfully');
                //     } else {
                //         throw new Error(result.message); // Stop execution if email sending fails
                //     }
                // })
                .catch(error => {
                    handleError(error);
                })
                .finally(() => {
                    // Remove the Preloader
                    setTimeout(() => $('.event_trigger_loader').removeClass('active'), 1000);
                });



        } else {
            $('#ErrorForProjectValidation').show();
            $('#ProjectValidation_alert_para').text('Please ensure all mandatory fields are filled out before submitting the form.');

            // Hide After 3 Seconds
            setTimeout(function() {
                $('#ErrorForProjectValidation').hide();
            }, 4000);
        }
    });



    // ==================== Helper Functions addProject Start ====================
    
    
    /**
     * Collects and prepares form data for submission.
     */
    function prepareFormData() {
        const repeaterData = collectRepeaterData();

            const formData = new URLSearchParams({
                'name': $('#name').val(),
                'linkurl': $('#linkurl').val(),
                'platform': $('#platform').val(),
                'details': $('#details').val(),
                'date': $('#date').val(),
                'time': $('#time').val(),
                'hid': $('#hiddenId').val(),
                'hstatus': $('#hiddenstatus').val(),
                'hiddenassigned': $('#hiddenassigned').val(),
                'hiddenGuid': $('#hiddenGuid').val(),
                'submit': $('#submit').val()
            });

        // Append selectempoyees (ensure it's always an array)
        const selectempoyees = $('#selectempoyees').val() || [];
        if (Array.isArray(selectempoyees)) {
            selectempoyees.forEach(emp => formData.append('selectempoyees[]', emp));
        } else {
            formData.append('selectempoyees[]', selectempoyees);
        }

        // Append repeater data
        repeaterData.forEach((item, index) => {
            formData.append(`repeater[${index}][employeeName]`, item.employeeName);
            formData.append(`repeater[${index}][deadlineDate]`, item.deadlineDate);
            formData.append(`repeater[${index}][deadlineTime]`, item.deadlineTime);
            formData.append(`repeater[${index}][projectInfo]`, item.projectInfo);
        });

        return formData;
    }

    /**
     * Sends form data to function.php and returns the response.
     */
    async function sendFormDataToFunction(formData) {
        try {
            const response = await fetch('function.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json' // Set content type to JSON
                },
                body: JSON.stringify(formData) // Convert formData to JSON format
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            return await response.json();
        } catch (error) {
            console.error('Error sending data to function.php:', error);
            throw new Error('Failed to send data to function.php');
        }

    }

    /**
     * Sends form data to sendmail.php and returns the response.
     */
    async function sendFormDataToSendMail(formData) {
        try {
            const response = await fetch('include/handlers/sendmail.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            });

            const text = await response.text();
            return JSON.parse(text); // Manually parse JSON
        } catch (error) {
            console.error('Error sending data to sendmail.php:', error);
            throw new Error('Failed to send data to sendmail.php');
        }
    }

    /**
     * Handles the response from function.php.
     */
    function handleFunctionResponse(result, formData) {
        console.log(result);
        const successMessage = result.status === 'success' ? `${formData.get('name')} Added` : `${formData.get('name')} updated`;
        showModalWithParams(successMessage, 'true');

        // // Prepare socket data
        // const socketData = {
        //     name: formData.get('name'),
        //     linkurl: formData.get('linkurl'),
        //     platform: formData.get('platform'),
        //     details: formData.get('details'),
        //     date: formData.get('date'),
        //     time: formData.get('time'),
        //     hid: formData.get('hid'),
        //     selectempoyees: formData.getAll('selectempoyees[]'),
        //     hiddenassigned: formData.get('hiddenassigned'),
        //     hiddenGuid: formData.get('hiddenGuid'),
        //     repeater: collectRepeaterData(),
        //     NewProjectId: result.ProjectId,
        //     NewAssignedBy: result.assignedBy
        // };

        // Send to socket (commented out for now)
        // sendSubmittedDataToSocket(socketData);
    }

    /**
     * Handles errors during the process.
     */
    function handleError(error) {
        console.error('Error:', error);
        showModalWithParams(`An error occurred: ${error.message || error}`, 'false');
    }

    /**
     * Collects repeater data from the form.
     */
    function collectRepeaterData() {
        const repeaterData = [];
        $('.repeater-item').each(function() {
            repeaterData.push({
                employeeName: $(this).find('.employeeName').val(),
                deadlineDate: $(this).find('.deadlineDate').val(),
                deadlineTime: $(this).find('.deadlineTime').val(),
                projectInfo: $(this).find('.projectInfo').val()
            });
        });
        return repeaterData;
    }

    // ==================== Helper Functions Start ====================



    function UpdateTaskStatusToPending(StatusText, SubTaskId, Project_Pid) {
        $('.event_trigger_loader').addClass('active');

        const message = JSON.stringify({
            action: 'UpdateTaskStatusToPending',
            Status: StatusText,
            ProjectId: Project_Pid,
            TaskId: SubTaskId
        });



        fetch('include/handlers/update_subtask.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: message,
            })
            .then(response => response.json())
            .then(data => {

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                if (data.status === 'Update_Subtask_Success') {
                    showModalWithParams(`${data.message}`, 'true');
                } else if (data.error) {
                    showModalWithParams(`${data.error}`, 'false');
                }


            })
            .catch(error => {

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                showModalWithParams(`An error occurred: ${error}`, 'false');
            });
    }


    function UpdateTaskStatusToInProgress(StatusText, NotesText, SubTaskId, Project_Pid) {
        $('.event_trigger_loader').addClass('active');

        const message = JSON.stringify({
            action: 'UpdateTaskStatusToInProgress',
            Status: StatusText,
            Notes: NotesText,
            ProjectId: Project_Pid,
            TaskId: SubTaskId
        });


        fetch('include/handlers/update_subtask.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: message,
            })
            .then(response => response.json())
            .then(data => {

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                if (data.status === 'Update_Subtask_Success') {
                    showModalWithParams(`${data.message}`, 'true');
                } else if (data.error) {
                    showModalWithParams(`${data.error}`, 'false');
                }


            })
            .catch(error => {

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                showModalWithParams(`An error occurred: ${error}`, 'false');
            });
    }



    function UpdateTaskStatusToCompleted(StatusText, NotesText, LinkText, SubTaskId, Project_Pid) {
        //Event Preloader
        $('.event_trigger_loader').addClass('active');

        const message = JSON.stringify({
            action: 'UpdateTaskStatusToCompleted',
            Status: StatusText,
            Notes: NotesText,
            Link: LinkText,
            ProjectId: Project_Pid,
            TaskId: SubTaskId
        });

        const SendSocketValue = {
            NewProjectId: Project_Pid,
        };

        // Send a POST request to the PHP script
        fetch('include/handlers/update_subtask.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: message,
            })
            .then(response => response.json())
            .then(data => {

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                if (data.status === 'Update_Subtask_Success') {
                    SendSocketValue.NewAssignedBy = data.assignedBy;
                    SendSocketValue.selectempoyees = data.assignedTo;
                    SendSocketValue.name = data.name;
                    SendSocketValue.hid = data.id;
                    SendSocketValue.platform = data.platform;
                    SendSocketValue.details = data.details;
                    SendSocketValue.date = data.date;
                    SendSocketValue.time = data.time;
                    sendSubmittedDataToSocket(SendSocketValue);
                    showModalWithParams(`${data.message}`, 'true');
                } else if (data.error) {
                    showModalWithParams(`${data.error}`, 'false');
                }
            })
            .catch(error => {

                //Remove the Preloader
                setTimeout(function() {
                    $('.event_trigger_loader').removeClass('active');
                }, 1000);

                showModalWithParams(`An error occurred: ${error}`, 'false');
            });
    }





    function collectRepeaterData() {
        var repeaterData = [];
        $('.project_emp_rpt .repeater-wrapper').each(function() {
            var $wrapper = $(this);

            // Correct class names based on your HTML
            var employeeName = $wrapper.find('.repeater_emp_name').val().trim();
            var deadlineDate = $wrapper.find('.flatpickr_date_rpt').val().trim(); // Updated class name
            var deadlineTime = $wrapper.find('.empdeadlinetime').val().trim(); // Class name as per your HTML
            var projectInfo = $wrapper.find('textarea').val().trim();

            // Only add non-empty repeater items
            if (employeeName || deadlineDate || deadlineTime || projectInfo) {
                repeaterData.push({
                    employeeName: employeeName,
                    deadlineDate: deadlineDate,
                    deadlineTime: deadlineTime,
                    projectInfo: projectInfo
                });
            }
        });
        return repeaterData;
    }


    function playNotificationSound() {
        const audio = new Audio('notification-sound.mp3');
        audio.play().catch(error => console.error('Audio playback failed:', error));
    }

    function showNotification(title, options) {
        if (Notification.permission === 'granted') {
            const pushNotify = new Notification(title, options);
            pushNotify.addEventListener("close", e => {
                //  alert(e);
            });
        } else {
            console.log('Notification permission not granted');
        }
    }

    function requestNotificationPermission() {
        if (Notification.permission === 'granted') {
            return Promise.resolve();
        } else if (Notification.permission === 'denied') {
            return Promise.reject('Notifications are blocked.');
        } else {
            return Notification.requestPermission();
        }
    }







    //  ======================  Insert Sales Row to Notification Table Start ===========================

    const insertNotificationIntoDatabase = (reminder) => {
        // Construct the payload for the request
        const payload = {
            notificationid: reminder.id,
            taskid: reminder.taskid,
            name: reminder.taskName,
            platform: reminder.platform,
            employee: reminder.employeeName,
            details: reminder.taskDetails,
            date: reminder.date,
            time: reminder.time,
            action: 'InsertNotification'
        };

        // Send a POST request to the PHP script
        fetch('include/handlers/notificationHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Notification inserted successfully');
                    // Fetch Notification to Notification box
                    fetchNotifications();
                } else {
                    console.error('Failed to insert notification:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });



    };

    //  ======================  Insert Sales Row to Notification Table End ===========================




    //  ======================  Insert Reminder Row to Notification Table Start ===========================

    const insertReminderIntoDatabase = (reminder) => {
        // Construct the payload for the request
        const payload = {
            notificationid: reminder.id,
            name: reminder.name,
            assignedTo: reminder.assignedTo,
            duration: reminder.duration,
            date: reminder.date,
            recurring: reminder.recurring,
            action: 'InsertReminderNotification'
        };

        // Send a POST request to the PHP script
        fetch('include/handlers/notificationHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('reminder Notification inserted successfully');

                    fetchReminderNotifications();
                } else {
                    console.error('Failed to insert notification:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });



    };

    //  ======================  Insert Reminder Row to Notification Table Start ===========================



    //  ======================  Insert Project Row to Notification Table Start ===========================

    const insertprojectNotificationIntoDatabase = (reminder) => {
        // console.log(reminder);
        const payload = {
            notificationid: reminder.id,
            taskid: reminder.ProjectId,
            name: reminder.ProjectName,
            platform: reminder.Platform,
            employee: reminder.Name,
            details: reminder.Information,
            date: reminder.NotifiedDate,
            time: reminder.NotifiedTime,
            action: 'InsertProjectNotification'
        };

        // Send a POST request to the PHP script
        fetch('include/handlers/notificationHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Notification inserted successfully');
                    // Send Notification to Notification box
                    fetchNotifications();
                } else {
                    console.error('Failed to insert notification:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });



    };

    //  ======================  Insert Deadline Row to Notification Table Start ===========================



    
    const insertClientJobNotificationIntoDatabase = (reminder) => {
        // console.log(reminder);
        const payload = {
            name: reminder.ProjectName,
            platform: reminder.job_name,
            employee: reminder.name,
            details: reminder.Information,
            createdat: reminder.NotifiedDate,
            time: reminder.NotifiedTime,
            action: 'InsertProjectNotification'
        };

        // Send a POST request to the PHP script
        fetch('include/handlers/notificationHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Notification inserted successfully');
                    // Send Notification to Notification box
                    fetchNotifications();
                } else {
                    console.error('Failed to insert notification:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });



    };


    //  ======================  Insert Sales Deadline Row to Notification Table Start ===========================

    const insertprojectDeadlineNotificationIntoDatabase = (reminder) => {

        const payload = {
            notificationid: reminder.id,
            name: reminder.ProjectName,
            platform: reminder.Platform,
            employee: reminder.Name,
            details: reminder.Information,
            date: reminder.DeadlineDate,
            time: reminder.DeadlineTime,
            action: 'InsertDeadlineSales'
        };

        // Send a POST request to the PHP script
        fetch('include/handlers/notificationHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Notification inserted successfully');
                    fetchNotifications();
                } else {
                    console.error('Failed to insert notification:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });



    };

    //  ======================  Insert Sales Deadline Row to Notification Table ENd =============================


    

    const updateNotificationCount = () => {
        const notificationCountElement = document.getElementById('notification-count');
        const notificationBadgeElement = document.getElementById('notification-badge');

        const notificationList = document.querySelector('#notification-items');
        // Use the correct selector for your notification items
        const notifiedCountHelpElements = notificationList.querySelectorAll('.dropdown-notifications-item'); // Adjusted selector
        const count = notifiedCountHelpElements.length;

        notificationCountElement.textContent = count; // Update notification icon count
        notificationBadgeElement.textContent = `${count} New`; // Update badge text
    };


    // ============= Fetch all new Notification to Notification bell Section Start ===============

    const fetchNotifications = async () => {
        const username = window.loggedInUserName;
        try {
            const response = await fetch(`include/handlers/GeneralFetchHandler.php?username=${encodeURIComponent(username)}&action=FetchNotifications`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const notifications = await response.json();

            //console.log('Fetching reminder:', notifications);


            // notifications.forEach(updateNotificationDropdown1);
            reminderCount = notifications.length;
            notifications.forEach(reminder_obj => {
                updateNotificationDropdown1(reminder_obj);
            });
            updateNotificationCount();

        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    };


    const fetch_gs_Notifications = async () => {
        const user_id = window.loggedInUserId;
        try {
            const response = await fetch(`include/handlers/GeneralFetchHandler.php?user_id=${encodeURIComponent(user_id)}&action=Fetch_GS_Notifications`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const notifications = await response.json();

            console.log('Fetching reminder:', notifications);

            reminderCount = notifications.length;
            notifications.forEach(reminder_obj => {
                updateNotificationDropdownForJob_Project(reminder_obj);
            });
            updateNotificationCount();

        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    };


    // ============= Fetch all new Notification to Notification bell Section End ===============



    const fetchReminderNotifications = async () => {
        const username = window.loggedInUserName;
        try {
            const response = await fetch(`include/handlers/fetch_reminder_notifications.php?username=${encodeURIComponent(username)}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const notifications = await response.json();
            reminderCount = notifications.length;
            notifications.forEach(reminder_obj => {
                updateNotificationDropdown1(reminder_obj);
            });
            updateNotificationCount();

        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    };


    const updateNotificationDropdownForJob_Project = (reminder) => {
        if (!reminder) {
            console.warn('Reminder JSON is Null:', reminder);
            return;
        } else if (typeof reminder !== 'object') {
            console.warn('Reminder JSON Not Object:', reminder);
            return;
        } else if (!reminder.id) {
            console.warn('Reminder Id is Null:', reminder);
            return;
        }

        const notificationList = document.querySelector('#notification-items');
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item list-group-item-action dropdown-notifications-item';
        listItem.dataset.id = reminder.id;

        let title, details, href, Formatted_details;

        href = `assignjob.php?page=assignJob&rowId=${reminder.id}`
        title = `Assign Jobs : ${reminder.job_name || 'No Name'}`;
        details = `Details: ${reminder.notification_text || 'No Details'}`;
        recurring = `${reminder.created_at || 'No Date'}`;

        if (details.length > 65) {
            Formatted_details = details.substring(0, 65) + '...';
        } else {
            Formatted_details = details;
        }

        listItem.innerHTML = `
    
        <div class="d-flex notified_count_help">
            <div class="flex-shrink-0 me-3">
                <div class="avatar">
                    <span class="avatar-initial rounded-circle rs_bg-label-success">
                        <i class="ri-notification-3-fill"></i>
                    </span>
                </div>
            </div>
            <div style="width: 80%; padding-right: 10px;">
             <a href="${href}">
             <div class="flex-grow-1">
                <h6 class="mb-1 small">${title}</h6>
                <div class="Notify_text"><small class="mb-1 d-block text-body">${Formatted_details}</small></div>
                <div class="text-tooltip scrollable-container">${details}</div>
                <small class="text-muted">${recurring}</small>
             </div>
             </a>
            </div>
            <div class="flex-shrink-0 dropdown-notifications-actions">
                <a href="javascript:void(0)" class="dropdown-notifications-cancel" onclick="cancelNotification(${reminder.id}, '${reminder.type}')">
                    <span class="ri-close-line ri-20px"></span>
                </a>
            </div>
        </div>
       
    `;

    notificationList.appendChild(listItem);

    };

    const updateNotificationDropdown1 = (reminder) => {
        if (!reminder) {
            console.warn('Reminder JSON is Null:', reminder);
            return;
        } else if (typeof reminder !== 'object') {
            console.warn('Reminder JSON Not Object:', reminder);
            return;
        } else if (!reminder.id) {
            console.warn('Reminder Id is Null:', reminder);
            return;
        }

        const notificationList = document.querySelector('#notification-items');
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item list-group-item-action dropdown-notifications-item';
        listItem.dataset.id = reminder.id;


        // Check if notification already exists to avoid duplicates
        const existingNotification = notificationList.querySelector(`[data-id ="${reminder.id}"]`);
        if (existingNotification) return; // Skip if it already exists

        let title, details, recurring, href, Formatted_details;

        if (reminder.type === 'Task') {
            href = `assignTask.php?page=assignTask&rowId=${reminder.rowid}`
            title = `${reminder.type || 'No type'}: ${reminder.name || 'No Name'}`;
            details = `Details: ${reminder.details || 'No Details'}`;
            recurring = `${reminder.date || 'No Date'} ${reminder.time || 'No Time'}`;

            // Increment task count
            taskCount++;
        } else if (reminder.type === 'Reminder') {
            title = `Reminder: ${reminder.name || 'No Name'}`;
            details = `Assigned By: ${reminder.assignedBy || 'No AssignedBy'}`;
            recurring = `${reminder.createdOn || 'No Date'} Recurring: ${reminder.recurring === 1 ? 'Yes' : 'No'}`;

            // Increment reminder count
            reminderCount++;
        } else if (reminder.type === 'Project') {
            href = `assignProject.php?page=assignProject&rowId=${reminder.rowid}`
            title = `${reminder.type || 'No type'}: ${reminder.name || 'No Name'}`;
            details = `Details: ${reminder.details || 'No Details'} Platform : ${reminder.platform || 'No Details'}`;
            recurring = `${reminder.date || 'No Date'} ${reminder.time || 'No Time'}`;
            projectCount++;
        }

        if (details.length > 65) {
            Formatted_details = details.substring(0, 65) + '...';
        } else {
            Formatted_details = details;
        }

        listItem.innerHTML = `
    
        <div class="d-flex notified_count_help">
            <div class="flex-shrink-0 me-3">
                <div class="avatar">
                    <span class="avatar-initial rounded-circle rs_bg-label-success">
                        <i class="ri-notification-3-fill"></i>
                    </span>
                </div>
            </div>
            <div style="width: 80%; padding-right: 10px;">
             <a href="${href}">
             <div class="flex-grow-1">
                <h6 class="mb-1 small">${title}</h6>
                <div class="Notify_text"><small class="mb-1 d-block text-body">${Formatted_details}</small></div>
                <div class="text-tooltip scrollable-container">${details}</div>
                <small class="text-muted">${recurring}</small>
             </div>
             </a>
            </div>
            <div class="flex-shrink-0 dropdown-notifications-actions">
                <a href="javascript:void(0)" class="dropdown-notifications-cancel" onclick="cancelNotification(${reminder.id}, '${reminder.type}')">
                    <span class="ri-close-line ri-20px"></span>
                </a>
            </div>
        </div>
       
    `;

        notificationList.appendChild(listItem);

    };


    // ========================  Cancel Notification for Click Close Button In Bell Section Start   ==================


    const cancelNotification = async (notificationId, notificationType) => {
        try 
        {
            
            const response = await fetch(
                notificationType === 'Task' || notificationType === 'Project' ? 'include/handlers/notificationHandler.php' : 'include/handlers/notificationHandler.php', 
            {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: notificationId,
                        // Send Request to NotoficationHandler Page
                        action: (notificationType === 'Task' || notificationType === 'Project') ? 'CancelNotification' : 'CancelReminderNotification'
                    }),
            });

                const data = await response.json();
                if (data.success) 
                {
                    console.log(`Notification ${notificationId} canceled successfully`);
                    // Optionally, remove the notification from the dropdown
                    const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                    if (notificationItem) {
                        notificationItem.remove();
                    }

                    if (notificationType === 'Task') {
                        updateNotificationCount();
                    } else if (notificationType === 'Reminder') {
                        updateNotificationCount();;
                    } else if (notificationType === 'Project') {
                        updateNotificationCount();;
                    }
                    updateNotificationCount();
                    } else {
                    console.error('Failed to cancel notification:', data.error);
                }
        } 
        catch (error) {
            console.error('Error canceling notification:', error);
        }
    };


    // ========================  Cancel Notification for Click Close Button In Bell Section Start   ==================




    // function updateReminderdate(reminderId) {
    //     fetch('updateReminderDate.php', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/x-www-form-urlencoded'
    //             },
    //             body: new URLSearchParams({
    //                 'id': reminderId,

    //             })
    //         })
    //         .then(response => response.text())
    //         .then(result => {
    //             if (result.status === 'success') {
    //                 console.log('Reminder date updated successfully.');
    //             } else {
    //                 console.error('Error updating reminder date:', result.message);
    //             }
    //         })
    //         .catch(error => console.error('Error updating reminder date:', error));
    // }


    // function updateReminderStatus(reminderId) {
    //     return fetch('include/handlers/updateReminderStatus.php', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/x-www-form-urlencoded'
    //             },
    //             body: new URLSearchParams({
    //                 'id': reminderId,
    //                 'status': 1
    //             })
    //         })
    //         .then(response => response.text())
    //         .then(result => {
    //             console.log('Reminder status updated:', result);
    //             if (result.trim() !== 'success') {
    //                 console.error('Error updating reminder status:', result);
    //             }
    //         })
    //         .catch(error => console.error('Error:', error));
    // }



    // document.getElementById('SelectType').addEventListener('change', function() {
    //     var selectedType = this.value;
    //     var selectClient = document.getElementById('selectClient');


    //     selectClient.innerHTML = '<option value="" disabled selected>Select</option>';


    //     if (selectedType === 'employee') {
    //         var allOption = document.createElement('option');
    //         allOption.value = 'all';
    //         allOption.textContent = 'All';
    //         selectClient.appendChild(allOption);
    //         clientLabel.textContent = 'User';
    //     } else if (selectedType === 'task') {
    //         clientLabel.textContent = 'Client'; // Change label to Client
    //     }

    //     var url = selectedType === 'employee' ? 'include/handlers/dropFetchEmployee.php' : 'include/handlers/dropFetchTask.php';

    //     fetch(url)
    //         .then(response => response.json())
    //         .then(data => {

    //             data.forEach(function(item) {
    //                 var option = document.createElement('option');
    //                 option.value = item.name;
    //                 option.textContent = item.name;
    //                 selectClient.appendChild(option);
    //             });
    //         })
    //         .catch(error => {
    //             console.error('Error fetching data:', error);
    //         });
    // });


    

</script>


</body>

</html>