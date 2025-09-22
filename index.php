 <?php include('include/head.php');

require 'data/dbconfig.php';
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = $JWT_userID;
$permissionsql = "SELECT * FROM permissions WHERE userID='$id' ";
$resultPermission = $conn->query($permissionsql);
$number_of_employees = '';
$number_of_active_employees = '';
$inactive_employees = '';
$number_of_admin = '';
$active_admin = '';
$inactive_admin = '';
$today_task = '';
$task_for_next_6_days = '';
$task_for_7th_to_31st_day = '';
$TaskPending = '';
$TaskFollowUp = '';
$TaskCompleted = '';
$TaskNotInterested = '';
$project_all_time = '';
$pending_project = '';
$extended_project = '';
$completed_project = '';
$reminder_count = '';
$document_count = '';
$TotalTask = '';
if ($resultPermission->num_rows > 0) {
  $row = $resultPermission->fetch_assoc();
  $number_of_employees = htmlspecialchars($row['number_of_employees']);
  $number_of_active_employees = htmlspecialchars($row['number_of_active_employees']);
  $inactive_employees = $row['inactive_employees'];
  $number_of_admin = htmlspecialchars($row['number_of_admin']);
  $active_admin = htmlspecialchars($row['active_admin']);
  $inactive_admin = htmlspecialchars($row['inactive_admin']);
  $today_task = htmlspecialchars($row['today_task']); // Fetch the role from the database
  $task_for_next_6_days = htmlspecialchars($row['task_for_next_6_days']);
  $task_for_7th_to_31st_day = htmlspecialchars($row['task_for_7th_to_31st_day']);
  $project_all_time = htmlspecialchars($row['project_all_time']);
  $pending_project = htmlspecialchars($row['pending_project']);
  $extended_project = htmlspecialchars($row['extended_project']);
  $completed_project = htmlspecialchars($row['completed_project']);
  $reminder_count = htmlspecialchars($row['reminder_count']);
  $document_count = htmlspecialchars($row['document_count']);
  $TaskPending = htmlspecialchars($row['TaskPending']);
  $TaskFollowUp = htmlspecialchars($row['TaskFollowUp']);
  $TaskCompleted = htmlspecialchars($row['TaskCompleted']);
  $TaskNotInterested = htmlspecialchars($row['TaskNotInterested']);
  $TotalTask = htmlspecialchars($row['TotalTask']);
}



// Get the current date
$today = date('Y-m-d');

$tomorrow = date('Y-m-d', strtotime('+1 days'));

// Get the date 6 days from today
$next_six_days = date('Y-m-d', strtotime('+6 days'));

$seventhDay = date('Y-m-d', strtotime('+7 days'));

// Get the date 30 days from today
$next_thirty_days = date('Y-m-d', strtotime('+31 days'));

// Query to get the count of events for today
$sql_today = "SELECT COUNT(*) AS count FROM task_descriptions WHERE `date` = '$today' AND addedBy like '%" . $conn->real_escape_string($JWT_adminName) . "%'";
$result_today = $conn->query($sql_today);
$count_today = $result_today->fetch_assoc()['count'];

// Query to get the count of events for the next 6 days
$sql_six_days = "SELECT COUNT(*) AS count FROM task_descriptions WHERE `date` BETWEEN '$tomorrow' AND '$next_six_days' AND addedBy like '%" . $conn->real_escape_string($JWT_adminName) . "%'";
$result_six_days = $conn->query($sql_six_days);
$count_six_days = $result_six_days->fetch_assoc()['count'];

// Query to get the count of events for the next 30 days
$sql_thirty_days = "SELECT COUNT(*) AS count FROM task_descriptions WHERE `date` BETWEEN '$seventhDay' AND '$next_thirty_days' AND addedBy like '%" . $conn->real_escape_string($JWT_adminName) . "%'";
$result_thirty_days = $conn->query($sql_thirty_days);
$count_thirty_days = $result_thirty_days->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as employee_count FROM employee");
$row = $result->fetch_assoc();
$employeeCount = $row['employee_count'];

$result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE isenable = 1");
$row = $result->fetch_assoc();
$employeeActiveCount = $row['employee_count'];

$result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE isenable = 0");
$row = $result->fetch_assoc();
$employeeInActiveCount = $row['employee_count'];


$result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE role = 'admin'");
$row = $result->fetch_assoc();
$adminCount = $row['employee_count'];


$result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE role = 'admin' AND isenable = 1");
$row = $result->fetch_assoc();
$adminActiveCount = $row['employee_count'];


$result = $conn->query("SELECT COUNT(*) as employee_count FROM employee WHERE role = 'admin' AND isenable = 0");
$row = $result->fetch_assoc();
$adminInActiveCount = $row['employee_count'];


$result = $conn->query("SELECT COUNT(*) as totalcount FROM event WHERE  tagemployee like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
$row = $result->fetch_assoc();
$TotalSalesTask = $row['totalcount'];


$result = $conn->query("SELECT COUNT(*) as follow_count FROM event WHERE status = 'Follow Up' AND tagemployee like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
$row = $result->fetch_assoc();
$taskFollowUpCount = $row['follow_count'];

$result = $conn->query("SELECT COUNT(*) as Completed_count FROM event WHERE status = 'Completed' AND tagemployee like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
$row = $result->fetch_assoc();
$taskCompletedCount = $row['Completed_count'];


$result = $conn->query("SELECT COUNT(*) as Not_count FROM event WHERE status = 'Not Interested' AND tagemployee like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
$row = $result->fetch_assoc();
$taskNICount = $row['Not_count'];


// $result = $conn->query("SELECT COUNT(*) as pcount FROM assignproject WHERE SubTaskStatus = 'Pending' AND Name like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
// $row = $result->fetch_assoc();
// $pendingProjCount = $row['pcount'];



// $result = $conn->query("SELECT COUNT(*) as ecount FROM assignproject WHERE SubTaskStatus = 'Extended' AND Name like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
// $row = $result->fetch_assoc();
// $extendedProjCount = $row['ecount'];


$result = $conn->query("SELECT COUNT(*) as ccount FROM assignproject WHERE SubTaskStatus = 'Completed' AND Name like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
$row = $result->fetch_assoc();
$completedProjCount = $row['ccount'];


// $result = $conn->query("SELECT COUNT(*) as reminder_count FROM reminder WHERE tagemployee like '%" . $conn->real_escape_string($JWT_adminName) . "%' OR assignedBy like '%" . $conn->real_escape_string($JWT_adminName) . "%' AND date = CURDATE()");
// $row = $result->fetch_assoc();
// $reminderCount = $row['reminder_count'];



$result = $conn->query("SELECT COUNT(*) as doc_count FROM docs_upload");
$row = $result->fetch_assoc();
$docCount = $row['doc_count'];



$days = isset($_POST['days']) ? (int) $_POST['days'] : null;

$currentDate = date('Y-m-d');
if ($days !== null) {
  $dateLimit = date('Y-m-d', strtotime("+$days days"));

  $result = $conn->query("
      SELECT COUNT(*) as pending_count 
        FROM event 
        WHERE status = 'Processing' 
        AND tagemployee LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%' 
        AND DATE(date) BETWEEN '$currentDate' AND '$dateLimit'
");
} else {

  $result = $conn->query("
        SELECT COUNT(*) as pending_count 
        FROM event 
        WHERE status = 'Processing' 
        AND tagemployee LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%'
    ");
}


$row = $result->fetch_assoc();
$taskPendingCount = $row['pending_count'];



$projdays = isset($_POST['projdays']) ? (int) $_POST['projdays'] : null;

$currentDate = date('Y-m-d');
if ($projdays !== null) {
  $dateLimit = date('Y-m-d', strtotime("+$projdays days"));

  $result = $conn->query("
      SELECT COUNT(*) as pcount 
        FROM assignproject 
        WHERE  SubTaskStatus = 'Pending'  
        AND Name LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%' 
        AND DATE(DeadlineDate) BETWEEN '$currentDate' AND '$dateLimit'
");
} else {

  $result = $conn->query("
       SELECT COUNT(*) as pcount FROM assignproject WHERE SubTaskStatus = 'Pending' AND Name like '%" . $conn->real_escape_string($JWT_adminName) . "%'
    ");
}


$row = $result->fetch_assoc();
$pendingProjCount = $row['pcount'];




// $extendedProjCount = $row['ecount'];


$result = $conn->query("SELECT COUNT(*) as ccount FROM assignproject WHERE SubTaskStatus = 'Completed' AND Name like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
$row = $result->fetch_assoc();
$completedProjCount = $row['ccount'];


$result = $conn->query("SELECT COUNT(*) as reminder_count FROM reminder_notification WHERE assignedBy like '%" . $conn->real_escape_string($JWT_adminName) . "%' OR assignedBy like '%" . $conn->real_escape_string($JWT_adminName) . "%' AND date = CURDATE()");
$row = $result->fetch_assoc();
$reminderCount = $row['reminder_count'];



$result = $conn->query("SELECT COUNT(*) as doc_count FROM docs_upload WHERE tagged_emp like '%" . $conn->real_escape_string($JWT_adminName) . "%'");
$row = $result->fetch_assoc();
$docCount = $row['doc_count'];



// $days = isset($_POST['days']) ? (int) $_POST['days'] : null;

// $currentDate = date('Y-m-d');
// if ($days !== null) {
//   $dateLimit = date('Y-m-d', strtotime("+$days days"));

//   $result = $conn->query("
//       SELECT COUNT(*) as pending_count 
//         FROM event 
//         WHERE status = 'Processing' 
//         AND tagemployee LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%' 
//         AND DATE(date) BETWEEN '$currentDate' AND '$dateLimit'
// ");
// } else {

//   $result = $conn->query("
//         SELECT COUNT(*) as pending_count 
//         FROM event 
//         WHERE status = 'Processing' 
//         AND tagemployee LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%'
//     ");
// }


// $row = $result->fetch_assoc();
// $taskPendingCount = $row['pending_count'];



$projExtendeddays = isset($_POST['projExtendeddays']) ? (int) $_POST['projExtendeddays'] : null;

$currentDate = date('Y-m-d');
if ($projExtendeddays !== null) {
  $dateLimit = date('Y-m-d', strtotime("+$projExtendeddays days"));

  $result = $conn->query("
      SELECT COUNT(*) as ecount 
        FROM assignproject 
        WHERE  SubTaskStatus = 'Extended'  
        AND Name LIKE '%" . $conn->real_escape_string($JWT_adminName) . "%' 
        AND DATE(DeadlineDate) BETWEEN '$currentDate' AND '$dateLimit'
");
} else {

  $result = $conn->query("
       SELECT COUNT(*) as ecount FROM assignproject WHERE SubTaskStatus = 'Extended' AND Name like '%" . $conn->real_escape_string($JWT_adminName) . "%'
    ");
}


$row = $result->fetch_assoc();
$extendedProjCount = $row['ecount'];


$sql = "SELECT * FROM logo WHERE id= 1 ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();

  $Title = htmlspecialchars($row['Title']);
  $SubTitle = htmlspecialchars($row['SubTitle']);
}
// Close connection
//$conn->close();




?>




<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
      
      <?php if (isset($JWT_adminRole) && $JWT_adminRole === 'admin') : ?>

        <div class="row g-6">

            <!-- Welcome Card for User Start-->
            <div class="col-md-12 col-xxl-12">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-md-7 order-2 order-md-1">
                            <div class="card-body">
                                <h4 class="card-title mb-4"><?php echo htmlspecialchars($Title); ?>🎉</h4>
                                <p><?php echo htmlspecialchars($SubTitle); ?></p>
                                <a href="assignTask.php" class="btn btn-primary">View All Appointments</a>
                            </div>
                        </div>
                        <div class="col-md-5 text-center text-md-end order-1 order-md-2">
                            <div class="card-body pb-0 px-0 pt-2">
                                <img src="assets/img/illustrations/illustration-john-light.png" height="186"
                                    class="scaleX-n1-rtl" alt="View Profile"
                                    data-app-light-img="illustrations/illustration-john-light.png"
                                    data-app-dark-img="illustrations/illustration-john-dark.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Welcome Card for User En-->

        </div>
      
      <?php endif; ?>
      
        <?php if (
      $number_of_employees === 'Enable' ||
      $number_of_active_employees === 'Enable' ||
      $inactive_employees === 'Enable'

    ):
    ?>
        <div class="card mt-7 mb-7 p-5">


            <div class="row">
                <div class="col-sm-6 col-lg-2">
                    <div>
                        <img style="width:100%; margin: auto;" src="assets/img/illustrations/emp_status.png" />
                    </div>
                </div>
                <div class="col-sm-6 col-lg-10">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div class="card-header p-2 mb-3">
                                <div class="justify-content-between">
                                    <h5 class="mb-0">Users Status Overview</h5>
                                    <p style="color: #bcbcbc;" class="mb-0">Overview of active and inactive users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-12">
                            <div class="row g-6 mb-6">
                                <!-- Task Status Related Counts Start -->
                                <?php if ($number_of_employees === 'Enable'): ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card card-border-shadow-warning h-100">
                                        <a href="addemployee.php?page=addemployee">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="avatar me-4">
                                                        <span class="avatar-initial rounded-3 bg-label-warning"><img
                                                                class="svg_dash_icons"
                                                                src="assets/img/illustrations/svg_dash/All_employee.svg" />
                                                        </span>
                                                    </div>
                                                    <h4 class="mb-0"><?php echo $employeeCount; ?></h4>
                                                </div>
                                                <h6 class="mb-0 fw-normal">Number of Users</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if ($number_of_active_employees === 'Enable'): ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card card-border-shadow-warning h-100">
                                        <a href="addemployee.php?page=addemployee">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="avatar me-4">
                                                        <span class="avatar-initial rounded-3 bg-label-warning"><img
                                                                class="svg_dash_icons"
                                                                src="assets/img/illustrations/svg_dash/active_employees.svg" />
                                                        </span>
                                                    </div>
                                                    <h4 class="mb-0"><?php echo $employeeActiveCount; ?></h4>
                                                </div>
                                                <h6 class="mb-0 fw-normal">Number of Active Users</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if ($inactive_employees === 'Enable'): ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card card-border-shadow-warning h-100">
                                        <a href="addemployee.php?page=addemployee">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="avatar me-4">
                                                        <span class="avatar-initial rounded-3 bg-label-warning"><img
                                                                class="svg_dash_icons"
                                                                src="assets/img/illustrations/svg_dash/inactive_employees.svg" />
                                                        </span>
                                                    </div>
                                                    <h4 class="mb-0"><?php echo $employeeInActiveCount; ?></h4>
                                                </div>
                                                <h6 class="mb-0 fw-normal">Inactive Users</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <!-- Task Status Related Counts End -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php endif; ?>

        <?php if (
      $number_of_admin === 'Enable' ||
      $active_admin === 'Enable' ||
      $inactive_admin === 'Enable'

    ):
    ?>
        <div class="card mt-7 mb-7 p-5">
            <div class="row">
                <div class="col-sm-6 col-lg-10">
                    <div class="col-sm-12 col-lg-12">
                        <div class="card-header p-2 mb-3">
                            <div class="justify-content-between">
                                <h5 class="mb-0">Admin Status Overview</h5>
                                <p style="color: #bcbcbc;" class="mb-0">Overview of active and inactive admins.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12">
                        <div class="row g-6">
                            <!-- Employee Status and Role Related Counts Start -->
                            <?php if ($number_of_admin === 'Enable'): ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="card card-border-shadow-primary h-100">
                                    <a href="employeeReport.php?page=employeeReport&role=admin">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="avatar me-4">
                                                    <span class="avatar-initial rounded-3 bg-label-primary">
                                                        <img class="svg_dash_icons"
                                                            src="assets/img/illustrations/svg_dash/Admins.svg" />
                                                    </span>
                                                </div>
                                                <h4 class="mb-0"><?php echo $adminCount; ?></h4>
                                            </div>
                                            <h6 class="mb-0 fw-normal">Number of Admin</h6>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($active_admin === 'Enable'): ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="card card-border-shadow-primary h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="avatar me-4">
                                                <span class="avatar-initial rounded-3 bg-label-primary"><img
                                                        class="svg_dash_icons"
                                                        src="assets/img/illustrations/svg_dash/ActiveAdmin.svg" />
                                                </span>
                                            </div>
                                            <h4 class="mb-0"><?php echo $adminActiveCount; ?></h4>
                                        </div>
                                        <h6 class="mb-0 fw-normal">Active Admin</h6>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($inactive_admin === 'Enable'): ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="card card-border-shadow-primary h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="avatar me-4">
                                                <span class="avatar-initial rounded-3 bg-label-primary">
                                                    <img class="svg_dash_icons"
                                                        src="assets/img/illustrations/svg_dash/InActiveAdmin.svg" />
                                                </span>
                                            </div>
                                            <h4 class="mb-0"><?php echo $adminInActiveCount; ?></h4>
                                        </div>
                                        <h6 class="mb-0 fw-normal">Inactive Admin</h6>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <!-- Employee Status and Role Related Counts End -->
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div>
                        <img style="width:100%; margin: auto;" src="assets/img/illustrations/AdminOverview.png" />
                    </div>
                </div>
            </div>

        </div>
        <?php endif; ?>
        <?php
    // Check if any report access is enabled
    if (
      $today_task === 'Enable' ||
      $task_for_next_6_days === 'Enable' ||
      $task_for_7th_to_31st_day === 'Enable' ||
      $TotalTask === 'Enable'
    ):
    ?>
        <div class="card mt-7 mb-7 p-5">
            <div class="card-header p-2 mb-3">
                <div class="row">
                    <div class="col-9 col-lg-9 col-md-9 col-sm-12">
                        <h5 class="mb-0">Sales Task Summary</h5>
                        <p style="color: #bcbcbc;" class="mb-0">Tasks, including today’s tasks, upcoming tasks for the
                            next week,
                            and pending tasks.</p>
                    </div>
                    <div class="col-3 col-lg-3 col-md-3 col-sm-12 select_dash_board_user">
                        <div>
                            <?php

                $sql2 = "SELECT name FROM employee";
                $result2 = $conn->query($sql2);


                echo ' <div class="form-floating form-floating-outline form-floating-select2 mt-5">';
                echo '   <div class="position-relative">';
                echo '     <select  id="SaleTaskselectempoyees" class="select2 form-select">';
                echo '       <option value="AllUsers">All Users</option>';

                if ($result2->num_rows > 0) {

                  $employees = [];
                  while ($row = $result2->fetch_assoc()) {
                    $name = htmlspecialchars($row['name']);
                    $employees[] = $name;
                  }


                  sort($employees);


                  foreach ($employees as $name) {
                    $isSelected = ($JWT_adminName == $name) ? 'selected' : '';
                    echo '<option value="' . $name . '"' . $isSelected . '>' . $name . '</option>';
                  }
                } else {

                  echo '<option value="">No Users found.</option>';
                }

                echo '     </select>';
                echo '   </div>';
                echo '   <label for="SaleTaskselectempoyees">User</label>';
                echo ' </div>';
                ?>


                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-6 mb-6">
                <!-- Task Status Related Counts Start -->
                <?php if ($today_task === 'Enable'): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-border-shadow-warning h-100">
                        <a href="salesTaskReport.php?page=salesTaskReport&name=<?php echo $JWT_adminName; ?>"
                            id="userLink1">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="avatar me-4">
                                        <span class="avatar-initial rounded-3 bg-label-warning"><img
                                                class="svg_dash_icons"
                                                src="assets/img/illustrations/svg_dash/TodayTask.svg" />
                                        </span>
                                    </div>
                                    <h4 class="mb-0" id="countToday"><?php echo $count_today; ?></h4>
                                </div>
                                <h6 class="mb-0 fw-normal">Today Task</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($task_for_next_6_days === 'Enable'): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-border-shadow-warning h-100">
                        <a href="salesTaskReport.php?page=salesTaskReport&name=<?php echo $JWT_adminName; ?>&six=6"
                            id="userLink2">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="avatar me-4">
                                        <span class="avatar-initial rounded-3 bg-label-warning"><img
                                                class="svg_dash_icons"
                                                src="assets/img/illustrations/svg_dash/6task.svg" />
                                        </span>
                                    </div>
                                    <h4 class="mb-0" id="countSixDays"><?php echo $count_six_days; ?></h4>
                                </div>
                                <h6 class="mb-0 fw-normal">Task for Next 6 Days</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($task_for_7th_to_31st_day === 'Enable'): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-border-shadow-warning h-100">
                        <a href="salesTaskReport.php?page=salesTaskReport&name=<?php echo $JWT_adminName; ?>&thirty=30"
                            id="userLink3">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="avatar me-4">
                                        <span class="avatar-initial rounded-3 bg-label-warning"><img
                                                class="svg_dash_icons"
                                                src="assets/img/illustrations/svg_dash/31Task.svg" />
                                        </span>
                                    </div>
                                    <h4 class="mb-0" id="count30Days"><?php echo $count_thirty_days; ?></h4>
                                </div>
                                <h6 class="mb-0 fw-normal">Task for 7th Day to 31th Day</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($TotalTask === 'Enable'): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-border-shadow-warning h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded-3 bg-label-warning"><img class="svg_dash_icons"
                                            src="assets/img/illustrations/svg_dash/TodayTask.svg" />
                                    </span>
                                </div>
                                <h4 class="mb-0"><?php echo $TotalSalesTask; ?></h4>
                            </div>
                            <h6 class="mb-0 fw-normal">Total Assigned Task</h6>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Task Status Related Counts End -->
            </div>
        </div>
        <?php endif; ?>


        <?php
    // Check if any report access is enabled
    if (
      $TaskPending === 'Enable' ||
      $TaskFollowUp === 'Enable' ||
      $TaskCompleted === 'Enable' ||
      $TaskNotInterested === 'Enable'
    ):
    ?>
        <div class="card mt-7 mb-7 p-5">
            <div class="card-header p-2 mb-3">
                <div class="row">
                    <div class="col-9 col-lg-9 col-md-9 col-sm-12">
                        <h5 class="mb-0">Sales Status Overview</h5>
                        <p style="color: #bcbcbc;" class="mb-0">Overview of pending, follow up, completed and not
                            interested task
                        </p>
                    </div>
                    <div class="col-3 col-lg-3 col-md- col-sm-12 select_dash_board_user">
                        <div>
                            <?php

                $sql2 = "SELECT name FROM employee";
                $result2 = $conn->query($sql2);

                echo ' <div class="form-floating form-floating-outline form-floating-select2 mt-5">';
                echo '   <div class="position-relative">';
                echo '     <select  id="StatusSaleselectempoyees" class="select2 form-select">';

                // Default option
                echo '       <option value="AllUsers">All Users</option>';


                if ($result2->num_rows > 0) {

                  $employees = [];
                  while ($row = $result2->fetch_assoc()) {
                    $name = htmlspecialchars($row['name']);
                    $employees[] = $name;
                  }

                  sort($employees);


                  foreach ($employees as $name) {
                    $isSelected = ($JWT_adminName == $name) ? 'selected' : '';
                    echo '<option value="' . $name . '"' . $isSelected . '>' . $name . '</option>';
                  }
                } else {

                  echo '<option value="">No Users found.</option>';
                }

                echo '     </select>';
                echo '   </div>';
                echo '   <label for="StatusSaleselectempoyees">User</label>';
                echo ' </div>';
                ?>


                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-6">
                <!-- Task Status Related Counts Start -->
                <?php if ($TaskPending === 'Enable'): ?>
                <div class="col-sm-6 col-lg-3">
                    <div>
                        <div class="select_dash bg-label-danger">
                            <form method="post">
                                <select id="dateRange" name="days" class="dashboard_select bg-label-danger"
                                    onchange="this.form.submit()">
                                    <option value="">All Time</option>
                                    <option value="1" <?php if ($days == 1)
                                          echo 'selected'; ?>>Last 1 Day</option>
                                    <option value="7" <?php if ($days == 7)
                                          echo 'selected'; ?>>Last 7 Days</option>
                                    <option value="15" <?php if ($days == 15)
                                            echo 'selected'; ?>>Last 15 Days</option>
                                    <option value="30" <?php if ($days == 30)
                                            echo 'selected'; ?>>Last 30 Days</option>
                                    <option value="60" <?php if ($days == 60)
                                            echo 'selected'; ?>>Last 60 Days</option>
                                    <option value="90" <?php if ($days == 90)
                                            echo 'selected'; ?>>Last 90 Days</option>
                                </select>
                            </form>
                        </div>

                        <div class="card card-border-shadow-danger h-100 custom_dash_card">
                            <a href="salesTaskReport.php?page=salesTaskReport&name=<?php echo $JWT_adminName; ?>&status=Processing"
                                id="userStatusLink1">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="avatar me-4">
                                            <span class="avatar-initial rounded-3 bg-label-danger"><img
                                                    class="svg_dash_icons"
                                                    src="assets/img/illustrations/svg_dash/pendingTask.svg" />
                                            </span>
                                        </div>
                                        <h4 class="mb-0" id="taskPendingCount"><?php echo $taskPendingCount; ?></h4>
                                    </div>
                                    <h6 class="mb-0 fw-normal">Pending Task</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($TaskFollowUp === 'Enable'): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-border-shadow-danger h-100">
                        <a href="salesTaskReport.php?page=salesTaskReport&name=<?php echo $JWT_adminName; ?>&status=Follow Up"
                            id="userStatusLink2">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="avatar me-4">
                                        <span class="avatar-initial rounded-3 bg-label-danger"><img
                                                class="svg_dash_icons"
                                                src="assets/img/illustrations/svg_dash/FollowUp.svg" />
                                        </span>
                                    </div>
                                    <h4 class="mb-0" id="taskFollowCount"><?php echo $taskFollowUpCount; ?></h4>
                                </div>
                                <h6 class="mb-0 fw-normal">Follow Up</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($TaskCompleted === 'Enable'): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-border-shadow-danger h-100">
                        <a href="salesTaskReport.php?page=salesTaskReport&name=<?php echo $JWT_adminName; ?>&status=Completed"
                            id="userStatusLink3">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="avatar me-4">
                                        <span class="avatar-initial rounded-3 bg-label-danger"><img
                                                class="svg_dash_icons"
                                                src="assets/img/illustrations/svg_dash/compltedsale.svg" />
                                        </span>
                                    </div>
                                    <h4 class="mb-0" id="taskCompletedCount"><?php echo $taskCompletedCount; ?></h4>
                                </div>
                                <h6 class="mb-0 fw-normal">Completed</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($TaskNotInterested === 'Enable'): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-border-shadow-danger h-100">
                        <a href="salesTaskReport.php?page=salesTaskReport&name=<?php echo $JWT_adminName; ?>&status=Not Interested"
                            id="userStatusLink4">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="avatar me-4">
                                        <span class="avatar-initial rounded-3 bg-label-danger"><img
                                                class="svg_dash_icons"
                                                src="assets/img/illustrations/svg_dash/notIntrrested.svg" />
                                        </span>
                                    </div>
                                    <h4 class="mb-0" id="taskNICount"><?php echo $taskNICount; ?></h4>
                                </div>
                                <h6 class="mb-0 fw-normal">Not Interested</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Task Status Related Counts Start -->
            </div>
        </div>
        <?php endif; ?>


        <?php
    // Check if any report access is enabled
    if (
      $pending_project === 'Enable' ||
      $extended_project === 'Enable' ||
      $completed_project === 'Enable'

    ):
    ?>

        <div class="card mt-7 mb-4 p-5">
            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div>
                        <img style="width:100%; margin: auto;" src="assets/img/illustrations/projectsummery.png" />
                    </div>
                </div>
                <div class="col-sm-6 col-lg-9">
                    <div class="row g-6 mb-6">
                        <div class="col-sm-12 col-lg-12">
                            <div class="card-header p-2 mb-3">
                                <div class="row">
                                    <div class="col-9 col-lg-9 col-md-9 col-sm-12">
                                        <h5 class="mb-0">Project Management Summary</h5>
                                        <p style="color: #bcbcbc;" class="mb-0">Overview of project status for users and
                                            admins.</p>
                                    </div>
                                    <div class="col-3 col-lg-3 col-md-3 col-sm-12 select_dash_board_user">
                                        <div>
                                            <?php

                        $sql2 = "SELECT name FROM employee";
                        $result2 = $conn->query($sql2);

                        echo ' <div class="form-floating form-floating-outline form-floating-select2 mt-5">';
                        echo '   <div class="position-relative">';
                        echo '     <select  id="projectselectempoyees" class="select2 form-select">';

                        // Default option
                        echo '       <option value="AllUsers">All Users</option>';


                        if ($result2->num_rows > 0) {

                          $employees = [];
                          while ($row = $result2->fetch_assoc()) {
                            $name = htmlspecialchars($row['name']);
                            $employees[] = $name;
                          }

                          sort($employees);


                          foreach ($employees as $name) {
                            $isSelected = ($JWT_adminName == $name) ? 'selected' : '';
                            echo '<option value="' . $name . '" ' . $isSelected . '>' . $name . '</option>';
                          }
                        } else {

                          echo '<option value="">No Users found.</option>';
                        }

                        echo '     </select>';
                        echo '   </div>';
                        echo '   <label for="projectselectempoyees">User</label>';
                        echo ' </div>';
                        ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-12">
                            <div class="row">
                                <!-- Project Status Related Counts Start -->
                                <?php if ($pending_project === 'Enable'): ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div>
                                        <div class="select_dash bg-label-info">
                                            <form method="post">
                                                <select id="dateRange" name="projdays"
                                                    class="dashboard_select bg-label-info"
                                                    onchange="this.form.submit()">
                                                    <option value="">All Time</option>
                                                    <option value="1" <?php if ($days == 1)
                                                  echo 'selected'; ?>>Last 1 Day</option>
                                                    <option value="7" <?php if ($days == 7)
                                                  echo 'selected'; ?>>Last 7 Days</option>
                                                    <option value="15" <?php if ($days == 15)
                                                    echo 'selected'; ?>>Last 15 Days</option>
                                                    <option value="30" <?php if ($days == 30)
                                                    echo 'selected'; ?>>Last 30 Days</option>
                                                    <option value="60" <?php if ($days == 60)
                                                    echo 'selected'; ?>>Last 60 Days</option>
                                                    <option value="90" <?php if ($days == 90)
                                                    echo 'selected'; ?>>Last 90 Days</option>
                                                </select>
                                            </form>
                                        </div>
                                        <div class="card card-border-shadow-info custom_dash_card h-100">
                                            <a href="projectReport.php?page=projectReport&name=<?php echo $JWT_adminName; ?>&status=Pending"
                                                id="userProjectLink1">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="avatar me-4">
                                                            <span class="avatar-initial rounded-3 bg-label-info"><img
                                                                    class="svg_dash_icons"
                                                                    src="assets/img/illustrations/svg_dash/penidngProject.svg" />
                                                            </span>
                                                        </div>
                                                        <h4 class="mb-0" id="pendingProject">
                                                            <?php echo $pendingProjCount; ?></h4>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">Pending Project</h6>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if ($extended_project === 'Enable'): ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div>
                                        <div class="select_dash bg-label-info">
                                            <form method="post">
                                                <select id="dateRange" name="projExtendeddays"
                                                    class="dashboard_select bg-label-info"
                                                    onchange="this.form.submit()">
                                                    <option value="">All Time</option>
                                                    <option value="1" <?php if ($days == 1)
                                                  echo 'selected'; ?>>Last 1 Day</option>
                                                    <option value="7" <?php if ($days == 7)
                                                  echo 'selected'; ?>>Last 7 Days</option>
                                                    <option value="15" <?php if ($days == 15)
                                                    echo 'selected'; ?>>Last 15 Days</option>
                                                    <option value="30" <?php if ($days == 30)
                                                    echo 'selected'; ?>>Last 30 Days</option>
                                                    <option value="60" <?php if ($days == 60)
                                                    echo 'selected'; ?>>Last 60 Days</option>
                                                    <option value="90" <?php if ($days == 90)
                                                    echo 'selected'; ?>>Last 90 Days</option>
                                                </select>
                                            </form>
                                        </div>
                                        <div class="card card-border-shadow-info custom_dash_card h-100">
                                            <a href="projectReport.php?page=projectReport&name=<?php echo $JWT_adminName; ?>&status=Extended"
                                                id="userProjectLink2">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="avatar me-4">
                                                            <span class="avatar-initial rounded-3 bg-label-info"><img
                                                                    class="svg_dash_icons"
                                                                    src="assets/img/illustrations/svg_dash/extendedproject.svg" />
                                                            </span>
                                                        </div>
                                                        <h4 class="mb-0" id="extendedProject">
                                                            <?php echo $extendedProjCount; ?></h4>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">Extended Project</h6>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if ($completed_project === 'Enable'): ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card card-border-shadow-info h-100">
                                        <a href="projectReport.php?page=projectReport&name=<?php echo $JWT_adminName; ?>&status=Completed"
                                            id="userProjectLink3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="avatar me-4">
                                                        <span class="avatar-initial rounded-3 bg-label-info"><img
                                                                class="svg_dash_icons"
                                                                src="assets/img/illustrations/svg_dash/CompletedProject.svg" />
                                                        </span>
                                                    </div>
                                                    <h4 class="mb-0" id="completedProject">
                                                        <?php echo $completedProjCount; ?></h4>
                                                </div>
                                                <h6 class="mb-0 fw-normal">Completed Project </h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <!-- Project Status Related Counts End -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php
    // Check if any report access is enabled
    if (
      $reminder_count === 'Enable' ||
      $document_count === 'Enable'


    ):
    ?>

        <div class="card mt-7 mb-4 p-5">
            <div class="row g-6 mt-3">
                <?php if ($reminder_count === 'Enable'): ?>
                <!-- Reminders and Documents Related Counts End -->
                <div class="col-sm-6 col-lg-4">
                    <div class="card card-border-shadow-success h-100">
                        <a href="reminderReport.php?page=reminderReport&name=<?php echo $JWT_adminName; ?>">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="avatar me-4">
                                        <span class="avatar-initial rounded-3 bg-label-success"><img
                                                class="svg_dash_icons"
                                                src="assets/img/illustrations/svg_dash/Reminder.svg" />
                                        </span>
                                    </div>
                                    <h4 class="mb-0"><?php echo $reminderCount; ?></h4>
                                </div>
                                <h6 class="mb-0 fw-normal">Total Reminder</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($document_count === 'Enable'): ?>
                <!-- <div class="col-sm-6 col-lg-4">
              <div class="card card-border-shadow-success h-100">
                <a href="Documents.php?page=Documents&name=<?php echo $JWT_adminName; ?>">
                  <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div class="avatar me-4">
                        <span class="avatar-initial rounded-3 bg-label-success"><img class="svg_dash_icons"
                            src="assets/img/illustrations/svg_dash/Document.svg" />
                        </span>
                      </div>
                      <h4 class="mb-0"><?php echo $docCount; ?></h4>
                    </div>
                    <h6 class="mb-0 fw-normal">Total Document</h6>
                  </div>
                </a>
              </div>
            </div> -->
                <?php endif; ?>
                <!-- Reminders and Documents Related Counts End -->

            </div>
        </div>
        <?php endif; ?>

    </div>


    <!-- <div class="floating_dash_option" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Analytical Dashboard">

    <div class="dash_Btn_container">
      <a class="dash_Btn is-play" href="GioDashboard.php">
        <div class="button-outer-circle has-scale-animation"></div>
        <div class="button-outer-circle has-scale-animation has-delay-short"></div>
        <div class="button-icon is-play">
          <i class="ri-line-chart-line ri-24px"></i>
        </div>
      </a>
    </div>
  </div> -->


    <!-- / Content -->
    <?php include('include/footer.php'); ?>


    <script>
    $(document).ready(function() {
        // Get the dropdown, link, and the element displaying the task count
        var selectElement = $('#SaleTaskselectempoyees');
        var linkElement1 = $('#userLink1');
        var linkElement2 = $('#userLink2');
        var linkElement3 = $('#userLink3');
        var countElement = $('#countToday'); // This is the element showing the task count
        var countSixDays = $('#countSixDays');
        var count30Days = $('#count30Days');

        selectElement.on('change', function() {

            var selectedValue = selectElement.val();

            // Update the href attribute of the link
            linkElement1.attr('href', 'salesTaskReport.php?page=salesTaskReport&name=' + selectedValue);
            linkElement2.attr('href', 'salesTaskReport.php?page=salesTaskReport&name=' + selectedValue);
            linkElement3.attr('href', 'salesTaskReport.php?page=salesTaskReport&name=' + selectedValue);

            // Perform an AJAX request to fetch the new count based on the selected user
            $.ajax({
                url: 'include/handlers/GeneralFetchHandler.php',
                type: 'GET',
                data: {
                    user: selectedValue,
                    action: 'GetTodayTaskCount'
                }, // Pass the selected user value to the backend
                success: function(response) {
                    // Assuming the response is a JSON object with the count
                    var data = JSON.parse(response);

                    // Update the count on the page
                    countElement.text(data.count);
                },
                error: function() {
                    // Handle error (optional)
                    alert('Error fetching task count.');
                }
            });

            $.ajax({
                url: 'include/handlers/GeneralFetchHandler.php',
                type: 'GET',
                data: {
                    user: selectedValue,
                    action: 'GetSixDaysCount'
                }, // Pass the selected user value to the backend
                success: function(response) {
                    // Assuming the response is a JSON object with the count
                    var data = JSON.parse(response);

                    // Update the count on the page
                    countSixDays.text(data.count);
                },
                error: function() {
                    // Handle error (optional)
                    alert('Error fetching task count.');
                }
            });

            $.ajax({
                url: 'include/handlers/GeneralFetchHandler.php',
                type: 'GET',
                data: {
                    user: selectedValue,
                    action: 'Get30DaysCount'
                }, // Pass the selected user value to the backend
                success: function(response) {
                    // Assuming the response is a JSON object with the count
                    var data = JSON.parse(response);

                    // Update the count on the page
                    count30Days.text(data.count);
                },
                error: function() {
                    // Handle error (optional)
                    alert('Error fetching task count.');
                }
            });
        });



    });


    $(document).ready(function() {

        var selectElementProject = $('#projectselectempoyees');
        var linkProj1 = $('#userProjectLink1');
        var linkProj2 = $('#userProjectLink2');
        var linkProj3 = $('#userProjectLink3');

        var pendingProject = $('#pendingProject'); // This is the element showing the task count
        var extendedProject = $('#extendedProject');
        var completedProject = $('#completedProject');


        selectElementProject.on('change', function() {
            var selectedValueProj = selectElementProject.val();

            // Update the href attribute of the link
            linkProj1.attr('href', 'projectReport.php?page=projectReport&name=' + selectedValueProj +
                '&status=Pending');
            linkProj2.attr('href', 'projectReport.php?page=projectReport&name=' + selectedValueProj +
                '&status=Extended');
            linkProj3.attr('href', 'projectReport.php?page=projectReport&name=' + selectedValueProj +
                '&status=Completed');


            // Perform an AJAX request to fetch the new count based on the selected user
            $.ajax({
                url: 'include/handlers/GeneralFetchHandler.php',
                type: 'GET',
                data: {
                    user: selectedValueProj,
                    action: 'GetProjectCount'
                }, // Pass the selected user value to the backend
                success: function(response) {
                    // Assuming the response is a JSON object with the count
                    var data = JSON.parse(response);


                    pendingProject.text(data.pendingCount);
                    extendedProject.text(data.extendedCount);
                    completedProject.text(data.completedCount);

                },
                error: function() {
                    // Handle error (optional)
                    alert('Error fetching task count.');
                }
            });


        });
    });





    $(document).ready(function() {

        var selectElementtask = $('#StatusSaleselectempoyees');
        var linkElement1 = $('#userStatusLink1');
        var linkElement2 = $('#userStatusLink2');
        var linkElement3 = $('#userStatusLink3');
        var linkElement4 = $('#userStatusLink4');
        var taskPendingCount = $('#taskPendingCount'); // This is the element showing the task count
        var taskFollowCount = $('#taskFollowCount');
        var taskCompletedCount = $('#taskCompletedCount');
        var taskNICount = $('#taskNICount');

        selectElementtask.on('change', function() {
            var selectedValuetask = selectElementtask.val();

            // Update the href attribute of the link
            linkElement1.attr('href', 'salesTaskReport.php?page=salesTaskReport&name=' +
                selectedValuetask + '&status=Processing');
            linkElement2.attr('href', 'salesTaskReport.php?page=salesTaskReport&name=' +
                selectedValuetask + '&status=Follow Up');
            linkElement3.attr('href', 'salesTaskReport.php?page=salesTaskReport&name=' +
                selectedValuetask + '&status=Completed');
            linkElement4.attr('href', 'salesTaskReport.php?page=salesTaskReport&name=' +
                selectedValuetask + '&status=Not Interested');

            // Perform an AJAX request to fetch the new count based on the selected user
            $.ajax({
                url: 'include/handlers/GeneralFetchHandler.php',
                type: 'GET',
                data: {
                    user: selectedValuetask,
                    action: 'GetTaskCount'
                }, // Pass the selected user value to the backend
                success: function(response) {
                    // Assuming the response is a JSON object with the count
                    var data = JSON.parse(response);


                    taskPendingCount.text(data.pendingCount);
                    taskFollowCount.text(data.followCount);
                    taskCompletedCount.text(data.completedCount);
                    taskNICount.text(data.niCount);
                },
                error: function() {
                    // Handle error (optional)
                    alert('Error fetching task count.');
                }
            });


        });
    });
    </script>