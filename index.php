<?php

    include('config/db_connect.php');

    //write query for all goals
    $sql = 'SELECT title,link FROM twitterlinks';

    // make query and get restult
    $result = mysqli_query($conn,$sql);

    // fetch resulting rows
    $twitterlinks = mysqli_fetch_all($result,MYSQLI_ASSOC);

    // free result from memory as not needed
    mysqli_free_result($result);

    //closing the connection
    mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gathering Positivity</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/5a250bff97.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> 
</head>

<body>

<script>


$(document).ready(function(){
    var requestURL = "https://api.twitter.com/2/users/2244994945/tweets";

    //AJAX request to get contents and set to variable
    console.log("Before request");
    var xhReq = new XMLHttpRequest();
    xhReq.open("GET",requestURL,true);
    console.log(requestURL);
    console.log("GET REQUest");
    xhReq.send(null);
    console.log("SENT NULL");
    var json = xhReq.responseText;
    console.log(json);
    console.log("RECEIVED RESPONSE");
    var obj = JSON.parse(json);
    console.log(obj);
    var htmlencoded = obj['html'];

    var r = /\\u([\d\w]{4})/gi;
    htmlencoded = htmlencoded.replace(r, function (match, grp) {
        return String.fromCharCode(parseInt(grp, 16)); } );
    var finalEmbed = unescape(htmlencoded);

    $(finalEmbed).appendTo('body')

    console.log("HEKKI");
});


</script>

    </div>
    <h1 class = "title" >GATHERING POSITIVITY</h1>

    <ul>
        <li><a href="#">People<a/></li>
        <li><a href="#">Technology</a></li>
        <li><a href="#">Nature</a></li>
    </ul>
    <br>
    <hr> 
     
    <?php
        foreach($twitterlinks as $link){?>

            <!-- <h1 class ="topic"><?php echo htmlspecialchars($link['title']) ?></h1> -->
            <!-- <blockquote class="twitter-tweet tw-align-center">
                <a href="<?php echo htmlspecialchars($link['link']) ?>"></a> 
            </blockquote> -->

    <?php }?>

</body>

</html>