<?php
    // $sql = "insert into emails(email) values (".$email.");"; 
    if(isset($_REQUEST['submit'])){
        // get form data
        $commenter_name = mysqli_real_escape_string($conn,$_POST['commenter_name']);
        $comment = mysqli_real_escape_string($conn,$_POST['comment']);

        //setting the query to insert data
        $query = "insert into comment(comment_auther,comment_desc,article_id,comment_status) values('$commenter_name','$comment','$id',0)";
        
        mysqli_query($conn,$query);
    }
?>