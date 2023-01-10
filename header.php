<!-- Referance : https://freefrontend.com/bootstrap-sidebars/ -->
<!-- Referance : https://getbootstrap.com/ -->
<?php
  session_start();
  if(!isset($_SESSION['id'])) {
    ?> <script type="text/javascript">window.location="login.php"</script> <?php
  }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Admin</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- CSS -->
    <link rel='stylesheet' type='text/css' media='screen' href='./css/sidebar.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
    
    <!-- JavaScript -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src='js/sidebar.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <!-- <script src="js/jquery.dataTables.min.js" type="text/javascript"></script> -->
</head>
<body id="body-pd">
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >

    <!-- Header -->
    <header class="header navbar" id="header"  style="margin-bottom:20px; padding-left: calc(var(--nav-width) + -50px);">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"> News Portal | Admin </i></div>
        <div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpg" alt=""> </div>
    </header>

    <!-- Navbar -->
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div> <a href="index.php" class="nav_logo" style="text-decoration: none;"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">News Portal</span> </a>
                <div class="nav_list">
                    <!-- Sidebar option -->
                    <a href="index.php" style="text-decoration: none;" class="nav_link <?php if(basename($_SERVER['PHP_SELF']) == "index.php") echo "active"; ?>"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Dashboard</span> </a> 
                    <a href="add_admin.php" style="text-decoration: none;" class="nav_link <?php if(basename($_SERVER['PHP_SELF']) == "add_admin.php") echo "active"; ?>"> <i style="font-size:25px;" class='bx bx-user-plus nav_icon'></i> <span class="nav_name"> Add Admin</span> </a> 
                    <a href="view_admin.php" style="text-decoration: none;" class="nav_link <?php if(basename($_SERVER['PHP_SELF']) == "view_admin.php") echo "active"; ?>"> <i class='bx bx-user nav_icon'></i> <span class="nav_name"> View Admin</span> </a> 
                    <a href="add_article.php" style="text-decoration: none;" class="nav_link <?php if(basename($_SERVER['PHP_SELF']) == "add_article.php") echo "active"; ?>"> <i class='bx bx-book-add nav_icon'></i> <span class="nav_name"> Add Post</span> </a> 
                    <a href="view_article.php" style="text-decoration: none;" class="nav_link <?php if(basename($_SERVER['PHP_SELF']) == "view_article.php") echo "active"; ?>"> <i class='bx bx-book-open nav_icon'></i> <span class="nav_name"> View Post</span> </a> 
                    <a href="view_comment.php" style="text-decoration: none;" class="nav_link <?php if(basename($_SERVER['PHP_SELF']) == "view_comment.php") echo "active"; ?>"> <i class='bx bx-comment-detail nav_icon'></i> <span class="nav_name"> View Comment</span> </a> 
                </div>
            </div> <a href="logout.php" class="nav_link" style="text-decoration: none;"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">SignOut</span> </a>
        </nav>
    </div>