<?php
    // Database credentials
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "Animesh@98";
    $db = "cs699proj";

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // echo "Connected succesfully.";
    extract($_POST);
    ob_start(); 

    //Admin table & columns name
    $adminTable = "admins";
    $admin_id = "admin_id";
    $admin_name = "admin_name";
    $admin_email = "admin_email";
    $admin_pass = "admin_password";
    $admin_status = "admin_status";

    //Article table & columns name
    $articleTable = "article";
    $article_id = "article_id";
    $article_title_img = "article_title_img";
    $article_title = "article_title";
    $article_category = "article_category";
    $article_desc = "article_desc";
    $article_status = "article_status";
    $article_visit = "article_visit";
    $article_like = "article_like";
    $article_dislike = "article_dislike";

    //Comment table & columns name
    $commentTable = "comment";
    $comment_id = "comment_id";
    $comment_auther = "comment_auther";
    $comment_desc = "comment_desc";
    $comment_status = "comment_status";

    //Emails table & columns name
    $subscriberTable = "emails";
    $emails_email = "email";

    //Email credentials
    $email_id = "cs699project2021@gmail.com";
    $email_pass = "Cs699@project2021";

    //Email validation function
    function valid_email($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }
?>
