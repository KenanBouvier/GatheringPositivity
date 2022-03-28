<?php

	require_once 'vadersentiment.php';

	function getTweetsFromUser($screenName){
		// include config and twitter api wrappe
		require_once( 'config/keys.php' );
		require_once( 'TwitterAPIExchange.php' );

		// settings for twitter api connection
		$settings = array(
			'oauth_access_token' => TWITTER_ACCESS_TOKEN, 
			'oauth_access_token_secret' => TWITTER_ACCESS_TOKEN_SECRET, 
			'consumer_key' => TWITTER_CONSUMER_KEY, 
			'consumer_secret' => TWITTER_CONSUMER_SECRET
		);
		
		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

		$requestMethod = 'GET';


		// twitter api endpoint data
		$getfield = '?count=10&screen_name=';

		$getfield = $getfield.$screenName;

		// make api call to twiiter
		$twitter = new TwitterAPIExchange( $settings );
		$twitter->setGetfield( $getfield );
		$twitter->buildOauth( $url, $requestMethod );
		$response = $twitter->performRequest( true, array( CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0 ) );
		$tweets = json_decode( $response, true );
	    // now we have this php array tweets

		return $tweets;
	}

	function getValidTimeTweets($tweets,$prevDateStr,$conn){
		$validTweets = [];

		// get sql variable representing latest time done request
		// $prevDateStr = 'Wed Mar 23 14:40:27 +0000 2022'; 

		$recentDate = date_create_from_format('D M d G:i:s e Y',$prevDateStr);

		foreach($tweets as $tweet){

			if(isset($tweet['retweeted_status'],$tweet)){
				$dateStr = $tweet['retweeted_status']['created_at'];
			}
			else{
				$dateStr = $tweet['created_at'];
			}

			$dateCreation = date_create_from_format('D M d G:i:s e Y',$dateStr);

			if($dateCreation > $recentDate){
				array_push($validTweets,$tweet);
			}
			else{

			}

		}
		//update sql variable to now. I.e: date('D M d G:i:s e Y')

		$nowDateStr = "date('D M d G:i:s e Y')";

		$sql = "UPDATE latestupdatedate SET latestDate = $nowDateStr WHERE id = 1";

		if(mysqli_query($conn,$sql)){
			// echo("YAY");
		}
		else{
			echo("Oh no date update date didn't work :(".mysqli_error());
		}

		return $validTweets;
	}


	function correctTypeOfTweet($txt){
		return true;
		//optimise this as it is important to control allowed

		$lowerBound = 1;
		$goodWords = ['startup','initiative','helping','saving','launched','launch','build','project','program','aim'];

		$words = explode(' ',$txt);
		$counter = 0;

		foreach($words as $word){
			if(in_array($word,$goodWords)){
				$counter += 1;
			}
		}
		if($counter>$lowerBound){
			return true;
		}

		return true;
	}


	function sentimentAnalysis($txt){
		return true;
		$sentimenter = new SentimentIntensityAnalyzer();
		$res = $sentimenter->getSentiment($txt);
		// print_r($res);
		if($res['compound']>0.75){
			echo("PASSED");
			return true;
		}
		return false;
	}

	function textAnalysis($txt){
		$txt = preg_replace("/[^A-Za-z ]/", '', $txt);

		if(!correctTypeOfTweet($txt)){
			return false;
		}
		if(!sentimentAnalysis($txt)){
			return false;
		}

		return true;
	}

	function findGoodTweets($tweets){
		$culled_tweets = [];

		foreach ( $tweets as $tweet ){

            $txtVal = $tweet['text'];

            if(textAnalysis($txtVal)){
            	array_push($culled_tweets,$tweet);
            }

        }
        return $culled_tweets;
	}

	function insertTweetsInDB($finalTweets,$conn){
		foreach ( $finalTweets as $tweet ){

            $idVal = $tweet['id_str'];
            $screenName = $tweet['user']['screen_name'];

            //creating url
            $urlInsert = "https://twitter.com/";
            $urlInsert = $urlInsert . $screenName;
            $urlInsert = $urlInsert . "/status/";
            $urlInsert = '$urlInsert . $idVal'; 

            $titlePlaceholder = '"TBD"';

            $sql = "INSERT INTO twitterlinks(title,link) VALUES($titlePlaceholder,$urlInsert)";
            if(!mysqli_query($conn,$sql)){
                echo("<script>console.log('something wrong when inserting into db')</script>");
            }
        }
	}
?>
