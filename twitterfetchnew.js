var Twitter = require('twitter');
require('dotenv/config')

const apikey = process.env.apikey;
const apiSecretKey = process.env.apikeysecret;
const accesstoken = process.env.accesstoken;
const accessTokenSecret = process.env.accesstokensecret;

var client = new Twitter({
  consumer_key: apikey,
  consumer_secret: apiSecretKey,
  access_token_key: accesstoken,
  access_token_secret: accessTokenSecret
});

const daysBack = 10;
var cur = new Date();
// cur.setDate(cur.getDate()-daysBack);
var strDate = cur.toISOString();

// some documentation https://github.com/desmondmorris/node-twitter/tree/master/examples#tweet

function goodTweet(txt){
	var txt = txt.toLowerCase();

	// nlp algorithm here

	if(txt.includes("russia")){
		return false;
	}
	return true;
}

function output(tweets){ //takes in tweets and
	for(let i = 0 ;i<tweets.length;i++){
		var obj = tweets[i];
		var txt = obj['text'];
		var id = obj['id_str'];

		if(goodTweet(txt)){
			//insert into database
			console.log(txt);
			console.log(id);
			console.log('\n')
		}
		else{
			console.log("DIDNT PASS");
		}
	}
}

function getRequest(){
	var params = {
		screen_name: 'thetimes'
		// start_time: '2022-03-20T18:00:00Z'
	};
	client.get('/statuses/user_timeline', params, function(error, tweets, response) {
	  if (!error) {
	  	output(tweets);
	  }
	});
}

//ISSUING REQUESTS AND TIMERS
var interval;

function startDetection(){
	interval = setInterval(function(){ //request sent repeat timer
		console.log("HI");
		// getRequest();
	},2000);
}

function stopDetection(){
	clearInterval(interval);
}

startDetection();

setTimeout(function(){ //stop after x amount of time
	stopDetection();
},10000);

