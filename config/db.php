<?php
    //Create Connection
    $conn = mysqli_connect('localhost','root','Animesh@98','cs699proj');
    //Check Connection
    if(mysqli_connect_errno()){
        //Connection failed
        echo 'Failed to connect to MySQL '.mysqli_connect_errno();
    }
?>