<!-- Referance : https://mdbootstrap.com/docs/b4/jquery/tables/pagination/ -->
<?php include_once 'header.php';?>
<?php include_once 'db.php'; ?>

<?php

  $i = 0;

  // Activate the admin whose id is aid.
  if($_GET["aid"]) {
    $sql = "UPDATE ".$adminTable." SET ".$admin_status." = 1 WHERE ".$admin_id." = ".$_GET["aid"];
    mysqli_query($conn, $sql);
  }

  // De-Activate the admin whose id is did.
  if($_GET["did"]) {
    $sql = "UPDATE ".$adminTable." SET ".$admin_status." = 0 WHERE ".$admin_id." = ".$_GET["did"];
    mysqli_query($conn, $sql);
  }

  // Delete the admin whose id is ddid.
  if($_GET["ddid"]) {
    $sql = "DELETE FROM ".$adminTable." WHERE ".$admin_id." = ".$_GET["ddid"];
    mysqli_query($conn, $sql);
  }
  $sql = "SELECT * FROM ".$adminTable;
  $result = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <!-- <div class="card">
        <div class="card-body"> -->

          <!-- Admin Table -->
          <h3 class="card-title">View Admin</h3>
          <table id="dataTable" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <!-- Table header -->
            <thead>
              <tr>
                <th class="col th-sm">ID</th>
                <th class="col th-sm">User Name</th>
                <th class="col th-sm">Email</th>
                <th class="col th-sm">Password</th>
                <th class="col th-sm">Edit Details</th>
                <?php if($_SESSION['id'] == "1") { ?>
                  <th class="col th-sm">Delete User</th>
                <?php } ?>
                <th class="col th-sm">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = mysqli_fetch_assoc($result)) { $i = $i + 1; ?>
                <tr>
                  <!-- admin id -->
                  <td><?php echo $row[$admin_id]; ?></td>
                  <!-- admin name -->
                  <td><?php echo $row[$admin_name]; ?></td>
                  <!-- admin email -->
                  <td><?php echo $row[$admin_email]; ?></td>
                  <td><?php if($_SESSION['id'] == 1) echo $row[$admin_pass]; else echo "*******"?></td>
                  <td>
                    <!-- <a href="./add_admin.php?eid=<?php //echo $row[$admin_id]; ?>"> -->
                    <!-- Edit Admin -->
                    <a <?php if(!($_SESSION['id'] != $row[$admin_id] && $_SESSION['id'] != 1)) echo "href=./add_admin.php?eid=".$row[$admin_id]; ?>>
                      <button type="button" class="btn btn-primary" <?php if($_SESSION['id'] != $row[$admin_id] && $_SESSION['id'] != 1) echo "disabled"; ?> ><i class="fa fa-edit"></i> Edit </button>
                    </a>
                  </td>
                  <?php if($_SESSION['id'] == "1") { ?>
                    <td> 
                      <!-- <a href="./view_admin.php?ddid=<?php echo $row[$admin_id]; ?>"> -->
                      <!-- Delete Admin -->
                      <a <?php if($row[$admin_id] != "1") echo "href=./view_admin.php?ddid=".$row[$admin_id]; ?>>
                        <!-- <button type="button" class="btn btn-danger text-white" <?php if($_SESSION['id'] != "1" || $row[$admin_id] == "1") echo "disabled"; ?> ><i class="fa fa-trash"></i> Delete</button> -->
                        <button type="button" class="btn btn-danger text-white" <?php if($row[$admin_id] == "1") echo "disabled"; ?> ><i class="fa fa-trash"></i> Delete</button>
                      </a>
                    </td>
                  <?php } ?>
                  <td>
                    <!-- Active/Deactive Admin -->
                    <?php if($row[$admin_status] != 1) { ?>
                      <!-- <a href="./view_admin.php?aid=<?php //echo $row[$admin_id]; ?>"> -->
                      <a <?php if(!($_SESSION['id'] != "1" || $row[$admin_id] == "1")) echo "href=./view_admin.php?aid=".$row[$admin_id]; ?>>
                        <button type="button" class="btn btn-primary" <?php if($_SESSION['id'] != "1" || $row[$admin_id] == "1") echo "disabled"; ?> > Active </button>
                      </a>
                    <?php } else { ?>
                      <!-- <a href="./view_admin.php?did=<?php //echo $row[$admin_id]; ?>"> -->
                      <a <?php if(!($_SESSION['id'] != "1" || $row[$admin_id] == "1")) echo "href=./view_admin.php?did=".$row[$admin_id]; ?>>
                        <button type="button" class="btn btn-danger" <?php if($_SESSION['id'] != "1" || $row[$admin_id] == "1") echo "disabled"; ?> > De-Active </button>
                      </a>
                    <?php } ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
            <!-- Table footer -->
            <tfoot>
              <tr>
                <th class="col th-sm">ID</th>
                <th class="col th-sm">User Name</th>
                <th class="col th-sm">Email</th>
                <th class="col th-sm">Password</th>
                <th class="col th-sm">Edit Details</th>
                <?php if($_SESSION['id'] == "1") { ?>
                  <th class="col th-sm">Delete User</th>
                <?php } ?>
                <th class="col th-sm">Status</th>
              </tr>
            </tfoot>
          </table>
        <!-- </div>
      </div> -->
    </div>
  </div>
</div>

<?php include_once 'footer.php'; ?>