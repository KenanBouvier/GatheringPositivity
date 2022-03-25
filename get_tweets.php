<?php
// TODO: to combat the repeated inserts into db, keep track of latest refresh or similar and only check tweets from that time and forwards.

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
		$getfield = '?count=1&screen_name=';

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


	function correctTypeOfTweet($txt){
		

		return true;
	}

	function textAnalysis($txt){
		$txt = strtolower($txt);

		if(!correctTypeOfTweet($txt)){
			return false;
		}
		//NLP algo here!

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
            $urlInsert = $urlInsert . $idVal; 

            $titlePlaceholder = "TBD";

            $sql = "INSERT INTO twitterlinks(title,link) VALUES('$titlePlaceholder','$urlInsert')";
            if(!mysqli_query($conn,$sql)){
                echo("<script>alert('something wrong when inserting into db')</script>");
            }
        }
	}
?>
