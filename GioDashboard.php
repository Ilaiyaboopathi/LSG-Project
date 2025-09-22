    <?php include('include/head.php');

require 'data/dbconfig.php';

    $result = $conn->query("SELECT COUNT(*) as employee_count FROM employee");
    $row = $result->fetch_assoc();
    $employeeCount = $row['employee_count'];

    $result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE isenable = 1");
    $row = $result->fetch_assoc();
    $employeeActiveCount = $row['employee_count'];

    $result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE isenable = 0");
    $row = $result->fetch_assoc();
    $employeeInActiveCount = $row['employee_count'];




    $currentMonth = date('m'); // Get current month
    $currentYear = date('Y');  // Get current year

    $queryCurrentMonth = "SELECT COUNT(*) as reminder_count 
                      FROM reminder_notification 
                      WHERE assignedBy LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%' 
                      AND MONTH(createdOn) = $currentMonth 
                      AND YEAR(createdOn) = $currentYear";

    $result = $conn->query($queryCurrentMonth);
    $row = $result->fetch_assoc();
    $currentMonthReminderCount = $row['reminder_count'];

    // Last Month Reminder Count
    $lastMonth = date('m', strtotime('first day of last month')); // Get last month
    $lastMonthYear = date('Y', strtotime('first day of last month'));  // Get last month year

    $queryLastMonth = "SELECT COUNT(*) as reminder_count 
                   FROM reminder_notification 
                   WHERE assignedBy LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%' 
                   AND MONTH(createdOn) = $lastMonth 
                   AND YEAR(createdOn) = $lastMonthYear";

    $result = $conn->query($queryLastMonth);
    $row = $result->fetch_assoc();
    $lastMonthReminderCount = $row['reminder_count'];

    // Calculate Percentage Change
    $percentageChange = 0; // Default in case of no reminders last month
    if ($lastMonthReminderCount > 0) {
        $percentageChange = (($currentMonthReminderCount - $lastMonthReminderCount) / $lastMonthReminderCount) * 100;
    }

    // Round the percentage to one decimal point for better readability
    $percentageChange = round($percentageChange, 1);



    $result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE role = 'admin'");
    $row = $result->fetch_assoc();
    $adminCount = $row['employee_count'];


    $result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE role = 'admin' AND isenable = 1");
    $row = $result->fetch_assoc();
    $adminActiveCount = $row['employee_count'];


    $result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE role = 'admin' AND isenable = 0");
    $row = $result->fetch_assoc();
    $adminInActiveCount = $row['employee_count'];


    $result = $conn->query("SELECT COUNT(*) as doc_count FROM docs_upload WHERE tagged_emp like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
    $row = $result->fetch_assoc();
    $docCount = $row['doc_count'];






  

    ?>


    <div class="content-wrapper dashboard_body">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row g-6">
                <!-- Sales Summery Card -->
                <div class="col-md-12 col-xxl-8">
                    <div class="card h-100 parent_sort_section">
                        <div class="card-header justify-content-between">

                            <div class="row">
                                <div class="col-xxl-8 col-md-8 col-sm-12">
                                    <div class="card-title mb-0">
                                        <h4 class="card-title mb-4">Great work, <span class="fw-bold">MBW Admin !</span> 🎉</h4>
                                        <div class="d-flex align-items-left justify-content-start gap-3">
                                            <div class="TotalText_Text text-center m-0">
                                                <h6 class="mb-0 fw-normal">Sales Task Summary</h6>
                                            </div>
                                            <div class="TotalText_Text text-center m-0">
                                                <h6 class="mb-0 fw-normal">Total Task: <b>200</b></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-md-4 col-sm-12">
                                    <div class="dropdown">
                                        <div class="sort_btn_section d-flex align-items-center gap-3" id="sales_summery_task">
                                            <div class="sorted_icon">
                                                <i class="text-heading ri-filter-2-line ri-24px"></i>
                                            </div>
                                            <div class="sorted_text">
                                                <p class="m-0">14-05-24 to 14-58-12</p>
                                                <hr class="m-0">
                                                <p class="m-0">Praveen Kumar</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="shipmentStatisticsChart"></div>
                        </div>
                        <div class="sort_option_in_dash d-flex justify-content-center align-items-center" id="sales_summery_task_filler_content">
                            <div class="sort_div_content_section row p-10">
                                <div class="closeIcon col-12 d-flex justify-content-end">
                                    <button class="btn close_sales_overview_btn" id="close_sales_overview_btn_sales_summery">
                                        <i class="ri-close-line ri-24px"></i>
                                    </button>
                                </div>
                                <div class="headlineOfContent col-12">
                                    <h3 class="mt-3 me-2">Sort by Name & Date Range</h3>
                                </div>
                                <div class="sortOptions col-12">
                                    <div class="row">
                                        <div class="col-md-12 col-12 mb-6">
                                            <div class="position-relative" data-select2-id="8"><select name="re_status3" class="select2 form-select form-select-lg  select2-hidden-accessible" data-allow-clear="true" required="" data-select2-id="4" tabindex="-1" aria-hidden="true">
                                                    <option value="" data-select2-id="6">Select Status</option>
                                                    <option value="Completed" data-select2-id="14">Completed</option>
                                                    <option value="Extended" data-select2-id="15">Extended</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 mb-6">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                    type="text"
                                                    style="color: #fff;"
                                                    class="form-control flatpickr-range"
                                                    placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                                <label for="flatpickr-range">Range Picker</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 mb-6 d-flex justify-content-end gap-3">
                                            <button type="button" class="btn btn-outline-primary sortNow_button">
                                                Use Default<i class="ri-filter-2-line ri-24px"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-primary sortNow_button">
                                                Filler Now <i class="ri-filter-2-line ri-24px"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Sales Summery Card -->

                <!-- Active Inactive Users Card -->
                <div class="col-xxl-4 col-sm-6">
                    <div class="card h-100 user_and_admin_card">
                        <div class="card-body active_card user_inactive_active open">
                            <div class="UserStatusText">
                                <div class="user_headline_Text text-center">
                                    <h5 class="card-title mb-4">Active Inactive Users</h5>
                                </div>
                                <div class="TotalText_Text text-center">
                                    <h6 class="mb-0 fw-normal">Total Users: <?php echo $employeeCount; ?></h6>
                                </div>
                            </div>
                            <div class="CanvasDiv">
                                <canvas id="UserActiveInactiveContainer" width="200" height="200"></canvas>
                            </div>
                            <div class="card card-border-shadow-success mt-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2 justify-content-between">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar me-4">
                                                <span class="avatar-initial rounded-3 bg-label-success"><img class="svg_dash_icons" src="assets/img/illustrations/svg_dash/Reminder.svg">
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-normal">Total Reminder</h6>
                                                <p class="mb-0">
                                                    <span class="me-1 fw-medium"><?php echo ($percentageChange > 0 ? '+' : '') . $percentageChange; ?>%</span>
                                                    <small class="text-muted">than last month</small>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <h4 class="mb-0"><?php echo $currentMonthReminderCount; ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body active_card admin_inactive_active">
                            <div class="UserStatusText">
                                <div class="user_headline_Text text-center">
                                    <h5 class="card-title mb-4">Active Inactive Admin</h5>
                                </div>
                                <div class="TotalText_Text text-center">
                                    <h6 class="mb-0 fw-normal">Total Admins: <?php echo $adminCount; ?></h6>
                                </div>
                            </div>
                            <div class="CanvasDiv">
                                <canvas id="UserActiveInactiveAdmins" width="200" height="200"></canvas>
                            </div>
                            <div class="card card-border-shadow-success mt-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2 justify-content-between">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar me-4">
                                                <span class="avatar-initial rounded-3 bg-label-success"><img class="svg_dash_icons" src="assets/img/illustrations/svg_dash/Document.svg">
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-normal">Total Documents</h6>
                                                <!-- <p class="mb-0">
                                                    <span class="me-1 fw-medium">-9.5%</span>
                                                    <small class="text-muted">than last month</small>
                                                </p> -->
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <h4 class="mb-0"><?php echo $docCount; ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="left_user_change_btn" onclick="change_user_to_admin();"><i class="ri-arrow-left-line"></i></button>
                        <button class="right_user_change_btn" onclick="change_user_to_admin();"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <!--/ Active Inactive Users Card -->

                <!-- Department Progress Tracker Card -->
                <div class="col-12 col-xxl-8">
                    <div class="card h-100">
                        <div class="card-header justify-content-between">
                            <div class="row">
                                <div class="col-xxl-8 col-md-8  col-sm-12">
                                    <div class="card-title mb-0">
                                        <h5 class="m-0 me-2 text-left">Department Progress Tracker</h5>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-md-4 col-sm-12">
                                    <div class="dropdown">
                                        <div class="sort_btn_section d-flex align-items-center gap-3" id="Department_task_report">
                                            <div class="sorted_icon">
                                                <i class="text-heading ri-filter-2-line ri-24px"></i>
                                            </div>
                                            <div class="sorted_text">
                                                <p class="m-0">14-05-24 to 14-58-12</p>
                                                <hr class="m-0">
                                                <p class="m-0">Praveen Kumar</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <div id="horizontalBarChart"></div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-around align-items-center">
                                <div>
                                    <div class="d-flex align-items-baseline">
                                        <span class="text-primary me-2"><i class="ri-circle-fill ri-12px"></i></span>
                                        <div>
                                            <p class="mb-0">Sales Overview</p>
                                            <h5 class="mb-0">35%</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-baseline my-10">
                                        <span class="text-success me-2"><i class="ri-circle-fill ri-12px"></i></span>
                                        <div>
                                            <p class="mb-0">Web Designing</p>
                                            <h5 class="mb-0">14%</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-baseline">
                                        <span class="text-danger me-2"><i class="ri-circle-fill ri-12px"></i></span>
                                        <div>
                                            <p class="mb-0">Web Development</p>
                                            <h5 class="mb-0">10%</h5>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex align-items-baseline">
                                        <span class="text-info me-2"><i class="ri-circle-fill ri-12px"></i></span>
                                        <div>
                                            <p class="mb-0">Digital Marketing</p>
                                            <h5 class="mb-0">20%</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-baseline my-10">
                                        <span class="text-secondary me-2"><i class="ri-circle-fill ri-12px"></i></span>
                                        <div>
                                            <p class="mb-0">Accounts</p>
                                            <h5 class="mb-0">12%</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-baseline">
                                        <span class="text-warning me-2"><i class="ri-circle-fill ri-12px"></i></span>
                                        <div>
                                            <p class="mb-0">Admin</p>
                                            <h5 class="mb-0">9%</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sort_option_in_dash d-flex justify-content-center align-items-center" id="Department_task_report_filler_content">
                            <div class="sort_div_content_section row p-10">
                                <div class="closeIcon col-12 d-flex justify-content-end">
                                    <button class="btn close_sales_overview_btn" id="close_department_filter_content">
                                        <i class="ri-close-line ri-24px"></i>
                                    </button>
                                </div>
                                <div class="headlineOfContent col-12">
                                    <h3 class="mt-3 me-2">Sort by Name & Date Range</h3>
                                </div>
                                <div class="sortOptions col-12">
                                    <div class="row">
                                        <div class="col-md-12 col-12 mb-6">
                                            <div class="position-relative" data-select2-id="8"><select name="re_status2" class="select2 form-select form-select-lg  select2-hidden-accessible" data-allow-clear="true" required="" data-select2-id="4" tabindex="-1" aria-hidden="true">
                                                    <option value="" data-select2-id="6">Select Status</option>
                                                    <option value="Completed" data-select2-id="14">Completed</option>
                                                    <option value="Extended" data-select2-id="15">Extended</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 mb-6">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                    type="text"
                                                    style="color: #fff;"
                                                    class="form-control flatpickr-range"
                                                    placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                                <label for="flatpickr-range">Range Picker</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 mb-6 d-flex justify-content-end gap-3">
                                            <button type="button" class="btn btn-outline-primary sortNow_button">
                                                Use Default<i class="ri-filter-2-line ri-24px"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-primary sortNow_button">
                                                Filler Now <i class="ri-filter-2-line ri-24px"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Department Progress Tracker Card -->

                <!-- The Login Scorecard Card -->
                <div class="col-xxl-4 col-sm-6">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Online Users</h5>
                            </div>
                        </div>

                        <!-- Search Box -->
                        <div class="px-5 py-2">
                            <input type="text" id="searchBox" class="form-control" placeholder="Search by name...">
                        </div>

                        <!-- <div class="px-5 py-4 border border-start-0 border-end-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fs-xsmall text-uppercase fw-normal">User Name</h6>
                                <h6 class="mb-0 fs-xsmall text-uppercase fw-normal">Active Days</h6>
                            </div>
                        </div> -->

                        <div class="card-body pt-5 dashboard_all_user" id="userList">

                        <?php
require 'data/dbconfig.php';

// Query to get employee data
$sql = "SELECT name, designation, isOnline FROM employee ORDER BY name ASC";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['name'];
        $designation = $row['designation'];
        $isOnline = $row['isOnline'];

        // Determine avatar background color based on online status
        $avatarStatus = $isOnline ? 'avatar-online' : 'avatar-offline'; 

        // Display employee info in HTML
        echo '
         <div class="d-flex justify-content-between align-items-center mb-6 dash-user-item">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-online me-4">
                <span class="avatar-initial rounded-circle rs_bg-label-success">' . strtoupper(substr($name, 0, 3)) . '</span>
            </div>
            <div>
                <div>
                    <h6 class="mb-0 text-truncate">' . $name . '</h6>
                    <small class="text-truncate">' . $designation . '</small>
                </div>
            </div>
            </div>
        </div>';
    }
} 
?>

                          
                        </div>
                    </div>
                </div>
                <!-- The Login Scorecard Card -->



                <!-- Sales Appointments Overview Card -->
                <div class="col-xxl-8 order-5 order-xxl-0">
                    <div class="parent_sort_section card h-100">
                        <div class="card-header justify-content-between">
                            <div class="row">
                                <div class="col-xxl-8 col-md-8  col-sm-12">
                                    <div class="card-title mb-0">
                                        <h5 class="m-0 me-2">Sales Appointments Overview</h5>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-md-4 col-sm-12">
                                    <div class="dropdown">
                                        <div class="sort_btn_section d-flex align-items-center gap-3" id="sales_status_task">
                                            <div class="sorted_icon">
                                                <i class="text-heading ri-filter-2-line ri-24px"></i>
                                            </div>


                                            <?php
                                            $currentDate = date('d-m-Y');

                                            // Get the current day, month, and year
                                            $currentDay = date('d'); // Current day of the month (e.g., 05)
                                            $currentMonth = date('m'); // Current month (e.g., 12)
                                            $currentYear = date('Y'); // Current year (e.g., 2024)

                                            // Calculate the start date for the previous month (e.g., 05-11-2024)
                                            $startDate = date('d-m-Y', strtotime('last month', strtotime($currentYear . '-' . $currentMonth . '-' . $currentDay)));

                                            // Calculate the end date for the current month (e.g., 05-12-2024)
                                            $endDate = date('d-m-Y', strtotime($currentYear . '-' . $currentMonth . '-' . $currentDay));

                                            // Concatenate the date range into a single variable
                                            $dateRange = $startDate . " to " . $endDate;

                                            // Output the dynamic date range (e.g., "05-11-2024 to 05-12-2024")
                                            $dateRange = $startDate . " to " . $endDate;
                                            ?>
                                            <div class="sorted_text">
                                                <p class="m-0" id="dateRangeDisplay"><?php echo ($dateRange); ?></p>
                                                <hr class="m-0">
                                                <p class="m-0" id="employeeNameDisplay"><?php echo ($JWT_adminName); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php
                        // Get the current day, month, and year
                        $currentDay = date('d'); // Current day of the month (e.g., 05)
                        $currentMonth = date('m'); // Current month (e.g., 12)
                        $currentYear = date('Y'); // Current year (e.g., 2024)

                        // Calculate the start date for the previous month (e.g., 05-11-2024)
                        $startDatetask = date('Y-m-d', strtotime('last month', strtotime($currentYear . '-' . $currentMonth . '-' . $currentDay)));

                        // Calculate the end date for the current month (e.g., 05-12-2024)
                        $endDatetask = date('Y-m-d', strtotime($currentYear . '-' . $currentMonth . '-' . $currentDay));

                        // SQL query to fetch task descriptions based on the date range and addedBy condition
                        $sql_ = "SELECT * FROM task_descriptions WHERE `date` BETWEEN '$startDatetask' AND '$endDatetask' AND addedBy LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%'";
                        $result = $conn->query($sql_);

                        // Initialize counters for each status
                        $pendingCount = 0;
                        $followUpCount = 0;
                        $completedCount = 0;
                        $notInterestedCount = 0;
                        $totalCount = 0;

                        // Loop through the results to count the tasks based on their status
                        while ($row = $result->fetch_assoc()) {
                            $status = $row['status']; // Assuming 'status' column contains the task status

                            // Increment the appropriate counter based on the status
                            switch ($status) {
                                case 'Processing':
                                    $pendingCount++;
                                    break;
                                case 'Follow Up':
                                    $followUpCount++;
                                    break;
                                case 'Completed':
                                    $completedCount++;
                                    break;
                                case 'Not Interested':
                                    $notInterestedCount++;
                                    break;
                            }
                            $totalCount++;
                        }

                        // Calculate percentages
                        $pendingPercentage = ($totalCount > 0) ? ($pendingCount / $totalCount) * 100 : 0;
                        $followUpPercentage = ($totalCount > 0) ? ($followUpCount / $totalCount) * 100 : 0;
                        $completedPercentage = ($totalCount > 0) ? ($completedCount / $totalCount) * 100 : 0;
                        $notInterestedPercentage = ($totalCount > 0) ? ($notInterestedCount / $totalCount) * 100 : 0;

                        // Output the counts and percentages
                        // echo "Pending Task: " . $pendingCount . " (" . number_format($pendingPercentage, 1) . "%)<br>";
                        // echo "Follow Up: " . $followUpCount . " (" . number_format($followUpPercentage, 1) . "%)<br>";
                        // echo "Completed: " . $completedCount . " (" . number_format($completedPercentage, 1) . "%)<br>";
                        // echo "Not Interested: " . $notInterestedCount . " (" . number_format($notInterestedPercentage, 1) . "%)<br>";
                        ?>






                        <div class="card-body sales-types-report-div pb-2">
                            <div class="d-none d-lg-flex vehicles-progress-labels mb-5">
                                <div class="vehicles-progress-label on-the-way-text" style="width: 39.7%">Pending Task</div>
                                <div class="vehicles-progress-label unloading-text" style="width: 28.3%">Follow Up</div>
                                <div class="vehicles-progress-label loading-text" style="width: 17.4%">Completed</div>
                                <div class="vehicles-progress-label waiting-text" style="width: 14.6%">Not Interested</div>
                            </div>
                            <div
                                class="vehicles-overview-progress progress rounded-4 bg-transparent mb-2"
                                style="height: 46px">
                                <div class="progress-bar pending small fw-medium text-start rounded-start bg-lighter text-heading px-1 px-lg-4" role="progressbar" style="width: <?php echo $pendingPercentage; ?>%" aria-valuenow="<?php echo $pendingPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo $pendingCount; ?>
                                </div>
                                <div class="progress-bar follow-up small fw-medium text-start bg-primary px-1 px-lg-4" role="progressbar" style="width: <?php echo $followUpPercentage; ?>%" aria-valuenow="<?php echo $followUpPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo $followUpCount; ?>
                                </div>
                                <div class="progress-bar completed small fw-medium text-start text-bg-info px-1 px-lg-4" role="progressbar" style="width: <?php echo $completedPercentage; ?>%" aria-valuenow="<?php echo $completedPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo $completedCount; ?>
                                </div>
                                <div class="progress-bar not-interested small fw-medium text-start rounded-end bg-gray-900 px-1 px-lg-4" role="progressbar" style="width: <?php echo $notInterestedPercentage; ?>%" aria-valuenow="<?php echo $notInterestedPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo $notInterestedCount; ?>
                                </div>
                            </div>
                            <div class="table-responsive sales-types-report-table">
                                <table class="table card-table">
                                    <tbody class="table-border-bottom-0">
                                        <tr>
                                            <td class="w-75 ps-0">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="me-2">
                                                        <i class="text-heading ri-time-line ri-24px"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">Pending Task</h6>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0 text-nowrap">
                                                <h6 class="mb-0 task-count pending"><?php echo $pendingCount; ?></h6>
                                            </td>
                                            <td class="text-end pe-0 ps-6">
                                                <span class="percentage pending"><?php echo number_format($pendingPercentage, 1); ?>%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-75 ps-0">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="me-2">
                                                        <i class="text-heading ri-arrow-go-back-line ri-24px"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">Follow Up</h6>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0 text-nowrap">
                                                <h6 class="mb-0 task-count follow-up"><?php echo $followUpCount; ?></h6>
                                            </td>
                                            <td class="text-end pe-0 ps-6">
                                                <span class="percentage follow-up"><?php echo number_format($followUpPercentage, 1); ?>%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-75 ps-0">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="me-2">
                                                        <i class="text-heading ri-check-double-fill ri-24px"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">Completed</h6>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0 text-nowrap">
                                                <h6 class="mb-0 task-count completed"><?php echo $completedCount; ?></h6>
                                            </td>
                                            <td class="text-end pe-0 ps-6">
                                                <span class="percentage completed"><?php echo number_format($completedPercentage, 1); ?>%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-75 ps-0">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="me-2">
                                                        <i class="text-heading ri-prohibited-line ri-24px"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">Not Interested</h6>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0 text-nowrap">
                                                <h6 class="mb-0 task-count not-interested"><?php echo $notInterestedCount; ?></h6>
                                            </td>
                                            <td class="text-end pe-0 ps-6">
                                                <span class="percentage not-interested"><?php echo number_format($notInterestedPercentage, 1); ?>%</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="sort_option_in_dash d-flex justify-content-center align-items-center" id="sales_status_task_filler_content">
                            <div class="sort_div_content_section row p-10">
                                <div class="closeIcon col-12 d-flex justify-content-end">
                                    <button class="btn close_sales_overview_btn" id="close_sales_overview_btn_sales_status">
                                        <i class="ri-close-line ri-24px"></i>
                                    </button>
                                </div>
                                <div class="headlineOfContent col-12">
                                    <h3 class="mt-3 me-2">Sort by Name & Date Range</h3>
                                </div>
                                <div class="sortOptions col-12">
                                    <div class="row">
                                        <div class="col-md-12 col-12 mb-6">
                                            <div class="position-relative" data-select2-id="8">
                                                <select name="re_status" class="select2 form-select form-select-lg select2-hidden-accessible" data-allow-clear="true" required="" data-select2-id="4" tabindex="-1" aria-hidden="true">
                                                    <option value="" data-select2-id="6">Select Employee</option>
                                                    <?php
                                                    // SQL to fetch employee names from the employee table
                                                    $sql = "SELECT name FROM employee WHERE isenable = 1  ORDER BY name ASC"; // Ensure you're only getting active employees
                                                    $result = $conn->query($sql);

                                                    // Check if there are any employees in the database
                                                    if ($result->num_rows > 0) {
                                                        // Loop through each employee and create an option for the dropdown
                                                        while ($row = $result->fetch_assoc()) {
                                                            $name = htmlspecialchars($row['name']); // Secure the name from potential XSS
                                                            echo '<option value="' . $name . '">' . $name . '</option>';
                                                        }
                                                    } else {
                                                        // In case there are no employees in the database
                                                        echo '<option value="">No Employees Found</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-12 col-12 mb-6">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                    type="text"
                                                    style="color: #fff;"
                                                    class="form-control flatpickr-range" id="dateRangeInput"
                                                    placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                                <label for="flatpickr-range">Range Picker</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 mb-6 d-flex justify-content-end gap-3">
                                            <!-- <button type="button" class="btn btn-outline-primary sortNow_button">
                                                Use Default<i class="ri-filter-2-line ri-24px"></i>
                                            </button> -->
                                            <button type="button" class="btn btn-outline-primary sortNow_button">
                                                Filter Now <i class="ri-filter-2-line ri-24px"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Sales Appointments Overview Card -->




                <?php

                 // Get the current day, month, and year
                 $currentDay = date('d'); // Current day of the month (e.g., 05)
                 $currentMonth = date('m'); // Current month (e.g., 12)
                 $currentYear = date('Y'); // Current year (e.g., 2024)

                 // Calculate the start date for the previous month (e.g., 05-11-2024)
                 $startDateProject = date('Y-m-d', strtotime('last month', strtotime($currentYear . '-' . $currentMonth . '-' . $currentDay)));

                 // Calculate the end date for the current month (e.g., 05-12-2024)
                 $endDateProject = date('Y-m-d', strtotime($currentYear . '-' . $currentMonth . '-' . $currentDay));
                  $sql = "
                  SELECT 
                      COUNT(CASE WHEN SubTaskStatus = 'Pending' THEN 1 END) AS pcount,
                      COUNT(CASE WHEN SubTaskStatus = 'Extended' THEN 1 END) AS ecount,
                      COUNT(CASE WHEN SubTaskStatus = 'Completed' THEN 1 END) AS ccount
                  FROM assignproject 
                  WHERE DeadlineDate BETWEEN '$startDateProject' AND '$endDateProject' AND Name LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%'
              ";
              
              // Execute the query
              $result = $conn->query($sql);
              
              // Fetch the results
              $row = $result->fetch_assoc();
              
              $pendingProjCount = $row['pcount'];
              $extendedProjCount = $row['ecount'];
              $completedProjCount = $row['ccount'];
                ?>

                <!-- ---- Project Management Summary ---- -->
                <div class="col-md-6 col-xxl-4 order-1 order-xxl-3">
                    <div class="parent_sort_section card h-100">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-xxl-12 col-md-12 col-sm-12">
                                    <div class="card-title mb-0">
                                        <h5 class="m-0 me-2 text-left">Project Management Summary</h5>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-md-12 col-sm-12">
                                    <div class="dropdown d-flex justify-content-end mt-5">
                                        <div class="sort_btn_section d-flex align-items-center gap-3" id="project_status_task">
                                            <div class="sorted_icon">
                                                <i class="text-heading ri-filter-2-line ri-24px"></i>
                                            </div>
                                            <div class="sorted_text">
                                                <p class="m-0" id="dateRangeProjectDisplay"><?php echo ($dateRange); ?></p>
                                                <hr class="m-0">
                                                <p class="m-0"  id="employeeNameProjectDisplay"><?php echo ($JWT_adminName); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="deliveryExceptionsChart"></div>
                        </div>
                        <div class="sort_option_in_dash d-flex justify-content-center align-items-center" id="project_status_task_filler_content">
                            <div class="sort_div_content_section row p-10">
                                <div class="closeIcon col-12 d-flex justify-content-end">
                                    <button class="btn close_sales_overview_btn" id="close_sales_overview_btn_project_status">
                                        <i class="ri-close-line ri-24px"></i>
                                    </button>
                                </div>
                                <div class="headlineOfContent col-12">
                                    <h3 class="mt-3 me-2">Sort by Name & Date Range</h3>
                                </div>
                                <div class="sortOptions col-12">
                                    <div class="row">
                                        <div class="col-md-12 col-12 mb-6">
                                            <div class="position-relative" data-select2-id="8"><select name="re_status1" class="select2 form-select form-select-lg  select2-hidden-accessible" data-allow-clear="true" required="" data-select2-id="4" tabindex="-1" aria-hidden="true">
                                                    <option value="" data-select2-id="6">Select Employee</option>
                                                    <?php
                                                    // SQL to fetch employee names from the employee table
                                                    $sql = "SELECT name FROM employee WHERE isenable = 1  ORDER BY name ASC"; // Ensure you're only getting active employees
                                                    $result = $conn->query($sql);

                                                    // Check if there are any employees in the database
                                                    if ($result->num_rows > 0) {
                                                        // Loop through each employee and create an option for the dropdown
                                                        while ($row = $result->fetch_assoc()) {
                                                            $name = htmlspecialchars($row['name']); // Secure the name from potential XSS
                                                            echo '<option value="' . $name . '">' . $name . '</option>';
                                                        }
                                                    } else {
                                                        // In case there are no employees in the database
                                                        echo '<option value="">No Employees Found</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 mb-6">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                    type="text"
                                                    style="color: #fff;"
                                                    class="form-control flatpickr-range"  id="dateProjectRangeInput"
                                                    placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                                                <label for="flatpickr-range">Range Picker</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 mb-6 d-flex justify-content-end gap-3">
                                            <!-- <button type="button" class="btn btn-outline-primary sortNow_button">
                                                Use Default<i class="ri-filter-2-line ri-24px"></i>
                                            </button> -->
                                            <button type="button" class="btn btn-outline-primary sortNowProj_button">
                                                Filter Now <i class="ri-filter-2-line ri-24px"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Project Management Summary -->

            </div>


        </div>

        <div class="floating_dash_option" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Numeric Dashboard">

            <div class="dash_Btn_container">
                <a class="dash_Btn is-play" href="index.php">
                    <div class="button-outer-circle has-scale-animation"></div>
                    <div class="button-outer-circle has-scale-animation has-delay-short"></div>
                    <div class="button-icon is-play">
                        <i class="ri-list-ordered-2 ri-24px"></i>
                    </div>
                </a>
            </div>
        </div>


        <?php include('include/footer.php'); ?>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script src="assets/js/chart-dashboard.js"></script>


        <script>
            const ctx = document.getElementById('UserActiveInactiveContainer').getContext('2d'); // Get 2D context

            const data = {
                labels: ['Active User', 'Inactive User'],
                datasets: [{
                    label: 'User Activity',
                    data: [<?php echo $employeeActiveCount; ?>, <?php echo $employeeInActiveCount; ?>],
                    backgroundColor: [
                        '#06ad37',
                        '#f54d55'
                    ],
                    hoverOffset: 4
                }]
            };

            const config = {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 20,
                                padding: 10,
                            }
                        }
                    },
                    cutout: '80%',
                    layout: {
                        padding: 10
                    }
                }
            };

            const myChart = new Chart(ctx, config);


            const ctx_admin = document.getElementById('UserActiveInactiveAdmins').getContext('2d'); // Get 2D context

            const data_admin = {
                labels: ['Active Admins', 'Inactive Admins'],
                datasets: [{
                    label: 'Admin Activity',
                    data: [<?php echo $adminActiveCount; ?>, <?php echo $adminInActiveCount; ?>],
                    backgroundColor: [
                        '#06ad37',
                        '#f54d55'
                    ],
                    hoverOffset: 4
                }]
            };

            const config_admin = {
                type: 'doughnut',
                data: data_admin,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 20,
                                padding: 10,
                            }
                        }
                    },
                    cutout: '80%',
                    layout: {
                        padding: 20
                    }
                }
            };

            const myChart_admin = new Chart(ctx_admin, config_admin);


            // Sales Summery Task Functionalities  ------------------- Start

            $("#sales_summery_task").click(function() {
                $("#sales_summery_task_filler_content").addClass("open");
            });

            $("#close_sales_overview_btn_sales_summery").click(function() {
                $("#sales_summery_task_filler_content").removeClass("open");
            });

            // Sales Summery Task Functionalities  ------------------- End

            // Sales Status Task Functionalities  ------------------- Start

            $("#sales_status_task").click(function() {
                $("#sales_status_task_filler_content").addClass("open");
            });

            $("#close_sales_overview_btn_sales_status").click(function() {
                $("#sales_status_task_filler_content").removeClass("open");

            });

            // Sales Status Task Functionalities  ------------------- End


            // Project Status Task Functionalities   ------------------- Start

            $("#project_status_task").click(function() {
                $("#project_status_task_filler_content").addClass("open");
            });

            $("#close_sales_overview_btn_project_status").click(function() {
                $("#project_status_task_filler_content").removeClass("open");

            });

            // Project Status Task Functionalities   ------------------- Start


            // Department Progress Tracker Functionalities   ------------------- Start

            $("#Department_task_report").click(function() {
                $("#Department_task_report_filler_content").addClass("open");
            });

            $("#close_department_filter_content").click(function() {
                $("#Department_task_report_filler_content").removeClass("open");

            });

            // Department Progress Tracker Functionalities   ------------------- Start


            function change_user_to_admin() {
                // If user is currently active (open), switch to admin
                if ($('.user_inactive_active').hasClass('open')) {
                    $('.user_inactive_active').removeClass('open');
                    $('.admin_inactive_active').addClass('open');
                }
                // If admin is currently active (open), switch to user
                else if ($('.admin_inactive_active').hasClass('open')) {
                    $('.admin_inactive_active').removeClass('open');
                    $('.user_inactive_active').addClass('open');
                }
            }


            $('#searchBox').on('keyup', function() {
                var searchQuery = $(this).val().toLowerCase();

                // Iterate over each user item
                $('.dash-user-item').each(function() {
                    var userName = $(this).find('h6.mb-0').text().toLowerCase();

                    if (userName.indexOf(searchQuery) !== -1) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            });

           

            $(document).ready(function() {
                let deliveryExceptionsChart; 
                updateProjectPie(<?php echo $pendingProjCount; ?>, <?php echo $extendedProjCount; ?>, <?php echo $completedProjCount; ?>);
                $(".sortNow_button").click(function() {
                    // Get selected employee name
                    var employeeName = $("select[name='re_status']").val();

                    // Get selected date range using the new ID
                    var dateRange = $("#dateRangeInput").val(); // Use the ID to get the value

                    // Split the date range into start and end date
                    var dates = dateRange.split(' to ');
                    var startDate = dates[0]; // Format start date to Y-m-d
                    var endDate = dates[1]; // Format end date to Y-m-d

                    // Send the data via AJAX
                    $.ajax({
                        url: 'include/handlers/GioGraphDashHandler.php', // PHP file to handle the filtering
                        type: 'POST',
                        data: {
                            employee: employeeName,
                            startDate: startDate,
                            endDate: endDate,
                            action: 'FillerData'
                        },
                        success: function(response) {
                            var data = JSON.parse(response);


                            // Update the date range and employee name dynamically
                            $("#dateRangeDisplay").text(startDate + " to " + endDate);
                            $("#employeeNameDisplay").text(employeeName);
                            updateProgressBars(data);
                        },
                        error: function() {
                            alert("An error occurred while processing the filter.");
                        }
                    });
                });


  

                function updateProgressBars(data) {
                    // Assuming data contains updated task counts and percentages
                    var pendingPercentage = data.pendingPercentage;
                    var followUpPercentage = data.followUpPercentage;
                    var completedPercentage = data.completedPercentage;
                    var notInterestedPercentage = data.notInterestedPercentage;

                    // Update progress bars and counts
                    $(".progress-bar.pending").css('width', pendingPercentage + '%').text(data.pendingCount);
                    $(".progress-bar.follow-up").css('width', followUpPercentage + '%').text(data.followUpCount);
                    $(".progress-bar.completed").css('width', completedPercentage + '%').text(data.completedCount);
                    $(".progress-bar.not-interested").css('width', notInterestedPercentage + '%').text(data.notInterestedCount);

                    // Update task counts below the progress bars
                    $(".task-count.pending").text(data.pendingCount);
                    $(".task-count.follow-up").text(data.followUpCount);
                    $(".task-count.completed").text(data.completedCount);
                    $(".task-count.not-interested").text(data.notInterestedCount);

                    // Update percentages below the progress bars
                    $(".percentage.pending").text(data.pendingPercentage.toFixed(1) + '%');
                    $(".percentage.follow-up").text(data.followUpPercentage.toFixed(1) + '%');
                    $(".percentage.completed").text(data.completedPercentage.toFixed(1) + '%');
                    $(".percentage.not-interested").text(data.notInterestedPercentage.toFixed(1) + '%');
                }
           

               // Handle the "Filter Now" button click
               $(".sortNowProj_button").click(function() {
        // Get selected employee name
        var employeeName = $("select[name='re_status1']").val();

        // Get selected date range using the new ID
        var dateRange = $("#dateProjectRangeInput").val(); // Use the ID to get the value

        // Validate that both fields are selected
        if (!employeeName || !dateRange) {
            alert("Please select an employee and date range.");
            return;
        }

        // Split the date range into start and end date
        var dates = dateRange.split(' to ');
        var startDate = dates[0]; // Format start date to Y-m-d
        var endDate = dates[1]; // Format end date to Y-m-d

        // Send the data via AJAX to filter_data.php
        $.ajax({
            url: 'include/handlers/GeneralFetchHandler.php', // PHP file to handle the filtering
            type: 'POST',
            data: {
                employee: employeeName,
                startDate: startDate,
                endDate: endDate,
                action: 'GioGraphDashHandler'
            },
            success: function(response) {
                var data = JSON.parse(response);
                
               
                $("#dateRangeProjectDisplay").text(startDate + " to " + endDate);
                $("#employeeNameProjectDisplay").text(employeeName);
                updateProjectPie( data.pcount, data.ecount, data.ccount);
               
               
            },
            error: function() {
                alert("An error occurred while processing the filter.");
            }
        });
    });

      // Function to update the pie chart with new data
    function updateProjectPie(pendingProjCount, extendedProjCount, completedProjCount) {
        var config1 = {
            colors: {
                textMuted: "#A0A0A0",
                headingColor: "#333333",
                bodyColor: "#000000",
                borderColor: "#CCCCCC"
            },
            colors_dark: {
                textMuted: "#888888",
                headingColor: "#FFFFFF",
                bodyColor: "#EEEEEE",
                borderColor: "#444444"
            }
        };

        // Check if dark mode is enabled
        var isDarkStyle = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) || false;

        let labelColor, headingColor, bodyColor, borderColor, currentTheme;

        if (isDarkStyle) {
            labelColor = config1.colors_dark.textMuted;
            headingColor = config1.colors_dark.headingColor;
            bodyColor = config1.colors_dark.bodyColor;
            borderColor = config1.colors_dark.borderColor;
            currentTheme = 'dark';
        } else {
            labelColor = config1.colors.textMuted;
            headingColor = config1.colors.headingColor;
            borderColor = config1.colors.borderColor;
            bodyColor = config1.colors.bodyColor;
            currentTheme = 'light';
        }

        const deliveryExceptionsChartE1 = document.querySelector('#deliveryExceptionsChart');

        const deliveryExceptionsChartConfig = {
            chart: {
                height: 350,
                parentHeightOffset: 0,
                type: 'donut'
            },
            labels: ['Pending Project', 'Extended Project', 'Completed Project'],
            series: [Number(pendingProjCount), Number(extendedProjCount), Number(completedProjCount)], // Use dynamic counts
            colors: [
                '#E5997F', // Pending color
                '#FFEFB2', // Extended color
                '#06ad37'  // Completed color
            ],
            stroke: {
                width: 0
            },
            dataLabels: {
                enabled: false,
                formatter: function (val, opt) {
                    return parseInt(val) + '%';
                }
            },
            legend: {
                show: true,
                position: 'bottom',
                offsetY: 10,
                markers: {
                    width: 8,
                    height: 8,
                    offsetX: -5
                },
                itemMargin: {
                    horizontal: 16,
                    vertical: 5
                },
                fontSize: '13px',
                fontFamily: 'Inter',
                fontWeight: 400,
                labels: {
                    colors: headingColor,
                    useSeriesColors: false
                }
            },
            tooltip: {
                theme: currentTheme
            },
            grid: {
                padding: {
                    top: 15
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '24px',
                                fontFamily: 'Inter',
                                color: headingColor,
                                fontWeight: 500,
                                offsetY: -30,
                                formatter: function (val) {
                                    return parseInt(val) + '';
                                }
                            },
                            name: {
                                offsetY: 20,
                                fontFamily: 'Inter'
                            },
                            total: {
                                show: true,
                                fontSize: '15px',
                                fontFamily: 'Inter',
                                label: 'Total Project',
                                color: bodyColor,
                                formatter: function (w) {
                                    var totalProjects = Number(pendingProjCount) + Number(extendedProjCount) + Number(completedProjCount);
        return totalProjects;
                                }
                            }
                        }
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 420,
                    options: {
                        chart: {
                            height: 360
                        }
                    }
                }
            ]
        };

        if (deliveryExceptionsChart) {
        deliveryExceptionsChart.destroy();
    }

    // Initialize or update the chart
    if (deliveryExceptionsChartE1) {
        deliveryExceptionsChart = new ApexCharts(deliveryExceptionsChartE1, deliveryExceptionsChartConfig);
        deliveryExceptionsChart.render();
    }



    const platformCounts = <?php
       
        $sql = "SELECT platform, COUNT(*) AS count FROM task_descriptions GROUP BY platform";
        $result = $conn->query($sql);

        $platformCounts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $platformCounts[] = [
                    'platform' => $row['platform'],
                    'count' => $row['count']
                ];
            }
        }

        $conn->close();

        // Output the PHP array as a JSON response
        echo json_encode($platformCounts);
    ?>;

    // Step 1: Calculate the total count
    const totalCount = platformCounts.reduce((sum, platform) => sum + platform.count, 0);

    // Step 2: Calculate the percentage for each platform
    const platformPercentages = platformCounts.map(platform => ({
        platform: platform.platform,
        percentage: ((platform.count / totalCount) * 100).toFixed(2) // rounded to 2 decimal places
    }));

    // Step 3: Extract the platforms and percentages
    const labels = platformPercentages.map(item => item.platform);
    const data1 = platformPercentages.map(item => parseFloat(item.percentage));

    // Step 4: Update the horizontal bar chart configuration
    const horizontalBarChartConfig = {
        chart: {
            height: 270,
            type: 'bar',
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '70%',
                distributed: true,
                startingShape: 'rounded',
                borderRadius: 7
            }
        },
        grid: {
            strokeDashArray: 10,
            borderColor: "#ddd",
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: false
                }
            },
            padding: {
                top: -35,
                bottom: -12
            }
        },
        fill: {
            opacity: 1
        },
        colors: [
            "#007bff", "#17a2b8", "#28a745", "#6c757d", "#dc3545", "#ffc107"
        ],
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#fff'],
                fontWeight: 500,
                fontSize: '13px',
                fontFamily: 'Inter'
            },
            formatter: function (val, opts) {
                return `${labels[opts.dataPointIndex]}: ${val}%`;
            },
            offsetX: 0,
            dropShadow: {
                enabled: false
            }
        },
        labels: labels, // Platform labels
        series: [
            {
                data: data1 // Percentages
            }
        ],
        xaxis: {
            categories: ['6', '5', '4', '3', '2', '1'],
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            labels: {
                style: {
                    colors: "#333",
                    fontSize: '13px'
                },
                formatter: function (val) {
                    return `${val}%`;
                }
            }
        },
        yaxis: {
            max: Math.max(...data), // Dynamically set max value
            labels: {
                style: {
                    colors: ["#333"],
                    fontFamily: 'Inter',
                    fontSize: '13px'
                }
            }
        },
        tooltip: {
            enabled: true,
            style: {
                fontSize: '12px'
            },
            custom: function ({ series, seriesIndex, dataPointIndex, w }) {
                return `<div class="px-3 py-2"><span>${series[seriesIndex][dataPointIndex]}%</span></div>`;
            }
        },
        legend: {
            show: false
        }
    };

    // Initialize and render the chart
    const horizontalBarChartEl = document.querySelector('#horizontalBarChart');
    if (horizontalBarChartEl) {
        const horizontalBarChart = new ApexCharts(horizontalBarChartEl, horizontalBarChartConfig);
        horizontalBarChart.render();
    }


    }



    
           
});



//           var   config1 = {
//   colors: {
//     textMuted: "#A0A0A0",
//     headingColor: "#333333",
//     bodyColor: "#000000",
//     borderColor: "#CCCCCC"
//   },
//   colors_dark: {
//     textMuted: "#888888",
//     headingColor: "#FFFFFF",
//     bodyColor: "#EEEEEE",
//     borderColor: "#444444"
//   }
// };

// // Check if dark mode is enabled (you can adjust this to your requirements)
// var isDarkStyle = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) || false;



//             let labelColor, headingColor, borderColor, bodyColor, currentTheme;

// if (isDarkStyle) {
//   labelColor = config1.colors_dark.textMuted;
//   headingColor = config1.colors_dark.headingColor;
//   bodyColor = config1.colors_dark.bodyColor;
//   borderColor = config1.colors_dark.borderColor;
//   currentTheme = 'dark';
// } else {
//   labelColor = config1.colors.textMuted;
//   headingColor = config1.colors.headingColor;
//   borderColor = config1.colors.borderColor;
//   bodyColor = config1.colors.bodyColor;
//   currentTheme = 'light';
// }

//             const deliveryExceptionsChartE1 = document.querySelector('#deliveryExceptionsChart');

// const deliveryExceptionsChartConfig = {
//   chart: {
//     height: 350,
//     parentHeightOffset: 0,
//     type: 'donut'
//   },
//   labels: ['Pending Project', 'Extended Project', 'Completed Project'],
//   series: [<?php echo $pendingProjCount; ?>, <?php echo $extendedProjCount; ?>, <?php echo $completedProjCount; ?>], // Dynamically injected counts
//   colors: [
//     '#E5997F',
//     '#FFEFB2',
//     '#06ad37'
//   ],
//   stroke: {
//     width: 0
//   },
//   dataLabels: {
//     enabled: false,
//     formatter: function (val, opt) {
//       return parseInt(val) + '%';
//     }
//   },
//   legend: {
//     show: true,
//     position: 'bottom',
//     offsetY: 10,
//     markers: {
//       width: 8,
//       height: 8,
//       offsetX: -5
//     },
//     itemMargin: {
//       horizontal: 16,
//       vertical: 5
//     },
//     fontSize: '13px',
//     fontFamily: 'Inter',
//     fontWeight: 400,
//     labels: {
//       colors: headingColor,
//       useSeriesColors: false
//     }
//   },
//   tooltip: {
//     theme: currentTheme
//   },
//   grid: {
//     padding: {
//       top: 15
//     }
//   },
//   plotOptions: {
//     pie: {
//       donut: {
//         size: '75%',
//         labels: {
//           show: true,
//           value: {
//             fontSize: '24px',
//             fontFamily: 'Inter',
//             color: headingColor,
//             fontWeight: 500,
//             offsetY: -30,
//             formatter: function (val) {
//               return parseInt(val) + '';
//             }
//           },
//           name: {
//             offsetY: 20,
//             fontFamily: 'Inter'
//           },
//           total: {
//             show: true,
//             fontSize: '15px',
//             fontFamily: 'Inter',
//             label: 'Total Project',
//             color: bodyColor,
//             formatter: function (w) {
//               return '<?php echo $pendingProjCount + $extendedProjCount + $completedProjCount; ?>'; // Total Projects
//             }
//           }
//         }
//       }
//     }
//   },
//   responsive: [
//     {
//       breakpoint: 420,
//       options: {
//         chart: {
//           height: 360
//         }
//       }
//     }
//   ]
// };

// if (typeof deliveryExceptionsChartE1 !== undefined && deliveryExceptionsChartE1 !== null) {
//   const deliveryExceptionsChart = new ApexCharts(deliveryExceptionsChartE1, deliveryExceptionsChartConfig);
//   deliveryExceptionsChart.render();
// }


// PHP sends the data as a JSON object



        </script>