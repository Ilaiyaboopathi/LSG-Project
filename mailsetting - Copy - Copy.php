<?php 
include('include/head.php');
require 'data/dbconfig.php';

$buttonText = 'Submit';
$id = $host = $port = $username = $password = $secure = $from_email = '';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM smtp_settings WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row       = $result->fetch_assoc();
        $host      = htmlspecialchars($row['host']);
        $port      = htmlspecialchars($row['port']);
        $username  = htmlspecialchars($row['username']);
        $password  = htmlspecialchars($row['password']);
        $secure    = htmlspecialchars($row['SMTPSecure']);
        $from_email= htmlspecialchars($row['from_email']);
        $buttonText = 'Update';
    }
}
?>

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <!-- Form Section -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <form id="smtp_add" class="row g-4">
              <input type="hidden" id="hiddenId" value="<?php echo $id; ?>">

              <div class="col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" name="host" id="host" value="<?php echo $host; ?>" class="form-control" required>
                  <label for="host">Host Name *</label>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="number" name="port" id="port" value="<?php echo $port; ?>" class="form-control" required>
                  <label for="port">Port (e.g., 587 / 465)*</label>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" name="username" id="username" value="<?php echo $username; ?>" class="form-control" required>
                  <label for="username">Username *</label>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="password" name="password" id="password" value="<?php echo $password; ?>" class="form-control" required>
                  <label for="password">Password *</label>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" name="secure" id="secure" value="<?php echo $secure; ?>" class="form-control" required>
                  <label for="secure">SMTPSecure (e.g., ssl / tls) *</label>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-floating form-floating-outline mb-3">
                  <input type="text" name="from_email" id="from_email" value="<?php echo $from_email; ?>" class="form-control" required>
                  <label for="from_email">From Email *</label>
                </div>
              </div>

              <div class="col-12 d-flex justify-content-end">
                <button type="reset" class="btn btn-outline-secondary me-3">Cancel</button>
                <button type="submit" id="submit"
                  value="<?php echo ($buttonText === 'Update') ? 'UpdateSMTP' : 'AddSMTP'; ?>"
                  class="btn btn-primary">
                  <?php echo $buttonText; ?>
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div><br><br>

    <!-- Grid Section -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="card-datatable">

              <table class="datatables-SMTP table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Host</th>
                    <th>Port</th>
                    <th>User name</th>
                    <th>From Email</th>
                    <th>Secure</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * FROM smtp_settings ORDER BY id DESC";
                  $result = $conn->query($sql);
                  $i = 1;
                  if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          echo "<tr>
                              <td>{$i}</td>
                              <td>".htmlspecialchars($row['host'])."</td>
                              <td>".htmlspecialchars($row['port'])."</td>
                              <td>".htmlspecialchars($row['username'])."</td>
                              <td>".htmlspecialchars($row['from_email'])."</td>
                              <td>".htmlspecialchars($row['SMTPSecure'])."</td>
                              <td>
                                <a href='mailsetting.php?id={$row['id']}' class='btn btn-sm btn-primary'>Edit</a>
                              </td>
                          </tr>";
                          $i++;
                      }
                  }
                  ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include('include/footer.php'); ?>

<!-- JS -->
<script>
$('#smtp_add').on('submit', function(e) {
  e.preventDefault();
  const formData = {
    host: $('#host').val(),
    port: $('#port').val(),
    username: $('#username').val(),
    password: $('#password').val(),
    secure: $('#secure').val(),
    from_email: $('#from_email').val(),
    hid: $('#hiddenId').val(),
    submit: $('#submit').val()
  };

  fetch('function1.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams(formData)
  })
  .then(r => r.text())
  .then(result => {
    let trimmedResult = result.trim();
    if (trimmedResult === 'Success') {
        showModalWithParams("SMTP Added", 'true');
    } else if (trimmedResult === 'updated') {
        showModalWithParams("SMTP Updated", 'true');
    } else {
        showModalWithParams("Error: " + trimmedResult, 'false');
    }
  });
});

$(document).ready(function() {
  $('.datatables-SMTP').DataTable({
    responsive: true,
    pageLength: 10
  });
});
</script>
