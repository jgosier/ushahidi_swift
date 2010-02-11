<?php defined('SYSPATH') or die('No direct script access.');
/**
* Feed Controller
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   Feed Controller	
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
* 
*/

class Feed_Controller extends Controller
{
	public $template;

	public $auto_render = TRUE;
	
	function __construct()
	{
		parent::__construct();
		
			$this->template->header = new View('header');
				$this->template->footer  = new View('footer');
		
		$this->template->header->this_page = "feed_add";
		
		$this->template->header->l = 1;
		$this->template->header->locales_array = array();
		
		$this->template->header->site_name_style = "";
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
		$this->template->header->site_name = 'Add feed';
		$this->template->header->map_enabled = FALSE;
		$this->template->header->validator_enabled = TRUE;
		$this->template->header->datepicker_enabled = FALSE;
		$this->template->header->photoslider_enabled = FALSE;
		$this->template->header->videoslider_enabled = FALSE;
		$this->template->header->protochart_enabled = FALSE;
		$this->template->header->main_page = FALSE;
		$this->template->header->allow_feed = FALSE;
		
		
		$this->template->header->js = new View('reports_submit_js');
		$this->template->header->js->default_map = Kohana::config('settings.default_map');
		$this->template->header->js->default_zoom = Kohana::config('settings.default_zoom');

		//include footer form js file
		 $this->template->header->js = new View('footer_form_js');
		 $this->template->header->pages = ORM::factory('page')->where('page_active', '1')->find_all();
		
		
	}
	public function index($feedtype = 'rss2') 
	{
		if(!Kohana::config('settings.allow_feed')) {
			throw new Kohana_404_Exception();
		}
		if($feedtype!='atom' AND $feedtype!= 'rss2') {
			throw new Kohana_404_Exception();
		}		
		
		// How Many Items Should We Retrieve?
		$limit = ( isset($_GET['l']) && !empty($_GET['l'])
		 	&& (int) $_GET['l'] <= 200)
			? (int) $_GET['l'] : 20;
		
		// Start at which page?
		$page = ( isset($_GET['p']) && !empty($_GET['p'])
		 	&& (int) $_GET['p'] >= 1 )
			? (int) $_GET['p'] : 1;
		$page_position = ( $page == 1 ) ? 0 : 
			( $page * $limit ) ; // Query position
		
		$site_url = url::base();
			
		// Cache the Feed
		$cache = Cache::instance();
		$feed_items = $cache->get('feed_'.$limit.'_'.$page);
		if ($feed_items == NULL)
		{ // Cache is Empty so Re-Cache
			$incidents = ORM::factory('incident')
							->where('incident_active', '1')
							->orderby('incident_date', 'desc')
							->limit($limit, $page_position)->find_all();
			$items = array();
			
			foreach($incidents as $incident)
			{
				$item = array();
				$item['title'] = $incident->incident_title;
				$item['link'] = $site_url.'reports/view/'.$incident->id;
				$item['description'] = $incident->incident_description;
				$item['date'] = $incident->incident_date;

				if($incident->location_id != 0 
					AND $incident->location->longitude 
					AND $incident->location->latitude)
				{
						$item['point'] = array($incident->location->latitude, 
												$incident->location->longitude);
						$items[] = $item;
				}
			}
			
			$cache->set('feed_'.$limit.'_'.$page, $items, array('feed'), 3600); // 1 Hour
			$feed_items = $items;
		}
		
		$feedpath = $feedtype == 'atom' ? 'feed/atom/' : 'feed/';
		
		//header("Content-Type: text/xml; charset=utf-8");
		$view = new View('feed_'.$feedtype);
		$view->feed_title = htmlspecialchars(Kohana::config('settings.site_name'));
		$view->site_url = $site_url;
		$view->georss = 1; // this adds georss namespace in the feed
		$view->feed_url = $site_url.$feedpath;
		$view->feed_date = gmdate("D, d M Y H:i:s T", time());
		$view->feed_description = 'Incident feed for '.Kohana::config('settings.site_name');
		$view->items = $feed_items;
		$view->render(TRUE);
	}

	/**
		Function for the administrator to add feeds.
		
	*/
	
	
	public function add($feedtype = 'rss2') 
	{
		//$this->template->header->this_page = 'feed_add';
	
		$this->template->content = new View('feed_add');
		// setup and initialize form field names
		$form = array
		(
			'feed_name' => '',
			'feed_url' => '',
			'feed_active' => 1,
			'feed_category' => 0
		);
		//copy the form as errors, so the errors will be stored with keys corresponding to the form field names
		$errors = $form;
		$form_error = FALSE;

		// check, has the form been submitted, if so, setup validation
		if ($_POST)
		{
			// Instantiate Validation, use $post, so we don't overwrite $_POST fields with our own things
			$post = Validation::factory(array_merge($_POST,$_FILES));
			
			 //  Add some filters
			$post->pre_filter('trim', TRUE);

			// Add some rules, the input field, followed by a list of checks, carried out in order
			$post->add_rules('feed_name', 'required', 'length[3,200]');
			$post->add_rules('feed_url', 'required');
			//$post->add_rules('feed_active', 'required');
			$post->add_rules('feed_category', 'required');
			
		//	echo " post->feed_active ".(isset($post->feed_active))?"True","FALSE" ;
		//	exit(0);
		
			// Test to see if things passed the rule checks
			if ($post->validate())
			{
				// STEP 2: SAVE INCIDENT
				$feed = new Feed_Model();
				$feed->feed_name = $post->feed_name;
				$feed->feed_url = $post->feed_url;
				$feed->feed_active = (isset($post->feed_active))? 1:0;
				$feed->category_id = $post->feed_category;	
				if (ORM::factory('feed')->where('feed_url',$post->feed_url)->count_all() == 0)	
				{			
					$feed->save();
				}
				else
				{
					$errors['feed_url'] = 'This url is already in the database';
									// repopulate the form fields
					$form = arr::overwrite($form, $post->as_array());

					// populate the error fields, if any
					$errors = arr::overwrite($errors, $post->errors('report'));
					$form_error = TRUE;
				}
							// Notify Admin Of New Report
				$send = notifications::notify_admins(
					"[".Kohana::config('settings.site_name')."] ".
						Kohana::lang('notifications.admin_new_report.subject'),
					Kohana::lang('notifications.admin_new_report.message')
						."\n\n'".strtoupper($feed->feed_name)."'"
						."\n".$feed->feed_url
					);
				
			//	url::redirect('reports/thanks');
			}
	
			// No! We have validation errors, we need to show the form again, with the errors
			else   
			{
				// repopulate the form fields
				$form = arr::overwrite($form, $post->as_array());

				// populate the error fields, if any
				$errors = arr::overwrite($errors, $post->errors('report'));
				$form_error = TRUE;
			}
			
		}
				
		$this->template->content->form = $form;
		$this->template->content->errors = $errors;
		$this->template->content->form_error = $form_error;
		$this->template->content->categories = $this->_get_categories($form['feed_category']);
		

		 
		 $this->template->header->render(TRUE);
		 $this->template->content->render(TRUE);
		 		 

     //  $footerjs = new View('footer_form_js');
    // $this->template->Render();    
	//	echo $footerjs."I made in to add function without post 5 <br>";
		// Pack the javascript using the javascriptpacker helper
//		$myPacker = new javascriptpacker($footerjs , 'Normal', false, false);
//		$footerjs = $myPacker->pack();
       // $this->template->header->js = $footerjs;
		

	
	}
	
	private function _get_categories($selected_categories)
	{
		// Count categories to determine column length
		$categories_total = ORM::factory('category')
                            ->where('category_visible', '1')
                            ->count_all();

		$this->template->content->categories_total = $categories_total;

		$categories = ORM::factory('category')
                 ->where('category_visible', '1')
                 ->find_all();
/*
		foreach (ORM::factory('category')
                 ->where('category_visible', '1')
                 ->find_all() as $category)
		{
			// Create a list of all categories
			$categories[$category->id] = array($category->category_title, $category->category_color);
		}
*/
		return $categories;
	}
	
	
	
}
