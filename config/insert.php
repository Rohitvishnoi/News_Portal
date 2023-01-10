<?php
    // $sql = "insert into emails(email) values (".$email.");"; 
    if(isset($_REQUEST['submit'])){
        // get form data
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        //setting the query to check if the entered email is already present
        $email_counts = mysqli_fetch_assoc(mysqli_query($conn,"select count(*) from emails where email='$email';")); //if $email_counts['count(*)'] > 0 then that email is already present.
        //email regex validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = 1;
        }
        else{
            $emailErr = 0;
        }
    }

?>