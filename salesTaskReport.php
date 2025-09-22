<?php include('include/head.php'); ?>

<?php require 'data/dbconfig.php';


if (isset($_GET['name']) || isset($_GET['status'])  || isset($_GET['six']) || isset($_GET['thirty'])) {
    $name = $_GET['name'];
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $six = isset($_GET['six']) ? $_GET['six'] : '';
    $thirty = isset($_GET['thirty']) ? $_GET['thirty'] : '';

    $tomorrow = date('Y-m-d', strtotime('+1 days')); // Get the date for tomorrow
    $next_six_days = date('Y-m-d', strtotime('+6 days')); // Get the date 6 days from today
    $seventhDay = date('Y-m-d', strtotime('+7 days')); // Get the date 7 days from today
    $next_thirty_days = date('Y-m-d', strtotime('+31 days')); // Get the date 30 days from today

    // Start building the base SQL query
    $sql = "SELECT td.* FROM task_descriptions td WHERE td.addedBy = '$name'";

    // Modify the query based on the status or date filters
    if (!empty($status)) {
        $sql .= " AND td.status = '$status'";
    } elseif (!empty($six)) {
        // Include the AND before the date condition
        $sql .= " AND td.date BETWEEN '$tomorrow' AND '$next_six_days'";
    } elseif (!empty($thirty)) {
        // Include the AND before the date condition
        $sql .= " AND td.date BETWEEN '$seventhDay' AND '$next_thirty_days'";
    } else {
        // Default to today's date if no other conditions are set
        $sql .= " AND td.date = CURDATE()";
    }
    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $data[] = array(

                'id' => $row['id'],
                'taskName' => $row['taskName'],
                'details' => $row['details'],
                'date' => $row['date'],
                'time' => $row['time'],
                'status' => $row['status'],
                'addedBy' => $row['addedBy'],
            );
        }
    }

    $response = array('data' => $data);
    $json = json_encode($response, JSON_PRETTY_PRINT);
} else {
    // If no role is set, initialize $json as an empty array
    $json = json_encode(array('data' => []), JSON_PRETTY_PRINT);
}


?>


<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">

            <form id="reportForm">
                <div class="">
                    <div class="col-md-12">
                        <div class="card mb-9">
                            <div class="card-body">

                                <div class="row" id="SameNameAlert" style="display:none;">
                                    <div class="col-12">
                                        <div class="alert alert-danger" role="alert">
                                            <p id="Same_name_alert_para"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gy-5">
                                    <!-- Left Column Start -->
                                    <div class="col-md-6">
                                        <input type="hidden" id="hiddenId" value="">

                                        <div class="row mb-3">
                                            <h5 class="Address_ship_headline">Sales Task Report</h5>
                                        </div>

                                        <div class="form-floating form-floating-outline mb-5 mt-3">
                                            <select id="SelectType" name="status" class="select2 form-select"
                                                data-allow-clear="true">
                                                <option value="">Select</option>
                                                <option value="employee">User</option>
                                                <option value="task">Task</option>

                                            </select>
                                            <label for="SelectType">Choose *</label>
                                        </div>


                                        <?php
                                        // Fetch names from the event table
                                        $sql2 = "SELECT name FROM event";
                                        $result2 = $conn->query($sql2);



                                        // Initialize an associative array to hold unique names
                                        $options = [];

                                        // Add event names to the options
                                        if ($result2->num_rows > 0) {
                                            while ($row = $result2->fetch_assoc()) {
                                                $options[$row['name']] = htmlspecialchars($row['name']);
                                            }
                                        }



                                        // Sort the unique options alphabetically
                                        $uniqueOptions = array_values($options);
                                        sort($uniqueOptions);

                                        // Build the dropdown
                                        echo '<div class="form-floating form-floating-outline mb-5 mt-3">';
                                        echo '<select id="selectClient" name="selectClient" required class="select2 form-select" data-allow-clear="true">';
                                        echo '<option value="" disabled selected>Select</option>';
                                        if (count($uniqueOptions) > 0) {
                                            foreach ($uniqueOptions as $name) {
                                                echo '<option value="' . $name . '">' . $name . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No names found.</option>';
                                        }
                                        echo '</select>';
                                        echo '<label id="clientLabel" for="selectClient">Client / User *</label>';
                                        echo '</div>';
                                        ?>

                                        <div class="form-floating form-floating-outline mb-5 mt-3">
                                            <select id="SelecteStatus" name="status" class="select2 form-select"
                                                data-allow-clear="true">
                                                <option value="All">All</option>
                                                <option value="Processing">Pending</option>
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Not Interested">Not Interested</option>
                                            </select>
                                            <label for="SelecteStatus">Status *</label>
                                        </div>




                                    </div>
                                    <!-- Left Column End -->

                                    <!-- Date and Time Start -->
                                    <div class="col-md-6 mt-12">
                                        <div class="form-floating form-floating-outline mb-5 mt-9">
                                            <input type="text" readonly="readonly" id="from_date"
                                                placeholder="DD-MM-YYYY" class="form-control flatpickr_date">
                                            <label for="from_date">From Date *</label>
                                        </div>

                                        <div class="form-floating form-floating-outline mb-3">
                                            <input type="text" readonly="readonly" id="to_date" placeholder="DD-MM-YYYY"
                                                class="form-control flatpickr_date">
                                            <label for="to_date">To Date *</label>
                                        </div>
                                    </div>

                                </div>

                                <!-- Buttons -->
                                <div class="col-12 d-flex justify-content-end mb-6">
                                    <button type="reset"
                                        class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                    <button type="submit" id="submit" value="taskReport"
                                        class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>


        <div class="row" style="margin-top: 50px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-datatable table-responsive">
                            <?php if ($SalesExcel === 'Enable'): ?>
                                <button id="exportToExcel" class="btn btn-primary" style="margin: 20px;">Export to
                                    Excel</button>
                            <?php endif; ?>
                            <table class="datatables-taskReport table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>S.No</th>
                                        <th>Full Name</th>
                                        <th>Phone</th>
                                        <th>Platform</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Tag User</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <div id="noDatafound" style="padding: 20px;background: aliceblue;text-align: center;">
                                <span>No data fetch. Please Select and View....</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>




        <!-- / Content -->
        <?php include('include/footer.php'); ?>

        <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Form submission handling
                document.getElementById('reportForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const SelectType = document.getElementById('SelectType').value;
                    const selectClient = document.getElementById('selectClient').value;
                    const SelecteStatus = document.getElementById('SelecteStatus').value;
                    const from_date = document.getElementById('from_date').value;
                    const to_date = document.getElementById('to_date').value;
                    const submit = document.getElementById('submit').value;

                    if (!SelectType || !selectClient || !SelecteStatus || !from_date || !to_date) {
                        // If any field is empty, show an alert
                        // alert('All fields must be filled out!');
                        $('#SameNameAlert').show();
                        $('#Same_name_alert_para').html('Please fill a mandatory fields ');

                        // Hide after 3 seconds
                        setTimeout(function() {
                            $('#SameNameAlert').hide();
                        }, 4000);

                        $('.event_trigger_loader').removeClass(
                            'active'); // Remove the preloader if validation fails

                        return; // Stop form submission
                    }

                    const formData = new URLSearchParams({
                        'SelectType': SelectType,
                        'selectClient': selectClient,
                        'SelecteStatus': SelecteStatus,
                        'from_date': from_date,
                        'to_date': to_date,
                        'submit': submit
                    });

                    fetch('function.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: formData.toString()
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.status === 'success') {
                                initializeDataTable('.datatables-taskReport', result.data,
                                    result.type);
                            } else {
                                console.error('Error:', result.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });


                $('#SelectType').on('change', function() {
                    var selectedType = $(this).val();
                    var selectClient = $('#selectClient');
                    var clientLabel = $('#clientLabel'); // Assuming you have a label with this ID

                    selectClient.empty(); // Clear the select element
                    selectClient.append('<option value="" disabled selected>Select</option>');

                    if (selectedType === 'employee') {
                        var allOption = $('<option></option>').val('all').text('All');
                        selectClient.append(allOption);
                        clientLabel.text(
                            'User'); // Change label to 'User' when 'employee' is selected
                    } else if (selectedType === 'task') {
                        clientLabel.text(
                            'Client'); // Change label to 'Client' when 'task' is selected
                    }

                    var url = 'include/handlers/GeneralFetchHandler.php';

                    $.ajax({
                        url: url,
                        method: 'GET',
                        data: {
                            action: (selectedType === 'employee') ? 'DropFetchEmployee':'DropFetchTask'
                        },
                        dataType: 'json',
                        success: function(data) {
                            // Create an array to hold the options
                            var optionsArray = [];

                            // Iterate through the response data and create option elements
                            $.each(data, function(index, item) {
                                 // var option = $('<option></option>').val(item
                                //     .taskName).text(item.taskName);
                                // optionsArray.push(option);
                                  // ✅ Handle both employee.name and task.taskName
                                var textValue = item.name || item.taskName;

                                if (textValue) {
                                    var option = $('<option></option>').val(textValue).text(textValue);
                                    optionsArray.push(option);
                                }
                            });

                            // Sort the options alphabetically by the option text (name)
                            optionsArray.sort(function(a, b) {
                                return a.text().localeCompare(b.text());
                            });

                            // Append sorted options to the selectClient dropdown
                            selectClient.empty(); // Clear existing options first
                            $.each(optionsArray, function(index, option) {
                                selectClient.append(option);
                            });
                        },
                        error: function(error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });


                function initializeDataTable(selector, data, type) {
                    $('#noDatafound').hide();
                    $(selector).DataTable().clear().destroy();
                    let columns, columnDefs, headers;

                    if (type === 'event') {
                        headers = `
               <tr>
                                            <th></th>
                                            <th>S.No</th>
                                            <th>Full Name</th>
                                            <th>Phone</th>
                                            <th>Platform</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Tag User</th>
                                            <th>Assigned By</th>
                                            <th>Status</th>
                                        </tr>`;
                        columns = [{
                                data: ''
                            },
                            {
                                data: 'id'
                            },
                            {
                                data: 'name'
                            },
                            {
                                data: 'phone'
                            },
                            {
                                data: 'platform'
                            },
                            {
                                data: 'date'
                            },
                            {
                                data: 'time'
                            },
                            {
                                data: 'tagemployee'
                            },
                            {
                                data: 'assignedBy'
                            },
                            {
                                data: 'status'
                            },
                        ];

                        columnDefs = [{
                                className: 'dt-control',
                                searchable: false,
                                orderable: false,
                                responsivePriority: 2,
                                targets: 0,
                                render: function(data, type, full, meta) {
                                    return '';
                                }
                            },
                            {
                                targets: 1,
                                searchable: false,
                                orderable: true,
                                render: function(data, type, full, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                targets: 2,
                                responsivePriority: 1,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full.name + '</span>';
                                }
                            },
                            {
                                targets: 5,
                                render: function(data, type, full) {
                                    const dateParts = full.date.split('-');
                                    const formattedDate =
                                        `${String(dateParts[2]).padStart(2, '0')}-${String(dateParts[1]).padStart(2, '0')}-${dateParts[0]}`;
                                    return '<span class="text-heading">' + formattedDate +
                                        '</span>';
                                }
                            },
                            {
                                targets: 6,
                                render: function(data, type, full) {
                                    const timeParts = full.time.split(':');
                                    let hours = parseInt(timeParts[0], 10);
                                    const minutes = String(timeParts[1]).padStart(2, '0');
                                    const period = hours >= 12 ? 'pm' : 'am';
                                    hours = hours % 12 || 12;
                                    const formattedTime = `${hours}:${minutes} ${period}`;
                                    return '<span class="text-heading">' + formattedTime +
                                        '</span>';
                                }
                            },
                            {
                                targets: 8,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full.assignedBy +
                                        '</span>';
                                }
                            },

                            {
                                targets: 9,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + (full.status ===
                                        'Processing' ? 'Pending' : full.status) + '</span>';
                                }
                            }
                        ];
                        $(selector + ' thead').html(headers);

                        const dt_customer = $(selector).DataTable({
                            data: data,
                            columns: columns,
                            columnDefs: columnDefs,
                            order: [
                                [1, 'asc']
                            ],
                            language: {
                                sLengthMenu: '_MENU_',
                                search: ' ',
                                searchPlaceholder: 'Universal Search'
                            },
                            stateSave: true,
                            rowId: 'id',
                            responsive: {
                                details: {
                                    display: $.fn.dataTable.Responsive.display.modal({
                                        header: function(row) {
                                            const data = row.data();
                                            return 'Details of ' + data.name;
                                        }
                                    }),
                                    type: 'column',
                                    renderer: function(api, rowIdx, columns) {
                                        const data = $.map(columns, function(col) {
                                            return col.title !== '' ?
                                                '<tr data-dt-row="' + col.rowIndex +
                                                '" data-dt-column="' + col.columnIndex +
                                                '">' +
                                                '<td>' + col.title + ':</td>' +
                                                '<td>' + col.data + '</td>' +
                                                '</tr>' : '';
                                        }).join('');
                                        return data ? $('<table class="table"/><tbody />')
                                            .append(data) : false;
                                    }
                                }
                            }
                        });

                        // Add event listener for opening and closing details
                        let currentlyOpenRow = null;

                        dt_customer.on('click', 'td.dt-control', function(e) {
                            const tr = $(this).closest('tr'); // Use jQuery to find the closest row
                            const row = dt_customer.row(tr);

                            // Check if the row is valid before proceeding
                            if (row.length) {
                                if (row.child.isShown()) {
                                    row.child.hide();
                                    tr.removeClass('highlight_row');
                                } else {
                                    // Close previously opened row if it exists
                                    if (currentlyOpenRow && currentlyOpenRow.child.isShown()) {
                                        currentlyOpenRow.child.hide();
                                        currentlyOpenRow.node().classList.remove('highlight_row');
                                    }

                                    row.child(format(row.data())).show();
                                    currentlyOpenRow = row;
                                    tr.addClass('highlight_row');
                                }
                            } else {
                                //  console.error('Row not found or does not exist.');
                            }
                        });


                        function format(d) {
                            // Ensure that d is defined and has the expected structure
                            if (!d || !d.tasks) {
                                return '<div>No details available</div>';
                            }

                            const tasks = d.tasks || [];
                            const groupedTasks = tasks.reduce((acc, task) => {
                                const taskId = task.taskid;
                                if (!acc[taskId]) {
                                    acc[taskId] = [];
                                }
                                acc[taskId].push(task);
                                return acc;
                            }, {});

                            return (
                                '<table class="table SubTable" style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">' +
                                '<thead>' +
                                '<tr>' +
                                '<th style="width:5%">S.No</th>' +
                                '<th style="width:35%">Details</th>' +
                                '<th style="width:20%">Created On</th>' +
                                '<th style="width:18%">Added By</th>' +
                                '<th style="width:12%">Task Status</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>' +
                                (Object.entries(groupedTasks).map(([taskId, subtasks]) => {
                                    return subtasks.map((task, subIndex) => {
                                        const taskDateTime = new Date(task
                                            .task_createdon);
                                        const day = String(taskDateTime.getDate())
                                            .padStart(2, '0');
                                        const month = String(taskDateTime.getMonth() +
                                            1).padStart(2, '0');
                                        const year = taskDateTime.getFullYear();
                                        const hours = String(taskDateTime.getHours())
                                            .padStart(2, '0');
                                        const minutes = String(taskDateTime
                                            .getMinutes()).padStart(2, '0');
                                        const seconds = String(taskDateTime
                                            .getSeconds()).padStart(2, '0');
                                        const Task_status = task.task_status;

                                        let badgeHtml = '';
                                        if (Task_status === "Processing") {
                                            badgeHtml =
                                                '<div class="Change_status_work badge rounded-pill bg-label-danger me-1 text-decoration-none">' +
                                                '<span>Pending</span></div>';
                                        } else if (Task_status === "Completed") {
                                            badgeHtml =
                                                '<div class="Change_status_work badge rounded-pill bg-label-success me-1 text-decoration-none">' +
                                                '<span>' + Task_status +
                                                '</span></div>';
                                        } else {
                                            badgeHtml =
                                                '<div class="Change_status_work badge rounded-pill bg-label-warning me-1 text-decoration-none">' +
                                                '<span>' + Task_status +
                                                '</span></div>';
                                        }

                                        const formattedDate =
                                            `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;

                                        return (
                                            '<tr>' +
                                            '<td>' + (subIndex + 1) + '</td>' +
                                            '<td>' + task.task_details + '</td>' +
                                            '<td>' + formattedDate + '</td>' +
                                            '<td>' + task.task_addedBy + '</td>' +
                                            '<td>' + badgeHtml + '</td>' +
                                            '</tr>'
                                        );
                                    }).join('');
                                }).join('') || '<tr><td colspan="5">No tasks available</td></tr>') +
                                '</tbody>' +
                                '</table>'
                            );
                        }
                    } else {
                        headers = `
                <tr>
                    <th></th>
                    <th>S.No</th>
                    <th>Task Name</th>
                    <th>Details</th>
                        <th>Date</th>
                            <th>Time</th>
                    <th>Status</th>
                    <th>User Name</th>
                   
                </tr>`;
                        columns = [{
                                data: ''
                            },
                            {
                                data: 'id'
                            },
                            {
                                data: 'taskName'
                            },
                            {
                                data: 'details'
                            },
                            {
                                data: 'date'
                            },
                            {
                                data: 'time'
                            },
                            {
                                data: 'status'
                            },
                            {
                                data: 'addedBy'
                            }

                        ];

                        columnDefs = [{
                                targets: 0,
                                render: function() {
                                    return '';
                                }
                            },
                            {
                                targets: 1,
                                searchable: false,
                                orderable: true,
                                render: function(data, type, full, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                targets: 2,
                                responsivePriority: 1,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['taskName'] +
                                        '</span>';
                                }
                            },
                            {
                                targets: 3,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['details'] +
                                        '</span>';
                                }
                            },
                            {
                                targets: 4,
                                render: function(data, type, full) {
                                    const dateParts = full.date.split('-');
                                    const formattedDate =
                                        `${String(dateParts[2]).padStart(2, '0')}-${String(dateParts[1]).padStart(2, '0')}-${dateParts[0]}`;
                                    return '<span class="text-heading">' + formattedDate +
                                        '</span>';

                                }
                            },
                            {
                                targets: 5,
                                render: function(data, type, full) {
                                    const timeParts = full.time.split(':');
                                    let hours = parseInt(timeParts[0], 10);
                                    const minutes = String(timeParts[1]).padStart(2, '0');
                                    const period = hours >= 12 ? 'pm' : 'am';
                                    hours = hours % 12 || 12;
                                    const formattedTime = `${hours}:${minutes} ${period}`;
                                    return '<span class="text-heading">' + formattedTime +
                                        '</span>';
                                }
                            },
                            {
                                targets: 6,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + (full['status'] ===
                                        'Processing' ? 'Pending' : full['status']) + '</span>';
                                }
                            },
                            {
                                targets: 7,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['addedBy'] +
                                        '</span>';
                                }
                            }

                        ];
                        $(selector + ' thead').html(headers);

                        $(selector).DataTable({
                            data: data,
                            columns: columns,
                            columnDefs: columnDefs,
                            order: [
                                [1, 'asc']
                            ],
                            language: {
                                sLengthMenu: '_MENU_',
                                search: ' ',
                                searchPlaceholder: 'Universal Search'
                            },
                            stateSave: true,
                            rowId: 'id',
                            responsive: {
                                details: {
                                    display: $.fn.dataTable.Responsive.display.modal({
                                        header: function(row) {
                                            var data = row.data();
                                            return 'Details of ' + data['name'];
                                        }
                                    }),
                                    type: 'column',
                                    renderer: function(api, rowIdx, columns) {
                                        var data = $.map(columns, function(col) {
                                            return col.title !==
                                                '' // Do not show row in modal popup if title is blank
                                                ?
                                                '<tr data-dt-row="' + col.rowIndex +
                                                '" data-dt-column="' + col.columnIndex +
                                                '">' +
                                                '<td>' + col.title + ':</td>' +
                                                '<td>' + col.data + '</td>' +
                                                '</tr>' : '';
                                        }).join('');

                                        return data ? $('<table class="table"/><tbody />')
                                            .append(data) : false;
                                    }
                                }
                            }
                        });

                        // Additional styling adjustments
                        $('.dataTables_length').addClass('my-0');
                        $('.dt-action-buttons').addClass('pt-0');
                        $('.dataTables_filter input').addClass('ms-0');
                        $('.dt-buttons').addClass('d-flex flex-wrap');
                    }

                };

                var jsonData = <?php echo $json; ?>;

                //console.log('log',jsonData);
                $(document).ready(function() {

                    if (jsonData && jsonData.data && jsonData.data.length > 0) {
                        initializeDataTable('.datatables-taskReport', jsonData.data, 'employee');
                    } else {

                        console.log("No data available to display.");
                    }
                });

                // document.getElementById('exportToExcel').addEventListener('click', function() {
                //     // Initialize DataTable
                //     const dataTable = $('.datatables-taskReport').DataTable();

                //     // Get all data from DataTable
                //     const data = dataTable.rows().data().toArray();

                //     // Prepare data for Excel
                //     const exportData = data.map((row, index) => {
                //         const rowData = {
                //             'S.No': index + 1
                //         };

                //         // Determine headers and fill rowData based on the presence of properties
                //         if (row.name) {
                //             // If row.name exists, use employee headers
                //             rowData['Full Name'] = row.name || '';
                //             rowData['Phone'] = row.phone || '';
                //             rowData['Platform'] = row.platform || '';
                //             rowData['Date'] = formatDate(row.date || '');
                //             rowData['Time'] = formatTime(row.time || '');
                //             rowData['Tag User'] = row.tagemployee || '';
                //             rowData['Assigned By'] = row.assignedBy || '';
                //             rowData['Status'] = row.status || '';
                //         } else if (row.taskName) {
                //             // If row.taskName exists, use task headers
                //             rowData['Task Name'] = row.taskName || '';
                //             rowData['Details'] = row.details || '';
                //             rowData['Date'] = formatDate(row.date || '');
                //             rowData['Time'] = formatTime(row.time || '');
                //             rowData['Status'] = row.status || '';
                //             rowData['User Name'] = row.employeeName || '';
                //         }

                //         return rowData;
                //     });


                //     let currentDate = new Date();

                //     // Format the date as dd-mm-yyyy
                //     let day = String(currentDate.getDate()).padStart(2,
                //         '0'); // Add leading zero if necessary
                //     let month = String(currentDate.getMonth() + 1).padStart(2,
                //         '0'); // Get month (0-11, so add 1)
                //     let year = currentDate.getFullYear();

                //     // Combine into dd-mm-yyyy format
                //     let formattedDate = `${day}-${month}-${year}`;

                //     // Determine headers based on the first row's properties
                //     let headers;
                //     let fileNameBase = 'Sales Appointment Report';
                //     if (data.length > 0) {
                //         if (data[0].name) {
                //             headers = [
                //                 'S.No', 'Full Name', 'Phone', 'Platform', 'Date', 'Time',
                //                 'Tag User', 'Assigned By', 'Status'

                //             ];
                //             fileNameBase += `-${data[0].name}`;
                //         } else {
                //             headers = [
                //                 'S.No', 'Task Name', 'Details', 'Date', 'Time', 'Status',
                //                 'User Name'
                //             ];
                //             fileNameBase += `-${data[0].employeeName}`;
                //         }
                //     }

                //     // Convert to worksheet and workbook
                //     const worksheet = XLSX.utils.json_to_sheet(exportData, {
                //         header: headers
                //     });
                //     const workbook = XLSX.utils.book_new();
                //     XLSX.utils.book_append_sheet(workbook, worksheet, 'Report');

                //     // Export to Excel
                //     XLSX.writeFile(workbook, `${fileNameBase += `- ${formattedDate}`}.xlsx`);
                // });

                // Helper functions for formatting
                function formatDate(date) {
                    const dateParts = date.split('-');
                    return `${String(dateParts[2]).padStart(2, '0')}-${String(dateParts[1]).padStart(2, '0')}-${dateParts[0]}`;
                }

                function formatTime(time) {
                    const timeParts = time.split(':');
                    let hours = parseInt(timeParts[0], 10);
                    const minutes = String(timeParts[1]).padStart(2, '0');
                    const period = hours >= 12 ? 'pm' : 'am';
                    hours = hours % 12 || 12;
                    return `${hours}:${minutes} ${period}`;
                }


            });
        </script>