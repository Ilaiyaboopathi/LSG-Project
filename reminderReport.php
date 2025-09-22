<?php include('include/head.php'); ?>

<?php require 'data/dbconfig.php';

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $sql = "SELECT * FROM reminder_notification WHERE assignedBy='$name' ";
    $result = $conn->query($sql);
    
   


    $data = array();
    
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // Add each row to the data array
        $data[] = array(
    
          'id' => $row['id'],
          'name' => $row['name'],
          'assignedBy' => $row['assignedBy'],
          'duration' => $row['duration'],
          'date' => $row['date'],
          'recurring' => $row['recurring'],
         
        );
      }
    }
    
    $response = array('data' => $data);
    $json = json_encode($response, JSON_PRETTY_PRINT);
    }
    else {
        // If no role is set, initialize $json as an empty array
        $json = json_encode(array('data' => []), JSON_PRETTY_PRINT);
    }
?>


<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">

            <form id="reportReminderForm">
                <div class="">
                    <div class="col-md-12">
                        <div class="card mb-9">
                            <div class="card-body">
                                <div class="row gy-5">
                                    <!-- Left Column Start -->
                                    <div class="col-md-6">
                                        <input type="hidden" id="hiddenId" value="">

                                        <div class="row mb-3">
                                            <h5 class="Address_ship_headline">Reminder Report</h5>
                                        </div>



                                        <?php
                                        // Fetch names from the event table
                                        $sql2 = "SELECT assignment_name FROM reminder";
                                        $result2 = $conn->query($sql2);


                                        // Initialize an associative array to hold unique names
                                        $options = [];

                                        // Add event names to the options
                                        if ($result2->num_rows > 0) {
                                            while ($row = $result2->fetch_assoc()) {
                                                $options[$row['assignment_name']] = htmlspecialchars($row['assignment_name']);
                                            }
                                        }



                                        // Sort the unique options alphabetically
                                        $uniqueOptions = array_values($options);
                                        sort($uniqueOptions);

                                        // Build the dropdown
                                        echo '<div class="form-floating form-floating-outline mb-5 mt-3">';
                                        echo '<select id="selectAssignmentName" name="selectAssignmentName" required class="select2 form-select" data-allow-clear="true">';
                                        echo '<option value="" disabled selected>Select</option>';
                                        if (count($uniqueOptions) > 0) {
                                            foreach ($uniqueOptions as $name) {
                                                echo '<option value="' . $name . '">' . $name . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No names found.</option>';
                                        }
                                        echo '</select>';
                                        echo '<label for="selectAssignmentName">Choose - Assignment Name *</label>';
                                        echo '</div>';
                                        ?>

                                        <?php
                                        // Fetch names from the event table
                                        $sql2 = "SELECT assignedTo FROM recurring_queued";
                                        $result = $conn->query($sql2);


                                        // Initialize an associative array to hold unique names
                                        $root = [];

                                        // Add event names to the options
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $root[$row['assignedTo']] = htmlspecialchars($row['assignedTo']);
                                            }
                                        }



                                        // Sort the unique options alphabetically
                                        $uniqueOptions = array_values($root);
                                        sort($uniqueOptions);

                                        // Build the dropdown
                                        echo '<div class="form-floating form-floating-outline mb-5 mt-3">';
                                        echo '<select id="selectEmpName" name="selectEmpName"  class="select2 form-select" data-allow-clear="true" required>';
                                        echo '<option value="">All</option>';
                                        if (count($uniqueOptions) > 0) {
                                            foreach ($uniqueOptions as $name) {
                                                echo '<option value="' . $name . '">' . $name . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No names found.</option>';
                                        }
                                        echo '</select>';
                                        echo '<label for="selectEmpName">Choose - User Name *</label>';
                                        echo '</div>';
                                        ?>




                                    </div>



                                </div>

                                <!-- Buttons -->
                                <div class="col-12 d-flex justify-content-end mb-6">
                                    <button type="reset"
                                        class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                    <button type="submit" id="submit" value="reminReport"
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
                                <?php if ($ReminderExcel === 'Enable'): ?>
                                <button id="exportToExcel" class="btn btn-primary" style="margin: 20px;">Export to
                                    Excel</button>
                                <?php endif; ?>
                                <table class="datatables-reminderReport table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>S.No</th>
                                            <th>Assignment Name</th>
                                            <th>To Name</th>
                                            <th>Duration</th>
                                            <th>Date</th>
                                            <th>Recurring</th>

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
                document.getElementById('reportReminderForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const selectAssignmentName = document.getElementById('selectAssignmentName').value;
                    const selectEmpName = document.getElementById('selectEmpName').value;

                    const formData = new URLSearchParams({
                        'selectAssignmentName': selectAssignmentName,
                        'selectEmpName': selectEmpName,
                        'submit': 'reminReport' // Ensure this is a string
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
                            // Log the entire response object
                            console.log('Response from server:', result);

                            if (result.status === 'success') {
                                console.log(result.data);
                                initializeDataTable('.datatables-reminderReport', result.data);
                            } else {
                                console.error('Error:', result.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });


                // document.getElementById('exportToExcel').addEventListener('click', function() {
                //     const dataTable = $('.datatables-reminderReport').DataTable();

                //     // Get all data from DataTable (not just the current page)
                //     const allData = dataTable.rows().data().toArray();

                //     // Log data to see if it's being retrieved correctly
                //     // console.log(allData); 

                //     // Prepare headers
                //     const headers = [
                //         'S.No', 'Assignment Name', 'To Name', 'Duration', 'Date', 'Recurring'

                //     ];

                //     // Prepare data for export
                //     const exportData = allData.map((row, index) => {
                //         console.log(row); // Log each row to verify its structure
                //         return {
                //             'S.No': index + 1,
                //             'Assignment Name': row.name || '',
                //             'To Name': row.assignedBy || '',
                //             'Duration': row.duration || '',
                //             'Date': row.date || '',
                //             'Recurring': row.recurring == 1 ? 'Yes' : 'No'
                //             //'Is Enabled': row.isenable == 1 ? 'Yes' : 'No'
                //         };
                //     });


                //     // console.log(exportData); 

                //     if (exportData.length > 0) {
                //         const worksheet = XLSX.utils.json_to_sheet(exportData, {
                //             header: headers
                //         });
                //         const workbook = XLSX.utils.book_new();
                //         XLSX.utils.book_append_sheet(workbook, worksheet, 'Report');

                //         // Get the current date and format it as dd-mm-yyyy
                //         let currentDate = new Date();
                //         let day = String(currentDate.getDate()).padStart(2, '0');
                //         let month = String(currentDate.getMonth() + 1).padStart(2,
                //         '0'); // Months are 0-indexed
                //         let year = currentDate.getFullYear();
                //         let formattedDate = `${day}-${month}-${year}`;

                //         // Get the assignment name
                //         const AssName = exportData.length > 0 ? exportData[0]['Assignment Name'] :
                //             'Assignment Name';

                //         // Append the formatted date to the filename
                //         const fileName = `${AssName}-Reminder Report-${formattedDate}.xlsx`;

                //         // Export to Excel
                //         XLSX.writeFile(workbook, fileName);
                //     } else {
                //         alert("No data available to export.");
                //     }

                // });


                function initializeDataTable(selector, data) {
                    $('#noDatafound').hide();
                    $(selector).DataTable().clear()
                .destroy(); // Clear and destroy any existing DataTable instance

                    const columns = [{
                            data: ''
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'assignedBy'
                        },
                        {
                            data: 'duration'
                        },
                        {
                            data: 'date'
                        },
                        {
                            data: 'recurring'
                        },


                    ];

                    const columnDefs = [{
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
                                return '<span class="text-heading">' + full['name'] + '</span>';
                            }
                        },
                        {
                            targets: 3,
                            render: function(data, type, full) {
                                return '<span class="text-heading">' + full['assignedBy'] + '</span>';
                            }
                        },
                        {
                            targets: 4,
                            render: function(data, type, full) {
                                const duration = full['duration'];
                                let displayDuration = '';

                                if (duration < 60) {
                                    displayDuration = `${duration} min`;
                                } else if (duration < 1440) { // less than 24 hours
                                    const hours = Math.floor(duration / 60);
                                    const minutes = duration % 60;
                                    displayDuration = `${hours} hr${hours > 1 ? 's' : ''} `;
                                } else {
                                    const days = Math.floor(duration / 1440);
                                    const hours = Math.floor((duration % 1440) / 60);
                                    displayDuration = `${days} day${days > 1 ? 's' : ''}`;
                                }

                                return '<span class="text-heading">' + displayDuration + '</span>';
                            }
                        },
                        {
                            targets: 5,
                            render: function(data, type, full) {
                                // Get the createdOn date
                                const createdOn = new Date(full['date']);

                                // Format the date to DD-MM-YYYY
                                const day = String(createdOn.getDate()).padStart(2, '0');
                                const month = String(createdOn.getMonth() + 1).padStart(2,
                                '0'); // Months are zero-based
                                const year = createdOn.getFullYear();

                                // Format the time to HH:MM AM/PM
                                let hours = createdOn.getHours();
                                const minutes = String(createdOn.getMinutes()).padStart(2, '0');
                                const period = hours >= 12 ? 'pm' : 'am';
                                hours = hours % 12 || 12; // Convert to 12-hour format

                                // Combine the formatted date and time
                                const formattedDate =
                                    `${day}-${month}-${year} ${hours}:${minutes} ${period}`;

                                return '<span class="text-heading">' + formattedDate + '</span>';
                            }
                        },
                        {
                            targets: 6,

                            render: function(data, type, full) {
                                var recurring = full['recurring'];
                                return '<span class="text-heading">' + (recurring == 1 ? 'Yes' : 'No') +
                                    '</span>';
                            }
                        },

                    ];

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
                                            '" data-dt-column="' + col.columnIndex + '">' +
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


                var jsonData = <?php echo $json; ?>;

                //console.log('log',jsonData);
                $(document).ready(function() {

                    if (jsonData && jsonData.data && jsonData.data.length > 0) {
                        initializeDataTable('.datatables-reminderReport', jsonData.data);
                    } else {

                        console.log("No data available to display.");
                    }
                });
            });
            </script>