<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Twitter utility
 * Access twitter messages and save them to the database.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Admin Messages Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class twitterAPI
{


	/**
	* Collects the twitter messages and loads them into the database
    */
	function load_tweets($twitter_hashtags)
	{
		// Set a timer so Twitter doesn't get requests every page load.
		// Note: We will move this to the fake-cron in the scheduler controller and change this.
		$proceed = 0; // Sanity check. This is just in case $proceed doesn't get set.
		if(!isset($_SESSION['twitter_timer'])) {
			$_SESSION['twitter_timer'] = time();
			$proceed = 1;
		}else{
			$timeCheck = time() - $_SESSION['twitter_timer'];
			if($timeCheck > 0) { //If it has been longer than 300 seconds (5 min)
				$proceed = 1;
				$_SESSION['twitter_timer'] = time(); //Only if we proceed do we want to reset the timer
			}else{
				$proceed = 0;
			}
		}

		if($proceed == 1) { // Grab Tweets

			// Grabbing tweets requires cURL so we will check for that here.
			if (!function_exists('curl_exec'))
			{
				throw new Kohana_Exception('messages.load_tweets.cURL_not_installed');
				return false;
			}

			// Retrieve Current Settings
			$settings = ORM::factory('settings', 1);

			//Perform Hashtag Search
			$hashtags = explode(',',$twitter_hashtags);
			foreach($hashtags as $hashtag){
				$page = 1;
				$have_results = TRUE; //just starting us off as true, although there may be no results
				while($have_results == TRUE && $page <= 5){ //This loop is for pagination of rss results
				$hashtag = trim(str_replace('#','',$hashtag));
					//$twitter_url = 'http://search.twitter.com/search.atom?q=%23'.$hashtag.'&page='.$page;
					$twitter_url = 'http://search.twitter.com/search.json?q=%23'.$hashtag.'&page='.$page;
					$curl_handle = curl_init();
					curl_setopt($curl_handle,CURLOPT_URL,$twitter_url);
					curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2); //Since Twitter is down a lot, set timeout to 2 secs
					curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1); //Set curl to store data in variable instead of print
					$buffer = curl_exec($curl_handle);
					curl_close($curl_handle);
					$have_results = $this->add_tweets($buffer,$hashtag); //if FALSE, we will drop out of the loop
				//	$have_results = $this->add_json_tweets($buffer); //if FALSE, we will drop out of the loop
					$page++;
				}
			}

			//Perform Direct Reports Search
			$username = $settings->twitter_username;
			$password = $settings->twitter_password;
			if (!empty($username) && !empty($password))
			{
				$twitter_url = 'http://twitter.com/statuses/replies.json';
				$curl_handle = curl_init();
				curl_setopt($curl_handle,CURLOPT_URL,$twitter_url);
				curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
				curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($curl_handle,CURLOPT_USERPWD,"$username:$password"); //Authenticate!
				$buffer = curl_exec($curl_handle);
				curl_close($curl_handle);
				$this->add_tweets($buffer,null,$username);
				//$this->add_json_tweets($buffer);
			}
		}
	}

/**
*
*
*/
	function add_tweets_rss($data,$hashsearch = null,$username='')
	{
	
	$feed_data = $this->_setup_simplepie( $data ); //Pass this the raw xml data
		if($feed_data->get_item_quantity() != 0){
			foreach($feed_data->get_items(0,50) as $feed_data_item) {
				//Grab tweet data from RSS feed
				$tweet_link = $feed_data_item->get_link();
				$full_date = $feed_data_item->get_date();
				$tweet_date = date("Y-m-d H:i:s",strtotime($full_date));
				if($hashsearch != null){
					$tweet_hashtag = $hashsearch;
					$full_tweet = $feed_data_item->get_title();
					$tweet_from = $feed_data_item->get_author()->get_name();
					//chop off string at "("
					$tweet_from = trim(substr($tweet_from,0,stripos($tweet_from,'(')));
					$tweet_to = ''; // There is no "to" so we make it blank
					$tweet = $full_tweet;
				}else{
					$tweet_hashtag = ''; //not searching using a hashtag
					$full_tweet = $feed_data_item->get_description();
					//Parse tweet for data
					$chop_location = ': @'.$username;
					$cut1 = stripos($full_tweet, $chop_location); //Find the position of the username
					$cut2 = $cut1 + strlen($chop_location) + 1; //Calculate the pos of the start of the tweet
					$tweet_from = substr($full_tweet,0,$cut1);
					$tweet_to = $username;
					$tweet = substr($full_tweet,$cut2);
				}

				if(isset($full_tweet) && !empty($full_tweet)) {
					// We need to check for duplicates.
					// Note: Heave on server.
					$dupe_count = ORM::factory('twitter')->where('tweet_link',$tweet_link)->where('tweet',$tweet)->count_all();
					if ($dupe_count == 0) {
						// Add tweet to database
						$newtweet = new Twitter_Model();
						$newtweet->tweet_from = $tweet_from;
						$newtweet->tweet_to = $tweet_to;
						$newtweet->tweet_hashtag = $tweet_hashtag;
						$newtweet->tweet_link = $tweet_link;
						$newtweet->tweet = $tweet;
						$newtweet->tweet_date = $tweet_date;
						$newtweet->save();
					}
				}
			}
		}else{
			return FALSE; //if there are no items in the feed
		}
		$feed_data->__destruct(); //in the off chance we hit a ton of feeds, we need to clean it out
		return TRUE; //if there were items in the feed
	
	}





	/**
	* Adds tweets to the database.
    * @param string $data - Twitter XML results
    * @param string $hashsearch - null if using auth session, or the hashtag being used to search
    * @param string $username
    */
	function add_tweets($data,$hashsearch = null,$username=''){
		$feed_data = $this->_setup_simplepie( $data ); //Pass this the raw xml data
		if($feed_data->get_item_quantity() != 0){
			foreach($feed_data->get_items(0,50) as $feed_data_item) {
				//Grab tweet data from RSS feed
				$tweet_link = $feed_data_item->get_link();
				$full_date = $feed_data_item->get_date();
				$tweet_date = date("Y-m-d H:i:s",strtotime($full_date));
				if($hashsearch != null){
					$tweet_hashtag = $hashsearch;
					$full_tweet = $feed_data_item->get_title();
					$tweet_from = $feed_data_item->get_author()->get_name();
					//chop off string at "("
					$tweet_from = trim(substr($tweet_from,0,stripos($tweet_from,'(')));
					$tweet_to = ''; // There is no "to" so we make it blank
					$tweet = $full_tweet;
				}else{
					$tweet_hashtag = ''; //not searching using a hashtag
					$full_tweet = $feed_data_item->get_description();
					//Parse tweet for data
					$chop_location = ': @'.$username;
					$cut1 = stripos($full_tweet, $chop_location); //Find the position of the username
					$cut2 = $cut1 + strlen($chop_location) + 1; //Calculate the pos of the start of the tweet
					$tweet_from = substr($full_tweet,0,$cut1);
					$tweet_to = $username;
					$tweet = substr($full_tweet,$cut2);
				}

				if(isset($full_tweet) && !empty($full_tweet)) {
					// We need to check for duplicates.
					// Note: Heave on server.
					$dupe_count = ORM::factory('twitter')->where('tweet_link',$tweet_link)->where('tweet',$tweet)->count_all();
					if ($dupe_count == 0) {
						// Add tweet to database
						$newtweet = new Twitter_Model();
						$newtweet->tweet_from = $tweet_from;
						$newtweet->tweet_to = $tweet_to;
						$newtweet->tweet_hashtag = $tweet_hashtag;
						$newtweet->tweet_link = $tweet_link;
						$newtweet->tweet = $tweet;
						$newtweet->tweet_date = $tweet_date;
						$newtweet->save();
					}
				}
			}
		}else{
			return FALSE; //if there are no items in the feed
		}
		$feed_data->__destruct(); //in the off chance we hit a ton of feeds, we need to clean it out
		return TRUE; //if there were items in the feed
	}

	/**
	* Adds tweets in JSON format to the database and saves the sender as a new
	* Reporter if they don't already exist unless the message is a TWitter Search result
    * @param string $data - Twitter JSON results
    */

	private function add_json_tweets($data)
	{
		$services = new Service_Model();
    	$service = $services->where('service_name', 'Twitter')->find();
	   	if (!$service) {
 		    return false;
	    }
		$tweets = json_decode($data, false);
		if (!$tweets) {
			return false;
		}
		
		if (array_key_exists('results', $tweets)) {
			$tweets = $tweets->{'results'};
		}

		foreach($tweets as $tweet) {
			$tweet_user = null;
			if (array_key_exists('user', $tweet)) {
				$tweet_user = $tweet->{'user'};
			}
			
			//XXX For Twitter Search, should we curl Twitter for a full tweet?
			
    		$reporter = null;
    		if ($tweet_user) {
	    		$reporter_model = new Reporter_Model();
				$reporters = $reporter_model->where('service_id', $service->id)->
				             where('service_userid', $tweet_user->{'id'})->
				             find_all();
				if (count($reporters) < 1) {
					// Add new reporter
		    		$names = explode(' ', $tweet_user->{'name'}, 2);
		    		$last_name = '';
		    		if (count($names) == 2) {
		    			$last_name = $names[1];
		    		}

		    		// get default reporter level (Untrusted)
		    		$levels = new Level_Model();
			    	$default_level = $levels->where('level_weight', 0)->find();

		    		$reporter = new Reporter_Model();
		    		$reporter->service_id       = $service->id;
		    		$reporter->service_userid   = $tweet_user->{'id'};
		    		$reporter->service_account  = $tweet_user->{'screen_name'};
		    		$reporter->reporter_level   = $default_level;
		    		$reporter->reporter_first   = $names[0];
		    		$reporter->reporter_last    = $last_name;
		    		$reporter->reporter_email   = null;
		    		$reporter->reporter_phone   = null;
		    		$reporter->reporter_ip      = null;
		    		$reporter->reporter_date    = date('Y-m-d');
		    		$reporter->save();
	    		} else {
	    			// reporter already exists
	    			$reporter = $reporters[0];
	    		}
	    	}

			if (count(ORM::factory('message')->where('service_messageid', $tweet->{'id'})
			                           ->find_all()) == 0) {
				// Save Tweet as Message
	    		$message = new Message_Model();
	    		$message->parent_id = 0;
	    		$message->incident_id = 0;
	    		$message->user_id = 0;
	    		
	    		if ($reporter) {
	    			$message->reporter_id = $reporter->id;
	    		} else {
	    			$message->reporter_id = 0;
	    		}
	    		
		    	if ($tweet_user) { 
		    		$message->message_from = $tweet_user->{'screen_name'};
	    		} elseif (array_key_exists('from_user', $tweet)) { // Twitter Search tweets
		    		$message->message_from = $tweet->{'from_user'};
	    		}
	    		$message->message_to = null;
	    		$message->message = $tweet->{'text'};
	    		$message->message_detail = null;
	    		$message->message_type = 1; // Inbox
	    		$tweet_date = date("Y-m-d H:i:s",strtotime($tweet->{'created_at'}));
	    		$message->message_date = $tweet_date;
	    		$message->service_messageid = $tweet->{'id'};
	    		$message->save();
    		}
    	}
    	return true;
	}
	
	
	/**
	 * setup simplepie
	 * @param string $raw_data
	 */
	private function _setup_simplepie( $raw_data ) {
			$data = new SimplePie();
			$data->set_raw_data( $raw_data );
			$data->enable_cache(false);
			$data->enable_order_by_date(true);
			$data->init();
			$data->handle_content_type();
			return $data;
	}



}