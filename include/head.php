<?php

session_start();
require 'data/dbconfig.php';
require 'vendor/autoload.php';
require 'JWTValues.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
// use DomainException;
// use InvalidArgumentException;
// use UnexpectedValueException;

$secretKey = 'a8d123461b643452fc0b9c8186f80de25b4ab7e8769010d57d309f867fcfcf99';

// Instantiate the JWTValues class
$jwtHandler = new JWTValues($secretKey);

// Decode the token and retrieve user data
$jwtHandler->decodeToken();

// Check if the user is logged in
if ($jwtHandler->isLoggedIn()) {
    // Get the user data
    $userData = $jwtHandler->getUserData();


    // Perform the SQL query using the user ID
    $JWT_userID = $jwtHandler->getUserID();
  	$JWT_adminName = $jwtHandler->getAdminName();
  	$JWT_userRole = $jwtHandler->getUserRole();
  	$JWT_userEmail = $jwtHandler->getUserEmail();
  	$JWT_userDesignation = $jwtHandler->getUserDesignation();
  
  
    $sql = "SELECT * FROM employee WHERE id LIKE '%" . $JWT_userID . "%' AND isenable = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If any inactive employees are found, redirect to login page
        header("Location: login.php");
        exit();
    }
} else {
    // If the user is not logged in, redirect to login
    header("Location: login.php");
    exit();
}




$ReportDownloadAccess = '';
$ReportEmployeeAccess = '';
$ReportSalesTaskAccess = '';
$ReportProjectTaskAccess = '';
$ReportReminderAccess = '';
$ReportLogAccess = '';
$ProfilePicAdd = '';
$ChangePassword = '';
$UserRoles = '';
$AddNewEmployee = '';
$AddNewClient = '';
$AssignJob = '';
$AssignedJob = '';
$JobsExcel ='';
$SalesTask = '';
$TaskViews = '';
$AddDepartment = '';
$DepartmentViews = '';
$AddNewDocument = '';
$DocumentViews = '';
$BulkUser = '';
$SettingAddDesignation = '';
$SettingDesignationView = '';
$SettingAddLogo = '';
$SettingLogoView = '';
$UserExcel = '';
$SalesExcel = '';
$ProjectExcel = '';
$ReminderExcel = '';
$LogExcel = '';
$ReportEmployeeAccess = '';
$ReportSalesTaskAccess = '';
$ReportProjectTaskAccess = '';
$ReportReminderAccess = '';
$ReportLogAccess = '';
$DeleteLog = '';
$DeleteDocuments = '';
$AddNewSaleTask = '';
$TaskReply = '';
$AddNewProject = '';
$ProjectReply = '';
$AddNewReminder = '';
$ReminderViews = '';
$AddNewDocument = '';
$DocumentViews = '';

$pid =  $JWT_userID;
$permissionsql = "SELECT * FROM permissions WHERE userID='$pid' ";
$resultPermission = $conn->query($permissionsql);
if ($resultPermission->num_rows > 0) {
  $row = $resultPermission->fetch_assoc();
  $ReportDownloadAccess = htmlspecialchars($row['ReportDownloadAccess']);
  $ReportEmployeeAccess = htmlspecialchars($row['ReportEmployeeAccess']);

  $ReportSalesTaskAccess = htmlspecialchars($row['ReportSalesTaskAccess']);
  $ReportProjectTaskAccess = htmlspecialchars($row['ReportProjectTaskAccess']);
  $ReportReminderAccess = htmlspecialchars($row['ReportReminderAccess']);
  $ReportLogAccess = htmlspecialchars($row['ReportLogAccess']);
  $ProfilePicAdd = htmlspecialchars($row['ProfilePicAdd']);
  $ChangePassword = htmlspecialchars($row['ChangePassword']);
  $UserRoles = htmlspecialchars($row['UserRoles']);
  $AddNewEmployee = htmlspecialchars($row['AddNewEmployee']);
  $BulkUser = htmlspecialchars($row['BulkUser']);
  $SalesTask = htmlspecialchars($row['SettingSalesTask']);
  $AddNewClient = htmlspecialchars($row['AddNewClient']);
  $AssignJob = htmlspecialchars($row['AssignJob']);
  $AssignedJob = htmlspecialchars($row['AssignedJob']);
  $JobsExcel = htmlspecialchars($row['JobsExcel']);

  $TaskViews = htmlspecialchars($row['SettingTaskViews']);
  $AddDepartment = htmlspecialchars($row['SettingAddDepartment']);
  $DepartmentViews = htmlspecialchars($row['SettingDepartmentViews']);
  $AddNewDocument = htmlspecialchars($row['AddNewDocument']);
  $DocumentViews = htmlspecialchars($row['DocumentViews']);

  $SettingAddDesignation =  htmlspecialchars($row['SettingAddDesignation']);
  $SettingDesignationView =  htmlspecialchars($row['SettingDesignationView']);
  $SettingAddLogo =  htmlspecialchars($row['SettingAddLogo']);
  $SettingLogoView =  htmlspecialchars($row['SettingLogoView']);

  $UserExcel = htmlspecialchars($row['UserExcel']);
  $SalesExcel = htmlspecialchars($row['SalesExcel']);
  $ProjectExcel = htmlspecialchars($row['ProjectExcel']);
  $ReminderExcel = htmlspecialchars($row['ReminderExcel']);
  $LogExcel = htmlspecialchars($row['LogExcel']);

  $ReportEmployeeAccess = htmlspecialchars($row['ReportEmployeeAccess']);

  $ReportSalesTaskAccess = htmlspecialchars($row['ReportSalesTaskAccess']);
  $ReportProjectTaskAccess = htmlspecialchars($row['ReportProjectTaskAccess']);
  $ReportReminderAccess = htmlspecialchars($row['ReportReminderAccess']);
  $ReportLogAccess = htmlspecialchars($row['ReportLogAccess']);
  $DeleteLog = htmlspecialchars($row['DeleteLog']);
  $DeleteDocuments = htmlspecialchars($row['DeleteDocuments']);

  $AddNewSaleTask = htmlspecialchars($row['AddNewSaleTask']);
  $TaskReply = htmlspecialchars($row['TaskReply']);
  $AddNewProject = htmlspecialchars($row['AddNewProject']);
  $ProjectReply = htmlspecialchars($row['ProjectReply']);
  $AddNewReminder = htmlspecialchars($row['AddNewReminder']);

  $ReminderViews = htmlspecialchars($row['ReminderViews']);
  $AddNewDocument = htmlspecialchars($row['AddNewDocument']);
  $DocumentViews = htmlspecialchars($row['DocumentViews']);
}


$pageTitle = 'Dashboard | MBW Techimpex';


if (isset($_GET['page'])) {
  switch ($_GET['page']) {

    case 'addemployee':
      $pageTitle = 'Add User ';
      break;

      case 'addclient':
        $pageTitle = 'Add client ';
        break;

    case 'assignTask':
      $pageTitle = 'Assign Sales Appointments ';
      break;
    case 'assignProject':
      $pageTitle = 'Assign Projects';
      break;

    case 'assignJob':
      $pageTitle = 'Create New Job';
      break;

    case 'assignedJob':
      $pageTitle = 'View Jobs';
      break;
        case 'status':
      $pageTitle = 'project status';
      break;

    case 'reminder':
      $pageTitle = 'Reminders';
      break;
    case 'userpermission':
      $pageTitle = 'User Permission';
      break;
    case 'employeeReport':
      $pageTitle = 'User Report ';
      break;
    case 'salesTaskReport':
      $pageTitle = 'Sales Appointments Report ';
      break;
    case 'projectReport':
      $pageTitle = 'Project Report ';
      break;
    case 'reminderReport':
      $pageTitle = 'Reminder Report ';
      break;
    case 'loginReport':
      $pageTitle = 'Log Report ';
      break;
    case 'deleteReport':
      $pageTitle = 'Delete Report ';
      break;
    case 'taskType':
      $pageTitle = 'Add New Appointment Division';
      break;
    case 'Documents':
      $pageTitle = 'Documents ';
      break;
    case 'department':
      $pageTitle = 'Department';
      break;
    case 'designation':
      $pageTitle = 'Designation';
      break;
    case 'logoUpload':
      $pageTitle = 'Logo Upload';
      break;
    case 'Editproject':
      $pageTitle = 'Project Details';
      break;
    default:
      $pageTitle = 'Dashboard';
      break;
  }
}



?>

<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-no-customizer-starter"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?php echo $pageTitle; ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/logos/logo2.png" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="assets/vendor/fonts/remixicon/remixicon.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Preloader CSS -->
    <link rel="stylesheet" href="assets/css/preloader.css" />
    <link rel="stylesheet" href="assets/vendor/libs/spinkit/spinkit.css" />

    <link rel="stylesheet" href="assets/vendor/libs/dropzone/dropzone.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />

    <!-- ---- Data Table Css ---- -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />

    <!-- ---Date Picker---- -->
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />

    <!-- ---Date Picker---- -->
    <link rel="stylesheet" href="assets/vendor/css/pages/app-invoice.css" />

    <!-- ---Chart For Dashboard---- -->
    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- ---Text Editor---- -->
    <link rel="stylesheet" href="assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="assets/vendor/libs/quill/katex.css" />


    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <script src="assets/vendor/libs/jquery/jquery.js"></script>

    <script type="text/javascript">
    var deleteDocumentsValue = "<?php echo $DeleteDocuments; ?>";
    </script>

    <!-- ---Alert_show---- -->
    <script>
    // function showModalWithParams(message, status) {
    //   var content = $("#custom_alert_side");
    //   var content_para = $("#custom_alert_side .msg_content");

    //   // Remove any existing success or error classes
    //   content.removeClass("success_my_alert");
    //   content.removeClass("error_my_alert");

    //   // Add the appropriate class based on status
    //   if (status === 'false') {
    //     content.addClass("error_my_alert");
    //     content_para.html(message);
    //   } else {
    //     content.addClass("success_my_alert");
    //     content_para.html(message);
    //   }


    //   var modal = new bootstrap.Modal(document.getElementById('alertCardModal'), {
    //     keyboard: false
    //   });
    //   modal.show();

    //   // setTimeout(() => {
    //   //     modal.hide();


    //   //     var currentUrl = new URL(window.location.href);


    //   //         currentUrl.searchParams.delete('id');

    //   //         window.location.href = currentUrl.toString();

    //   // }, 3000); 



    // }


    function showModalWithParams(message, status) {
        var content = $("#custom_alert_side");
        var content_para = $("#custom_alert_side .msg_content");

        // Remove any existing success or error classes
        content.removeClass("success_my_alert");
        content.removeClass("error_my_alert");

        // Add the appropriate class based on status
        if (status === 'false') {
            content.addClass("error_my_alert");
            content_para.html(message);
        } else {
            content.addClass("success_my_alert");
            content_para.html(message);
        }

        var modal = new bootstrap.Modal(document.getElementById('alertCardModal'), {
            keyboard: false, // Disables closing by pressing the keyboard (Esc)
            backdrop: 'static' // Disables closing by clicking outside the modal
        });

        modal.show();
    }
    </script>
    <!-- ---Alert_show---- -->

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <?php
      $sql = "SELECT * FROM logo WHERE id= 1 ";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        //$name_of_file = htmlspecialchars($row['name']);
        $file_url = htmlspecialchars($row['file_url']);
      }
      ?>
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.php" class="app-brand-link">
                        <img width="150" src="<?php echo htmlspecialchars($file_url); ?>">
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z"
                                fill-opacity="0.9" />
                            <path
                                d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z"
                                fill-opacity="0.4" />
                        </svg>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>



                <ul class="menu-inner py-1">

                    <li
                          <?php if (isset($JWT_adminRole) && $JWT_adminRole === 'admin' ||'user') : ?>
                        class="menu-item <?php echo !isset($_GET['page']) || $_GET['page'] == 'dashboard' ? 'active' : ''; ?>">
                        <a href="index.php?page=dashboard" class="menu-link">
                            <i class="menu-icon tf-icons ri-dashboard-2-line"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                       <?php endif; ?>
                    </li>
                  
                    <?php
          // Check if any report access is enabled
          if (
            $AddNewEmployee === 'Enable' ||
            $UserRoles === 'Enable' || $AddNewClient === 'Enable'
          ) :
          ?>
                    <li
                        class="menu-item <?php echo (isset($_GET['page']) && ($_GET['page'] == 'addemployee' ||$_GET['page'] == 'addclient'|| $_GET['page'] == 'userpermission')) ? 'open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                            <i class="menu-icon tf-icons ri-shield-user-line"></i>
                            <div data-i18n="All Users">All Users</div>
                        </a>

                        <ul class="menu-sub">
                            <?php if ($AddNewEmployee === 'Enable') : ?>
                            <li
                                class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'addemployee' ? 'active' : ''; ?>">
                                <a href="addemployee.php?page=addemployee" class="menu-link">
                                    <i class="sub-menu-icon ri-user-add-line"></i>
                                    <div data-i18n="Add User">Add User</div>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if ($AddNewClient === 'Enable') : ?>
                            <li
                                class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'addclient' ? 'active' : ''; ?>">
                                <a href="addclient.php?page=addclient" class="menu-link">
                                    <i class="sub-menu-icon ri-file-user-line"></i>
                                    <div data-i18n="Add Client">Add Client</div>
                                </a>
                            </li>
                            <?php endif; ?>


                            <?php if ($UserRoles === 'Enable') : ?>
                            <li
                                class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'userpermission' ? 'active' : ''; ?>">
                                <a href="UserPermission.php?page=userpermission" class="menu-link">
                                    <i class="sub-menu-icon ri-phone-lock-line"></i>
                                    <div data-i18n="User Role Permissions">User Role Permissions</div>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php
          // Check if any report access is enabled
          if (
            $AddNewSaleTask  === 'Enable' ||
            $TaskReply  === 'Enable'
          ) :
          ?>

                    <li
                        class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'assignTask' ? 'active' : ''; ?>">
                        <a href="assignTask.php?page=assignTask" class="menu-link">
                            <i class="menu-icon tf-icons ri-list-check-3"></i>
                            <div data-i18n="Sales Appointments">Sales Appointments</div>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php
          // Check if any report access is enabled
          if (
            $AddNewProject === 'Enable' ||
            $ProjectReply  === 'Enable' 
           
          ) :
          ?>

                    <li
                        class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'assignProject' ? 'active' : ''; ?>">
                        <a href="clientProject.php?page=assignProject" class="menu-link">
                            <i class="menu-icon tf-icons ri-sticky-note-add-line"></i>
                            <div data-i18n="Projects">Projects</div>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($AssignJob === 'Enable') : ?>
                    <li
                        class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'assignJob' ? 'active' : ''; ?>">
                        <a href="assignjob.php?page=assignJob" class="menu-link">
                            <i class="menu-icon tf-icons ri-suitcase-line"></i>
                            <div data-i18n="Create New Job">Create New Job</div>


                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($AssignedJob === 'Enable') : ?>
                    <li
                        class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'assignedJob' ? 'active' : ''; ?>">
                        <a href="assignedJob.php?page=assignedJob" class="menu-link">
                            <i class="menu-icon tf-icons ri-eye-2-line"></i>
                            <div data-i18n="View Jobs">View Jobs</div>
                        </a>
                    </li>
                    <?php endif; ?>
                        <?php if ($AssignedJob === 'Enable') : ?>
                    <li
                        class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'projectstatus' ? 'active' : ''; ?>">
                        <a href="clientfinalprojects.php?page=projectstatus" class="menu-link">
                            <i class="menu-icon tf-icons ri-sticky-note-add-line"></i>
                            <div data-i18n="project status">project status</div>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php
          // Check if any report access is enabled
          if (
            $AddNewReminder === 'Enable' ||
            $ReminderViews  === 'Enable'
          ) :
          ?>

                    <li
                        class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'reminder' ? 'active' : ''; ?>">
                        <a href="reminder.php?page=reminder" class="menu-link">
                            <i class="menu-icon tf-icons ri-calendar-schedule-line"></i>
                            <div data-i18n="Reminder">Reminders</div>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($AddNewDocument === 'Enable') : ?>
                    <!-- <li class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'Documents' ? 'active' : ''; ?>">
              <a href="DriveDocuments.php?page=Documents" class="menu-link">
                <i class="menu-icon tf-icons ri-file-pdf-2-line"></i>
                <div data-i18n="Documents">Documents</div>
              </a>
            </li> -->
                    <?php endif; ?>
                    <?php
          $activePages = ['taskType', 'department'];
          $currentPage = isset($_GET['page']) ? $_GET['page'] : '';

          $submenu_active =  in_array($currentPage, $activePages) ? 'active' : '';
          ?>
                    <?php
          // Check if any report access is enabled
          if (
            $SalesTask === 'Enable' ||
            $AddDepartment === 'Enable' ||
            $DepartmentViews === 'Enable' ||
            $SettingAddLogo  === 'Enable' ||
            $SettingAddDesignation === 'Enable' ||
            $SettingDesignationView === 'Enable' ||
            $SettingLogoView === 'Enable'
          ) :
          ?>
                    <li
                        class="menu-item <?php echo (isset($_GET['page']) && ($_GET['page'] == 'taskType' || $_GET['page'] == 'department' || $_GET['page'] == 'designation' || $_GET['page'] == 'logoUpload')) ? 'open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                            <i class="menu-icon tf-icons ri-settings-5-line"></i>
                            <div data-i18n="Setting">Setting</div>
                        </a>
                        <ul class="menu-sub">
                            <?php if ($SalesTask === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'taskType' ? 'active' : ''; ?>">
                                <a href="TaskType.php?page=taskType" class="menu-link">
                                    <i class="sub-menu-icon ri-pages-line"></i>
                                    <div data-i18n="Sales Appointments">Sales Appointments</div>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($SalesTask === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'service' ? 'active' : ''; ?>">
                                <a href="service.php?page=service" class="menu-link">
                                    <i class="sub-menu-icon ri-pages-line"></i>
                                    <div data-i18n="Service Request">Service Request</div>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($AddDepartment === 'Enable' || $DepartmentViews === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'department' ? 'active' : ''; ?>">
                                <a href="department.php?page=department" class="menu-link">
                                    <i class="sub-menu-icon ri-pantone-line"></i>
                                    <div data-i18n="Department ">Department</div>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($SettingAddDesignation  === 'Enable' || $SettingDesignationView  === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'designation' ? 'active' : ''; ?>">
                                <a href="designation.php?page=designation" class="menu-link">
                                    <i class="sub-menu-icon ri-macbook-line"></i>
                                    <div data-i18n="Designation ">Designation</div>
                                </a>
                            </li>
                            <?php endif; ?>

                             <?php if ($SettingAddDesignation  === 'Enable' || $SettingDesignationView  === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'mailsetting' ? 'active' : ''; ?>">
                                <a href="mailsetting.php?page=mailsetting" class="menu-link">
                                    <i class="sub-menu-icon ri-discount-percent-line"></i>
                                    <div data-i18n="SMTP ">SMTP</div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php if ($SettingAddLogo  === 'Enable' || $SettingLogoView  === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'logoUpload' ? 'active' : ''; ?>">
                                <a href="logoUpload.php?page=logoUpload" class="menu-link">
                                    <i class="sub-menu-icon ri-export-line"></i>
                                    <div data-i18n="Logo Upload ">Logo Upload</div>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- <li class="menu-item <?php echo isset($_GET['page']) && $_GET['page'] == 'estimates' ? 'active' : ''; ?>">
              <a href="report.php?page=report" class="menu-link">
                <i class="menu-icon tf-icons ri-calculator-line"></i>
                <div data-i18n="Report">Report</div>
              </a>
            </li> -->
                    <?php
          // Check if any report access is enabled
          if (
            $ReportEmployeeAccess === 'Enable' ||
            $ReportSalesTaskAccess === 'Enable' ||
            $ReportProjectTaskAccess === 'Enable' ||
            $ReportReminderAccess === 'Enable' ||
            $ReportLogAccess === 'Enable'
          ) :
          ?>
                    <li
                        class="menu-item <?php echo (isset($_GET['page']) && ($_GET['page'] == 'employeeReport' || $_GET['page'] == 'salesTaskReport' || $_GET['page'] == 'projectReport' || $_GET['page'] == 'reminderReport' || $_GET['page'] == 'loginReport')) ? 'open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                            <i class="menu-icon tf-icons ri-calculator-line"></i>
                            <div data-i18n="Reports">Reports</div>
                        </a>
                        <ul class="menu-sub">
                            <?php if ($ReportEmployeeAccess === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'employeeReport' ? 'active' : ''; ?>">
                                <a href="employeeReport.php?page=employeeReport" class="menu-link">
                                    <i class="sub-menu-icon ri-group-line"></i>
                                    <div data-i18n="User ">User </div>
                                </a>
                            </li>

                            <?php endif; ?>
                            <?php if ($ReportSalesTaskAccess === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'salesTaskReport' ? 'active' : ''; ?>">
                                <a href="salesTaskReport.php?page=salesTaskReport" class="menu-link">
                                    <i class="sub-menu-icon ri-discount-percent-line"></i>
                                    <div data-i18n="Sales Appointments ">Sales Appointments </div>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if ($ReportProjectTaskAccess === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'projectReport' ? 'active' : ''; ?>">
                                <a href="projectReport.php?page=projectReport" class="menu-link">
                                    <i class="sub-menu-icon ri-file-copy-2-line"></i>
                                    <div data-i18n="Project ">Project </div>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($ReportReminderAccess === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'reminderReport' ? 'active' : ''; ?>">
                                <a href="reminderReport.php?page=reminderReport" class="menu-link">
                                    <i class="sub-menu-icon ri-time-line"></i>
                                    <div data-i18n="Reminder ">Reminder </div>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($ReportLogAccess === 'Enable') : ?>
                            <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'loginReport' ? 'active' : ''; ?>">
                                <a href="loginReport.php?page=loginReport" class="menu-link">
                                    <i class="sub-menu-icon ri-file-chart-line"></i>
                                    <div data-i18n="User Log ">User Log </div>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($DeleteLog === 'Enable') : ?>
                             <!-- <li
                                class="menu-item  <?php echo isset($_GET['page']) && $_GET['page'] == 'deleteReport' ? 'active' : ''; ?>">
                                <a href="deleteLogReport.php?page=deleteReport" class="menu-link">
                                    <i class="sub-menu-icon ri-file-chart-line"></i>
                                    <div data-i18n="Delete Log ">Delete Log </div>
                                </a>
                            </li> -->
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- <li class="menu-item">
              <form action="function.php" method="post">
                <button name="logout" style="border: none;" type="submit" class="menu-link">
                  <i class="menu-icon tf-icons ri-logout-circle-line"></i>
                  <div data-i18n="Log Out">Log Out</div>
                </button>
              </form>
            </li> -->


                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="ri-menu-fill ri-22px"></i>
                        </a>
                    </div>

                    <div class="w-50 navbar-nav-left align-items-center my_page_custom_headline">
                        <div class="nav-item navbar-search-wrapper mb-0">
                            <h4 class="mb-0"><?php echo $pageTitle; ?> </h4>
                        </div>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Notification -->
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-4 me-xl-1">

                                <a class="nav-link btn btn-text-secondary btn-icon dropdown-toggle hide-arrow"
                                    href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    <i class="ri-notification-2-line ri-22px"></i>
                                    <span id="notification-count"
                                        class="top-0 start-50 translate-middle-y badge  bg-danger mt-3 border"></span>
                                </a>

                                <ul id="notification-list" class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom py-50">
                                        <div class="dropdown-header d-flex align-items-center py-2">
                                            <h6 class="mb-0 me-auto">Notification</h6>
                                            <div class="d-flex align-items-center">
                                                <span id="notification-badge"
                                                    class="badge rounded-pill bg-label-primary fs-xsmall me-2">0
                                                    New</span>
                                                <!-- <a
                          href="javascript:void(0)"
                          class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all"
                          data-bs-toggle="tooltip"
                          data-bs-placement="top"
                          title="Mark all as read"><i class="ri-mail-open-line text-heading ri-20px"></i></a> -->
                                            </div>
                                        </div>
                                    </li>
                                    <!-- <li>
                    <div id="text-tooltip" class="text-tooltip"></div>
                  </li> -->
                                    <li id="notification-items"
                                        class="dropdown-notifications-list scrollable-container">
                                        <!-- Notification items will be dynamically added here -->
                                    </li>
                                </ul>

                            </li>
                            <!--/ Notification -->
                            <?php
              if (!empty($JWT_adminName)) {
                $Name = $JWT_adminName;

              ?>
                            <!-- User -->
                            <?php
                     
                      $Picture = "";
                      $Name = "User";

                      if ( $JWT_userID) {
                          // Fetch user details
                          $stmt = $conn->prepare("SELECT name, picture FROM employee WHERE id = ?");
                          $stmt->bind_param("s",  $JWT_userID);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          
                          if ($row = $result->fetch_assoc()) {
                              $Name = $row['name'];
                              $Picture = $row['picture'];
                          }
                          
                          $stmt->close();
                      }

                      $conn->close();
                      ?>
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatars avatar-online">
                                        <?php if (!empty($Picture)) : ?>
                                        <!-- Show Profile Picture -->
                                        <img src="<?php echo htmlspecialchars($Picture); ?>" alt="User Avatar"
                                            class="rounded-circle"
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                        <?php else : ?>
                                        <!-- Show Initials if No Profile Picture -->
                                        <span class="avatar-initial rounded-circle"
                                            style="width: 40px; height: 40px; background-color: #007bff; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
                                            <?php echo strtoupper(substr($Name, 0, 3)); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">

                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar avatar-online">
                                                        <?php if (!empty($Picture)) : ?>
                                                        <!-- Show Profile Picture -->
                                                        <img src="<?php echo htmlspecialchars($Picture); ?>"
                                                            alt="User Avatar" class="rounded-circle"
                                                            style="width: 100px; height: 100px; object-fit: cover;">
                                                        <?php else : ?>
                                                        <!-- Show Initials if No Profile Picture -->
                                                        <span class="avatar-initial rounded-circle"
                                                            style="width: 40px; height: 40px; background-color: #007bff; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
                                                            <?php echo strtoupper(substr($Name, 0, 3)); ?>
                                                        </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>



                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block"><?php echo $Name; ?></span>
                                                    <small
                                                        class="text-muted"><?php echo htmlspecialchars($JWT_userRole); ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <?php
                                            } else {
                                              //echo "<script>window.location.href = 'login.php';</script>";
                                            }
                                              ?>


                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <?php if ($ChangePassword === 'Enable') : ?>
                                    <li>
                                        <a href="reset.php?page=reset" class="dropdown-item waves-effect">
                                            <i class="ri-lock-password-line me-3"></i>
                                            <span class="align-middle">Change Password</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <form action="function.php" method="post">
                                            <button type="submit" class="dropdown-item" name="logout">
                                                <i class="ri-shut-down-line me-3"></i>
                                                <span class="align-middle">Log Out</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>