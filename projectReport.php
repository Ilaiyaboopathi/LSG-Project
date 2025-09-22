<?php include('include/head.php'); ?>

<?php require 'data/dbconfig.php';

if (isset($_GET['name']) && isset($_GET['status'])) {
    $name = $_GET['name'];
    $status = $_GET['status'];
    $sql = "SELECT a.*  
    FROM assignproject a
    WHERE a.Name = '$name' AND a.SubTaskStatus = '$status'";
    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $data[] = array(

                'id' => $row['id'],
                'Name' => $row['Name'],
                'ProjectName' => $row['ProjectName'],
                'Platform' => $row['Platform'],
                'DeadlineDate' => $row['DeadlineDate'],
                'DeadlineTime' => $row['DeadlineTime'],
                'Information' => $row['Information'],
                'SubTaskStatus' => $row['SubTaskStatus'],
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
                                            <h5 class="Address_ship_headline">Project Report</h5>
                                        </div>

                                        <div class="form-floating form-floating-outline mb-5 mt-3">
                                            <select id="SelectProjectType" name="status" class="select2 form-select"
                                                data-allow-clear="true">
                                                <option value="">Select</option>
                                                <option value="employee">User</option>
                                                <option value="project">Project</option>
                                            </select>
                                            <label for="SelectProjectType">Choose *</label>
                                        </div>


                                        <?php
                                        // Fetch names from the event table
                                        $sql2 = "SELECT name FROM project";
                                        $result2 = $conn->query($sql2);

                                        $options = [];

                                        // Add event names to the options
                                        if ($result2->num_rows > 0) {
                                            while ($row = $result2->fetch_assoc()) {
                                                $options[$row['name']] = htmlspecialchars($row['name']);
                                            }
                                        }


                                        $uniqueOptions = array_values($options);
                                        sort($uniqueOptions);

                                        // Build the dropdown
                                        echo '<div class="form-floating form-floating-outline mb-5 mt-3">';
                                        echo '<select id="selectProject" name="selectClient" required class="select2 form-select" data-allow-clear="true">';
                                        echo '<option value="" disabled selected>Select</option>';
                                        if (count($uniqueOptions) > 0) {
                                            foreach ($uniqueOptions as $name) {
                                                echo '<option value="' . $name . '">' . $name . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No Project found.</option>';
                                        }
                                        echo '</select>';
                                        echo '<label for="selectProject">Choose Name *</label>';
                                        echo '</div>';
                                        ?>

                                        <div class="form-floating form-floating-outline mb-5 mt-3">
                                            <select id="SelecteStatus" name="status" class="select2 form-select"
                                                data-allow-clear="true">
                                                <option value="All">All</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Extended">Extended</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                            <label for="SelecteStatus">Status *</label>
                                        </div>




                                    </div>

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
                                    <button type="submit" id="submit" value="projectReport"
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
                            <?php if ($ProjectExcel === 'Enable'): ?>
                                <button id="exportToExcel" class="btn btn-primary" style="margin: 20px;">Export to
                                    Excel</button>
                            <?php endif; ?>
                            <table class="datatables-projectReport table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>S.No</th>
                                        <th>Full Name</th>
                                        <th>Type</th>
                                        <th>Assigned By</th>
                                        <th>Assigned To</th>
                                        <th>Final Date</th>
                                        <th>Final Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <div id="noDatafound" style="padding: 20px;background: aliceblue;text-align: center;">
                                <span>No data
                                    fetch. Please Select and View....</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






        <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {


                // Form submission handling
                document.getElementById('reportForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const SelectType = document.getElementById('SelectProjectType').value;
                    const selectProject = document.getElementById('selectProject').value;
                    const SelecteStatus = document.getElementById('SelecteStatus').value;
                    const from_date = document.getElementById('from_date').value;
                    const to_date = document.getElementById('to_date').value;

                    const submit = document.getElementById('submit').value;

                    if (!SelectType || !selectProject || !SelecteStatus || !from_date || !to_date) {
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
                        'selectProject': selectProject,
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
                                initializeDataTable('.datatables-projectReport', result.data, result
                                    .type);
                            } else {
                                console.error('Error:', result.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });


                $('#SelectProjectType').on('change', function() {

                    var selectedType = $(this).val();
                    var selectProject = $('#selectProject');

                    selectProject.empty(); // Clear the select element
                    selectProject.append('<option value="" disabled selected>Select</option>');

                    if (selectedType === 'employee') {
                        var allOption = $('<option></option>').val('all').text('All');
                        selectProject.append(allOption);
                    }

                    var url = 'include/handlers/GeneralFetchHandler.php';

                    $.ajax({
                        url: url,
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            action: (selectedType === 'employee') ? 'DropFetchEmployee' :'DropFetchProject'
                        },
                        success: function(data) {
                            var optionsArray = [];

                            // Iterate through the response data and create option elements
                            $.each(data, function(index, item) {
                                // var option = $('<option></option>').val(item.name)
                                //     .text(item.name);
                                // optionsArray.push(option);
                                 var textValue = item.name || item.job_name;

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
                            selectProject.empty(); // Clear existing options first
                            $.each(optionsArray, function(index, option) {
                                selectProject.append(option);
                            });
                        },
                        error: function(error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });


                document.getElementById('exportToExcel').addEventListener('click', function() {
                    // Initialize DataTable
                    const dataTable = $('.datatables-projectReport').DataTable();

                    // Get all data from DataTable
                    const data = dataTable.rows().data().toArray();

                    // Prepare data for Excel
                    const exportData = data.map((row, index) => {
                        const rowData = {
                            'S.No': index + 1
                        };

                        // Determine headers and fill rowData based on the presence of properties
                        if (row.name) {
                            // If row.name exists, use employee headers
                            rowData['Full Name'] = row.name || '';
                            rowData['Type'] = row.type || '';
                            rowData['Assigned By'] = row.assignedBy || '';
                            rowData['Assigned To'] = row.assignedTo || '';
                            rowData['Final Date'] = formatDate(row.deadlineDate || '');
                            rowData['Final Time'] = formatTime(row.deadlineTime || '');
                            rowData['Status'] = row.ProjectStatus || '';

                        } else if (row.Name) {
                            // If row.taskName exists, use task headers
                            rowData['User Name'] = row.Name || '';
                            rowData['Project Name'] = row.ProjectName || '';
                            rowData['Platform'] = row.Platform || '';
                            rowData['Final Date'] = formatDate(row.DeadlineDate || '');
                            rowData['Final Time'] = formatTime(row.DeadlineTime || '');
                            rowData['Information'] = row.Information || '';
                            rowData['Status'] = row.SubTaskStatus || '';


                        }

                        return rowData;
                    });


                    let currentDate = new Date();

                    // Format the date as dd-mm-yyyy
                    let day = String(currentDate.getDate()).padStart(2,
                        '0'); // Add leading zero if necessary
                    let month = String(currentDate.getMonth() + 1).padStart(2,
                        '0'); // Get month (0-11, so add 1)
                    let year = currentDate.getFullYear();

                    // Combine into dd-mm-yyyy format
                    let formattedDate = `${day}-${month}-${year}`;
                    // Determine headers based on the first row's properties
                    let headers;
                    let fileNameBase = 'Project Report';
                    if (data.length > 0) {
                        if (data[0].name) {
                            headers = [
                                'S.No', 'Full Name', 'Type', 'Assigned By', 'Assigned To',
                                'Final Date', 'Final Time', 'Status'


                            ];
                            fileNameBase += `-${data[0].name}`;
                        } else {
                            headers = [
                                'S.No', 'User Name', 'Project Name', 'Platform', 'Final Date',
                                'Final Time', 'Information', 'Status'


                            ];
                            fileNameBase += `-${data[0].Name}`;
                        }
                    }

                    // Convert to worksheet and workbook
                    const worksheet = XLSX.utils.json_to_sheet(exportData, {
                        header: headers
                    });
                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, 'Report');

                    // Export to Excel
                    XLSX.writeFile(workbook, `${fileNameBase += `- ${formattedDate}`}.xlsx`);
                });



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


                function initializeDataTable(selector, data, type) {
                    $('#noDatafound').hide();
                    $(selector).DataTable().clear().destroy();
                    let columns, columnDefs, headers;
                    headers = `
                                    <tr>
                                        <th></th>
                                        <th>S.No</th>
                                        <th>Full Name</th>
                                        <th>Type</th>
                                        <th>Assigned By</th>
                                        <th>Assigned To</th>
                                        <th>Final Date</th>
                                        <th>Final Time</th>
                                        <th>Status</th>
                                    </tr>`;

                    if (type === 'project') {
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
                                data: 'type'
                            },
                            {
                                data: 'assignedBy'
                            },
                            {
                                data: 'assignedTo'
                            },
                            {
                                data: 'deadlineDate'
                            },
                            {
                                data: 'deadlineTime'
                            },
                            {
                                data: 'ProjectStatus'
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
                                    return '<span class="text-heading">' + full['name'] + '</span>';
                                }
                            },
                            {
                                targets: 3,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['type'] + '</span>';
                                }
                            },
                            {
                                targets: 4,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['assignedBy'] +
                                        '</span>';
                                }
                            },
                            {
                                targets: 5,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['assignedTo'] +
                                        '</span>';
                                }
                            },
                            {
                                targets: 6,
                                render: function(data, type, full) {
                                    // Convert the deadlineDate from YYYY-MM-DD to DD-MM-YYYY
                                    const deadlineDateParts = full['deadlineDate'].split('-');
                                    const formattedDate =
                                        `${String(deadlineDateParts[2]).padStart(2, '0')}-${String(deadlineDateParts[1]).padStart(2, '0')}-${deadlineDateParts[0]}`;

                                    return '<span class="text-heading">' + formattedDate + '</span>';
                                }
                            },
                            {
                                targets: 7,
                                render: function(data, type, full) {
                                    // Convert the deadlineTime from HH:MM:SS to 12-hour format with AM/PM
                                    const deadlineTimeParts = full['deadlineTime'].split(':');
                                    let hours = parseInt(deadlineTimeParts[0], 10);
                                    const minutes = String(deadlineTimeParts[1]).padStart(2, '0');
                                    const period = hours >= 12 ? 'pm' : 'am';
                                    hours = hours % 12 || 12; // Convert to 12-hour format

                                    const formattedTime = `${hours}:${minutes} ${period}`;

                                    return '<span class="text-heading">' + formattedTime + '</span>';
                                }
                            },



                            {
                                targets: 8,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['ProjectStatus'] +
                                        '</span>';
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
                                        return data ? $('<table class="table"/><tbody />').append(
                                            data) : false;
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
                            const tasks = d.assignments || [];
                            const groupedTasks = tasks.reduce((acc, task) => {
                                const taskId = task.pid;
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
                                '<th style="width:5%">S.No</th>' + // Added Serial Number column
                                '<th style="width:10%">User Name</th>' +
                                '<th style="width:45%">Information</th>' +
                                '<th style="width:15%">Due Date</th>' +
                                '<th style="width:10%">Due Time </th>' +
                                '<th style="width:10%">Task Status</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>' +
                                (Object.entries(groupedTasks).map(([taskId, subtasks]) => {
                                    return subtasks.map((task, subIndex) => {
                                        const taskDateTime = new Date(task.deadlineDate);
                                        const day = String(taskDateTime.getDate()).padStart(
                                            2, '0');
                                        const month = String(taskDateTime.getMonth() + 1)
                                            .padStart(2, '0');
                                        const year = taskDateTime.getFullYear();
                                        const hours = String(taskDateTime.getHours())
                                            .padStart(2, '0');
                                        const minutes = String(taskDateTime.getMinutes())
                                            .padStart(2, '0');
                                        const seconds = String(taskDateTime.getSeconds())
                                            .padStart(2, '0');
                                        const Task_status = task.SubTaskStatus;

                                        let badgeHtml = '';
                                        if (Task_status === "Pending") {
                                            badgeHtml =
                                                '<div class="Change_status_work badge rounded-pill bg-label-danger me-1 text-decoration-none">' +
                                                '<span>' + Task_status + '</span></div>';
                                        } else if (Task_status === "Completed") {
                                            badgeHtml =
                                                '<div class="Change_status_work badge rounded-pill bg-label-success me-1 text-decoration-none">' +
                                                '<span>' + Task_status + '</span></div>';
                                        } else {
                                            badgeHtml =
                                                '<div class="Change_status_work badge rounded-pill bg-label-warning me-1 text-decoration-none">' +
                                                '<span>' + Task_status + '</span></div>';
                                        }

                                        const formattedDate =
                                            `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;

                                        return (
                                            '<tr>' +
                                            '<td>' + (subIndex + 1) + '</td>' +
                                            '<td>' + task.name + '</td>' +
                                            '<td>' + task.info + '</td>' +
                                            '<td>' + formattedDate + '</td>' +
                                            '<td>' + task.deadlineTime + '</td>' +
                                            '<td>' + badgeHtml + '</td>' +
                                            '</tr>'
                                        );
                                    }).join('');
                                }).join('') || '<tr><td colspan="5">Not available</td></tr>') +
                                '</tbody>' +
                                '</table>'
                            );
                        }
                    } else {
                        headers = `
                                        <tr>
                                            <th></th>
                                            <th>S.No</th>
                                            <th>User Name</th>
                                            <th>Project Name</th>
                                            <th>Platform</th>
                                            <th>Final Date</th>
                                            <th>Final Time</th>
                                            <th>Information</th>
                                            <th>Status</th>
                                        </tr>`;
                        columns = [{
                                data: ''
                            },
                            {
                                data: 'id'
                            },
                            {
                                data: 'Name'
                            },
                            {
                                data: 'ProjectName'
                            },
                            {
                                data: 'Platform'
                            },
                            {
                                data: 'DeadlineDate'
                            },
                            {
                                data: 'DeadlineTime'
                            },
                            {
                                data: 'Information'
                            },
                            {
                                data: 'SubTaskStatus'
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
                                    return '<span class="text-heading">' + full['Name'] + '</span>';
                                }
                            },
                            {
                                targets: 3,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['ProjectName'] +
                                        '</span>';
                                }
                            },
                            {
                                targets: 4,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['Platform'] + '</span>';
                                }
                            },
                            {
                                targets: 5,
                                render: function(data, type, full) {
                                    const deadlineDateParts = full['DeadlineDate'].split('-');
                                    const formattedDate =
                                        `${String(deadlineDateParts[2]).padStart(2, '0')}-${String(deadlineDateParts[1]).padStart(2, '0')}-${deadlineDateParts[0]}`;

                                    return '<span class="text-heading">' + formattedDate + '</span>';
                                    //return '<span class="text-heading">' + full['DeadlineDate'] + '</span>';
                                }
                            },
                            {
                                targets: 6,
                                render: function(data, type, full) {
                                    const deadlineTimeParts = full['DeadlineTime'].split(':');
                                    let hours = parseInt(deadlineTimeParts[0], 10);
                                    const minutes = String(deadlineTimeParts[1]).padStart(2, '0');
                                    const period = hours >= 12 ? 'pm' : 'am';
                                    hours = hours % 12 || 12; // Convert to 12-hour format

                                    const formattedTime = `${hours}:${minutes} ${period}`;

                                    return '<span class="text-heading">' + formattedTime + '</span>';
                                    //  return '<span class="text-heading">' + full['DeadlineTime'] + '</span>';
                                }
                            },
                            {
                                targets: 7,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['Information'] +
                                        '</span>';
                                }
                            },
                            {
                                targets: 8,
                                render: function(data, type, full) {
                                    return '<span class="text-heading">' + full['SubTaskStatus'] +
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

                                        return data ? $('<table class="table"/><tbody />').append(
                                            data) : false;
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


                }


                var jsonData = <?php echo $json; ?>;

                //console.log('log',jsonData);
                $(document).ready(function() {

                    if (jsonData && jsonData.data && jsonData.data.length > 0) {
                        initializeDataTable('.datatables-projectReport', jsonData.data, 'employee');
                    } else {

                        console.log("No data available to display.");
                    }
                });

            });
        </script>

        <!-- / Content -->
        <?php include('include/footer.php'); ?>