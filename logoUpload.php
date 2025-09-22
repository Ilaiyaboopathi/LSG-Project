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

// // if (isset($_GET['id'])) {
// //     $id = intval($_GET['id']); // Sanitize and validate the ID
    $sql = "SELECT * FROM logo WHERE id= 1 ";
    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      //$name_of_file = htmlspecialchars($row['name']);
      $file_url = htmlspecialchars($row['file_url']);
      $Title = htmlspecialchars($row['Title']);
      $SubTitle = htmlspecialchars($row['SubTitle']);
     // $tagemployee = $row['tagged_emp'];
      $InfoFIle = pathinfo($file_url);
      $editFileName = $InfoFIle['basename'];
      $buttonText = 'Update'; // Set button text to 'Update'

    
    }

    // $fileExtension = pathinfo($file_url, PATHINFO_EXTENSION);
    // if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) 
    // {
    //     $file_url = htmlspecialchars($row['file_url']);
    // }
    
    
//   }
  
  // --------- Fetch Edit Details Start --------------



// ---------- Create JSON File Content Start -------------


$sql = "SELECT * FROM logo";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        if($JWT_userRole == "admin")
        {
            // Add each row to the data array
            $data[] = array(

                'id' => $row['id'],
              
                'file_url' => $row['file_url'],
                
                'Title' => $row['Title'],
                
                'SubTitle' => $row['SubTitle'],
               
                'enable' => $row['isEnable']
            );
        }
        else
        {
            $data[] = array(

                'id' => $row['id'],
               
                'file_url' => $row['file_url'],
                
                'Title' => $row['Title'],
                
                'SubTitle' => $row['SubTitle'],
                
                'enable' => $row['isEnable']
            );
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
                        <?php if ( $SettingAddLogo === 'Enable') : ?>
                        <form id="logo_submit" class="row g-5">
                            <!-- Reminder Form -->
                            <div class="col-12">
                                <h6>Logo</h6>
                                <hr class="mt-0" />
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="hidden" id="hiddenId"
                                        value="<?php echo empty($id) ? '' : htmlspecialchars($id); ?>">
                                    <div class="col-12 mb-4">
                                        <label for="formFile" class="form-label">Logo Image (only jpeg,jpg,png & webp
                                            are Allowed)(Size Must Be : 1351*421)</label>
                                        <input type="hidden" id="file_upload_hidden"
                                            value="<?php echo empty($file_url) ? '' : htmlspecialchars($file_url); ?>">
                                        <input class="form-control" type="file" id="logoUpload"
                                            onchange="validateFile()">
                                        <div id="fileError" class="text-danger" style="display:none;"></div>
                                    </div>
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="text" id="title" class="form-control"
                                            value="<?php echo empty($Title) ? '' : htmlspecialchars($Title); ?>"
                                            placeholder="Title" required />
                                        <label for="title">Company Name</label>
                                    </div>
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="text" id="subTitle" class="form-control"
                                            value="<?php echo empty($SubTitle) ? '' : htmlspecialchars($SubTitle); ?>"
                                            placeholder="Sub Title" required />
                                        <label for="subTitle">Sub Title</label>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-12 d-flex justify-content-end mt-8">
                                        <button type="reset"
                                            class="btn btn-outline-secondary cancel_click_btn me-4 waves-effect">Cancel</button>
                                        <button type="submit" id="file_submit_btn" value="UpdateLogo"
                                            class="btn btn-primary">
                                            <?php echo htmlspecialchars($buttonText); ?>
                                        </button>
                                    </div>

                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="col-md-12 mb-4">
                                        <div class="form-floating form-floating-outline pre_view_section">
                                            <img id="preview_emp_file" src="#" alt="Image Preview"
                                                style="display:none; max-width: 300px; max-height: 300px;" />
                                            <p style="display:none;" id="para_preview_file">filename.png</p>
                                        </div>
                                        <!-- ---No File Choose--- -->
                                        <div class="form-floating form-floating-outline No_pre_view_section">
                                            <img class="No_preview_file"
                                                src="<?php echo empty($file_url) ? 'assets\img\illustrations\No_file.png' : htmlspecialchars($file_url); ?>"
                                                alt="Image Preview" />
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



        <?php if ( $SettingLogoView === 'Enable') : ?>
        <div class="row" style="margin-top: 50px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-datatable table-responsive">
                            <table class="data_tables_logo_upload table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>S.No</th>

                                        <th>Preview</th>
                                        <th>Title</th>
                                        <th>Sub Title</th>
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

<script src="assets/js/logo.js"></script>


<script>
//  <!------ Cancel Click Event   -------->
$(document).on('click', '.cancel_click_btn', function(e) {
    window.location.href = 'Documents.php?page=Documents';
});
//  <!------ Cancel Click Event   -------->

$(document).ready(function() {



    $('#logo_submit').on('submit', function(e) {

        e.preventDefault();

        //Add the Preloader
        $('.event_trigger_loader').addClass('active');



        const fileInput = $('#logoUpload')[0];
        const file = fileInput.files[0];
        const hiddenId = $('#hiddenId').val();
        const file_upload_hidden = $('#file_upload_hidden').val();
        const title = $('#title').val();
        const subTitle = $('#subTitle').val();
        const submit = $('#file_submit_btn').val();

        if (!file || !title || !subTitle) {
            $('#SameNameAlert').show();
            $('#Same_name_alert_para').html('Please fill all mandatory fields.');

            // Hide after 3 seconds
            setTimeout(function() {
                $('#SameNameAlert').hide();
            }, 4000);

            // Remove the preloader
            $('.event_trigger_loader').removeClass('active');
            return; // Stop further execution
        }

        // Create FormData object
        const formData = new FormData();
        formData.append('file', file);
        formData.append('hidden_file_url', file_upload_hidden);
        formData.append('hid', hiddenId);
        formData.append('title', title);
        formData.append('subTitle', subTitle);

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
                    showModalWithParams(`Logo Added.`, 'true');
                    //window.location.href = 'Documents.php?page=Documents';
                } else if (trimmedResponse === 'updated') {
                    showModalWithParams(`Logo Updated.`, 'true');
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

function validateFile() {
    const fileInput = $('#logoUpload')[0];
    const fileError = $('#fileError');
    const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.webp)$/i;

    // Clear previous error messages
    fileError.hide();
    fileError.text('');

    // Get the selected file
    const file = fileInput.files[0];

    // Check if a file is selected
    if (file) {
        // Validate file format
        if (!allowedExtensions.exec(file.name)) {
            fileError.text('Invalid file format. Please upload a .jpg, .jpeg, .webp, or .png file.');
            fileError.show();
            fileInput.value = ''; // Clear the input
            return;
        }

        const maxSizeInBytes = 2 * 1024 * 1024; // 2MB
        if (file.size > maxSizeInBytes) {
            fileError.text('File size must be less than 2MB.');
            fileError.show();
            fileInput.value = ''; // Clear the input
            return;
        }

        // Validate image resolution (exact resolution of 1351x421)
        const img = new Image();
        const reader = new FileReader();

        reader.onload = function(event) {
            img.src = event.target.result;
            img.onload = function() {
                // Check if the image resolution is exactly 1351x421
                if (img.width !== 1351 || img.height !== 421) {
                    fileError.text('Image resolution must be exactly 1351x421 pixels.');
                    fileError.show();
                    fileInput.value = ''; // Clear the input
                }
            };
            img.onerror = function() {
                fileError.text('Failed to load the image. Please try another file.');
                fileError.show();
                fileInput.value = ''; // Clear the input
            };
        };

        reader.readAsDataURL(file);
    }
}

</script>