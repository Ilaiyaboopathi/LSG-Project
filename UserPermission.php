<?php include('include/head.php'); ?>
<?php require 'data/dbconfig.php';
$name = '';
$email = '';
$designation = '';
$mobile = '';
$role = '';
$active = '';
$role_active = '';
$roleUserName = '';

if (isset($_GET['name'])) {
  $roleUserName = $_GET['name'];
}
if (isset($_GET['id'])) {
  $role_active = "active";
  $id = $_GET['id']; // Sanitize and validate the ID
  $sql = "SELECT * FROM permissions WHERE userID='$id' ";
  $result = $conn->query($sql);

  $number_of_employees = '';
  $number_of_active_employees = '';
  $inactive_employees = '';
  $number_of_admin = '';
  $active_admin = '';
  $inactive_admin = '';
  $today_task = '';
  $task_for_next_6_days = '';
  $task_for_7th_to_31st_day = '';
  $TotalTask = '';
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
  $AddNewEmployee = '';
  $AddNewClient='';
  $AssignJob='';
  $AssignedJob='';
  $JobsExcel='';
  $BulkUser = '';
  $UserRoles = '';
  $AddNewSaleTask = '';
  $TaskReply = '';
  $AddNewProject = '';
  $ProjectReply = '';
  $AddNewReminder = '';
  $ReminderViews = '';
  $AddNewDocument = '';
  $DocumentViews = '';
  $SalesTask = '';
  $TaskViews = '';
  $AddDepartment = '';
  $DepartmentViews = '';
  $SettingAddDesignation = '';
  $SettingDesignationView = '';
  $SettingAddLogo = '';
  $SettingLogoView = '';
  $ReportDownloadAccess = '';
  $ReportEmployeeAccess = '';
  $ReportSalesTaskAccess = '';
  $ReportProjectTaskAccess = '';
  $ReportReminderAccess = '';
  $ReportLogAccess = '';
  $ProfilePicAdd = '';
  $ChangePassword = '';
  $UserExcel = '';
  $SalesExcel = '';
  $ProjectExcel = '';
  $ReminderExcel = '';
  $LogExcel = '';
  $DeleteLog = '';
  $DeleteDocuments = '';
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $number_of_employees = htmlspecialchars($row['number_of_employees']);
    $number_of_active_employees = htmlspecialchars($row['number_of_active_employees']);
    $inactive_employees = $row['inactive_employees'];
    $number_of_admin = htmlspecialchars($row['number_of_admin']);
    $active_admin = htmlspecialchars($row['active_admin']);
    $inactive_admin = htmlspecialchars($row['inactive_admin']);
    $today_task = htmlspecialchars($row['today_task']); // Fetch the role from the database
    $task_for_next_6_days = htmlspecialchars($row['task_for_next_6_days']);
    $task_for_7th_to_31st_day = htmlspecialchars($row['task_for_7th_to_31st_day']);
    $TotalTask = htmlspecialchars($row['TotalTask']);
    $TaskPending = htmlspecialchars($row['TaskPending']);
    $TaskFollowUp = htmlspecialchars($row['TaskFollowUp']);
    $TaskCompleted = htmlspecialchars($row['TaskCompleted']);
    $TaskNotInterested =  htmlspecialchars($row['TaskNotInterested']);
    $project_all_time = htmlspecialchars($row['project_all_time']);
    $pending_project = htmlspecialchars($row['pending_project']);
    $extended_project = htmlspecialchars($row['extended_project']);
    $completed_project = htmlspecialchars($row['completed_project']);
    $reminder_count = htmlspecialchars($row['reminder_count']);
    $document_count = htmlspecialchars($row['document_count']);
    $AddNewEmployee = htmlspecialchars($row['AddNewEmployee']);
    $BulkUser = htmlspecialchars($row['BulkUser']);
    $UserRoles = htmlspecialchars($row['UserRoles']);
    $AddNewClient = htmlspecialchars($row['AddNewClient']);
    $AssignJob = htmlspecialchars($row['AssignJob']);
    $AssignedJob = htmlspecialchars($row['AssignedJob']);
    $JobsExcel = htmlspecialchars($row['JobsExcel']);
    $AddNewSaleTask = htmlspecialchars($row['AddNewSaleTask']);
    $TaskReply = htmlspecialchars($row['TaskReply']);
    $AddNewProject = htmlspecialchars($row['AddNewProject']);
    $ProjectReply = htmlspecialchars($row['ProjectReply']);
    $AddNewReminder = htmlspecialchars($row['AddNewReminder']);

    $ReminderViews = htmlspecialchars($row['ReminderViews']);
    $AddNewDocument = htmlspecialchars($row['AddNewDocument']);
    $DocumentViews = htmlspecialchars($row['DocumentViews']);
    $SalesTask = htmlspecialchars($row['SettingSalesTask']);

    $TaskViews = htmlspecialchars($row['SettingTaskViews']);
    $AddDepartment = htmlspecialchars($row['SettingAddDepartment']);
    $DepartmentViews = htmlspecialchars($row['SettingDepartmentViews']);
    $SettingAddDesignation =  htmlspecialchars($row['SettingAddDesignation']);
    $SettingDesignationView =  htmlspecialchars($row['SettingDesignationView']);
    $SettingAddLogo =  htmlspecialchars($row['SettingAddLogo']);
    $SettingLogoView =  htmlspecialchars($row['SettingLogoView']);
    $ReportDownloadAccess = htmlspecialchars($row['ReportDownloadAccess']);
    $ReportEmployeeAccess = htmlspecialchars($row['ReportEmployeeAccess']);

    $ReportSalesTaskAccess = htmlspecialchars($row['ReportSalesTaskAccess']);
    $ReportProjectTaskAccess = htmlspecialchars($row['ReportProjectTaskAccess']);
    $ReportReminderAccess = htmlspecialchars($row['ReportReminderAccess']);
    $ReportLogAccess = htmlspecialchars($row['ReportLogAccess']);
    $ProfilePicAdd = htmlspecialchars($row['ProfilePicAdd']);
    $ChangePassword = htmlspecialchars($row['ChangePassword']);
    $UserExcel = htmlspecialchars($row['UserExcel']);
    $SalesExcel = htmlspecialchars($row['SalesExcel']);
    $ProjectExcel = htmlspecialchars($row['ProjectExcel']);
    $ReminderExcel = htmlspecialchars($row['ReminderExcel']);
    $LogExcel = htmlspecialchars($row['LogExcel']);
    $DeleteLog = htmlspecialchars($row['DeleteLog']);
    $DeleteDocuments = htmlspecialchars($row['DeleteDocuments']);
  }
}






$sql = "SELECT * FROM employee ORDER BY id DESC ";
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
    );
  }
}
$response = array('data' => $data);
$json = json_encode($response, JSON_PRETTY_PRINT);
//echo json_encode($response, JSON_PRETTY_PRINT);

$file = 'assets/json/employee_data.json';


if (file_put_contents($file, $json)) {
  // echo "Data successfully written to $file";
} else {
  echo "Failed to write data to $file";
}

?>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="row">

      <div
        class="container mt-5 hided_initially <?php echo empty($role_active) ? '' : htmlspecialchars($role_active); ?>">
        <!-- Estimate Form code Start-->
        <form id="addPermissionEmp">
          <div class="d-flex justify-content-center">
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

                    <!-- Header Permission Column Start -->
                    <div class="col-12">
                      <h6 style="padding-left: 8px;"><b>
                          <?php echo empty($roleUserName) ? '' : htmlspecialchars($roleUserName); ?>
                        </b> User Access</h6>
                      <hr class="mt-0">
                    </div>
                    <!-- Header Permission Column Start -->

                    <!-- Left Column Start -->
                    <div class="col-md-3">
                      <aside id="layout-menu" class="menu-vertical menu bg-menu-theme">
                        <ul class="menu-inner py-1">
                          <li class="menu-item access_menu_list">
                            <a href="javascript:void(0);" data-target="#access_section1"
                              class="menu-link menu-toggle waves-effect access_menu ">
                              <i class="menu-icon tf-icons ri-dashboard-2-line"></i>
                              <div data-i18n="Dashboard">Dashboard</div>
                            </a>
                          </li>
                          <li class="menu-item access_menu_list ">
                            <a href="javascript:void(0);" data-target="#access_section2"
                              class="menu-link menu-toggle waves-effect access_menu">
                              <i class="menu-icon tf-icons ri-shield-user-line"></i>
                              <div data-i18n="Add User">Add User</div>
                            </a>
                          </li>
                          <li class="menu-item access_menu_list ">
                            <a href="javascript:void(0);" data-target="#access_section3"
                              class="menu-link menu-toggle waves-effect access_menu">
                              <i class="menu-icon tf-icons ri-list-check-3"></i>
                              <div data-i18n="Sales Appointment">Sales Appointment</div>
                            </a>
                          </li>
                          <li class="menu-item access_menu_list ">
                            <a href="javascript:void(0);" data-target="#access_section4"
                              class="menu-link menu-toggle waves-effect access_menu">
                              <i class="menu-icon tf-icons ri-sticky-note-add-line"></i>
                              <div data-i18n="Assign Project">Assign Project</div>
                            </a>
                          </li>
                          <li class="menu-item access_menu_list ">
                            <a href="javascript:void(0);" data-target="#access_section9"
                              class="menu-link menu-toggle waves-effect access_menu">
                              <i class="menu-icon tf-icons ri-sticky-note-add-line"></i>
                              <div data-i18n="Create New Job">Create New Job</div>
                            </a>
                          </li>
                          <li class="menu-item access_menu_list ">
                            <a href="javascript:void(0);" data-target="#access_section10"
                              class="menu-link menu-toggle waves-effect access_menu">
                              <i class="menu-icon tf-icons ri-sticky-note-add-line"></i>
                              <div data-i18n="View Jobs">View Jobs</div>
                            </a>
                          </li>
                          <li class="menu-item access_menu_list ">
                            <a href="javascript:void(0);" data-target="#access_section5"
                              class="menu-link menu-toggle waves-effect access_menu">
                              <i class="menu-icon tf-icons ri-calendar-schedule-line"></i>
                              <div data-i18n="Reminders">Reminders</div>
                            </a>
                          </li>
                          <!-- <li class="menu-item access_menu_list ">
                            <a href="javascript:void(0);" data-target="#access_section6"
                              class="menu-link menu-toggle waves-effect access_menu">
                              <i class="menu-icon tf-icons ri-file-pdf-2-line"></i>
                              <div data-i18n="Documents">Documents</div>
                            </a>
                          </li> -->
                          <li class="menu-item access_menu_list ">
                            <a href="javascript:void(0);" data-target="#access_section7"
                              class="menu-link menu-toggle waves-effect access_menu">
                              <i class="menu-icon tf-icons ri-radio-button-line"></i>
                              <div data-i18n="Settings">Settings</div>
                            </a>
                          </li>
                          <li class="menu-item access_menu_list ">
                            <a href="javascript:void(0);" data-target="#access_section8"
                              class="menu-link menu-toggle waves-effect access_menu">
                              <i class="menu-icon tf-icons ri-calculator-line"></i>
                              <div data-i18n="Report">Report</div>
                            </a>
                          </li>
                        </ul>
                      </aside>
                    </div>
                    <!-- Left Column End -->


                    <!-- Right Column Start -->
                    <div class="col-md-9">
                      <div class="card bg-menu-theme Access_View_Section" id="access_section1">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          User Status Permissions
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the dashboard user analytics"
                                            data-bs-original-title="Allows a full access to the dashboard user analytics"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="UserStatusSelectAll">
                                            <label class="form-check-label"
                                              for="UserStatusSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Number of
                                        Users</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enable_no_of_employee">
                                                <input type="radio"
                                                  name="noOfEmployee"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enable_no_of_employee"
                                                  <?php echo ($number_of_employees  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disable_no_of_employee">
                                                <input type="radio"
                                                  name="noOfEmployee"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disable_no_of_employee"
                                                  <?php echo ($number_of_employees === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Number of
                                        Active Users</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableNumberOfActiveEmployees">
                                                <input type="radio"
                                                  name="activeEmployees"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableNumberOfActiveEmployees"
                                                  <?php echo ($number_of_active_employees  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableNumberOfActiveEmployees">
                                                <input type="radio"
                                                  name="activeEmployees"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableNumberOfActiveEmployees"
                                                  <?php echo ($number_of_active_employees  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Inactive
                                        Users</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableInactiveEmployees">
                                                <input type="radio"
                                                  name="inActiveEmployees"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableInactiveEmployees"
                                                  <?php echo ($inactive_employees  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableInactiveEmployees">
                                                <input type="radio"
                                                  name="inActiveEmployees"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableInactiveEmployees"
                                                  <?php echo ($inactive_employees  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Number of
                                        Admin</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableNumberOfAdmin">
                                                <input type="radio"
                                                  name="numberOfAdmin"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableNumberOfAdmin"
                                                  <?php echo ($number_of_admin  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableNumberOfAdmin">
                                                <input type="radio"
                                                  name="numberOfAdmin"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableNumberOfAdmin"
                                                  <?php echo ($number_of_admin  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Active
                                        Admin</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableActiveAdmin">
                                                <input type="radio"
                                                  name="activeAdmin"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableActiveAdmin"
                                                  <?php echo ($active_admin  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableActiveAdmin">
                                                <input type="radio"
                                                  name="activeAdmin"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableActiveAdmin"
                                                  <?php echo ($active_admin  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Inactive
                                        Admin</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableInactiveAdmin">
                                                <input type="radio"
                                                  name="inActiveAdmin"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableInactiveAdmin"
                                                  <?php echo ($inactive_admin  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableInactiveAdmin">
                                                <input type="radio"
                                                  name="inActiveAdmin"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableInactiveAdmin"
                                                  <?php echo ($inactive_admin  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Sales Appointment Summary
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the dashboard sales task management summary"
                                            data-bs-original-title="Allows a full access to the dashboard sales task management summary"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="SaleSelectAll">
                                            <label class="form-check-label"
                                              for="SaleSelectAll"> Enable
                                              All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Today Task
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableTodayTask">
                                                <input type="radio"
                                                  name="TodayTask"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableTodayTask"
                                                  <?php echo ($today_task  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTodayTask">
                                                <input type="radio"
                                                  name="TodayTask"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTodayTask"
                                                  <?php echo ($today_task  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Task for
                                        Next 6 Days</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableTaskNext6Days">
                                                <input type="radio"
                                                  name="TaskNext6Days"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableTaskNext6Days"
                                                  <?php echo ($task_for_next_6_days  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTaskNext6Days">
                                                <input type="radio"
                                                  name="TaskNext6Days"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTaskNext6Days"
                                                  <?php echo ($task_for_next_6_days  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Task for
                                        7th Day to 31th Day</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableTask7thDayTo31thDay">
                                                <input type="radio"
                                                  name="Task7thDayTo31thDay"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableTask7thDayTo31thDay"
                                                  <?php echo ($task_for_7th_to_31st_day === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTask7thDayTo31thDay">
                                                <input type="radio"
                                                  name="Task7thDayTo31thDay"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTask7thDayTo31thDay"
                                                  <?php echo ($task_for_7th_to_31st_day === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Total
                                        Assigned Task</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableTaskTotal">
                                                <input type="radio"
                                                  name="TaskTotal"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableTaskTotal"
                                                  <?php echo ($TotalTask === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTaskTotal">
                                                <input type="radio"
                                                  name="TaskTotal"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTaskTotal"
                                                  <?php echo ($TotalTask === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Pending
                                        Task </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableTaskPending">
                                                <input type="radio"
                                                  name="TaskPending"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableTaskPending"
                                                  <?php echo ($TaskPending === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTaskPending">
                                                <input type="radio"
                                                  name="TaskPending"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTaskPending"
                                                  <?php echo ($TaskPending === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>

                                    <tr>
                                      <td class="text-nowrap fw-medium">Follow Up
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableTaskFollowUp">
                                                <input type="radio"
                                                  name="TaskFollowUp"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableTaskFollowUp"
                                                  <?php echo ($TaskFollowUp === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTaskFollowUp">
                                                <input type="radio"
                                                  name="TaskFollowUp"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTaskFollowUp"
                                                  <?php echo ($TaskFollowUp === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>

                                    <tr>
                                      <td class="text-nowrap fw-medium">Completed
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableTaskCompleted">
                                                <input type="radio"
                                                  name="TaskCompleted"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableTaskCompleted"
                                                  <?php echo ($TaskCompleted === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTaskCompleted">
                                                <input type="radio"
                                                  name="TaskCompleted"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTaskCompleted"
                                                  <?php echo ($TaskCompleted === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>


                                    <tr>
                                      <td class="text-nowrap fw-medium">Not
                                        Interested</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableTaskNotInterested">
                                                <input type="radio"
                                                  name="TaskNotInterested"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableTaskNotInterested"
                                                  <?php echo ($TaskNotInterested === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTaskNotInterested">
                                                <input type="radio"
                                                  name="TaskNotInterested"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTaskNotInterested"
                                                  <?php echo ($TaskNotInterested === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Project Management Summary
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the dashboard project management summary"
                                            data-bs-original-title="Allows a full access to the dashboard project management summary"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="ProjectSelectAll">
                                            <label class="form-check-label"
                                              for="ProjectSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr style="display: none;">
                                      <td style="display: none;"
                                        class="text-nowrap fw-medium">Project
                                        All Time</td>
                                      <td>
                                        <div class="d-flex justify-content-end"
                                          style="display: none;">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableProjectAllTime">
                                                <input type="radio"
                                                  name="ProjectAllTime"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableProjectAllTime"
                                                  <?php echo ($project_all_time === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableProjectAllTime">
                                                <input type="radio"
                                                  name="ProjectAllTime"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableProjectAllTime"
                                                  <?php echo ($project_all_time === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Pending
                                        Project</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnablePendingProject">
                                                <input type="radio"
                                                  name="PendingProject"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnablePendingProject"
                                                  <?php echo ($pending_project === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisablePendingProject">
                                                <input type="radio"
                                                  name="PendingProject"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisablePendingProject"
                                                  <?php echo ($pending_project === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Extended
                                        Project</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableExtendedProject">
                                                <input type="radio"
                                                  name="ExtendedProject"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableExtendedProject"
                                                  <?php echo ($extended_project === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableExtendedProject">
                                                <input type="radio"
                                                  name="ExtendedProject"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableExtendedProject"
                                                  <?php echo ($extended_project === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Completed
                                        Project</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableCompletedProject">
                                                <input type="radio"
                                                  name="CompletedProject"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableCompletedProject"
                                                  <?php echo ($completed_project === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableCompletedProject">
                                                <input type="radio"
                                                  name="CompletedProject"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableCompletedProject"
                                                  <?php echo ($completed_project === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Reminder Summary
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the dashboard reminder summary"
                                            data-bs-original-title="Allows a full access to the dashboard reminder summary"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="ReminderSelectAll">
                                            <label class="form-check-label"
                                              for="ReminderSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Total
                                        Reminder</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableReminderCount">
                                                <input type="radio"
                                                  name="ReminderCount"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableReminderCount"
                                                  <?php echo ($reminder_count === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableReminderCount">
                                                <input type="radio"
                                                  name="ReminderCount"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableReminderCount"
                                                  <?php echo ($reminder_count === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Document Summary
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the dashboard document summary"
                                            data-bs-original-title="Allows a full access to the dashboard document summary"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="DocumentSelectAll">
                                            <label class="form-check-label"
                                              for="DocumentSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Total
                                        Document</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="EnableDocumentCount">
                                                <input type="radio"
                                                  name="DocumentCount"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="EnableDocumentCount"
                                                  <?php echo ($document_count === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableDocumentCount">
                                                <input type="radio"
                                                  name="DocumentCount"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableDocumentCount"
                                                  <?php echo ($document_count === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="card bg-menu-theme Access_View_Section" id="access_section2">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Add User
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Add Employee Module"
                                            data-bs-original-title="Allows a full access to the Add Employee Module"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="AddEmployeeSelectAll">
                                            <label class="form-check-label"
                                              for="AddEmployeeSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Add New
                                        User</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableAddNewEmployee">
                                                <input type="radio"
                                                  name="AddNewEmployee"

                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAddNewEmployee"
                                                  <?php echo ($AddNewEmployee === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableAddNewEmployee">
                                                <input type="radio"
                                                  name="AddNewEmployee"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableAddNewEmployee"
                                                  <?php echo ($AddNewEmployee === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Add New
                                        Client</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableAddNewClient">
                                                <input type="radio"
                                                  name="AddNewClient"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAddNewClient"
                                                  <?php echo ($AddNewClient === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableAddNewClient">
                                                <input type="radio"
                                                  name="AddNewClient"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableAddNewClient"
                                                  <?php echo ($AddNewClient === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Bulk
                                        Upload Users</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableBulkUser">
                                                <input type="radio"
                                                  name="BulkUser"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableBulkUser"
                                                  <?php echo ($BulkUser === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableBulkUser">
                                                <input type="radio"
                                                  name="BulkUser"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableBulkUser"
                                                  <?php echo ($BulkUser === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">User Roles
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableUserRoles">
                                                <input type="radio"
                                                  name="UserRoles"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableUserRoles"
                                                  <?php echo ($UserRoles === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableUserRoles">
                                                <input type="radio"
                                                  name="UserRoles"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableUserRoles"
                                                  <?php echo ($UserRoles === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card bg-menu-theme Access_View_Section" id="access_section3">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Sales Appointment
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Sales Task Module"
                                            data-bs-original-title="Allows a full access to the Sales Task Module"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="SalesSelectAll">
                                            <label class="form-check-label"
                                              for="SalesSelectAll"> Enable
                                              All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Add New
                                        Sales Appointment</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableAddNewSaleTask">
                                                <input type="radio"
                                                  name="AddNewSaleTask"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAddNewSaleTask"
                                                  <?php echo ($AddNewSaleTask === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableAddNewSaleTask">
                                                <input type="radio"
                                                  name="AddNewSaleTask"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableAddNewSaleTask"
                                                  <?php echo ($AddNewSaleTask === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        Appointment Reply</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableTaskReply">
                                                <input type="radio"
                                                  name="TaskReply"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableTaskReply"
                                                  <?php echo ($TaskReply === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTaskReply">
                                                <input type="radio"
                                                  name="TaskReply"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTaskReply"
                                                  <?php echo ($TaskReply === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card bg-menu-theme Access_View_Section" id="access_section4">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Assign Project
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Project Module"
                                            data-bs-original-title="Allows a full access to the Assign Project Module"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="ProjectSelectAll">
                                            <label class="form-check-label"
                                              for="ProjectSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Add New
                                        Project</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableAddNewProject">
                                                <input type="radio"
                                                  name="AddNewProject"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAddNewProject"
                                                  <?php echo ($AddNewProject === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableAddNewProject">
                                                <input type="radio"
                                                  name="AddNewProject"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableAddNewProject"
                                                  <?php echo ($AddNewProject === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Project
                                        Reply</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableProjectReply">
                                                <input type="radio"
                                                  name="ProjectReply"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableProjectReply"
                                                  <?php echo ($ProjectReply === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableProjectReply">
                                                <input type="radio"
                                                  name="ProjectReply"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableProjectReply"
                                                  <?php echo ($ProjectReply === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card bg-menu-theme Access_View_Section" id="access_section9">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Create New Job
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Project Module"
                                            data-bs-original-title="Allows a full access to the Assign Project Module"></i>
                                        </h6>
                                       
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="ProjectSelectAll">
                                            <label class="form-check-label"
                                              for="ProjectSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    
                                    <tr>
                                      <td class="text-nowrap fw-medium">Create New Job
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableAssignJob">
                                                <input type="radio"
                                                  name="AssignJob"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAssignJob"
                                                  <?php echo ($AssignJob === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableAssignJob">
                                                <input type="radio"
                                                  name="AssignJob"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableAssignJob"
                                                  <?php echo ($AssignJob === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card bg-menu-theme Access_View_Section" id="access_section10">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          View Jobs
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Project Module"
                                            data-bs-original-title="Allows a full access to the Assign Project Module"></i>
                                        </h6>
                                       
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="ProjectSelectAll">
                                            <label class="form-check-label"
                                              for="ProjectSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                   
                                    <tr>
                                      <td class="text-nowrap fw-medium">View Jobs
                                        </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableAssignedJob">
                                                <input type="radio"
                                                  name="AssignedJob"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAssignedJob"
                                                  <?php echo ($AssignedJob === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableAssignedJob">
                                                <input type="radio"
                                                  name="AssignedJob"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableAssignedJob"
                                                  <?php echo ($AssignedJob === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Download Jobs Excel
                                        </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableJobsExcel">
                                                <input type="radio"
                                                  name="JobsExcel"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableJobsExcel"
                                                  <?php echo ($JobsExcel === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableJobsExcel">
                                                <input type="radio"
                                                  name="JobsExcel"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableJobsExcel"
                                                  <?php echo ($JobsExcel === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card bg-menu-theme Access_View_Section" id="access_section5">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Reminder Module
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Reminder Module"
                                            data-bs-original-title="Allows a full access to the Assign Reminder Module"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="ReminderSelectAll">
                                            <label class="form-check-label"
                                              for="ReminderSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Add New
                                        Reminder</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableAddNewReminder">
                                                <input type="radio"
                                                  name="AddNewReminder"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAddNewReminder"
                                                  <?php echo ($AddNewReminder === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableAddNewReminder">
                                                <input type="radio"
                                                  name="AddNewReminder"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableAddNewReminder"
                                                  <?php echo ($AddNewReminder === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Reminder
                                        Views</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableReminderViews">
                                                <input type="radio"
                                                  name="ReminderViews"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableReminderViews"
                                                  <?php echo ($ReminderViews === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableReminderViews">
                                                <input type="radio"
                                                  name="ReminderViews"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableReminderViews"
                                                  <?php echo ($ReminderViews === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card bg-menu-theme Access_View_Section" id="access_section6">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Document Module
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Document Module"
                                            data-bs-original-title="Allows a full access to the Assign Document Module"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="DocumentSelectAll">
                                            <label class="form-check-label"
                                              for="DocumentSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Add New
                                        Document</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableAddNewDocument">
                                                <input type="radio"
                                                  name="AddNewDocument"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAddNewDocument"
                                                  <?php echo ($AddNewDocument === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableAddNewDocument">
                                                <input type="radio"
                                                  name="AddNewDocument"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableAddNewDocument"
                                                  <?php echo ($AddNewDocument === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Document
                                        Views</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableDocumentViews">
                                                <input type="radio"
                                                  name="DocumentViews"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableDocumentViews"
                                                  <?php echo ($DocumentViews === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableDocumentViews">
                                                <input type="radio"
                                                  name="DocumentViews"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableDocumentViews"
                                                  <?php echo ($DocumentViews === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>

                                    <!-- <tr>
                                      <td class="text-nowrap fw-medium">Document
                                        Delete</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableDocumentDelete">
                                                <input type="radio"
                                                  name="DocumentDelete"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableDocumentDelete"
                                                  <?php echo ($DeleteDocuments === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableDocumentDelete">
                                                <input type="radio"
                                                  name="DocumentDelete"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableDocumentDelete"
                                                  <?php echo ($DeleteDocuments === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr> -->
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card bg-menu-theme Access_View_Section" id="access_section7">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Sales Appointment Settings
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Sales Task Module"
                                            data-bs-original-title="Allows a full access to the Sales Task Settings Module"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="SalesSettingsSelectAll">
                                            <label class="form-check-label"
                                              for="SalesSettingsSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Sales
                                        Appointment Type</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableSalesTask">
                                                <input type="radio"
                                                  name="SalesTask"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableSalesTask"
                                                  <?php echo ($SalesTask === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableSalesTask">
                                                <input type="radio"
                                                  name="SalesTask"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableSalesTask"
                                                  <?php echo ($SalesTask === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Task Views
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableTaskViews">
                                                <input type="radio"
                                                  name="TaskViews"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableTaskViews"
                                                  <?php echo ($TaskViews === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableTaskViews">
                                                <input type="radio"
                                                  name="TaskViews"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableTaskViews"
                                                  <?php echo ($TaskViews === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Add Department
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Add Department"
                                            data-bs-original-title="Allows a full access to the Add Department"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="AddDepartmentSelectAll">
                                            <label class="form-check-label"
                                              for="AddDepartmentSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Add
                                        Department</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableAddDepartment">
                                                <input type="radio"
                                                  name="AddDepartment"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAddDepartment"
                                                  <?php echo ($AddDepartment === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableAddDepartment">
                                                <input type="radio"
                                                  name="AddDepartment"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableAddDepartment"
                                                  <?php echo ($AddDepartment === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Department
                                        Views</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableDepartmentViews">
                                                <input type="radio"
                                                  name="DepartmentViews"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableDepartmentViews"
                                                  <?php echo ($DepartmentViews === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableDepartmentViews">
                                                <input type="radio"
                                                  name="DepartmentViews"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableDepartmentViews"
                                                  <?php echo ($DepartmentViews === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>

                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Add Designation
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Add Department"
                                            data-bs-original-title="Allows a full access to the Add Department"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="AddDesignationSelectAll">
                                            <label class="form-check-label"
                                              for="AddDesignationSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Add
                                        Designation</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableAddDesignation">
                                                <input type="radio"
                                                  name="AddDesignation"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAddDesignation"
                                                  <?php echo ($SettingAddDesignation  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableAddDesignation">
                                                <input type="radio"
                                                  name="AddDesignation"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableAddDesignation"
                                                  <?php echo ($SettingAddDesignation  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        Designation Views</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableDesignationtViews">
                                                <input type="radio"
                                                  name="DesignationViews"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableDesignationViews"
                                                  <?php echo ($SettingDesignationView  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableDesignationViews">
                                                <input type="radio"
                                                  name="DesignationViews"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableDesignationViews"
                                                  <?php echo ($SettingDesignationView  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Add Logo
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Add Department"
                                            data-bs-original-title="Allows a full access to the Add Department"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="AddLogoSelectAll">
                                            <label class="form-check-label"
                                              for="AddLogoSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Add Logo
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableAddLogo">
                                                <input type="radio"
                                                  name="AddLogo"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableAddLogo"
                                                  <?php echo ($SettingAddLogo  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableAddLogo">
                                                <input type="radio"
                                                  name="AddLogo"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableAddLogo"
                                                  <?php echo ($SettingAddLogo  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Logo Views
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableLogotViews">
                                                <input type="radio"
                                                  name="LogoViews"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableLogoViews"
                                                  <?php echo ($SettingLogoView  === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableLogoViews">
                                                <input type="radio"
                                                  name="LogoViews"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableLogoViews"
                                                  <?php echo ($SettingLogoView  === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="card bg-menu-theme Access_View_Section" id="access_section8">
                        <div class="card-body user_role_card">
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Reports Analytics
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Sales Task Module"
                                            data-bs-original-title="Allows a full access to the Reports Analytics Module"></i>
                                        </h6>
                                        <!-- <label class="switch switch-square">
                                          <input type="checkbox" class="switch-input">
                                          <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ri-check-line"></i></span>
                                            <span class="switch-off"><i class="ri-close-line"></i></span>
                                          </span>
                                          <span class="switch-label">Enable</span>
                                        </label> -->
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="ReportsAnalyticsSelectAll">
                                            <label class="form-check-label"
                                              for="ReportsAnalyticsSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Download Access Report</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableReportDownloadAccess">
                                                <input type="radio"
                                                  name="ReportDownloadAccess"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableReportDownloadAccess"
                                                  <?php echo ($ReportDownloadAccess === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableReportDownloadAccess">
                                                <input type="radio"
                                                  name="ReportDownloadAccess"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableReportDownloadAccess"
                                                  <?php echo ($ReportDownloadAccess === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium"> User Access Report</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableReportEmployeeAccess">
                                                <input type="radio"
                                                  name="ReportEmployeeAccess"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableReportEmployeeAccess"
                                                  <?php echo ($ReportEmployeeAccess === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableReportEmployeeAccess">
                                                <input type="radio"
                                                  name="ReportEmployeeAccess"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableReportEmployeeAccess"
                                                  <?php echo ($ReportEmployeeAccess === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Sales Appointment Access Report</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableReportSalesTaskAccess">
                                                <input type="radio"
                                                  name="ReportSalesTaskAccess"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableReportSalesTaskAccess"
                                                  <?php echo ($ReportSalesTaskAccess === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableReportSalesTaskAccess">
                                                <input type="radio"
                                                  name="ReportSalesTaskAccess"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableReportReportSalesTaskAccess"
                                                  <?php echo ($ReportSalesTaskAccess === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium"> Project Access Report</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableReportProjectTaskAccess">
                                                <input type="radio"
                                                  name="ReportProjectTaskAccess"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableReportProjectTaskAccess"
                                                  <?php echo ($ReportProjectTaskAccess === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableReportProjectTaskAccess">
                                                <input type="radio"
                                                  name="ReportProjectTaskAccess"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableReportReportProjectTaskAccess"
                                                  <?php echo ($ReportProjectTaskAccess === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Report
                                      Reminder Access Report</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableReportReminderAccess">
                                                <input type="radio"
                                                  name="ReportReminderAccess"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableReportReminderAccess"
                                                  <?php echo ($ReportReminderAccess === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableReportReminderAccess">
                                                <input type="radio"
                                                  name="ReportReminderAccess"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableReportReportReminderAccess"
                                                  <?php echo ($ReportReminderAccess === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Report Log
                                      Access Report</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableReportLogAccess">
                                                <input type="radio"
                                                  name="ReportLogAccess"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableReportLogAccess"
                                                  <?php echo ($ReportLogAccess === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableReportLogAccess">
                                                <input type="radio"
                                                  name="ReportLogAccess"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableReportReportLogAccess"
                                                  <?php echo ($ReportLogAccess === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>

                                    <tr>
                                      <td class="text-nowrap fw-medium">Download
                                        User Excel</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableUserExcel">
                                                <input type="radio"
                                                  name="UserExcel"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableUserExcel"
                                                  <?php echo ($UserExcel === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableUserExcel">
                                                <input type="radio"
                                                  name="UserExcel"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableUserExcel"
                                                  <?php echo ($UserExcel === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Download
                                        Sales Excel</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableSalesExcel">
                                                <input type="radio"
                                                  name="SalesExcel"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableSalesExcel"
                                                  <?php echo ($SalesExcel === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableSalesExcel">
                                                <input type="radio"
                                                  name="SalesExcel"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableSalesExcel"
                                                  <?php echo ($SalesExcel === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Download
                                        Project Excel</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableProjectExcel">
                                                <input type="radio"
                                                  name="ProjectExcel"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableProjectExcel"
                                                  <?php echo ($ProjectExcel === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableProjectExcel">
                                                <input type="radio"
                                                  name="ProjectExcel"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableProjectExcel"
                                                  <?php echo ($ProjectExcel === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Download
                                        Reminder Excel</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableReminderExcel">
                                                <input type="radio"
                                                  name="ReminderExcel"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableReminderExcel"
                                                  <?php echo ($ReminderExcel === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableReminderExcel">
                                                <input type="radio"
                                                  name="ReminderExcel"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableReminderExcel"
                                                  <?php echo ($ReminderExcel === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="text-nowrap fw-medium">Download
                                        Log Excel</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableLogExcel">
                                                <input type="radio"
                                                  name="LogExcel"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableLogExcel"
                                                  <?php echo ($LogExcel === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableLogExcel">
                                                <input type="radio"
                                                  name="LogExcel"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableLogExcel"
                                                  <?php echo ($LogExcel === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                     <!-- <tr>
                                      <td class="text-nowrap fw-medium">Download
                                        Delete Log</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableDeleteLog">
                                                <input type="radio"
                                                  name="DeleteLog"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableDeleteLog"
                                                  <?php echo ($DeleteLog === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableDeleteLog">
                                                <input type="radio"
                                                  name="DeleteLog"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableDeleteLog"
                                                  <?php echo ($DeleteLog === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr> -->

                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="table-responsive mb-5">
                                <table
                                  class="table table-flush-spacing table_user_access">
                                  <thead>
                                    
                                    <!-- <tr>
                                      <td class="text-nowrap fw-medium">
                                        <h6 class="mb-0">
                                          Profile Access
                                          <i class="ri-information-line"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="Allows a full access to the Assign Profile Access"
                                            data-bs-original-title="Allows a full access to the Profile Access"></i>
                                        </h6>
                                      </td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="form-check mb-0 mt-1">
                                            <input
                                              class="form-check-input enable_all_access"
                                              type="checkbox"
                                              id="ProfileAccessSelectAll">
                                            <label class="form-check-label"
                                              for="ProfileAccessSelectAll">
                                              Enable All </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr> -->

                                  </thead>
                                  <tbody>
                                    <!-- <tr>
                                      <td class="text-nowrap fw-medium">Profile
                                        Pic Add</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="enableProfilePicAdd">
                                                <input type="radio"
                                                  name="ProfilePicAdd"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableProfilePicAdd"
                                                  <?php echo ($ProfilePicAdd === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label custom-option-content add_emp_radio"
                                                for="disableProfilePicAdd">
                                                <input type="radio"
                                                  name="ProfilePicAdd"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="disableProfilePicAdd"
                                                  <?php echo ($ProfilePicAdd === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr> -->
                                    <tr>
                                      <td class="text-nowrap fw-medium">Change
                                        Password</td>
                                      <td>
                                        <div class="d-flex justify-content-end">
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="enableChangePassword">
                                                <input type="radio"
                                                  name="ChangePassword"
                                                  class="form-check-input"
                                                  value="Enable"
                                                  id="enableChangePassword"
                                                  <?php echo ($ChangePassword === 'Enable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Enable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="">
                                            <div
                                              class="form-check access_permission">
                                              <label
                                                class="form-check-label estimate_address custom-option-content add_emp_radio"
                                                for="DisableChangePassword">
                                                <input type="radio"
                                                  name="ChangePassword"
                                                  class="form-check-input"
                                                  value="Disable"
                                                  id="DisableChangePassword"
                                                  <?php echo ($ChangePassword === 'Disable') ? 'checked' : ''; ?>>
                                                <span
                                                  class="custom-option-header inner_ship_selct">
                                                  <span
                                                    class="h6 mb-0">Disable</span>
                                                </span>
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>


                    </div>
                    <!-- Right Column End -->

                  </div>

                  <!-- Buttons -->

                  <div class="col-12 d-flex justify-content-end mt-4">
                    <button type="button" id="cancelbtn"
                      class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                    <button type="submit" id="submit" value="AddPermission" class="btn btn-primary">
                      Update
                    </button>
                  </div>

                </div>
              </div>
            </div>
          </div>
          <!-- Estimate Form code End-->
        </form>
      </div>


      <!-- -----Data Table assignTask Start------ -->
      <div class="container mt-5">
        <!-- Data Table -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="card-datatable table-responsive UserTablePermissionTable">
                  <table class="datatables-employee table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>S.No</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Mobile</th>
                        <th>Role</th>

                        <th>Permission</th>
                      </tr>
                    </thead>

                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Delete Card Modal -->
        <div class="modal fade" id="deleteCCModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              <div class="modal-body p-0">
                <div class="text-center mb-6">
                  <h4 class="mb-2">Praveen</h4>
                  <p>Are you sure you want to delete this item?</p>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-4 row-gap-4">
                  <button type="submit" class="btn btn-primary">Delete</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                    aria-label="Close">Cancel</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Send Mail Card Modal -->
        <div class="modal fade" id="mailsendCCModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              <div class="modal-body p-0">
                <div class="text-center mb-6">
                  <h4 class="mb-2">Praveen</h4>
                  <p>Are you sure you want to send mail for this item?</p>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-4 row-gap-4">
                  <button type="submit" class="btn btn-primary">Send Mail</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                    aria-label="Close">Cancel</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/ Send Mail Card Modal -->

      <!-- Work Order Send Card Modal -->
      <div class="modal fade" id="workorderCCModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
          <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0">
              <div class="text-center mb-6">
                <h4 class="mb-2">Add Date & Team</h4>
                <p>Fill and end to this to work order level</p>
              </div>

              <form id="addNewCCForm" class="row g-5 fv-plugins-bootstrap5 fv-plugins-framework"
                onsubmit="return false" novalidate="novalidate">
                <div class="col-12 fv-plugins-icon-container">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input type="text" class="form-control flatpickr-input active"
                        placeholder="DD-MM-YYYY" id="flatpickr-date">
                      <label for="flatpickr-date">Expected Work Date</label>
                    </div>
                  </div>
                  <div class="input-group input-group-merge mt-5">
                    <div class="form-floating form-floating-outline">
                      <input id="modalAddCard" name="modalAddCard"
                        class="form-control credit-card-mask" type="text"
                        placeholder="Sunder Raj" aria-describedby="modalAddCard2">
                      <label for="modalAddCard">Team Name</label>
                    </div>
                  </div>
                </div>
                <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4">
                  <button type="submit" class="btn btn-primary waves-effect waves-light">Move to Work
                    Order</button>
                  <button type="reset" class="btn btn-outline-secondary btn-reset waves-effect"
                    data-bs-dismiss="modal" aria-label="Close">
                    Cancel
                  </button>
                </div>

            </div>
          </div>
        </div>
      </div>
      <!--/ Send Mail Card Modal -->



      <!-- / Content -->
      <?php include('include/footer.php'); ?>
      <script src="assets/js/userpermission.js"></script>


      <script>
        $(document).ready(function() {
          $('input[type="radio"]').change(function() {
            // Get the name of the group (ActiveAdmin or InactiveAdmin)
            var groupName = $(this).attr('name');

            // Remove "checked" class from all access_permission divs in the current group only
            $('.access_permission').filter(function() {
              return $(this).find('input[type="radio"]').attr('name') === groupName;
            }).removeClass('checked');

            // Add "checked" class to the parent of the selected radio button
            $(this).closest('.access_permission').addClass('checked');
          });




          document.getElementById('addPermissionEmp').addEventListener('submit', function(e) {
            e.preventDefault();

            const params = new URLSearchParams(window.location.search);
            const UserId = params.get('id'); // Assuming the query string is ?id=value

            const formData = new URLSearchParams();
            formData.append('UserId', UserId);
            formData.append('noOfEmployee', $('input[name="noOfEmployee"]:checked').val() ||
              'Disable');
            formData.append('activeEmployees', $('input[name="activeEmployees"]:checked')
              .val() || 'Disable');
            formData.append('inActiveEmployees', $('input[name="inActiveEmployees"]:checked')
              .val() || 'Disable');
            formData.append('numberOfAdmin', $('input[name="numberOfAdmin"]:checked').val() ||
              'Disable');
            formData.append('activeAdmin', $('input[name="activeAdmin"]:checked').val() ||
              'Disable');
            formData.append('inActiveAdmin', $('input[name="inActiveAdmin"]:checked').val() ||
              'Disable');
            formData.append('TodayTask', $('input[name="TodayTask"]:checked').val() ||
              'Disable');
            formData.append('TaskNext6Days', $('input[name="TaskNext6Days"]:checked').val() ||
              'Disable');
            formData.append('Task7thDayTo31thDay', $(
              'input[name="Task7thDayTo31thDay"]:checked').val() || 'Disable');
            formData.append('TaskPending', $('input[name="TaskPending"]:checked').val() ||
              'Disable');
            formData.append('TaskTotal', $('input[name="TaskTotal"]:checked').val() ||
              'Disable');
            formData.append('TaskFollowUp', $('input[name="TaskFollowUp"]:checked').val() ||
              'Disable');
            formData.append('TaskCompleted', $('input[name="TaskCompleted"]:checked').val() ||
              'Disable');
            formData.append('TaskNotInterested', $('input[name="TaskNotInterested"]:checked')
              .val() || 'Disable');
            formData.append('ProjectAllTime', $('input[name="ProjectAllTime"]:checked').val() ||
              'Disable');
            formData.append('PendingProject', $('input[name="PendingProject"]:checked').val() ||
              'Disable');
            formData.append('ExtendedProject', $('input[name="ExtendedProject"]:checked')
              .val() || 'Disable');
            formData.append('CompletedProject', $('input[name="CompletedProject"]:checked')
              .val() || 'Disable');
            formData.append('ReminderCount', $('input[name="ReminderCount"]:checked').val() ||
              'Disable');
            formData.append('DocumentCount', $('input[name="DocumentCount"]:checked').val() ||
              'Disable');
            formData.append('AddNewEmployee', $('input[name="AddNewEmployee"]:checked').val() ||
              'Disable');
            formData.append('BulkUser', $('input[name="BulkUser"]:checked').val() || 'Disable');
            formData.append('UserRoles', $('input[name="UserRoles"]:checked').val() ||
              'Disable');
              formData.append('AddNewClient', $('input[name="AddNewClient"]:checked').val() ||
              'Disable');
              formData.append('AssignJob', $('input[name="AssignJob"]:checked').val() ||
              'Disable');
              formData.append('AssignedJob', $('input[name="AssignedJob"]:checked').val() ||
              'Disable');
              formData.append('JobsExcel', $('input[name="JobsExcel"]:checked').val() ||
              'Disable');
            formData.append('AddNewSaleTask', $('input[name="AddNewSaleTask"]:checked').val() ||
              'Disable');
            formData.append('TaskReply', $('input[name="TaskReply"]:checked').val() ||
              'Disable');
            formData.append('AddNewProject', $('input[name="AddNewProject"]:checked').val() ||
              'Disable');
            formData.append('ProjectReply', $('input[name="ProjectReply"]:checked').val() ||
              'Disable');
            formData.append('AddNewReminder', $('input[name="AddNewReminder"]:checked').val() ||
              'Disable');
            formData.append('ReminderViews', $('input[name="ReminderViews"]:checked').val() ||
              'Disable');
            formData.append('AddNewDocument', $('input[name="AddNewDocument"]:checked').val() ||
              'Disable');
            formData.append('DocumentViews', $('input[name="DocumentViews"]:checked').val() ||
              'Disable');
            formData.append('SalesTask', $('input[name="SalesTask"]:checked').val() ||
              'Disable');
            formData.append('TaskViews', $('input[name="TaskViews"]:checked').val() ||
              'Disable');
            formData.append('AddDepartment', $('input[name="AddDepartment"]:checked').val() ||
              'Disable');
            formData.append('DepartmentViews', $('input[name="DepartmentViews"]:checked')
              .val() || 'Disable');
            formData.append('AddDesignation', $('input[name="AddDesignation"]:checked').val() ||
              'Disable');
            formData.append('DesignationViews', $('input[name="DesignationViews"]:checked')
              .val() || 'Disable');
            formData.append('AddLogo', $('input[name="AddLogo"]:checked').val() || 'Disable');
            formData.append('LogoViews', $('input[name="LogoViews"]:checked').val() ||
              'Disable');
            formData.append('ReportDownloadAccess', $(
              'input[name="ReportDownloadAccess"]:checked').val() || 'Disable');
            formData.append('ReportEmployeeAccess', $(
              'input[name="ReportEmployeeAccess"]:checked').val() || 'Disable');
            formData.append('ReportSalesTaskAccess', $(
              'input[name="ReportSalesTaskAccess"]:checked').val() || 'Disable');
            formData.append('ReportProjectTaskAccess', $(
              'input[name="ReportProjectTaskAccess"]:checked').val() || 'Disable');
            formData.append('ReportReminderAccess', $(
              'input[name="ReportReminderAccess"]:checked').val() || 'Disable');
            formData.append('ReportLogAccess', $('input[name="ReportLogAccess"]:checked')
              .val() || 'Disable');
            formData.append('ProfilePicAdd', $('input[name="ProfilePicAdd"]:checked').val() ||
              'Disable');
            formData.append('ChangePassword', $('input[name="ChangePassword"]:checked').val() ||
              'Disable');
            formData.append('UserExcel', $('input[name="UserExcel"]:checked').val() ||
              'Disable');
            formData.append('SalesExcel', $('input[name="SalesExcel"]:checked').val() ||
              'Disable');
            formData.append('ProjectExcel', $('input[name="ProjectExcel"]:checked').val() ||
              'Disable');
            formData.append('ReminderExcel', $('input[name="ReminderExcel"]:checked').val() ||
              'Disable');
            formData.append('LogExcel', $('input[name="LogExcel"]:checked').val() || 'Disable');
            formData.append('DeleteLog', $('input[name="DeleteLog"]:checked').val() ||
              'Disable');
            formData.append('DocumentDelete', $('input[name="DocumentDelete"]:checked').val() ||
              'Disable');
            formData.append('submit', 'AddPermission');

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
                  //console.log(result.data);
                  showModalWithParams(`Employee Premission Updated`, 'true');
                  //initializeDataTable('.datatables-reminderReport', result.data);
                } else {
                  console.error('Error:', result.message);
                }
              })
              .catch(error => console.error('Error:', error));
          });
        });


        // Function to handle switch input change
        $('.switch-input').change(function() {
          const table = $(this).closest('.table_user_access');
          if ($(this).is(':checked')) {
            table.find('tbody').removeClass('access_blurred').css('pointer-events', 'auto');
          } else {
            table.find('tbody').addClass('access_blurred').css('pointer-events', 'none');
          }
        });

        $('.enable_all_access').change(function() {
          const table = $(this).closest('.table_user_access');

          if ($(this).is(':checked')) {
            if (!table.find('.switch-input').is(':checked')) {
              table.find('.switch-input').prop('checked', 'true');
              table.find('tbody').removeClass('access_blurred').css('pointer-events', 'auto');
            }
            table.find('input[type="radio"][value="Enable"]').prop('checked', true);
            table.find('input[type="radio"][value="Disable"]').prop('checked', false);
            table.find('.access_permission').removeClass('checked'); // Remove checked class from all
            table.find('input[type="radio"][value="Enable"]').closest('.access_permission').addClass(
              'checked');
          } else {
            // When the checkbox is unchecked
            table.find('input[type="radio"][value="Enable"]').prop('checked', false);
            table.find('input[type="radio"][value="Disable"]').prop('checked',
              true); // Check the Disable radio button
            table.find('.access_permission').removeClass('checked'); // Remove checked class from all
            table.find('input[type="radio"][value="Disable"]').closest('.access_permission').addClass(
              'checked'); // Add checked class to Disable
          }

        });

        function updateSwitchInput(row) {
          const hasCheckedRadio = row.find('input[type="radio"]:checked').length > 0;
          const table = row.closest('.table_user_access');

          if (hasCheckedRadio) {
            row.removeClass('access_blurred').css('pointer-events', 'auto');
            table.find('.switch-input').prop('checked', hasCheckedRadio);
          } else {
            row.addClass('access_blurred').css('pointer-events', 'none');
            table.find('.switch-input').prop('checked', hasCheckedRadio);
          }
        }

        $('tbody').each(function() {
          updateSwitchInput($(this));
        });



        // Show the first section initially
        $('.Access_View_Section').hide();
        $('#access_section1').show().addClass('active');
        $('.access_menu_list').first().addClass('active');

        // Click event for the tabs
        $('.access_menu').on('click', function(e) {
          e.preventDefault();

          $('.access_menu').closest('.access_menu_list').removeClass('active');
          $(this).closest('.access_menu_list').addClass('active');

          // Get the target section from the data attribute
          const target = $(this).data('target');

          // Hide all sections
          $('.Access_View_Section').removeClass('active').fadeOut(200, function() {
            // After fadeOut, show the target section with animation
            $(target).fadeIn(200).addClass('active');
          });

        });
      </script>