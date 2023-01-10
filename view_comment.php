<!-- Referance : https://mdbootstrap.com/docs/b4/jquery/tables/pagination/ -->
<?php include_once 'header.php';?>
<?php include_once 'db.php'; ?>

<?php

  // activate comment whose id is aid
  if($_GET["aid"]) {
    $sql = "UPDATE ".$commentTable." SET ".$comment_status." = 1 WHERE ".$comment_id." = ".$_GET["aid"];
    mysqli_query($conn, $sql);
  }

  // de-activate comment whose id is did
  if($_GET["did"]) {
    $sql = "UPDATE ".$commentTable." SET ".$comment_status." = 0 WHERE ".$comment_id." = ".$_GET["did"];
    mysqli_query($conn, $sql);
  }
  
  // delete comment whose id is ddid
  if($_GET["ddid"]) {
    $sql = "DELETE FROM ".$commentTable." WHERE ".$comment_id." = ".$_GET["ddid"];
    mysqli_query($conn, $sql);
  }

  // Fetch comment
  $sql = "SELECT * FROM ".$commentTable;
  // if current user is not high privileged admin
  if($_SESSION['id'] != "1")
    $sql = $sql." WHERE `".$article_id."` IN ( SELECT `".$article_id."` FROM  ".$articleTable." WHERE `".$admin_id."` = '".$_SESSION['id']."')";
  $result = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <!-- <div class="card">
        <div class="card-body"> -->

          <!-- View Comment Table -->
          <h3 class="card-title">View Comment</h3>
          <table id="dataTable" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <!-- Table header -->
            <thead>
              <tr>
                <th class="col th-sm">ID</th>
                <th class="col th-sm">Commenter</th>
                <th class="col th-sm">Comment</th>
                <th class="col th-sm">Article ID</th>
                <?php if($_SESSION['id'] == "1") { ?>
                    <th class="col th-sm">Auther ID</th>
                <?php } ?>
                <th class="col th-sm">Delete Comment</th>
                <th class="col th-sm">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                  <!-- comment id -->
                  <td><?php echo $row[$comment_id]; ?></td>
                  <!-- commenter -->
                  <td><?php echo $row[$comment_auther]; ?></td>
                  <!-- comment -->
                  <td><?php echo  $row[$comment_desc]; ?></td>
                  <!-- article id -->
                  <td><?php echo $row[$article_id]; ?></td>
                  <!-- article author id -->
                  <?php if($_SESSION['id'] == "1") {
                      $sql_ = "SELECT `".$admin_id."` FROM ".$articleTable." WHERE `".$article_id."` = '".$row[$article_id]."'";
                      $tmp=mysqli_query($conn, $sql_);
                      $arr=mysqli_fetch_assoc($tmp);
                  ?>
                    <td><?php echo $arr[$admin_id]; ?></td>
                  <?php } ?>
                  <td> 
                    <!-- Delete Comment -->
                    <a href="./view_comment.php?ddid=<?php echo $row[$comment_id]; ?>">
                        <button type="button" class="btn btn-danger text-white"><i class="fa fa-trash"></i> Delete </button>
                    </a>
                  </td>
                  <td>
                    <!-- Status of comment(active or deactive) -->
                    <?php if($row[$comment_status] != 1) { ?>
                      <a href="./view_comment.php?aid=<?php echo $row[$comment_id]; ?>">
                        <button type="button" class="btn btn-primary" > Active </button>
                      </a>
                    <?php } else { ?>
                      <a href="./view_comment.php?did=<?php echo $row[$comment_id]; ?>">
                        <button type="button" class="btn btn-danger" > De-Active </button>
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
                <th class="col th-sm">Commenter</th>
                <th class="col th-sm">Comment</th>
                <th class="col th-sm">Article ID</th>
                <?php if($_SESSION['id'] == "1") { ?>
                    <th class="col th-sm">Auther ID</th>
                <?php } ?>
                <th class="col th-sm">Delete Comment</th>
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
