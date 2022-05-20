<?php

    $conn = mysqli_connect('localhost','<USERNAME>','<PASSWORD>','<DB>');

    if(!$conn){
        echo 'Connection error: ' . mysqli_connect_error();
    }
?>
