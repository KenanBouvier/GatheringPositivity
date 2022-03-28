<?php

    $conn = mysqli_connect('localhost','kenan','Gs]JHmn0tcxNiles','gatheringpositivity');

    if(!$conn){
        echo 'Connection error: ' . mysqli_connect_error();
    }
?>