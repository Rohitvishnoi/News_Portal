<!-- Referance : https://codepen.io/eminqasimov/pen/zXJVzJ -->
<!-- Referance : https://www.w3schools.com/php/php_sessions.asp -->
<?php include_once 'db.php';?>

<?php
  $pass = $name = $err = "";

  // Verify user's credentials
  if (isset($_POST['submit'])) {
    // Fetch user name & password from the form
    $pass = $_POST['upass'];
    $name = $_POST['uname'];

    // check user is valid or not
    $sql = "SELECT * FROM ".$adminTable." WHERE ".$admin_name." = '".$name."' AND ".$admin_status." = 1 AND ".$admin_pass." = '".$pass."'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) != 0) {
      $err = "";
      session_start();

      // save user details in session
      $_SESSION['name']=$name;
      $singleRow = mysqli_fetch_row($result);
      $_SESSION['id'] = $singleRow[0];
      ?> <script type="text/javascript">window.location="index.php"</script> <?php // if user is valid the navigate to the home page
    } else {
      $err = "Invalid Username & Password.";
    }
    
  }
?>
<html>
    <title>Admin | Login</title>
<head>
<link rel='stylesheet' type='text/css' media='screen' href='./css/login.css'>
</head>
<body>
<div id="container">
		<div class="login">
			<div class="content">
					<h1>Log In</h1>
        <!-- Login form -->
				<form action="login.php" method="post">
					<input type="text" name="uname" placeholder="Email Address">
					<input type="password" name="upass" placeholder="Enter Password">
					<!-- <span class="clearfix"></span> -->
          <?php if($err != "") { ?> <center><p style="color:red"><?php echo $err; ?></p></center> <?php } ?>
					<button type="submit" name="submit">Log In</button>
				</form>
				<span class="copy">© XYZ Portal 2021</span>
			</div>
		</div>
		<div class="page front">
			<div class="content">
				 <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
					<h1>Hello, Admin!</h1>
					<p>Let's Start Something New!</p>
			</div>
		</div>
</div>


<script>
    const registerButton = document.getElementById('register')
  const loginButton = document.getElementById('login')
  const container = document.getElementById('container')

  registerButton.onclick = function(){
    container.className = 'active'
  }
  loginButton.onclick = function(){
      container.className = 'close'
  }
</script>
</body>
</html>