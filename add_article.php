<!-- Referance : https://getbootstrap.com/docs/4.0/components/input-group/ -->
<?php include_once 'header.php';?>
<?php include_once 'db.php';?>

<?php

//Include required PHPMailer files
require_once 'includes/PHPMailer.php';
require_once 'includes/SMTP.php';
require_once 'includes/Exception.php';
//Define name spaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// variables & image path
$err = $title = $category = $title_img = $description = "";
$path="assets/upload/article/";

// Handle edit article flow
if(!isset($_POST['submit']) && $_GET['eid']) {
  $sql = "SELECT * FROM ".$articleTable." WHERE ".$article_id." = ".$_GET["eid"];
  $result = mysqli_query($conn, $sql);

  // Fetch articale data whose id is eid
  while($row = mysqli_fetch_array($result)) {
    $title = $row[$article_title];
    $category = $row[$article_category];
    $old_title_img = $row[$article_title_img];
    $description = $row[$article_desc];
  }
}

// Submit details
if (isset($_POST['submit'])) {
  // $err = "Please select article category";
  // echo trim($_POST['category']) == "0";
  
  // data fetch
  $title = trim($_POST['title']);
  $category = trim($_POST['category']);
  $title_img = $_FILES['title_img']['name'];
  $description = $_POST['description'];
  $old_title_img = $_POST['old_title_img'];

  // Validation
  if($title == "") {
    $err = "Please enter article title";
  } else if(!$_GET['eid'] && $title_img == "") {
    $err = "Plese select title image";
  } else if($category == "0") {
    $err = "Please select article category";
  } else if($description == "") {
    $err = "Please enter article description";
  } else {
    $milliseconds = round(microtime(true) * 1000);
    $title = str_replace('"', '\"', $title);
    $description = str_replace('"', '\"', $description);
    
    // Add new article flow
    if(!$_GET['eid']) {
      // Update image name
      $title_img = $milliseconds."_".rand(10,100)."_".str_replace(' ', '_', $title_img);

      if (move_uploaded_file($_FILES['title_img']['tmp_name'], $path.$title_img))  {
        $sql = 'INSERT INTO '.$articleTable.' (`'.$article_title_img.'`,`'.$article_title.'`,`'.$article_category.'`,`'.$article_desc.'`,`'.$admin_id.'`) VALUES ("'.$title_img.'","'.$title.'","'.$category.'","'.$description.'","'.$_SESSION['id'].'")';
        if (mysqli_query($conn, $sql)) {
          //send emails
          //Fetch all email id
          $sql_ = "SELECT * FROM ".$subscriberTable;
          $result_ = mysqli_query($conn, $sql_);

          //Create instance of PHPMailer
          $mail = new PHPMailer();
          
          //Set mailer to use smtp
          $mail->isSMTP();
          
          //Define smtp host
          $mail->Host = "smtp.gmail.com";
          
          //Enable smtp authentication
          $mail->SMTPAuth = true;
          
          //Set smtp encryption type (ssl/tls)
          $mail->SMTPSecure = "tls";
          
          //Port to connect smtp
          $mail->Port = "587";
          
          //Set gmail username
          $mail->Username = $email_id;
          
          //Set gmail password
          $mail->Password = $email_pass;
          
          //Email subject
          $mail->Subject = "New article published : '".$title."'";
          
          //Set sender email
          $mail->setFrom($email_id);
          
          //Enable HTML
          $mail->isHTML(true);
          
          //Email body
          $mail->Body = "<h1>".$title."</h1></br><p>".$description."</p>";
          
          //Add recipient
          while($row = mysqli_fetch_assoc($result_)) {
            $mail->addAddress($row[$emails_email]);
          }
          
          //Finally send email
          if ( $mail->send() ) {
            $msg = "Email Sent..!";
          }else{
            $msg = "Message could not be sent.";
          }
          
          //Closing smtp connection
          $mail->smtpClose();
          
          // navigate to the home page if articale added sucessfully
          ?> <script type="text/javascript">window.location="index.php"</script> <?php
        } else {
          $err = "Something Goes Wrong. Please Try Agian.";
        }
      }else{
        $err = "Something goes wrong. Please try again!";
      }

    } else {
      // Edit artilce flow handle
      // upload new image and remove old image
      if($title_img != "") {
        $title_img = $milliseconds."_".rand(10,100)."_".str_replace(' ', '_', $title_img);
        if(move_uploaded_file($_FILES['title_img']['tmp_name'], $path.$title_img) && $old_title_img) {
          unlink($path.$old_title_img);
        }
      } else {
        $title_img = $old_title_img;
      }

      // update articale data
      $sql = 'UPDATE '.$articleTable.' SET `'.$article_title.'` = "'.$title.'", `'.$article_title_img.'` = "'.$title_img.'", `'.$article_category.'` = "'.$category.'", `'.$article_desc.'` = "'.$description.'" WHERE `'.$article_id.'` = "'.$_GET["eid"].'"';
      if (mysqli_query($conn, $sql)) { // navigate to the home page if articale edited sucessfully
        ?> <script type="text/javascript">window.location="index.php"</script> <?php
      } else {
        $err = "Something Goes Wrong. Please Try Agian.";
      }
    }
  }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                  <!-- Form -->
                    <form class="form-horizontal" method="post" action="./add_article.php<?php if($_GET["eid"]) echo "?eid=".$_GET["eid"]; ?>" enctype="multipart/form-data">
                    <div class="card-body">
                    <h3 class="card-title">Add News Article</h3>
                    <!-- Article Title -->
                    <div class="form-group row">
                      <label
                        for="title"
                        class="col-sm-4 control-label col-form-label"
                        >Article Title</label
                      >
                      <div class="col-sm-8">
                        <input
                          name="title"
                          type="text"
                          class="required form-control"
                          id="title"
                          <?php if($title != "") { ?>
                            value = "<?php echo ($title); ?>"
                          <?php } ?>
                          placeholder="Enter Article Title Here"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>
                    
                    <!-- Article Image -->
                    <div class="form-group row">
                        <label
                            for="title_img"
                            class="col-sm-4 control-label col-form-label"
                            >Article Image</label
                        >
                        <div class="col-sm-8">
                            <input type="file" class="custom-file-input" id="validatedCustomFile" name="title_img" accept="image/*">
                            <?php if($old_title_img) { ?> <img src ="<?php echo $path.$old_title_img; ?>" height="60px" width="60px"> <?php } ?>
                            <input type='hidden' name='old_title_img' value="<?php echo $old_title_img; ?>">
                        </div>
                    </div>

                    <div style="height:15px"> </div>
                    
                    <!-- Article Category -->
                    <div class="form-group row">
                      <label
                        for="category"
                        class="col-sm-4 control-label col-form-label"
                        >Article Category</label
                      >
                      <div class="col-sm-8">
                        <select class="form-select" name="category" aria-label="Default select example">
                            <option selected value="0">Please Select Article Category</option>
                            <option value="India" <?php if($category == "India") echo "selected"; ?> >India</option>
                            <option value="World" <?php if($category == "World") echo "selected"; ?> >World</option>
                            <option value="Tech" <?php if($category == "Tech") echo "selected"; ?> >Tech</option>
                            <option value="Sports" <?php if($category == "Sports") echo "selected"; ?> >Sports</option>
                            <option value="Entertainment" <?php if($category == "Entertainment") echo "selected"; ?> >Entertainment</option>
                            <option value="Business" <?php if($category == "Business") echo "selected"; ?> >Business</option>
                            <option value="Health" <?php if($category == "Health") echo "selected"; ?> >Health</option>
                            <option value="Life & Style" <?php if($category == "Life & Style") echo "selected"; ?> >Life & Style</option>
                        </select>
                      </div>
                    </div>

                    <div style="height:15px"> </div>

                    <!-- Article Description -->
                    <div class="form-group row">
                      <label
                        for="description"
                        class="col-sm-4 control-label col-form-label"
                        >Article Description</label
                      >
                      <div class="col-sm-8">
                        <textarea 
                            class="required form-control" 
                            id="description" 
                            name="description"
                            placeholder="Enter Article Description Here (In preformatted text i.e. use <br> for new-line)"
                            rows="5"><?php if($description != "") echo "$description"; ?></textarea>
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
                    <!-- Submit button -->
                    <div class="border-top" style="margin-bottom: -23px;">
                        <div class="card-body">
                            <center><input type="submit" name="submit" value="Submit" class="btn btn-primary"></center>
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