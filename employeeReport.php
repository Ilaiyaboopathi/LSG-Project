<?php include('include/head.php'); ?>

<?php require 'data/dbconfig.php'; 


if (isset($_GET['role'])) {

$sql = "SELECT * FROM employee WHERE role='admin' ";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    // Add each row to the data array
    $data[] = array(

      'id' => $row['id'],
      'name' => $row['name'],
      'email' => $row['email'],
      'designation' => $row['designation'],
      'mobile' => $row['mobile'],
      'role' => $row['role'],
      'isenable' => $row['isenable'],
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
<?php if ($ReportEmployeeAccess === 'Enable'): ?>
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
                                <div class="row gy-5">
                                    <!-- Left Column Start -->
                                    <div class="col-md-6">
                                        <input type="hidden" id="hiddenId" value="">

                                        <div class="row mb-3">
                                            <h5 class="Address_ship_headline">User Report</h5>
                                        </div>

                                        <div class="form-floating form-floating-outline mb-5 mt-3">
                                            <select id="selectEmployee" class="select2 form-select"
                                                data-allow-clear="true" required>
                                                <option value="">Select</option>
                                                <option value="admin"
                                                    <?php echo (isset($_GET['role']) && $_GET['role'] == 'admin') ? 'selected' : ''; ?>>
                                                    Admin</option>
                                                <option value="user" 
                                                 <?php echo (isset($_GET['role']) && $_GET['role'] == 'employee') ? 'selected' : ''; ?>>
                                                User</option>
                                                  <option value="client" 
                                                 <?php echo (isset($_GET['role']) && $_GET['role'] == 'client') ? 'selected' : ''; ?>>
                                                Client</option>


                                            </select>
                                            <label for="selectEmployee">Choose Admin / User *</label>
                                        </div>

                                        <div class="form-floating form-floating-outline mb-5 mt-3">
                                            <select id="SelectedEmployee" class="select2 form-select" required
                                                data-allow-clear="true">

                                            </select>
                                            <label for="SelectedEmployee">Choose Name *</label>
                                        </div>



                                    </div>
                                    <!-- Left Column End -->

                                    <!-- Date and Time Start -->
                                    <!-- <div class="col-md-6 mt-12">
                                        <div class="form-floating form-floating-outline mb-5 mt-9">
                                            <input type="text" readonly="readonly" id="from_date"  placeholder="DD-MM-YYYY" class="form-control flatpickr_date">
                                            <label for="from_date">From Date</label>
                                        </div>

                                        <div class="form-floating form-floating-outline mb-3">
                                            <input type="text" readonly="readonly" id="to_date"  placeholder="DD-MM-YYYY" class="form-control flatpickr_date">
                                            <label for="to_date">To Date</label>
                                        </div>
                                    </div>-->

                                </div>

                                <!-- Buttons -->
                                <div class="col-12 d-flex justify-content-end mb-6">
                                    <button type="reset"
                                        class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                    <button type="submit" id="submit" value="empReport"
                                        class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>



        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-datatable table-responsive">
                            <?php if ($UserExcel === 'Enable'): ?>
                            <button id="exportToExcel" class="btn btn-primary" style="margin: 20px;">Export to
                                Excel</button>
                            <?php endif; ?>

                            <table class="datatables-empreport table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>S.No</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Designation</th>
                                        <th>Department</th>
                                        <th>Mobile</th>
                                        <th>Role</th>
                                        <th>Active</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <div id="noDatafound" style="padding: 20px;background: aliceblue;text-align: center;">
                                <span>No data fetch. Please Select and View....</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

        <!-- / Content -->
        <?php include('include/footer.php'); ?>
        <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission handling
            document.getElementById('reportForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const selectEmployee = document.getElementById('selectEmployee').value;
                const SelectedEmployee = document.getElementById('SelectedEmployee').value;
                const submit = document.getElementById('submit').value;

                const formData = new URLSearchParams({
                    'selectEmployee': selectEmployee,
                    'SelectedEmployee': SelectedEmployee,
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
                            initializeDataTable('.datatables-empreport', result.data);
                        } else {
                            console.error('Error:', result.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            function initializeDataTable(selector, data) {
                $('#noDatafound').hide();
                $(selector).DataTable().clear().destroy(); // Clear and destroy any existing DataTable instance

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
                        data: 'email'
                    },
                    {
                        data: 'designation'
                    },
                    {
                        data: 'department'
                    },
                    {
                        data: 'mobile'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'isenable'
                    }
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
                            return '<span class="text-heading">' + full['email'] + '</span>';
                        }
                    },
                    {
                        targets: 4,
                        render: function(data, type, full) {
                            return '<span class="text-heading">' + full['designation'] + '</span>';
                        }
                    },
                    {
                        targets: 5,
                        render: function(data, type, full) {
                            return '<span class="text-heading">' + full['department'] + '</span>';
                        }
                    },
                    {
                        targets: 6,
                        render: function(data, type, full) {
                            return '<span class="text-heading">' + full['mobile'] + '</span>';
                        }
                    },
                    {
                        targets: 7,
                        render: function(data, type, full) {
                            return '<span class="text-heading">' + full['role'] + '</span>';
                        }
                    },
                    {
                        targets: 8,
                        render: function(data, type, full) {
                            var isenable = full['isenable'];
                            return '<span class="text-heading">' + (isenable == 1 ? 'Yes' : 'No') +
                                '</span>';
                        }
                    }
                ];

                $(selector).DataTable({
                    data: data,
                    columns: columns,
                    columnDefs: columnDefs,
                    order: [
                        [1, 'desc']
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

                                return data ? $('<table class="table"/><tbody />').append(data) :
                                    false;
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


            $(document).ready(function() {
                if (jsonData && jsonData.data && jsonData.data.length > 0) {
                    initializeDataTable('.datatables-empreport', jsonData.data);
                } else {

                    console.log("No data available to display.");
                }
            });

            // document.getElementById('exportToExcel').addEventListener('click', function() {
            //     const dataTable = $('.datatables-empreport').DataTable();

            //     // Get all data from DataTable (not just the current page)
            //     const allData = dataTable.rows().data().toArray();

            //     // Log data to see if it's being retrieved correctly
            //     // console.log(allData); 

            //     // Prepare headers
            //     const headers = [
            //         'S.No', 'Name', 'Email', 'Designation', 'Department', 'Mobile', 'Role',
            //         'Is Enabled'
            //     ];

            //     // Prepare data for export
            //     const exportData = allData.map((row, index) => {
            //         console.log(row); // Log each row to verify its structure
            //         return {
            //             'S.No': index + 1,
            //             'Name': row.name || '',
            //             'Email': row.email || '',
            //             'Designation': row.designation || '',
            //             'Department': row.department || '',
            //             'Mobile': row.mobile || '',
            //             'Role': row.role || '',
            //             'Is Enabled': row.isenable == 1 ? 'Yes' : 'No'
            //         };
            //     });

            //     // Check if exportData has any entries
            //     //console.log(exportData); 

            //     // Convert to worksheet and workbook only if exportData has data
            //     if (exportData.length > 0) {
            //         const worksheet = XLSX.utils.json_to_sheet(exportData, {
            //             header: headers
            //         });
            //         const workbook = XLSX.utils.book_new();
            //         XLSX.utils.book_append_sheet(workbook, worksheet, 'Report');


            //         // Export to Excel
            //         XLSX.writeFile(workbook, 'User Report.xlsx');
            //     } else {
            //         alert("No data available to export."); // Alert if there's no data
            //     }
            // });

        });
        </script>