<!-- Referance : https://getbootstrap.com/docs/4.0/components/input-group/ -->
<!-- Referance : https://getbootstrap.com/docs/4.0/components/modal/ -->
<?php include_once 'header.php';?>
<?php include_once 'db.php';?>

<?php

$err = $uname = $uemail = $upass = $cpass = "";

//Handle edit admin flow
if($_GET["eid"]) {
  $sql = "SELECT * FROM ".$adminTable." WHERE ".$admin_id." = ".$_GET["eid"];
  $result = mysqli_query($conn, $sql);
  // $row = mysqli_fetch_row($result);
  while($row = mysqli_fetch_array($result)) {
    $uname = $row[$admin_name];
    $uemail = $row[$admin_email];
    $upass = $row[$admin_pass];
    $cpass = $row[$admin_pass];
  }
}

//Update or Insert admin details
if (isset($_POST['submit'])) {
    $uname = trim($_POST['userName']);
    $uemail = trim($_POST['userEmail']);
    $upass = trim($_POST['passwd']);
    $cpass = trim($_POST['confirm']);
    
    //Validation
    if($uname == "") {
      $err = "Please Enter User Name.";
    } else if(strpos($uname, " ")) {
      $err = "Username doesn't contain space.";
    } else if(!valid_email($uemail)) {
      $err = "Please Enter Valid Email Id.";
    } else if($upass == "") {
      $err = "Please Enter Valid Password.";
    } else if(strlen($upass) < 8) {
      $err = "Password Must Contain At Least 8 Characters!"; 
    } else if($cpass == "") {
      $err = "Please Enter Confirm Password.";
    }  else if($cpass != $upass) {
      $err = "Password & Confirm Password doesn't match.";
    } else {
      // Insert new admin's data in admin table & Check admin with same username alredy exist or not
      if($_GET["eid"]) {
        $sql = "SELECT * FROM ".$adminTable." WHERE `".$admin_name."` = '".$uname."' AND ".$admin_id." != ".$_GET["eid"];
        if(mysqli_num_rows(mysqli_query($conn, $sql)) == 0) {
          $sql = "UPDATE ".$adminTable." SET ".$admin_name." = '".$uname."', ".$admin_email." = '".$uemail."', ".$admin_pass." = '".$upass."' WHERE ".$admin_id." = ".$_GET["eid"];
          if (mysqli_query($conn, $sql)) { // navigate to the home page if admin created sucessfully
            ?> <script type="text/javascript">window.location="index.php"</script> <?php
          } else {
            $err = "Something Goes Wrong. Please Try Agian.";
          }
        } else {
          $err = "Admin name alreday registered.";
        }
      } else {
        // Update admin's data in admin table
        $sql = "SELECT * FROM ".$adminTable." WHERE `".$admin_name."` = '".$uname."'";
        if(mysqli_num_rows(mysqli_query($conn, $sql)) == 0) {
          $sql = "INSERT INTO ".$adminTable." (`".$admin_name."`, `".$admin_email."`, `".$admin_pass."`) VALUES ('".$uname."','".$uemail."','".$upass."')";
          if (mysqli_query($conn, $sql)) { // navigate to the home page if admin details edited sucessfully
            ?> <script type="text/javascript">window.location="index.php"</script> <?php
          } else {
            $err = "Something Goes Wrong. Please Try Agian.";
          }
        } else {
          $err = "Admin name alreday registered.";
        }
      }
    }
}

?>

<div class="container-fluid">
    <div class="row" style="justify-content: space-around;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                  <!-- Add admin Form -->
                    <form class="form-horizontal" method="post" action="./add_admin.php<?php if($_GET["eid"]) echo "?eid=".$_GET["eid"]; ?>">
                    <div class="card-body">
                    <h3 class="card-title">Personal Info</h3>
                    <!-- User name -->
                    <div class="form-group row">
                      <label
                        for="uname"
                        class="col-sm-4 control-label col-form-label"
                        >User Name</label
                      >
                      <div class="col-sm-6">
                        <input
                          name="userName"
                          type="text"
                          class="required form-control"
                          id="userName"
                          <?php if($uname != "") { ?>
                            value = "<?php echo $uname; ?>"
                          <?php } ?>
                          placeholder="Enter User Name Here"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>
                    <!-- Email Id -->
                    <div class="form-group row">
                      <label
                        for="uemail"
                        class="col-sm-4 control-label col-form-label"
                        >Email Id</label
                      >
                      <div class="col-sm-6">
                        <input
                          name="userEmail"
                          type="email"
                          class="required form-control"
                          id="userEmail"
                          <?php if($uemail != "") { ?>
                            value = "<?php echo $uemail; ?>"
                          <?php } ?>
                          placeholder="Enter User Email Id Here"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>
                    
                    <!-- Password -->
                    <div class="form-group row">
                      <label for="passwd" class="col-sm-4 control-label col-form-label">Password</label>
                      <div class="col-sm-6">
                        <input
                          name="passwd"
                          type="password"
                          class="required form-control"
                          id="passwd"
                          <?php if($upass != "") { ?>
                            value = "<?php echo $upass; ?>"
                          <?php } ?>
                          placeholder="Enter Password Here"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>
                    
                    <!-- Confirm Password -->
                    <div class="form-group row">
                      <label
                        for="confirm"
                        class="col-sm-4 control-label col-form-label"
                        >Confirm Password</label
                      >
                      <div class="col-sm-6">
                        <input
                          name="confirm"
                          type="password"
                          class="required form-control"
                          id="confirm"
                          <?php if($cpass != "") { ?>
                            value = "<?php echo $cpass; ?>"
                          <?php } ?>
                          placeholder="Enter Confirm Password Here"
                        />
                      </div>
                    </div>
                    <div style="height:15px"> </div>
                    <!-- Error message -->
                    <?php
                        if($err != "") { 
                    ?>
                        <center>
                            <p style="color: red;"><?php echo $err; ?></p>
                        </center>
                    <?php
                        }
                    ?>
                    <div class="border-top" style="margin-bottom: -23px;">
                        <div class="card-body">
                            <center>
                              <?php if($_GET["eid"] || $_SESSION['id'] == 1) { ?>
                                <!-- Submit button (If current user's id is 1 or admin want to update the his/her details) -->
                                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                              <?php } else { ?>
                                <!-- Submit button for unauthorized user -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                  Submit
                                </button>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Admin</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        You are not authorised to add admin.
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php } ?>
                            </center>
                        </div>
                    </div>
                  </div>
                    </form>
                </div>    
            </div>
        </div>
    </div>
</div>


<?php include_once 'footer.php'; ?>