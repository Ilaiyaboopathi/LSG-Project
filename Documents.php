<?php include('include/head.php'); ?>


<?php require 'data/dbconfig.php';
    
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'data/dbconfig.php';



$AddNewDocument = '';
$DocumentViews = '';
$pid =  $JWT_userID ;
$permissionsql = "SELECT * FROM permissions WHERE userID='$pid' ";
$resultPermission = $conn->query($permissionsql);
if ($resultPermission->num_rows > 0) {
  $row = $resultPermission->fetch_assoc();
  $AddNewDocument = htmlspecialchars($row['AddNewDocument']);
    $DocumentViews = htmlspecialchars($row['DocumentViews']);
}




$buttonText = isset($buttonText) ? $buttonText : 'Submit';

$hideClass = isset($JWT_userRole) && $JWT_userRole === 'admin' ? '' : 'Docs_Emp_Hide';

$file_url = "";
$editFileName = "";

$customImageMap = [
    'pdf' => 'assets/img/illustrations/pdf.png',
    'xlsx' => 'assets/img/illustrations/xlsx.png',
    'csv' => 'assets/img/illustrations/csv.png',
    'docx' => 'assets/img/illustrations/docs.png',
    'doc' => 'assets/img/illustrations/docs.png'
];

// --------- Fetch Edit Details Start --------------

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize and validate the ID
    $sql = "SELECT * FROM docs_upload WHERE id='$id' ORDER BY id DESC";
    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $name_of_file = htmlspecialchars($row['name']);
      $file_url = htmlspecialchars($row['file_url']);
      $tagemployee = $row['tagged_emp'];
      $InfoFIle = pathinfo($file_url);
      $editFileName = $InfoFIle['basename'];
      $buttonText = 'Update'; // Set button text to 'Update'

       // Split the assigned employees string into an array
       $assignedEmployees = explode(',', $tagemployee);
    }

    $fileExtension = pathinfo($file_url, PATHINFO_EXTENSION);
    if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) 
    {
        $file_url = htmlspecialchars($row['file_url']);
    }
    else
    {
        $file_url = $customImageMap[$fileExtension];
    }
    
  }
  
  // --------- Fetch Edit Details Start --------------



// ---------- Create JSON File Content Start -------------

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $sql = "SELECT * FROM docs_upload WHERE tagged_emp like '%" . $name . "%'";
}
else{
    $sql = "SELECT * FROM docs_upload WHERE tagged_emp like '%" . $conn->real_escape_string($JWT_adminName) . "%' ";
} 


$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        if($JWT_userRole == "admin")
        {
            // Add each row to the data array
            $data[] = array(

                'id' => $row['id'],
                'name' => $row['name'],
                'file_url' => $row['file_url'],
                'tagged_emp' => $row['tagged_emp'],
                'enable' => $row['isenable'],
                'addedBy' => $row['addedBy']
            );
        }
        else
        {
            $tagEmp = $row['tagged_emp'];
            $name = $JWT_adminName;

            $TaggedEmployees = [];
            if (isset($tagEmp) && !empty($tagEmp)) {
                $TaggedEmployees = array_map('trim', explode(',', $tagEmp));
            }
        
            
            if(in_array($name, $TaggedEmployees))
            {
                // Add each row to the data array
                $data[] = array(

                    'id' => $row['id'],
                    'name' => $row['name'],
                    'file_url' => $row['file_url'],
                    'tagged_emp' => $row['tagged_emp'],
                    'enable' => $row['isenable'],
                    'addedBy' => $row['addedBy']
                );
            }
        }
    }
}
$response = array('data' => $data);
$json = json_encode($response, JSON_PRETTY_PRINT);

$file = 'assets/json/docs_upload.json';


if (file_put_contents($file, $json)) {
} else {
    echo "Failed to write data to $file";
}

// ---------- Create JSON File Content Start -------------

?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        

        <div class="row <?php echo $hideClass; ?>">
            <!-- FormValidation -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <?php if ( $AddNewDocument === 'Enable') : ?>
                        <form id="file_docs_submit" class="row g-5">
                            <!-- Reminder Form -->
                            <div class="col-12">
                                <h6>Documents </h6>
                                <hr class="mt-0" />
                            </div>

                            <div class="row">
                                <div class="col-6 ">
                                    <!-- Email -->
                                        <div class="col-md-12 mb-4">
                                            <div class="form-floating form-floating-outline">
                                                <input type="hidden" id="hiddenId"
                                                    value="<?php echo empty($id) ? '' : htmlspecialchars($id); ?>">
                                                <input type="text" id="docs_name" class="form-control"
                                                    value="<?php echo empty($name_of_file) ? '' : htmlspecialchars($name_of_file); ?>"
                                                    placeholder="Document Name" name="docs_name" />
                                                <label for="docs_name">Document Name</label>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-4">
                                            <label for="formFile" class="form-label">Attach File</label>
                                            <input type="hidden" id="file_upload_hidden"
                                                value="<?php echo empty($file_url) ? '' : htmlspecialchars($file_url); ?>">
                                            <input class="form-control" type="file" id="Emp_file_upload"onchange="validateFile()">
                                            <div id="fileError" class="text-danger" style="display:none;"></div>
                                        </div>

                                        <!-- Tag Employee Start -->
                                        <div class="col-12 mb-4">

                                            <?php

                                            $sql1 = "SELECT name FROM employee WHERE  isenable = 1";
                                            $result = $conn->query($sql1);

                                            $selectedEmployees = [];
                                            if (isset($tagemployee) && !empty($tagemployee)) {
                                                // Trim whitespace and ensure names are split correctly
                                                $selectedEmployees = array_map('trim', explode(',', $tagemployee));
                                            }

                                            echo '<div class="form-floating form-floating-outline form-floating-select2 mt-5">';
                                            echo '<div class="position-relative">';
                                            echo '<select  id="Docs_select_employees" class="select2 form-select" multiple>';

                                            // Generate the options
                                            if ($result->num_rows > 0) {
                                                $AllNames = [];
                                                while ($row = $result->fetch_assoc()) {
                                                $name = htmlspecialchars($row['name']);
                                                $AllNames[] = $name;
                                                }
                                                sort($AllNames);

                                                foreach ($AllNames as $name) {
                                                $isSelected = in_array($name, $selectedEmployees) ? ' selected' : '';
                                                echo '<option value="' . $name . '"' . $isSelected . '>' . $name . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No User found.</option>';
                                            }

                                            echo '</select>';
                                            echo '</div>';
                                            echo '<label for="selectempoyees">Tag User</label>';
                                            echo '</div>';

                                            // Close the connection
                                            $conn->close();
                                            ?>

                                        </div>

                                        <!-- Tag Employee End -->

                                        <!-- Buttons -->
                                        <div class="col-12 d-flex justify-content-end mt-8">
                                            <button type="reset"
                                                class="btn btn-outline-secondary cancel_click_btn me-4 waves-effect">Cancel</button>
                                            <button type="submit" id="file_submit_btn"
                                                value="<?php echo ($buttonText === 'Update') ? 'UpdateDocs' : 'AddDocs'; ?>"
                                                class="btn btn-primary">
                                                <?php echo htmlspecialchars($buttonText); ?>
                                            </button>
                                        </div>

                                    </div>

                                    <div class="col-6 ">
                                        <div class="col-md-12 mb-4">
                                            <div class="form-floating form-floating-outline pre_view_section">
                                                <img id="preview_emp_file" src="#" alt="Image Preview"
                                                    style="display:none; max-width: 300px; max-height: 300px;" />
                                                <p style="display:none;" id="para_preview_file">filename.png</p>
                                            </div>
                                            <!-- ---No File Choose--- -->
                                            <div class="form-floating form-floating-outline No_pre_view_section">
                                                <img class="No_preview_file" 
                                                    src="<?php echo empty($file_url) ? 'assets\img\illustrations\No_file.png' : htmlspecialchars($file_url); ?>" alt="Image Preview"/>
                                                <p class="No_preview_para">
                                                <?php echo empty($editFileName) ? 'No file selected to preview..!' : htmlspecialchars($editFileName); ?>
                                                </p>
                                            </div>
                                            <!-- ---No File Choose--- -->
                                        </div>
                                    </div>

                                    
                                </div>

                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


        <?php if ( $DocumentViews === 'Enable') : ?>

        <div class="row" style="margin-top: 50px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-datatable table-responsive">
                            <table class="data_tables_docs_upload table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>S.No</th>
                                        <th>Document Name</th>
                                        <th>Tagged Users</th>
                                        <th>Preview</th>
                                        <th>AddedBy</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<!-- / Content -->

<?php include('include/footer.php'); ?>

<script src="assets/js/docs_upload.js"></script>


<script>

    //  <!------ Cancel Click Event   -------->
      $(document).on('click', '.cancel_click_btn', function(e) {
        window.location.href = 'Documents.php?page=Documents';
      });
     //  <!------ Cancel Click Event   -------->

    $(document).ready(function () {
        $('#Emp_file_upload').change(function (event) {
            var reader = new FileReader();

            var file = event.target.files[0],
            fileName = file.name,
            fileExtension = fileName.split('.').pop().toLowerCase();

            $('.No_pre_view_section').hide();

            //if Select the file once Hidden Value Null
            $('#file_upload_hidden').val('');


            reader.onload = function (e) 
            {

                if (fileExtension === 'png' || fileExtension === 'jpg' || fileExtension === 'jpeg') 
                {
                    $('#preview_emp_file').attr('src', e.target.result).show();
                }
                else
                {
                    switch (fileExtension) { 
                        case 'pdf': 
                            $('#preview_emp_file').attr('src', 'assets/img/illustrations/pdf.png').show();
                            $('#preview_emp_file').addClass('view_custom_img');
                            break;
                        case 'xlsx': 
                            $('#preview_emp_file').attr('src', 'assets/img/illustrations/xlsx.png').show();
                            $('#preview_emp_file').addClass('view_custom_img');
                            break;
                        case 'csv': 
                            $('#preview_emp_file').attr('src', 'assets/img/illustrations/csv.png').show();
                            $('#preview_emp_file').addClass('view_custom_img');
                            break;		
                        case 'docx': 
                            $('#preview_emp_file').attr('src', 'assets/img/illustrations/docs.png').show();
                            $('#preview_emp_file').addClass('view_custom_img');
                            break;
                            case 'doc': 
                            $('#preview_emp_file').attr('src', 'assets/img/illustrations/docs.png').show();
                            $('#preview_emp_file').addClass('view_custom_img');
                            break;
                        default:
                            $('#preview_emp_file').attr('src', 'assets/img/illustrations/No_file.png').show();
                            $('#preview_emp_file').addClass('view_custom_img');
                    }                    
                }

                //fetch file name                
                $('#para_preview_file').html(file.name).show();
            }

            reader.readAsDataURL(file);
        });


        $('#file_docs_submit').on('submit', function(e) {

                e.preventDefault(); 

                //Add the Preloader
                $('.event_trigger_loader').addClass('active');


                const name = $('#docs_name').val();
                const fileInput = $('#Emp_file_upload')[0];
                const file = fileInput.files[0];
                const hiddenId = $('#hiddenId').val(); 
                const file_upload_hidden  = $('#file_upload_hidden').val();
                const select_employees = $('#Docs_select_employees').val();
                const submit = $('#file_submit_btn').val();


                // Validate file name length
                if (name.length > 100) {
                    showModalWithParams('File name must be 100 characters or less.', 'false');
                    return;
                }

                if(file)
                {

                    // Validate file type
                    const allowedExtensions = ['pdf', 'csv', 'doc', 'docx', 'jpg', 'png', 'jpeg', 'xlsx'];
                    const fileName = file.name;
                    const fileExtension = fileName.split('.').pop().toLowerCase();
                    if (!allowedExtensions.includes(fileExtension)) {
                        showModalWithParams('Allowed file types are: pdf, csv, doc, docx, jpg, png, jpeg, xlsx.', 'false');
                        return;
                    }
                }

                if(file)
                {
                    // Validate file size (10 MB = 10485760 bytes)
                    const maxSize = 10485760;
                    if (file.size > maxSize) {
                        showModalWithParams('File size must be 10 MB or less.', 'false');
                        return;
                    }
                }

                // Create FormData object
                const formData = new FormData();
                formData.append('name', name);
                formData.append('file', file);
                formData.append('hidden_file_url', file_upload_hidden);
                formData.append('hid', hiddenId);
                formData.append('TaggedEmployees', select_employees);
                formData.append('submit', submit);

                // Send the file via AJAX
                $.ajax({
                    url: 'function.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log('Server response:', response);

                        //Remove the Preloader
                        setTimeout(function() {
                        $('.event_trigger_loader').removeClass('active');
                        }, 1000);

                        // Trim any extra whitespace from the response
                        const trimmedResponse = response.trim();

                        // Handle the response text
                        if (trimmedResponse === 'success') {
                            showModalWithParams(`File Added.`, 'true');
                           //window.location.href = 'Documents.php?page=Documents';
                        } else if (trimmedResponse === 'updated') {
                            showModalWithParams(`File Updated.`, 'true');
                           // window.location.href = 'Documents.php?page=Documents';
                        } else {
                             showModalWithParams(trimmedResponse, 'false');
                        }
                    },
                    error: function(error) {
                       //Remove the Preloader
                        setTimeout(function() {
                        $('.event_trigger_loader').removeClass('active');
                        }, 1000);

                        showModalWithParams(`An error occurred: ${error}`, 'false');
                    }
                });
            });
    });


//     function validateFileSize() {
//     const fileInput = document.getElementById('Emp_file_upload');
//     const fileError = document.getElementById('fileError');
    
//     // Reset error message
//     fileError.style.display = 'none';
//     fileError.textContent = '';

//     const file = fileInput.files[0];
    
//     if (file) {
//         const fileSize = file.size; // Size in bytes
//         const maxSize = 2 * 1024 * 1024; // 2MB in bytes

//         if (fileSize > maxSize) {
//             fileError.textContent = 'File size must be 2MB or less.';
//             fileError.style.display = 'block';
//             fileInput.value = ''; // Clear the input
//         }
//     }
// }

function validateFile() {
    const fileInput = document.getElementById('Emp_file_upload');
    const fileError = document.getElementById('fileError');
    const allowedExtensions = /(\.doc|\.docx|\.xlsx|\.xls|\.pdf|\.txt|\.jpg|\.jpeg|\.png)$/i;

    // Clear previous error messages
    fileError.style.display = 'none';
    fileError.textContent = '';

    // Get the selected file
    const file = fileInput.files[0];

    // Check if a file is selected
    if (file) {
        // Validate file format
        if (!allowedExtensions.exec(file.name)) {
            fileError.textContent = 'Invalid file format. Please upload a .doc, .docx, .xlsx, .xls, .pdf, .png, .jpg, .jpeg or .txt file.';
            fileError.style.display = 'block';
            fileInput.value = ''; // Clear the input
            return;
        }

        // Optional: Validate file size (e.g., limit to 5MB)
        const maxSizeInBytes = 2 * 1024 * 1024; // 5MB
        if (file.size > maxSizeInBytes) {
            fileError.textContent = 'File size must be less than 2 MB.';
            fileError.style.display = 'block';
            fileInput.value = ''; // Clear the input
            return;
        }
    }
}

</script>