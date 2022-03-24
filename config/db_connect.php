<?php

    $conn = mysqli_connect('localhost','kenan','Mysql528','gatheringpositivity');

    if(!$conn){
        echo 'Connection error: ' . mysqli_connect_error();
    }
?>