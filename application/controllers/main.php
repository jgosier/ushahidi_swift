<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This is the controller for the main site.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Main Controller  
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
 
 require(APPPATH.'controllers/admin/messages.php');
 
class util{
	/**
			Get the tags for display in the home page.
		*/
		public static function showtags($id)
		{
				$db = new Database();
				$sql1 = "SELECT id,  tagged_id,  tablename,  tags   FROM tags WHERE tagged_id = ".$id." AND tablename = 'feed_item' AND correct_yn = 1 ";
				$tags = $db->query($sql1);
				$tagnew_tags = "";
				foreach($tags as $tag)
				{ //CC9966
						$tagnew_tags .= $tag->tags."&nbsp;<a href='javascript:mark_tag_false(".	$tag->id.",".$tag->tagged_id.")' title='Mark tag as incorrect' >".
											"<span style='color:#CC0000;background:#CCCCCC;font-weight:bold;'> X </span></a>&nbsp;&nbsp;" ;			
				}				
				return 	$tagnew_tags;	
		}
		/**
		 Get the categeories for display in the home page.
		*/
		
		public static function get_category_name($id)
		
		{
		
		$category = ORM::factory('category')->where('id',$id)->find_all();

		$category_title = $category[0]->category_title;              
		
		return   $category_title;  
		}

	} 
 
 
class Main_Controller extends Template_Controller {

    public $auto_render = TRUE;
	
    // Main template
    public $template = 'layout';
	
    // Cache instance
    protected $cache;

	// Session instance
	protected $session;
	
    public function __construct()
    {
        parent::__construct();	

			  // Load cache
        $this->cache = new Cache;


		// Load Session
		$this->session = Session::instance();
		
        // Load Header & Footer
        $this->template->header  = new View('header');
        $this->template->footer  = new View('footer');        
		
		//call the feedback form
		$this->_get_feedback_form();
        
		// Retrieve Default Settings
		$site_name = Kohana::config('settings.site_name');
			// Prevent Site Name From Breaking up if its too long
			// by reducing the size of the font
			if (strlen($site_name) > 20)
			{
				$site_name_style = " style=\"font-size:21px;\"";
			}
			else
			{
				$site_name_style = "";
			}
        $this->template->header->site_name = $site_name;
		$this->template->header->site_name_style = $site_name_style;
		$this->template->header->site_tagline = Kohana::config('settings.site_tagline');
        $this->template->header->api_url = Kohana::config('settings.api_url');

		// Display Contact Tab?
		$this->template->header->site_contact_page = Kohana::config('settings.site_contact_page');
				
		// Display Help Tab?
		$this->template->header->site_help_page = Kohana::config('settings.site_help_page');
		
		// Get Custom Pages
		$this->template->header->pages = ORM::factory('page')->where('page_active', '1')->find_all();
        
        // Get custom CSS file from settings
        $this->template->header->site_style = Kohana::config('settings.site_style');
		
				// Display News Feed?
				$this->template->header->allow_feed = Kohana::config('settings.allow_feed');
				
				// Javascript Header
				$this->template->header->map_enabled = FALSE;
				$this->template->header->validator_enabled = TRUE;
				$this->template->header->datepicker_enabled = FALSE;
				$this->template->header->photoslider_enabled = FALSE;
				$this->template->header->videoslider_enabled = FALSE;
				$this->template->header->protochart_enabled = FALSE;
				$this->template->header->main_page = FALSE;
				
				$footerjs = new View('footer_form_js');
				
				// Pack the javascript using the javascriptpacker helper
				$myPacker = new javascriptpacker($footerjs , 'Normal', false, false);
				$footerjs = $myPacker->pack();
				
				$this->template->header->js = $footerjs;
				
				$this->template->header->this_page = "";
				
				// Google Analytics
				$google_analytics = Kohana::config('settings.google_analytics');
				$this->template->footer->google_analytics = $this->_google_analytics($google_analytics);
				
				// *** Locales/Languages ***
				// First Get Available Locales
				$this->template->header->locales_array = $this->cache->get('locales');
				
				// Locale form submitted?
				if (isset($_GET['l']) && !empty($_GET['l']))
				{
					$this->session->set('locale', $_GET['l']);
				}
				// Has a locale session been set?
				if ($this->session->get('locale',FALSE))
				{
					// Change current locale
					Kohana::config_set('locale.language', $_SESSION['locale']);
				}
				$this->template->header->l = Kohana::config('locale.language');
				
				//Set up tracking gif
				if($_SERVER['SERVER_NAME'] != 'localhost' && $_SERVER['SERVER_NAME'] != '127.0.0.1'){
					$track_url = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
				}else{
					$track_url = 'null';
				}
				$this->template->footer->tracker_url = 'http://tracker.ushahidi.com/track.php?url='.urlencode($track_url).'&lang='.$this->template->header->l.'&version='.Kohana::config('version.ushahidi_version');
		        // Load profiler
        // $profiler = new Profiler;
        
        // Get tracking javascript for stats
        $this->template->footer->ushahidi_stats = $this->_ushahidi_stats();
    }
    
    
    /**
    		Mark as irrevant.
    */
    public function mark_irrelevant($feedid,$categoryid)
		{
				if(request::is_ajax())
				{

						$db = new Database();
						$this->auto_render=false;
						$sql1 = "";
			  		$sql2 = "";
						$sql3 = "";
						if($categoryid == 2 || $categoryid == 10 || $categoryid == 11)
					  {
					  		$sql2 = " UPDATE message SET submited_to_ushahidi = 2 WHERE id=".$feedid ;
						}
					  else
				  	{		
								$sql2 = " UPDATE feed_item SET submited_to_ushahidi = 2 WHERE id=".$feedid ;					
					  }	
					//	echo $sql2."<br/>";
						$update = $db->query($sql2);						
						echo json_encode(array('message' => '<span style=color:red> Feed marked for deletion.</span>'));				
				}
			//	url::redirect("/main/index/category/".$categoryid."/page/1");
		}
		/**
    		change the weight of the feed source.
    */
    
    public function increment_source_rating($feedid,$categoryid)
    {
    	$increment = " + 1";
			$this->change_source_rating($feedid,$categoryid,$increment);
    }
    public function decrement_source_rating($feedid,$categoryid)
    {
    		$decrement = " - 1";
    		$this->change_source_rating($feedid,$categoryid,$decrement);
    }
    private function change_source_rating($feedid,$categoryid,$increment)
		{
				if(request::is_ajax())
				{	
						$db = new Database();
						$this->auto_render=false;
						$sql1 = "";
			  		$sql2 = "";
						$sql3 = "";
						if($categoryid == 2 || $categoryid == 10 || $categoryid == 11)
					  {
								$sql2 = "UPDATE reporter SET weight = weight ".$increment." WHERE weight ".$increment." <= 100 AND weight ".$increment." >= 0 AND id IN (SELECT reporter_id FROM message WHERE id = ".$feedid." ) ";
								$sql3 = "SELECT weight FROM reporter  WHERE id IN (SELECT reporter_id FROM message WHERE id = ".$feedid." ) ";
					
					  }
					  else
				  	{		
								$sql2 = "UPDATE feed SET weight = weight ".$increment." WHERE weight ".$increment." <= 100 AND weight ".$increment." >= 0 AND id IN (SELECT feed_id FROM feed_item WHERE id = ".$feedid." ) ";
					  		$sql3 = "SELECT weight FROM feed  WHERE id IN (SELECT feed_id FROM feed_item WHERE id = ".$feedid." ) ";
						}	
					//	echo $sql2."<br/>";
						$update = $db->query($sql2);
						$weightrs = $db->query($sql3);
						$weight_value = round($weightrs[0]->weight,0);
										  
						echo json_encode(array('message' => 'Message Sent to Ushahidi','weight'=>$weight_value));						
				}
		}
    
    
    
    
    
    /**
    		This function submits reports to the ushahidi instance API
    */
    public function submit_report_via_API($feedid,$categoryid)
		{
				//if(request::is_ajax())
			//	{
					//get information from the database
						$db = new Database();
					  $this->auto_render=false;
					  $sql1 = "";
					
					  //categories news,blogs,others use the feeds table.  others come from the messages table.
					  if($categoryid == 2 || $categoryid == 10 || $categoryid == 11)
						{
								$sql1 =	" 	SELECT 
											 m.id as id
											,m.message as item_title,
											 m.message as item_description,
											  CASE r.service_id 
			 									WHEN 3 THEN CONCAT('http://twitter.com/',m.message_from,'/statuses/',m.service_messageid)
			 									ELSE '#'
			 									END as item_link,
											 m.message_date as item_date,
											 m.message_from as item_source,
											 l.longitude,	l.latitude,	l.location_name ,
											 r.reporter_first,  r.reporter_last ";  
											 
									if ($categoryid == 2 )//sms
											$sql1 .= ", r.reporter_phone as reporter_email" ;		
									else 
											$sql1 .= ", r.reporter_email" ; 	 
											
									$sql1	.= " FROM message m   
													LEFT OUTER JOIN reporter r ON  r.id = m.reporter_id  
													LEFT OUTER JOIN location l ON l.id = r.location_id
												WHERE m.id = ".$feedid ;
						}
						else
						{
								$sql1 = "SELECT 	f.id as id,	item_title,		item_description,		item_link, 
											item_date, 	a.feed_name as item_source,
											l.longitude,	l.latitude,	l.location_name ,
											 '' as reporter_first,  '' as reporter_last,  '' as reporter_email  
												FROM feed_item f 
														 LEFT OUTER JOIN feed a ON f.feed_id = a.id 
														 LEFT OUTER JOIN location l ON l.id = f.location_id
												WHERE f.id = ".$feedid;
						}
														 
														 
						$feeds = $db->query($sql1);
						$feed = $feeds[0];
						$xmlcontent = "task=report";
				  	
						//$reportdata="api?task=report&incident_title=Test&incident_description=Testing+with+the+api.&incident_date=03/18/2009&incident_hour=10&incident_minute=10&incident_ampm=pm&incident_category=2,4,5,7&latitude=-1.28730007&longitude=36.82145118200820&location_name=accra&person_first=Henry+Addo&person_last=Addo&person_email=henry@ushahidi.com&resp=xml "
							
							$xmlcontent .=	"&incident_title=".$feed->item_title; //"</incident_title>" ; // - Required. The title of the incident/report.
							$xmlcontent .=	"&incident_description=".$feed->item_description; //"</incident_description>" ; //incident_description - Required. The description of the incident/report.
							$xmlcontent .=	"&incident_date=".date('m/d/Y', strtotime($feed->item_date)); //"</incident_date>" ;//incident_date - Required. The date of the incident/report. It usually in the format mm/dd/yyyy.
							$xmlcontent .=	"&incident_hour=".date('h', strtotime($feed->item_date)); //"</incident_hour>"; //"incident_hour - Required. The hour of the incident/report. In the 12 hour format.
					  	$xmlcontent .=	"&incident_minute=".date('i', strtotime($feed->item_date)) ; //."</incident_minute>"; //incident_minute - Required. The minute of the incident/report.
							$xmlcontent .=	"&incident_ampm=";
													 if(date('H', strtotime($feed->item_date))<= 12) $xmlcontent .= "am"; else $xmlcontent .= "pm";
								 							//	$xmlcontent .= "</incident_ampm>"; //"incident_ampm - Required. Is the incident/report am or pm. It of the form, am or pm.
							$xmlcontent .=	"&incident_category=".$categoryid; //"</incident_category>";//	"incident_category - Required. The categories the incident/report belongs to. It should be a comma separated value csv
							$xmlcontent .=	"&latitude=".(!empty($feed->latitude) ? $feed->latitude:"0"); //"</latitude>"; //"latitude - Required. The latitude of the location of the incident report.
							$xmlcontent .=  "&longitude=".(!empty($feed->longitude) ?	$feed->longitude:"0") ; //"</longitude>"; //"longitude - Required. The longitude of the location of the incident/report.
							$xmlcontent .=	"&location_name=".(!empty($feed->location_name)? $feed->location_name :"unknown") ; //"</location_name>"; 	//"location_name - Required. The location of the incident/report.
							$xmlcontent .=	!empty($feed->reporter_first) ? "&person_first=".$feed->reporter_first: ""; //"</person_first>"; //person_first - Optional. The first name of the person submitting the incident/report.
							$xmlcontent .=	!empty($feed->reporter_last)? "&person_last=".$feed->reporter_last:""; //"</person_last>"; //person_last - Optional. The last name of the person submitting the incident/report.
							$xmlcontent .=	!empty($feed->reporter_email)? "&person_email=".$feed->reporter_email:""; //."</person_email>"; //person_email - Optional. The email address of the person submitting the incident/report.
							$xmlcontent .=	"&resp=json";//</resp></root>"; 	//resp - Optional. The data exchange, either XML or JSON. When not specified, JSON is used.
								
						//	echo 	$xmlcontent;
						//	exit(0);
/*"
incident_photo[] - Optional. Photos to accompany the incident/report.
incident_news - Optional. A news source regarding the incident/report. A news feed.
incident_video - Optional. A video link regarding the incident/report. Video services like youtube.com, video.google.com, metacafe.com,etc
 "*/
						$ushahidi_url = ORM::factory('settings', 1)->ushahidi_url;	
						
						if (empty($ushahidi_url))
						{		
								echo json_encode(array('message' => '<span style=color:red >The ushahidi instance url is not set. Contact Admin.</span>'));
								return;
						}	
							
						$ch = curl_init(); 
						curl_setopt($ch, CURLOPT_HEADER, 0); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
						curl_setopt($ch, CURLOPT_URL, $ushahidi_url."/api/index?"); 
						curl_setopt($ch, CURLOPT_POST, 1); 
						curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlcontent); 
						$content=curl_exec($ch); 
						
					  //	{"payload":{"success":"true"},"error":{"code":"0","message":"No Error."}}
						
						$status = false;
						if(strlen(strstr($content,"success\":\"true"))>0)
							$status = true;
						
						if ($status)
						{
									  $sql2 = "";
									  $sql3 = "";
								if($categoryid == 2 || $categoryid == 10 || $categoryid == 11)
							  {				
							  			$sql1 = "UPDATE message SET submited_to_ushahidi = 1 WHERE id=".$feedid ;
							  			$sql2 = "UPDATE reporter SET weight = weight + 5 WHERE weight + 5 <= 100 AND id IN (SELECT reporter_id FROM message WHERE id = ".$feedid." ) ";
											$sql3 = "SELECT weight FROM reporter  WHERE id IN (SELECT reporter_id FROM message WHERE id = ".$feedid." ) ";
							  }
								else
								{
							  			$sql1 = "UPDATE feed_item SET submited_to_ushahidi = 1 WHERE id=".$feedid ;
							  			$sql2 = "UPDATE feed SET weight = weight + 5 WHERE weight + 5 <= 100 AND id IN (SELECT feed_id FROM feed_item WHERE id = ".$feedid." ) ";
							  			$sql3 = "SELECT weight FROM feed  WHERE id IN (SELECT feed_id FROM feed_item WHERE id = ".$feedid." ) ";
								}	
								$update = $db->query($sql1);			
								$update = $db->query($sql2);	
								$weightrs = $db->query($sql3);
								$weight_value = round($weightrs[0]->weight,0);										  							  
	
								echo json_encode(array('message' => '<span style=color:red >Incident has been reported to Ushahidi</span>','weight'=>$weight_value));		
						}
						else
								echo ($content);
						
		//		}
					//url::redirect("/main/index/category/".$categoryid."/page/1");
    }
    
    
    /**
    	This function update the tags.
    */

		private function add_tags($id,$tag)
		{
					if(ORM::factory('tags')->where('tagged_id',$id)->where('tablename','feed_item')->where('tags.tags',$tag)->count_all() == 0)
					{	
						$tags = new Tags_Model();
						$tags->tagged_id = $id;
						$tags->tablename = 'feed_item';
						$tags->tags = $tag;
						$tags->save();
					}
		}
		
		/**
				Mark the tag as false
		*/
		public function Ajax_mark_tag_false($tagid,$feedid)
		{
				if(request::is_ajax())
				{	
					$this->auto_render=false;
					$db = new Database();
					$sql1 = "UPDATE tags SET correct_yn = 0  WHERE id = ".$tagid." ";
					$tags = $db->query($sql1);		
					$tagnew_tags = util::showtags($feedid);	
					echo json_encode(array('tags' => $tagnew_tags));	
				}
		}
		
	
		/**
				Add a tags.
		*/		
		public function Ajax_tagging($id,$tag)
		{
				if(request::is_ajax())
				{		$this->auto_render=false;
						$this->add_tags($id,$tag);		
						$tagnew_tags = util::showtags($id);	
						echo json_encode(array('tags' => $tagnew_tags));	
				}
		}
		
		/**
		*		This function help the tagging feeds
		*/
		public function tagging($feed,$object_id,$cat,$category_id,$page_val,$page_no)
		{			
					if($_POST)
					{
							$this->update_tags($object_id,$_POST["tag_$object_id"]);
							url::redirect("/main/index/category/$category_id/page/".$page_no );	
					}			
		}

/**
		*		This function help the verocity selector
		*/
		public function verocity($category_id)
		{			
					if($_POST)
					{
							$_SESSION['verocity_min'] = isset($_POST['verocity_min'])?$_POST['verocity_min']:0;
							$_SESSION['verocity_max'] = isset($_POST['verocity_max'])?$_POST['verocity_max']:100;
					}
							url::redirect("/main/index/category/".$category_id."/page/1" );	
								
		}
		
	/**
	*
	*   //get all the admin feeds in database.
	*/
		private function get_new_feeds($category_id)
		{  //get all the admin feeds in database.
		
			$dbfeeds = ORM::factory('feed')->select('id','feed_url','category_id')->where('category_id',$category_id)->find_all();
			
			if($category_id == 0) 
			{
				$dbfeeds	= ORM::factory('feed')->select('id','feed_url','category_id')->find_all();
			}
				
				foreach ($dbfeeds as $dbfeed )
				{				
						//Don't do anything about twitter categories.
						if($dbfeed->category_id != 11 )
						{	
								$url = "";
								$feed = new SimplePie();				
								$feed->enable_order_by_date(true);
								if ($dbfeed->category_id == 1)
								{
									$url	= "http://twitter.com/statuses/user_timeline/".$dbfeed->feed_url.".rss";
									$feed->set_feed_url($url);
															//	exit(0);
								}else
								{
									$url = $dbfeed->feed_url;
									$feed->set_feed_url($dbfeed->feed_url);
								}								

								$feed->set_cache_location(APPPATH.'cache');
								$feed->set_timeout(10);
								$feed->init();			
											
							//		$channel = $feed->get_feed_tags('', 'channel');
							//		echo " tags=> ".$channel."<br/>";
							// echo "$url :<br/>";
								
							//	exit(0)				
								$max_items =	$feed->get_item_quantity();								
								$require_new_items = 20;
								$new_item_counter = 0;
								$start = 0	;
																											
								for($i = $start ;$i < $max_items && $new_item_counter < $require_new_items;$i++)
								{
											$item = $feed->get_item($i);
											
								/*				//getting all the feed information.								 
									echo "$url:  latitude => ".$item->get_latitude();
									echo "   longitude => ".$item->get_longitude();
									echo '<a href="' . $feed->get_image_link() . '" title="' . $feed->get_image_title() . '">';
									echo '<img src="' . $feed->get_image_url() . '" width="' . $feed->get_image_width() . '" height="' . $feed->get_image_height() . '" />';
									echo '</a><br/>Title:'.$item->get_title();
									echo '<br/>Description:'.$item->get_description();
									echo '<hr/>';
											
									*/		
											
											$itemobj = new Feed_Item_Model();		
											$itemobj->feed_id = $dbfeed->id;
											$itemobj->item_title = $item->get_title();
											$itemobj->item_description = $item->get_description();
											$itemobj->item_link = $item->get_permalink();
											$itemobj->item_date = $item->get_date('Y-m-d h:m:s');
											if ($author = $item->get_author())
											{
													$itemobj->item_source = $item->get_author()->get_name(); //temporary not working.
											}											
										
										//echo "in Main Controller $dbfeed->feed_url =>  latitude =".$feed->get_latitude().", longitude =".$feed->get_longitude()."<br/>";
										//echo "in Main Controller $dbfeed->feed_url =>   get_author() => ".$feed->get_author()."<br/>";
											$linkCount = ORM::factory('feed_item')->where('item_link',$item->get_permalink())->count_all() ;
											if($linkCount == 0)	
											{ 	$new_item_counter++;
													//  echo "link:=> ".$item->get_permalink()." is new and has appear ".$linkCount." times <br/>";
											 		$itemobj->save();
										  }
										  else if($linkCount > 0)
										  {
										  //	echo "link:=> ".$item->get_permalink()." appears ".$linkCount." times <br/>";
										  }
											 
								}
						}
				}
				
		//		exit(0);
		 }
/**
This is the index function called by default.


*/
    public function index($categoryname="",$category_id = 0,$page,$page_no)
    {		
        $this->template->header->this_page = 'home';
        $this->template->content = new View('main');
        
        $this->template->content->auth = null;
        
       if(isset( $_SESSION['auth_user']))
       {
         $this->template->content->auth = $_SESSION['auth_user'] ;
			 }
			//try getting new feeds and cache them to the database.
			  $this->get_new_feeds($category_id);
				$messages = new Messages_Controller();
				$messages->auto_render=false;
				
				if($category_id == 11)
				{
					$messages->load_tweets();
				}
			
        // Get all active top level categories
        $parent_categories = array();
        foreach (ORM::factory('category')
				->where('category_visible', '1')
				->where('parent_id', '0')
				->find_all() as $category)
        {
            // Get The Children
			$children = array();
			foreach ($category->children as $child)
			{
				$children[$child->id] = array( 
					$child->category_title, 
					$child->category_color
				);
			}
			
			// Put it all together
        $parent_categories[$category->id] = array( 
				$category->category_title, 
				$category->category_color,
				$children
			);
        }
        $this->template->content->categories = $parent_categories;

		// Get all active Layers (KMZ/KML)
		$layers = array();
		foreach (ORM::factory('layer')
				  ->where('layer_visible', 1)
				  ->find_all() as $layer)
		{
			$layers[$layer->id] = array($layer->layer_name, $layer->layer_color,
				$layer->layer_url, $layer->layer_file);
		}
		$this->template->content->layers = $layers;
		
		// Get all active Shares
		$shares = array();
		foreach (ORM::factory('sharing')
				  ->where('sharing_active', 1)
				  ->where('sharing_type', 1)
				  ->find_all() as $share)
		{
			$shares[$share->id] = array($share->sharing_site_name, $share->sharing_color);
		}
		$this->template->content->shares = $shares;
		
		
		// Get Default Color
		$this->template->content->default_map_all = Kohana::config('settings.default_map_all');
		
		// Get Twitter Hashtags
		$this->template->content->twitter_hashtag_array = array_filter(array_map('trim', 
			explode(',', Kohana::config('settings.twitter_hashtags'))));
		
		// Get Report-To-Email
		$this->template->content->report_email = Kohana::config('settings.site_email');
		
		// Get SMS Numbers
		$phone_array = array();
		$sms_no1 = Kohana::config('settings.sms_no1');
		$sms_no2 = Kohana::config('settings.sms_no2');
		$sms_no3 = Kohana::config('settings.sms_no3');
		if (!empty($sms_no1)) {
			$phone_array[] = $sms_no1;
		}
		if (!empty($sms_no2)) {
			$phone_array[] = $sms_no2;
		}
		if (!empty($sms_no3)) {
			$phone_array[] = $sms_no3;
		}
		$this->template->content->phone_array = $phone_array;
		

	
	//Cache items to the database.
		

	// Filter By Category
			$categoryYes = ( isset($category_id) && !empty($category_id) && !$category_id == 0 );		
		  $category_filter = $categoryYes	? "  a.category_id = ".$category_id."  " : " 1=1 ";		  		
		  $category_filter2 =	" r.service_id = ".($category_id == 2?" 1 ":($category_id == 10? " 2 " : " 3 "));		
		  
		  $verocity_filter =	"";
		  if(isset( $_SESSION['verocity_min']) && isset( $_SESSION['verocity_max'])){
			 $verocity_filter =	"	AND weight >=	".$_SESSION['verocity_min']." AND weight <= ".$_SESSION['verocity_max']." ";
			}	
			else
			{
				$verocity_filter =	"	AND weight >=	20 AND weight <= 100 ";
			}

		$numItems_per_page =  Kohana::config('settings.items_per_page');
		
		$sql = "	SELECT 
												f.id as id,
												item_title,
												item_description,
												item_link,												
												item_date, 
										 		a.weight as weight,
										 		a.feed_name as item_source,
										 		a.category_id as category_id
												FROM feed_item f 
														 INNER JOIN feed a ON f.feed_id = a.id 
												WHERE submited_to_ushahidi = 0 AND ".$category_filter.$verocity_filter;
								
		if($category_id == 11 || $category_id == 10 || $category_id == 2 )
		{ 	
			$sql =	" 	SELECT 
											 m.id as id
											,m.message as item_title,
											 m.message as item_description,";
											//ISNULL(m.message,'') + ' ' + ISNULL (message_detail,'') as item_description,
			 $sql .=	  		" CASE r.service_id 
			 									WHEN 3 THEN CONCAT('http://twitter.com/',m.message_from,'/statuses/',m.service_messageid)
			 									ELSE '#'
			 									END as item_link,
											 m.message_date as item_date,
											 r.weight as weight,
											 m.message_from as item_source,
											 CASE r.service_id  WHEN 1 THEN 2 WHEN 2 THEN 10 ELSE 11 END as category_id
											FROM message m  
													INNER JOIN reporter r ON r.id = m.reporter_id 
													WHERE  submited_to_ushahidi = 0 AND ".$category_filter2.$verocity_filter;											
			}
				// echo $sql; exit(0);
			
			$sql .= " ORDER BY item_date desc ";	

		 $db=new Database;
			if ($category_id == 11 || $category_id == 10 || $category_id == 2 )
			{ 
					$countersql	= " SELECT count(m.id)as Total FROM message m INNER JOIN reporter r ON r.id = m.reporter_id AND ".$category_filter2.$verocity_filter ;
				  $Feedcounts =	$db->query($countersql);
		
			}else
			{
					$Feedcounts =	$db->query("select count(f.id)as Total FROM feed_item f INNER JOIN feed a ON f.feed_id = a.id  WHERE ".$category_filter.$verocity_filter);
			}
			
		
		$pagination = new Pagination(array(
				'base_url' => '/main/index/category/'.$category_id ,
				'uri_segment' => 'page',
				'items_per_page' => (int) $numItems_per_page,
				'style' => 'digg',
				'total_items' => $Feedcounts[0]->Total
				));
				

		
	  $Feedlist = $db->query($sql." Limit ".$numItems_per_page*($page_no - 1) ." , ".$numItems_per_page);
		// Get RSS News Feeds
		$this->template->content->feeds = $Feedlist;
		$this->template->content->current_page = $page_no;
					
			  // Get Summary
        // XXX: Might need to replace magic no. 8 with a constant
        $this->template->content->feedcounts = $Feedcounts->count();        
        
        $feed_summary_sql = " SELECT f.feed_name as feed_name ,f.feed_url as feed_url ,count(fi.id) as total 
															FROM `feed` f ,feed_item fi 
															WHERE fi.feed_id = f.id AND f.category_id NOT IN (1,11) AND submited_to_ushahidi = 0 GROUP BY f.feed_name 
															UNION 
															SELECT f.feed_name as feed_name ,concat('http://twitter.com/statuses/user_timeline/',f.feed_url,'.rss') as feed_url,count(fi.id) as total 
															FROM `feed` f ,feed_item fi 
															WHERE fi.feed_id = f.id AND f.category_id IN (1) AND  submited_to_ushahidi = 0  GROUP BY f.feed_name 
															UNION 
															SELECT  twitter_hashtags as feed_name, concat('http://twitter.com/search?q=', REPLACE(replace(twitter_hashtags,'#',''),',',' ' )) as 
															feed_url ,count(m.id) as total
															FROM settings s , message m WHERE m.submited_to_ushahidi = 0  Group BY feed_name ";
															
    
		$this->template->content->feedsummary = $db->query($feed_summary_sql);
		
		$AnalyicQuery = " SELECT 'Submitted' as title,
										(select count(*) FROM feed_item WHERE  submited_to_ushahidi = 1)+
										(select count(*) FROM message WHERE  submited_to_ushahidi = 1) as count,
										(select count(*) FROM feed_item )+(select count(*) FROM message ) as total
										UNION
										SELECT 'Sources Trusted' as title,
										(select count(*) FROM feed WHERE  weight > 99)+
										(select count(*) FROM reporter WHERE  weight > 99) as count,
										(select count(*) FROM feed )+(select count(*) FROM reporter ) as total
										UNION
										SELECT 'tags added' as title,
										(select count(*) FROM tags WHERE  tablename = 'feed_item') as count,
										(select count(*) FROM feed )+(select count(*) FROM reporter ) as total
										UNION
										SELECT 'tags approved' as title,
										(select count(*) FROM tags WHERE  tablename = 'feed_item' AND correct_yn = 1) as count,
										(select count(*) FROM feed )+(select count(*) FROM reporter ) as total
										 ";
		
		$this->template->content->analyticSummary = $db->query($AnalyicQuery);
		
		//	echo	$AnalyicQuery ;		
		//	exit(0);
		
		$this->template->content->pagination = $pagination;
		$this->template->content->selected_category = $category_id;
		
		$footerjs = new View('footer_form_js');
		$feedjs = new View('feed_functions_js');
		
		// Pack the javascript using the javascriptpacker helper		
		$this->template->header->js .= $footerjs;
		$this->template->header->js2 = $feedjs;
		
		$myPacker = new javascriptpacker($this->template->header->js , 'Normal', false, false);
	//	$this->template->header->js = $myPacker->pack();
	}
	
	/*
	* Ushahidi Stats HTML/JavaScript
    * @return mixed  Return ushahidi stats HTML code.
	*/
	private function _ushahidi_stats( )
	{	
		// Make sure cURL is installed
		if (!function_exists('curl_exec')) {
			throw new Kohana_Exception('footer.cURL_not_installed');
			return false;
		}
		
		$settings = ORM::factory('settings', 1);
		$stat_id = $settings->stat_id;
		
		if($stat_id == 0) return ''; 
		$url = 'http://tracker.ushahidi.com/px.php?task=tc&siteid='.$stat_id;
		
		$curl_handle = curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$url);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,15); // Timeout set to 15 seconds. This is somewhat arbitrary and can be changed.
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1); // Set cURL to store data in variable instead of print
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);
		
		try {
			$tag = @simplexml_load_string($buffer); // This works because the tracking code is only wrapped in one tag
		} catch (Exception $e) {
			// In case the xml was malformed for whatever reason, we will just guess what the tag should be here
			$tag = '<!-- Piwik -->
					<script type="text/javascript">
					var pkBaseURL = (("https:" == document.location.protocol) ? "https://tracker.ushahidi.com/piwik/" : "http://tracker.ushahidi.com/piwik/");
					document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));
					</script><script type="text/javascript">
					try {
					  var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", '.$stat_id.');
					  piwikTracker.trackPageView();
					  piwikTracker.enableLinkTracking();
					} catch( err ) {}
					</script><noscript><p><img src="http://tracker.ushahidi.com/piwik/piwik.php?idsite='.$stat_id.'" style="border:0" alt=""/></p></noscript>
					<!-- End Piwik Tag -->
					';
		}
		
		return $tag;

	}
	
	
	/*
	* Google Analytics
	* @param text mixed  Input google analytics web property ID.
    * @return mixed  Return google analytics HTML code.
	*/
	private function _google_analytics($google_analytics = false)
	{
		$html = "";
		if (!empty($google_analytics)) {
			$html = "<script type=\"text/javascript\">
				var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");
				document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));
				</script>
				<script type=\"text/javascript\">
				var pageTracker = _gat._getTracker(\"" . $google_analytics . "\");
				pageTracker._trackPageview();
				</script>";
		}
		return $html;
	}
	
	/**
	 * Get the feedback
	 */
	private function _get_feedback_form() {
		//setup and initialize form fields
		$form = array
		(
				'feedback_message' => '',
				'person_email' => '',
				'feedback_captcha' => ''
		);
		
		// Load Akismet API Key (Spam Blocker)
		$api_akismet = Kohana::config('settings.api_akismet');
		
		$captcha = Captcha::factory();
		
		//  copy the form as errors, so the errors will be stored with keys corresponding to the form field names
		$errors = $form;
		$form_error = FALSE;

		//has form been submitted, if so setup validation
		if($_POST)
		{

			$post = Validation::factory($_POST);

			//Trim whitespaces
			$post->pre_filter('trim', TRUE);

			//Add validation rules
			$post->add_rules('feedback_message','required');
			$post->add_rules('person_email', 'required','email');
			$post->add_rules('feedback_captcha', 'required', 'Captcha::valid');
			if( $post->validate() ) { 
				if($api_akismet != "" ) {
					// Run Akismet Spam Checker
						$akismet = new Akismet();

						// comment data
						$feedback = array(
							'feedback_message' => $post->feedback_message,
							'person_email' => $post->feedback_message,
						);

						$config = array(
							'blog_url' => url::site(),
							'api_key' => $api_akismet,
							'feedback' => $feedback
						);

						$akismet->init($config);

						if($akismet->errors_exist()) 
						{
							if($akismet->is_error('AKISMET_INVALID_KEY'))
							{
								// throw new Kohana_Exception('akismet.api_key');
							}
							elseif($akismet->is_error('AKISMET_RESPONSE_FAILED')) 
							{
								// throw new Kohana_Exception('akismet.server_failed');
							}
							elseif($akismet->is_error('AKISMET_SERVER_NOT_FOUND')) 
							{
								// throw new Kohana_Exception('akismet.server_not_found');
							}
							// If the server is down, we have to post 
							// the comment :(
							// $this->_post_comment($comment);
							$feedback_spam = 0;
						}
						else {
							if($akismet->is_spam()) 
							{
								$feedback_spam = 1;
							}
							else {
								$feedback_spam = 0;
							}
						}
					}
					else
					{ // No API Key!!
						$feedback_spam = 0;
					}
				$this->_dump_feedback($post);


				//send details to admin
				$frm = $post->person_email;
				$subject = Kohana::lang('feedback.feedback_details');;
				$message = $post->feedback_message;
				$email = Kohana::config('settings.site_email');
				$this->_send_feedback( $email, $message, $subject, $frm );

				//send details to ushahidi
				$frm = $post->person_email;
				$subject = Kohana::lang('feedback.feedback_details');;
				$message = $post->feedback_message;
				$message .= "Instance: ".url::base();
				$email = "feedback@ushahidi.com";
				$this->_send_feedback( $email, $message, $subject, $frm );
			}
			else
	        {
				// repopulate the form fields
	            $form = arr::overwrite($form, $post->as_array());

	            // populate the error fields, if any
	            $errors = arr::overwrite($errors, $post->errors('feedback'));
				$form_error = TRUE;
			}
		}
		$this->template->footer->js = new View('footer_form_js');
		$this->template->footer->form = $form;
		$this->template->footer->captcha = $captcha;
		$this->template->footer->errors = $errors;
		$this->template->footer->form_error = $form_error;
        }

        /**
         * Escape string
         */
        private function _escape_string($str) {
            if( $str != "" ){
                $str = str_replace(array('\''),array('\\\''),$str);
                $str = "'".$str."'";
            }else {
                return "";
            }
            return $str;
        }

	
	/**
	 * puts feedback info into the database.
	 * @param the post object
	 */
	private function _dump_feedback($post) {
		
		$feedback = new Feedback_Model();
		$feedback->feedback_mesg = $post->feedback_message;
		$feedback->feedback_dateadd = date("Y-m-d H:i:s",time());
		$feedback->save();//save feedback info to db
		
		$feedback_person = new Feedback_Person_Model();
		$feedback_person->feedback_id = $feedback->id;
		$feedback_person->person_email = $post->person_email;
		$feedback_person->person_date = date("Y-m-d H:i:s",time());
		$feedback_person->person_ip = $post->person_ip;
		$feedback_person->save(); //save person info to db
	}
	
	/**
	 * Send feedback info as email to admin and Ushahidi
	 */
	public function _send_feedback( $email, $message, $subject, $frm )
	{
		$to = $email;
		$from = $frm;
		$subject = $subject;
		
		$message .= "\n\n";
		//email details
		if( email::send( $to, $from, $subject, $message, FALSE ) == 1 )
		{
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}
	
} // End Main
