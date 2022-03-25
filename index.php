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

    </div>
    <h1 class = "title" >GATHERING POSITIVITY</h1>

    <!-- <ul>
        <li><a href="#">People<a/></li>
        <li><a href="#">Technology</a></li>
        <li><a href="#">Nature</a></li>
    </ul> -->
    <br>
    <hr> 
    <?php
        include 'get_tweets.php';
        $allAccounts = ['thetimes','RedPandaEveryHr','huskersbot'];

        // We can do this process for every user in list:
        // Get all the tweets by that user
        // Filter out and get the good tweets
        // insert those tweets into DB
        // that will then be displayed on page

        foreach($allAccounts as $screen_name){
            $tweets = getTweetsFromUser($screen_name);

            $culledTweets = findGoodTweets($tweets);
            if(count($culledTweets)>0){
                insertTweetsInDB($culledTweets,$conn);
            }
        }

        //closing the connection
        mysqli_close($conn);
    ?>
    

    <?php
        foreach($twitterlinks as $link){?>

            <!-- <h1 class ="topic"><?php echo htmlspecialchars($link['title']) ?></h1> -->
            <blockquote class="twitter-tweet tw-align-center">
                <a href="<?php echo htmlspecialchars($link['link']) ?>"></a> 
            </blockquote>

    <?php }?>

</body>

</html>