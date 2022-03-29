<?php

    include('php/config/db_connect.php');

    // max posts on the page at any point in time
    $numberOfPosts = 25;

    $buffer = 1000; // number of extra unused & unshown records before deletion


    //determining number of rows that aren't shown
    // then we get the number of rows that aren't displayed
    // we want to keep a backlog of some links so that size of the backlog is the buffer size.
    // we then get the difference between the notShown rows and the buffer to get the actual number of rows to remove

    $sql = 'SELECT COUNT(*) FROM twitterlinks;';
    $countResult = mysqli_query($conn,$sql);
    $rowCount = mysqli_fetch_all($countResult,MYSQLI_ASSOC);
    $rowCount = $rowCount[0]['COUNT(*)']; 
    $notShown = $rowCount - $numberOfPosts;
    $rowsToRemove = $notShown - $buffer;

    if($rowsToRemove>0){
        // $rowsToRemove = $rowsToRemove;
        $sql = "DELETE FROM twitterlinks ORDER BY id ASC limit $rowsToRemove;";
        if(!mysqli_query($conn,$sql)){
            echo mysqli_error($conn);
        }
    }

    //queries the latest $numberOfPosts posts and leaves the backlog in db not displayed

    $sql = "SELECT title,link FROM ( SELECT * FROM twitterlinks ORDER BY id DESC LIMIT $numberOfPosts )Var1 ORDER BY id ASC;";

    $result = mysqli_query($conn,$sql);

    //date variable
    $sql = 'SELECT latestDate FROM latestupdatedate WHERE id=1';

    $dateResult = mysqli_query($conn,$sql);
    $prevDate = mysqli_fetch_all($dateResult,MYSQLI_ASSOC);

    $strPrevDate = $prevDate[0]['latestDate'];
    // fetch twitter link resulting rows
    $twitterlinks = mysqli_fetch_all($result,MYSQLI_ASSOC);

    // free result from memory as not needed
    mysqli_free_result($countResult);
    mysqli_free_result($result);
    mysqli_free_result($dateResult);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gathering Positivity</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/widgets.js" charset="utf-8"></script> 
    <script src = "js/app.js"></script>
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
        include 'php/get_tweets.php';
        $allAccounts = ['PositiveNewsUK','somegoodnews','GoodNatureNews1','Agri_ut','actionhappiness'];

        // We can do this process for every user in list:
        // Get all the tweets by that user
        // Filter out and get the good tweets
        // insert those tweets into DB
        // that will then be displayed on page

        foreach($allAccounts as $screen_name){
            $tweets = getTweetsFromUser($screen_name);
            $validTimeTweets = getValidTimeTweets($tweets,$strPrevDate,$conn);
            
            $culledTweets = findGoodTweets($validTimeTweets);

            

            if(count($culledTweets)>0){
                insertTweetsInDB($culledTweets,$conn);
            }
        }

        //update sql variable to now. I.e: date('D M d G:i:s e Y')

        $nowDateStr = date('D M d G:i:s e Y');

        $sql = "UPDATE latestupdatedate SET latestDate = '$nowDateStr' WHERE id = 1";

        if(mysqli_query($conn,$sql)){
            // echo("YAY");
        }
        else{
            echo("Oh no date update date didn't work :(".mysqli_error());
        }

        //closing the connection
        mysqli_close($conn);
    ?>
    

    <?php
        //reverse output to get most recent
        $len = count($twitterlinks);
        for($i = $len-1; $i >= 0; $i--){
            $link = $twitterlinks[$i];
            ?>
            <blockquote class="twitter-tweet tw-align-center">
                <a href="<?php echo htmlspecialchars($link['link']) ?>"></a> 
            </blockquote>
    <?php 
    


    }?>
</body>
</html>