<?php


include('include/head.php');
require 'data/dbconfig.php';

$project = [];
$assignees = [];
$project_files = [];
$project_filess = [];
$assignee_files = [];
$user_role = null;

// ✅ Ensure the user is logged in
if (!isset($JWT_userID)) {
    echo "<script>alert('You must be logged in to view this page.'); window.location.href = 'login.php';</script>";
    exit();
}



// ✅ Fetch logged-in user's role
$stmt = $conn->prepare("SELECT role FROM employee WHERE id = ?");
$stmt->bind_param("i",  $JWT_userID);
$stmt->execute();
$stmt->bind_result($role);
if ($stmt->fetch()) {
    $user_role = strtolower(trim($role)); // Normalize
}
$stmt->close();

// ✅ Check if project_id is provided
if (isset($_GET['id'])) {
    $project_id = intval($_GET['id']);

    // ✅ Fetch project details
    $query = "SELECT a.*, u.name AS created_by_name, u.picture AS created_by_avatar, u.designation AS created_by_designation
              FROM client_projects a
              JOIN employee u ON a.created_by = u.id
              WHERE a.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $project = $result->fetch_assoc();
    $stmt->close();

    // ✅ Fetch assignees
    $query = "SELECT cpa.user_id AS assignee_id, 
                     u.name AS assignee_name, 
                     u.designation AS assignee_role, 
                     u.picture AS assignee_avatar, 
                     cpa.user_description 
              FROM client_project_assignees cpa
              JOIN employee u ON cpa.user_id = u.id
              WHERE cpa.project_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $assignees[] = $row;
    }
    $stmt->close();

    // ✅ Project files
    $query = "SELECT file_name, file_path, uploaded_at FROM client_project_files WHERE project_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $project_files[] = $row;
    }
    $stmt->close();

    // ✅ Job files
    //$query = "SELECT file_name, file_path, uploaded_at FROM job_files WHERE job_id = ?";
     $query = "SELECT uploaded_file,updated_at FROM client_projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $project_filess[] = $row;
    }
    $stmt->close();

    // ✅ Assignee uploaded files
    $query = "SELECT u.name AS assigned_user, af.file_name, af.file_path
              FROM client_assignee_files af
              JOIN employee u ON af.user_id = u.id
              WHERE af.project_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $assignee_files[] = $row;
    }
    $stmt->close();

} else {
    echo "<script>alert('Project ID is missing.'); window.history.back();</script>";
    exit();
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">

            <div class="d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="card mb-9">
                        <div class="card-body">

                            <!-- =================Project Overview Section Strart====================== -->

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="card mb-6">
                                        <div class="card-body projectNameDescDiv">
                                            <h4 class="mb-10">
                                                <?= htmlspecialchars($project['name'] ?? 'Project Name') ?></h4>
                                            <small class="card-text text-uppercase text-muted small EditProjStatus">
                                                <?php
                                                        $whole_Project_status = $project['status'] ?? 'Unknown';

                                                        // Define color classes based on status
                                                        $statusColors = [
                                                            "Pending" => "bg-label-warning",
                                                            "In Progress" => "bg-label-primary",
                                                            "Completed" => "bg-label-success",
                                                            "Cancelled" => "bg-label-danger",
                                                        ];

                                                        // Get the corresponding class, or default to "bg-label-secondary" if status is unknown
                                                        $statusClass = $statusColors[$whole_Project_status] ?? "bg-label-secondary";
                                                ?>
                                                    <span class="badge <?= $statusClass ?> rounded-pill"><?= htmlspecialchars($whole_Project_status) ?></span>
                                            </small>
                                            <ul class="list-unstyled mb-0 mt-3 pt-1 project_details_overview">
                                                <li class="d-flex align-items-center mb-4">
                                                    <span class="fw-medium mx-2" style="color: #9d9d9d;">
                                                        <?= $project['description'] ?? 'No description available' ?>
                                                    </span>
                                                </li>
                                            </ul>
                                            <ul class="list-unstyled mb-0 pt-1">
                                                <li class="d-flex align-items-right" style="float: right;">
                                                    <a href="#" class="footer-link view_full_details">More View</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-9">
                                    <div class="card mb-6">
                                        <div class="card-header align-items-center">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <?php
                                                            $Project_priority = $project['priority'] ?? 'Unknown';

                                                            // Define color classes based on status
                                                            $priorityColors = [
                                                                'Critical'=> 'bg-label-danger',
                                                                'High Priority'=>'bg-label-warning',
                                                                'Medium Priority'=> 'bg-label-info',
                                                                'Low Priority'=> 'bg-label-success'
                                                            ];

                                                            // Get the corresponding class, or default to "bg-label-secondary" if status is unknown
                                                            $priorityClass = $priorityColors[$Project_priority] ?? "bg-label-secondary";
                                                    ?>
                                                    <span class="badge <?= htmlspecialchars($priorityClass) ?> rounded-pill"
                                                        style="margin-top: 15px;">
                                                        <i class="ri-flag-line ri-24px"></i>
                                                        <span class="fw-medium mx-2">Priority:</span>
                                                        <span><?= htmlspecialchars($project['priority'] ?? 'Normal') ?></span>
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="addedbyusersec">
                                                        <div class="d-flex align-items-center User_tag">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar me-4">
                                                                    <img src="<?= htmlspecialchars($project['created_by_avatar'] ?? 'assets/img/avatars/default.png') ?>"
                                                                        alt="Avatar" class="square-circle">
                                                                </div>
                                                                <div class="me-2">
                                                                    <h6 class="mb-1">
                                                                        <?= htmlspecialchars($project['created_by_name'] ?? 'Unknown') ?>
                                                                    </h6>
                                                                    <small><?= htmlspecialchars($project['created_by_designation'] ?? 'Unknown') ?></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body border-top pt-5">
                                            <small class="card-text text-uppercase text-muted small">Project
                                                Details</small>
                                            <ul class="list-unstyled my-4 py-1 project_detail_list">
                                              <li class="d-flex align-items-center mb-4">
                                                    <i class="ri-star-smile-line ri-24px"></i>
                                                    <span class="fw-medium mx-2">Client Id:</span>
                                                    <span><?= htmlspecialchars($project['client_id'] ?? 'N/A') ?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-4">
                                                    <i class="ri-file-paper-line ri-24px"></i>
                                                    <span class="fw-medium mx-2">Project Name:</span>
                                                    <span><?= htmlspecialchars($project['name'] ?? 'N/A') ?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-4">
                                                    <i class="ri-check-line ri-24px"></i>
                                                    <span class="fw-medium mx-2">Status:</span>
                                                    <span class="badge <?= $statusClass ?> rounded-pill"><?= htmlspecialchars($whole_Project_status) ?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-4">
                                                    <i class="ri-star-smile-line ri-24px"></i>
                                                    <span class="fw-medium mx-2">Assigned To:</span>
                                                    <span>
                                                        <?php
                                                        if (!empty($assignees)) {
                                                            echo implode(', ', array_column($assignees, 'assignee_name'));
                                                        } else {
                                                            echo "No assignees";
                                                        }
                                                        ?>
                                                    </span>
                                                </li>
                                                <li class="d-flex align-items-center mb-4">
                                                    <i class="ri-calendar-schedule-line ri-24px"></i>
                                                    <span class="fw-medium mx-2">Deadline Date:</span>
                                                    <span><?= date('d M Y', strtotime($project['final_date'] )) ?></span>
                                                </li>
                                                <li class="d-flex align-items-center mb-2">
                                                    <i class="ri-file-word-2-line ri-24px"></i>
                                                    <span class="fw-medium mx-2">Project Files:</span>
                                                    <span>
                                                        <?= count($project_files) ?> Files
                                                        <a href="#" class="footer-link view_full_files">(View Files)</a>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- =================Project Overview Section End====================== -->

                            <!-- =================Project Files Section Start====================== -->


                            <div class="row all_project_files"
                                <?= empty($project_files) ? 'style="display: none;"' : '' ?>>
                                <div class="col-lg-12">
                                    <div class="card mb-6">
                                        <div class="card-body">
                                            <div class="row">
                                                <small class="card-text text-uppercase text-muted small">Project
                                                    Files</small>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="table-responsive text-nowrap">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th>Last Uploaded</th>
                                                                <!-- <th>File Size</th> -->
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                            <?php if (!empty($project_files)) : ?>
                                                            <?php foreach ($project_files as $file) : ?>
                                                            <?php
                                                                    $file_path = htmlspecialchars($file['file_path']);
                                                                    $file_size = file_exists($file_path) ? round(filesize($file_path) / 1024, 2) . ' KB' : 'N/A';
                                                                    ?>
                                                            <tr>
                                                                <td>
                                                                    <i
                                                                        class="ri-file-line ri-22px text-danger me-4"></i>
                                                                    <span
                                                                        class="fw-medium"><?= htmlspecialchars($file['file_name']) ?></span>
                                                                </td>
                                                                <td><span
                                                                        class="fw-medium"><?= date("d/m/Y H:i", strtotime($file['uploaded_at'] ?? 'now')) ?></span>
                                                                </td>
                                                                <!-- <td>
                                                                    
                                                                    <span class="badge rounded-pill bg-label-primary me-1"><?= $fileSizeFormatted ?></span>
                                                                </td> -->
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button"
                                                                            class="btn p-0 dropdown-toggle hide-arrow"
                                                                            data-bs-toggle="dropdown">
                                                                            <i class="ri-more-2-line"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <?php if (!empty($file_path)) : ?>
                                                                                <a class="dropdown-item waves-effect" href="https://drive.google.com/uc?id=<?= $file_path ?>&export=download">
                                                                                    <i class="ri-download-2-line me-1"></i> Download
                                                                                </a>
                                                                            <?php else : ?>
                                                                                <a class="dropdown-item waves-effect" href="<?= htmlspecialchars($file_path ) ?>" download>
                                                                                    <i class="ri-download-2-line me-1"></i> Download
                                                                                </a>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                            <?php else : ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center">No project files available</td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- =================Project Files Section Start====================== -->


                                 <!-- =================  client Project Files Section Start====================== -->


                              <!-- <div class="row all_project_files"
                                <?= empty($project_filess) ? 'style="display: none;"' : '' ?>>
                                <div class="col-lg-12">
                                    <div class="card mb-6">
                                        <div class="card-body">
                                            <div class="row">
                                                <small class="card-text text-uppercase text-muted small"> client Project
                                                    Files</small>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="table-responsive text-nowrap">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th>Last Uploaded</th>

                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                            <?php if (!empty($project_filess)) : ?>
                                                            <?php foreach ($project_filess as $file) : ?>
                                                            <?php
                                                                    $file_path = htmlspecialchars($file['uploaded_file']);
                                                                    $file_size = file_exists($file_path) ? round(filesize($file_path) / 1024, 2) . ' KB' : 'N/A';
                                                                    ?>
                                                            <tr>
                                                                <td>
                                                                    <i
                                                                        class="ri-file-line ri-22px text-danger me-4"></i>
                                                                    <span
                                                                        class="fw-medium"><?= htmlspecialchars($file['uploaded_file']) ?></span>
                                                                </td>
                                                                <td><span
                                                                        class="fw-medium"><?= date("d/m/Y H:i", strtotime($file['uploaded_at'] ?? 'now')) ?></span>
                                                                </td>

                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button"
                                                                            class="btn p-0 dropdown-toggle hide-arrow"
                                                                            data-bs-toggle="dropdown">
                                                                            <i class="ri-more-2-line"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <?php if (!empty($file_path)) : ?>
                                                                            <a class="dropdown-item waves-effect"
                                                                                href="https://drive.google.com/uc?id=<?= $file_path ?>&export=download">
                                                                                <i class="ri-download-2-line me-1"></i>
                                                                                Download
                                                                            </a>
                                                                            <?php else : ?>
                                                                            <a class="dropdown-item waves-effect"
                                                                                href="<?= htmlspecialchars($file_path ) ?>"
                                                                                download>
                                                                                <i class="ri-download-2-line me-1"></i>
                                                                                Download
                                                                            </a>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                            <?php else : ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center">No project files
                                                                    available</td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->



                            <!-- ================= client Project Files Section Start====================== -->


                            <!-- =================Project Details Section Start====================== -->

                            <div class="row all_project_details" style="display: none;">
                                <div class="col-lg-12">
                                    <div class="card mb-6">
                                        <div class="card-body">
                                            <small class="card-text text-uppercase text-muted small">Project
                                                Description</small>
                                            <ul class="list-unstyled mb-0 mt-3 pt-1 project_details_overview">
                                                <li class="d-flex align-items-center mb-4">
                                                    <span class="fw-medium mx-2">
                                                        <?= $project['description'] ?? 'No description available' ?>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- =================Project Details Section End====================== -->


                            <!-- =================Users Subtask Repeater Section Start====================== -->


                            <?php

                            //echo '<pre>'; print_r($assignees); echo '</pre>';


                            foreach ($assignees as $index => $assignee) :
                                $assignee_id = $assignee['assignee_id'];
                                $assignee_name = htmlspecialchars($assignee['assignee_name']);
                                $assignee_role = htmlspecialchars($assignee['assignee_role']);
                                $assignee_avatar = !empty($assignee['assignee_avatar']) ? $assignee['assignee_avatar'] : 'assets/img/avatars/default.png';
                                $task_description = $assignee['user_description'];

                                // Fetch files for this assignee
                                $query_files = "SELECT file_name, file_path, uploaded_at FROM client_assignee_files WHERE user_id = ? AND project_id = ?";
                                $stmt_files = $conn->prepare($query_files);
                                $stmt_files->bind_param("si", $assignee_id, $project_id);
                                $stmt_files->execute();
                                $result_files = $stmt_files->get_result();
                                $files = $result_files->fetch_all(MYSQLI_ASSOC);
                            ?>



                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-6">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    <small class="card-text text-uppercase text-muted small">
                                                        <span
                                                            class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit"
                                                            style="background: #f4e0ff;">#<?= $index + 1 ?></span>
                                                        <b><?= $assignee_name ?></b> Task
                                                    </small>
                                                    <ul class="list-unstyled mb-0 mt-3 pt-1 project_details_overview">
                                                        <li class="d-flex align-items-center mb-4">
                                                            <span class="fw-medium mx-2" style="color: #9d9d9d;">
                                                                <?= $task_description ?>                                                                
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-lg-3">
                                                    <small class="card-text text-uppercase text-muted small">Assigned
                                                        By</small>
                                                    <ul class="list-unstyled mb-0 mt-3 project_details_overview">
                                                        <li class="d-flex align-items-center">
                                                            <div class="assigedbyusersec">
                                                                <div class="d-flex align-items-center User_tag">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar me-4">
                                                                            <img src="<?= $assignee_avatar ?>"
                                                                                alt="Avatar" class="square-circle">
                                                                        </div>
                                                                        <div class="me-2">
                                                                            <h6 class="mb-1"><?= $assignee_name ?></h6>
                                                                            <small><?= $assignee_role ?></small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="table-responsive text-nowrap">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th>Last Uploaded</th>
                                                                <!-- <th>Size</th> -->
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                            <?php if (!empty($files)) : ?>
                                                            <?php foreach ($files as $file) :
                                                                $file_path = htmlspecialchars($file['file_path']);
                                                                $file_size = file_exists($file_path) ? round(filesize($file_path) / 1024 / 1024, 2) . ' MB' : 'N/A';
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <i
                                                                        class="ri-file-line ri-22px text-danger me-4"></i>
                                                                    <span
                                                                        class="fw-medium"><?= htmlspecialchars($file['file_name']) ?></span>
                                                                </td>
                                                                <td><span
                                                                        class="fw-medium"><?= date("d/m/Y H:i", strtotime($file['uploaded_at'])) ?></span>
                                                                </td>
                                                                <!-- <td><span
                                                                        class="badge rounded-pill bg-label-primary me-1"><?= $file_size ?></span>
                                                                </td> -->
                                                                   <!-- <td>
                                                                    <div class="dropdown">
                                                                        <button type="button"
                                                                            class="btn p-0 dropdown-toggle hide-arrow"
                                                                            data-bs-toggle="dropdown">
                                                                            <i class="ri-more-2-line"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item waves-effect"
                                                                                href="<?= $file_path ?>" download>
                                                                                <i class="ri-download-2-line me-1"></i>
                                                                                Download
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td> -->
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button type="button"
                                                                            class="btn p-0 dropdown-toggle hide-arrow"
                                                                            data-bs-toggle="dropdown">
                                                                            <i class="ri-more-2-line"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <?php if (!empty($file_path)) : ?>
                                                                                <a class="dropdown-item waves-effect" href="https://drive.google.com/uc?id=<?= $file_path ?>&export=download">
                                                                                    <i class="ri-download-2-line me-1"></i> Download
                                                                                </a>
                                                                            <?php else : ?>
                                                                                <a class="dropdown-item waves-effect" href="<?= htmlspecialchars($file_path ) ?>" download>
                                                                                    <i class="ri-download-2-line me-1"></i> Download
                                                                                </a>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                            <?php else : ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center">No files available
                                                                </td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="col-12 d-flex justify-content-start mt-5">
                                                        <button type="button" id="ViewAllReply" data-subtask-user-id="<?= $assignee_id ?>" data-project_id="<?= $project_id ?>"
                                                            class="btn btn-outline-secondary me-4 waves-effect View_all_reply_task_btn">
                                                            <i class="ri-message-3-line ri-22px me-4"></i> View Reply
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="col-12 d-flex justify-content-end mt-5">
                                                      <?php if ($user_role === 'user'): ?>
                                                        <button type="button" id="replyBtn" data-subtask-user-id="<?= $assignee_id ?>" data-project_id="<?= $project_id ?>"
                                                            class="btn btn-outline-secondary me-4 waves-effect reply_task_btn">
                                                            <i class="ri-message-3-line ri-22px me-4"></i> Reply
                                                            
                                                        </button>
                                                        <button type="button" id="statusbtn" data-subtask-user-id="<?= $assignee_id ?>" data-project_id="<?= $project_id ?>"
                                                            class="btn btn-outline-success me-4 waves-effect sub_change_status_btn">
                                                            <i class="ri-check-line ri-22px me-4"></i> Change Status
                                                        </button>
                                                        <!-- <button type="button" id="submit" value="1" data-subtask-user-id="<?= $assignee_id ?>" data-project_id="<?= $project_id ?>"
                                                            class="btn btn-primary   me-4 edit_whole_subtask_btn waves-effect waves-light">
                                                            <i class="ri-edit-line ri-22px me-4"></i> Edit
                                                        </button> -->
                                                        
                                                        <button type="button" id="submit" value="1"
                                                         data-subtask-user-id="<?= $assignee_id ?>"
                                                            data-project_id="<?= $project_id ?>"
                                                            class="btn btn-primary me-4 edit_whole_subtask_btn waves-effect waves-light">
                                                            <i class="ri-edit-line ri-22px me-4"></i> Edit
                                                        </button>
                                                    <?php endif; ?>
                                                     <?php if ($user_role === 'admin'): ?>
                                                         <button type="button" id="submit" value="2" data-subtask-user-id="<?= $assignee_id ?>" data-project_id="<?= $project_id ?>"
                                                            class="btn btn-primary edit_whole_subtask_btn1 waves-effect waves-light">
                                                            <i class="ri-edit-line ri-22px me-4"></i> Update Client Files
                                                        </button>
                                                         <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php endforeach; ?>




                            <!-- =================Users Subtask Repeater Section Start====================== -->
                        </div>
                    </div>
                </div>
            </div>



            <!-- Subtask Status Change Card Modal -->
            <div class="modal fade custom_model" id="Change_subtask_Modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body p-0">
                            <form id="Change_subtask_status_form" class="row g-5">
                                <!-- Account Details -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-center">Are you Sure..! <br> You Want Change Status</h6>
                                        <hr class="mt-0" />
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12">

                                        <div class="col-md-12 mb-4">
                                            <div class="form-floating form-floating-outline">

                                                <input type="hidden" id="Stautus_hiddenEditSubtaskId"
                                                    value="">
                                                <input type="hidden" id="Stautus_hiddenEditProjectId"
                                                    value="<?= $project_id ?>">


                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select name="Subtask_changestatus" id="Subtask_changestatus"
                                                        class="form-control select2" required>
                                                        <option value="Pending">Pending</option>
                                                        <option value="InProgress">InProgress</option>
                                                        <option value="Hold">Hold</option>
                                                        <option value="Completed">Completed</option>
                                                    </select>
                                                    <label for="changestatus">Choose Status Name</label>
                                                </div>


                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="col-12 d-flex justify-content-center mt-2">
                                            <button type="reset"
                                                class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                            <button type="submit" id="Change_subtask_status_btn" value="ChangeStatus"
                                                class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--Subtask Status Change Card Modal -->


            <!-- Whole Subtask Edit Card Modal -->
            <div class="modal fade custom_model" id="whole_subtask_edit_Modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                    <div class="modal-content p-10">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body p-0">
                            <form id="project_subtask_edit" class="row g-5">
                                <!-- Account Details -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-center"></h6>
                                        <hr class="mt-0" />
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="col-md-12 mb-4">
                                            <div class="form-floating form-floating-outline">

                                                <input type="hidden" id="hiddenEditSubtaskId"
                                                    value="">
                                                <input type="hidden" id="hiddenEditProjectId"
                                                    value="<?= $project_id ?>">

                                                <div class="form-floating form-floating-outline mb-3">
                                                    <div class="card-body project_editor_text p-0">
                                                        <div id="Edit_sub_task_fullEditor">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- <div class="form-floating form-floating-outline mb-3">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th>Size</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">

                                                        </tbody>
                                                    </table>
                                                </div> -->

                                                <div class="form-floating form-floating-outline mb-3">
                                                    <lebel for="NewFile_Single">Add Files</lebel>
                                                    <input type="file" class="form-control mt-2" name="NewFile_Single"
                                                        id="NewFile_Single" required>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="col-12 d-flex justify-content-center mt-12">
                                            <button type="reset"
                                                class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                            <button type="submit" id="NewFile_edit_submit" value="NewFile_edit"
                                                class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Whole Subtask Edit Card Modal -->
                     <!-- Whole Subtask Edit Card Modal  final upload-->
            <div class="modal fade custom_model" id="whole_subtask_edit_Modal1" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                    <div class="modal-content p-10">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body p-0">
                            <form id="project_subtask_edit_upload" class="row g-5">
                                <!-- Account Details -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-center"></h6>
                                        <hr class="mt-0" />
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="col-md-12 mb-4">
                                            <div class="form-floating form-floating-outline">

                                                <input type="hidden" id="hiddenEditSubtaskId1"
                                                    value="">
                                                <input type="hidden" id="hiddenEditProjectId1"
                                                    value="<?= $project_id ?>">

                                                <div class="form-floating form-floating-outline mb-3">
                                                    <div class="card-body project_editor_text p-0">
                                                        <div id="Edit_sub_task_fullEditor_one">
                                                        </div>
                                                    </div>
                                                </div>

                                                    <!-- <div class="form-floating form-floating-outline mb-3">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th>Size</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                           
                                                        </tbody>
                                                    </table>
                                                </div> -->
                                                   <div class="form-floating form-floating-outline mb-3">
                                                   <lebel for="client_id">Client Id</lebel>
                                                    <input type="text" class="form-control mt-2" name="client_id"
                                                            value="<?= htmlspecialchars($project['client_id'] ?? '') ?>"
                                                        id="client_id" required>
                                                </div>
                                                  <div class="form-floating form-floating-outline mb-3">
                                                    <lebel for="job_name">Job Name</lebel>
                                                    <input type="text" class="form-control mt-2" name="job_name"
                                                        value="<?= htmlspecialchars($project['name'] ?? '') ?>"
                                                        id="job_name" required>
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3">
                                                    <lebel for="NewFile_Single1">Add Files</lebel>
                                                    <input type="file" class="form-control mt-2" name="NewFile_Single1"
                                                        id="NewFile_Single1" required>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="col-12 d-flex justify-content-center mt-12">
                                            <button type="reset"
                                                class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                            <button type="submit" id="NewFile_edit_submit1" value="NewFile_edit"
                                                class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Whole Subtask Edit Card Modal final upload -->


            <!-- Reply Sub Task Card Modal -->
            <div class="modal fade custom_model" id="reply_subtask_Model" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body p-0">
                            <form id="Reply_form_SubTask" class="row g-5">
                                <!-- Account Details -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-center">Reply Task</h6>
                                        <hr class="mt-0" />
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="col-md-12 mb-4">
                                            <div class="form-floating form-floating-outline">

                                                <input type="hidden" id="Reply_hiddenEditSubtaskId"
                                                    value="">
                                                <input type="hidden" id="Reply_hiddenEditProjectId"
                                                    value="<?= $project_id ?>">

                                                    <div class="form-floating form-floating-outline mb-3">
                                                    <textarea class="form-control employee_details_txtBox" id="Comment_repli" style="height: 160px;" placeholder="Enter New Comment here" maxlength="100"></textarea>
                                                    <div id="Address_charCount" class="char-count-overlay">/100</div>
                                                </div>                         

                                                <!-- <div class="form-floating form-floating-outline mb-3">
                                                    <textarea class="form-control" id="reply_subtask_box" name="reply_subtask_box"
                                                        style="height: 150px;" placeholder="Enter Reply here"
                                                        required=""></textarea>
                                                    <label for="reply_text">Reply Text</label>
                                                </div> -->

                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="col-12 d-flex justify-content-center mt-12">
                                            <button type="reset"
                                                class="btn btn-outline-secondary me-4 waves-effect">Cancel</button>
                                            <button type="submit" id="Reply_subtas_btn" name="Reply_subtas_btn" value="Reply_subtas_btn"
                                                class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--Reply Sub Task  Card Modal -->


            <!-- Reply Sub Task Card Modal -->
            <div class="modal fade custom_model" id="View_all_reply_subtask_Model" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                        <div class="modal-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body p-0">
                            <form id="task_type_add" class="row g-5">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-center">All Replies</h6>
                                        <hr class="mt-0" />
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="card-body pt-5">
                                            <ul class="timeline mb-0"></ul> <!-- Empty list to be filled dynamically -->
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!--Reply Sub Task  Card Modal -->




            <!-- / Content -->
            <?php include('include/footer.php'); ?>


            <!-- Page JS -->
            <script src="assets/vendor/libs/quill/quill.js"></script>
            <script src="assets/vendor/libs/quill/katex.js"></script>
            <script src="assets/js/forms-editors.js"></script>




            <script>
            $(document).ready(function() {

                // When the "More View" link is clicked
                $('.view_full_details').on('click', function(e) {
                    e.preventDefault(); // Prevent default link behavior

                    // Check if the div is visible
                    if ($('.all_project_details').is(':visible')) {
                        // If it's visible, hide it
                        $('.all_project_details').slideUp();
                    } else {
                        // If it's not visible, show it
                        $('.all_project_details').slideDown();
                    }
                });


                // When the "More Files" link is clicked
                $('.view_full_files').on('click', function(e) {
                    e.preventDefault(); // Prevent default link behavior

                    // Check if the div is visible
                    if ($('.all_project_files').is(':visible')) {
                        // If it's visible, hide it
                        $('.all_project_files').slideUp();
                    } else {
                        // If it's not visible, show it
                        $('.all_project_files').slideDown();
                    }
                });

                // When the "Change Sub Task Status" link is clicked
                $('.sub_change_status_btn').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();

                        // Get Subtask User ID from the button
                        var subtaskUserId = $(this).data('subtask-user-id');
                        var project_id = $(this).data('project_id'); 

                        $('#Stautus_hiddenEditSubtaskId').val(subtaskUserId);
                        $('#Stautus_hiddenEditProjectId').val(project_id);


                        // Show the modal for each button clicked
                        $('#Change_subtask_Modal').modal('show');
                    });
                    
                });

                
                
                $("#Change_subtask_status_form").on("submit", function (e) {
                    e.preventDefault();

                    let projectid = $("#Stautus_hiddenEditProjectId").val();

                    let formData = new FormData(this);
                    formData.append("subtask_user_id", $("#Stautus_hiddenEditSubtaskId").val());
                    formData.append("action", "Status_subtask_change");
                    formData.append("projectId", projectid);


                    $.ajax({
                        url: "include/handlers/ClientProjectHandler.php", // PHP file to handle file upload
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            let result = JSON.parse(response);
                            if (result.success) {
                                showModalWithParams(`${result.message}`, 'true');
                                // Redirect to a new URL
                                setTimeout(function() {
                                    window.location.href = "editproject.php?page=Editproject&id=" + projectid;
                                }, 10000);
                            } else {
                                alert("SUbtask Stauts Change Process is Faild");
                            }
                        }
                    });

                });


                $('.edit_whole_subtask_btn').on('click', function (e) {
                        e.preventDefault();

                        // Get Subtask User ID from the button
                        var subtaskUserId = $(this).data('subtask-user-id'); 

                        var project_id = $(this).data('project_id'); 

                        // Check if ID exists
                        if (!subtaskUserId) {
                            alert("Subtask User ID is missing!");
                            return;
                        }

                        // Fetch Data using AJAX
                        $.ajax({
                            url: "include/handlers/ClientProjectHandler.php", // Backend script to fetch data
                            type: "POST",
                            data: 
                            {
                                 subtask_user_id: subtaskUserId,
                                 project_id: project_id,
                                 action: "fetch_subtask_details"
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    // Populate modal fields
                                    $('#whole_subtask_edit_Modal h6.text-center').text('Edit ' + response.assignee_name + ' Sub Task');
                                    appendContentToEditor('#Edit_sub_task_fullEditor', response.task_description);

                                    $('#hiddenEditSubtaskId').val(subtaskUserId);

                                    // Populate Files Table
                                    var filesHtml = '';
                                    if (response.files.length > 0) {
                                        response.files.forEach(function (file) {
                                            filesHtml += `
                                                <tr>
                                                    <td>${file.file_name}</td>
                                                    <td>
                                                        <a class="dropdown-item waves-effect"
                                                            href="https://drive.google.com/uc?id=${file.file_path}&export=download" target="_blank"><i class="ri-download-2-line me-1"></i>
                                                        </a>
                                                    </td>
                                                </tr>`;
                                        });
                                    } else {
                                        filesHtml = '<tr><td colspan="2">No files found</td></tr>';
                                    }
                                    $('#whole_subtask_edit_Modal tbody.table-border-bottom-0').html(filesHtml);

                                    // Open Modal
                                    $('#whole_subtask_edit_Modal').modal('show');
                                } else {
                                    alert("Failed to fetch subtask data!");
                                }
                            },
                            error: function () {
                                alert("Error fetching data!");
                            }
                        });
                });

                    $('.edit_whole_subtask_btn1').on('click', function (e) {
                        e.preventDefault();

                        // Get Subtask User ID from the button
                        var subtaskUserId = $(this).data('subtask-user-id'); 

                        var project_id = $(this).data('project_id'); 

                        // Check if ID exists
                        if (!subtaskUserId) {
                            alert("Subtask User ID is missing!");
                            return;
                        }

                        // Fetch Data using AJAX
                        $.ajax({
                            url: "include/handlers/ClientProjectHandler.php", // Backend script to fetch data
                            type: "POST",
                            data: 
                            {
                                 subtask_user_id_final: subtaskUserId,
                                 project_id_final: project_id,
                                 action: "fetch_subtask_details_final"
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.success) {
                                    // Populate modal fields
                                    $('#whole_subtask_edit_Modal1 h6.text-center').text('Edit ' + response.assignee_name + ' Sub Task');
                                    appendContentToEditor('#Edit_sub_task_fullEditor_one', response.task_description);

                                    $('#hiddenEditSubtaskId1').val(subtaskUserId);

                                    // Populate Files Table
                                    var filesHtml = '';
                                    if (response.files.length > 0) {
                                        response.files.forEach(function (file) {
                                            filesHtml += `
                                                <tr>
                                                    <td>${file.file_name}</td>
                                                    <td>
                                                        <a class="dropdown-item waves-effect"
                                                            href="https://drive.google.com/uc?id=${file.file_path}&export=download" target="_blank"><i class="ri-download-2-line me-1"></i>
                                                        </a>
                                                    </td>
                                                </tr>`;
                                        });
                                    } else {
                                        filesHtml = '<tr><td colspan="2">No files found</td></tr>';
                                    }
                                    $('#whole_subtask_edit_Modal1 tbody.table-border-bottom-0').html(filesHtml);

                                    // Open Modal
                                    $('#whole_subtask_edit_Modal1').modal('show');
                                } else {
                                    alert("Failed to fetch subtask data!");
                                }
                            },
                            error: function () {
                                alert("Error fetching data!");
                            }
                        });
                });
                



                // Handle File Upload
                $("#project_subtask_edit").on("submit", function (e) {
                    e.preventDefault();

                    let projectid = $("#hiddenEditProjectId").val();

                    let formData = new FormData(this);
                    formData.append("subtask_user_id", $("#hiddenEditSubtaskId").val());
                    formData.append("action", "update_subtask_details");
                    formData.append("projectId", projectid);

                    formData.append("project_description", encodeURIComponent($('#Edit_sub_task_fullEditor .ql-editor').html()));

                    $.ajax({
                        url: "include/handlers/ClientProjectHandler.php", // PHP file to handle file upload
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            let result = JSON.parse(response);
                            if (result.success) {
                                showModalWithParams(`${result.message}`, 'true');
                                // Redirect to a new URL
                                setTimeout(function() {
                                    window.location.href = "editproject.php?page=Editproject&id=" + projectid;
                                }, 10000);
                            } else {
                                alert("File upload failed!");
                            }
                        }
                    });
                });

                 // Handle File Upload
                $("#project_subtask_edit_upload").on("submit", function (e) {
                    e.preventDefault();

                    let projectid = $("#hiddenEditProjectId1").val();

                    let formData = new FormData(this);
                    formData.append("subtask_user_id_final", $("#hiddenEditSubtaskId1").val());
                         formData.append("client_id", $("#client_id").val());
                    formData.append("action", "admin_final_files");
                    formData.append("projectId", projectid);

                    formData.append("project_description", encodeURIComponent($('#Edit_sub_task_fullEditor_one .ql-editor').html()));

                    $.ajax({
                        url: "include/handlers/ClientProjectHandler.php", // PHP file to handle file upload
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            let result = JSON.parse(response);
                            if (result.success) {
                                showModalWithParams(`${result.message}`, 'true');
                                // Redirect to a new URL
                                setTimeout(function() {
                                    window.location.href = "editproject.php?page=Editproject&id=" + projectid;
                                }, 10000);
                            } else {
                                alert("File upload failed!");
                            }
                        }
                    });
                });



                // When the "Reply Task" link is clicked
                $('.reply_task_btn').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();

                        // Get Subtask User ID from the button
                        var subtaskUserId = $(this).data('subtask-user-id');
                        var project_id = $(this).data('project_id'); 

                        $('#Reply_hiddenEditSubtaskId').val(subtaskUserId);
                        $('#Reply_hiddenEditProjectId').val(project_id);

                        // Show the modal for each button clicked
                        $('#reply_subtask_Model').modal('show');
                    });
                });


                $("#Reply_form_SubTask").on("submit", function (e) {
                    e.preventDefault();

                    let projectid = $("#Reply_hiddenEditProjectId").val();

                    let formData = new FormData(this);
                    formData.append("subtask_user_id", $("#Reply_hiddenEditSubtaskId").val());   
                     formData.append("Address_charCount",  $("#Comment_repli").val());
                    formData.append("action", "Reply_subtask_Add");
                    formData.append("projectId", projectid);

                  


                    $.ajax({
                        url: "include/handlers/ClientProjectHandler.php", // PHP file to handle file upload
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            let result = JSON.parse(response);
                            if (result.success) {
                                showModalWithParams(`${result.message}`, 'true');
                                // Redirect to a new URL
                                setTimeout(function() {
                                    window.location.href = "editproject.php?page=Editproject&id=" + projectid;
                                }, 5000);
                            } else {
                                alert("Replying Process is Faild");
                            }
                        }
                    });

                });


                // // When the "View All Reply Task" link is clicked
                // $('.View_all_reply_task_btn').each(function() {
                //     $(this).on('click', function(e) {
                //         e.preventDefault();

                //         // Show the modal for each button clicked
                //         $('#View_all_reply_subtask_Model').modal('show');
                //     });
                // });

                $('.View_all_reply_task_btn').on('click', function() {
                    let projectId = $(this).data('project_id');
                    let userId = $(this).data('subtask-user-id');

                    $.ajax({
                        url: 'include/handlers/ClientProjectHandler.php',
                        type: 'POST',
                        data: { project_id: projectId, subtask_user_id: userId, action: "Fetch_all_replies" },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                let replyList = '';

                                if (response.replies.length > 0) {
                                    response.replies.forEach(function(reply) {
                                        replyList += `
                                            <li class="timeline-item timeline-item-transparent">
                                                <span class="timeline-point timeline-point-primary"></span>
                                                <div class="timeline-event">
                                                    <div class="timeline-header mb-3">
                                                        <h6 class="mb-0">${reply.user_name} Replied</h6>
                                                        <small class="text-muted">${reply.created_at}</small>
                                                    </div>
                                                    <p class="mb-2">${reply.comment}</p>
                                                </div>
                                            </li>`;
                                    });
                                } else {
                                    replyList = '<p class="text-center text-muted">No replies found.</p>';
                                }

                                $('#View_all_reply_subtask_Model .timeline').html(replyList);
                                $('#View_all_reply_subtask_Model').modal('show');
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                });

                    // ----------   comment new task set Limit   ----------   


          const $textarea = $('#Comment_repli');
          const $Address_charCount = $('#Address_charCount');

          // Update character count on input
          $textarea.on('input', function() {
            const remaining = 100 - $textarea.val().length;
            $Address_charCount.text(remaining);
          });

            });
            </script>