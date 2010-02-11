<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Messages Controller.
 * View SMS Messages Received Via FrontlineSMS
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

class Feeds_Controller extends Admin_Controller
{

	
	function __construct()
	{
		parent::__construct();

	//	$this->template->this_page = 'Add Feeds';
		//$this->template->content->form_saved = false;
	}

	public function index($feedtype = 'rss2') 
	{
		
		
		$this->template->this_page = 'feed_add';
	
		$this->template->content = new View('admin/feeds');
		// setup and initialize form field names
	/*	$cat1fields1 = array('1feed_url1' => '',
												'1weight1' => 1,
												'1feed_category1' => 1);		

		*/
		$form = array();
		$categories = ORM::factory('category')->where('category_visible', '1')->orderby('id')->find_all();
		
		$prev_feeds = ORM::factory('feed')->orderby('category_id')->find_all();
		$cat_counter = 0;
		$cat_max = count($prev_feeds);
		
		foreach($categories as $cat)
		{
				for($i=1;$i<7;$i++)
				{ 
					//this section gets the information from the database and sign it to the right fields.
					if( $cat_counter < $cat_max &&  $prev_feeds[$cat_counter]->category_id == $cat->id) 
					{
							$pre_feed	=	$prev_feeds[$cat_counter];
							$form[$cat->category_title."feed_url".$i] = $pre_feed->feed_url;
							$form[$cat->category_title."weight".$i] = $pre_feed->weight == 100 ?1:0 ;
							$form[$cat->category_title."feed_category".$i] = $pre_feed->category_id;
							
							$cat_counter++;
					}
					else
					{
							$form[$cat->category_title."feed_url".$i] = '';
							$form[$cat->category_title."weight".$i] = 0 ;
							$form[$cat->category_title."feed_category".$i] = $cat->id;
					}
					
				//	echo "cat->category_title.feed_url.i => ".$cat->category_title."feed_url".$i."<br/>";
				}
							
		}
		
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

					if ($post->validate())
					{
					
							  $deleted = ORM::factory('feed')->delete_all();
							
							foreach($categories as $cat)
							{
									for($i=1;$i<7;$i++)
									{
										if( isset($_POST[$cat->category_title."feed_url".$i]) )
										{
												$this->_save_feed($_POST[$cat->category_title."feed_url".$i],
																			$cat->id,
																			$_POST[$cat->category_title."feed_url".$i],
																			isset($_POST[$cat->category_title."weight".$i])?1:0
																			);
											
										}									
									

									}												
							}
									
										$this->template->content->form_saved = true;
										$this->template->content->form_action = "Added / Updated"	;	
										
										url::redirect("/admin/feeds");
						}
						else
						{				
									// populate the error fields, if any
									$errors = arr::overwrite($errors, $post->errors('report'));
									$form_error = TRUE;
					
				  	}
				}
				
				$this->template->content->errors = $errors;
			 	$this->template->content->form_error = $form_error;
				$this->template->content->form_saved = false;
				$this->template->content->formfields = $form;
				// Count categories to determine column length
			
					
	}
	
	// STEP 2: SAVE Feed
	private function _save_feed($feed_url,$feed_category,$feed_name="none",$weight=0)
	{
				if(isset($feed_url) && $feed_url != '' && $feed_category > 0 )
				{
						$feed = new Feed_Model();
							//if unique url then create new else update old.
						$numItems = ORM::factory('feed')->where('feed_url',$feed_url)->count_all();
						if ($numItems > 0)	
						{					
								$dbfeed = ORM::factory('feed')->where('feed_url',$feed_url)->find_all();
								$feed	= $dbfeed[0];
						}	
												
						
						$feed->feed_name = $feed_name == "none" ? $feed_url :$feed_name ;
						$feed->feed_url = $feed_url;
						$feed->weight = (isset($weight) && $weight != 0 )? 100:0;
						$feed->category_id = $feed_category;	
						
									
						$feed->save();
						$send = notifications::notify_admins(
													"[".Kohana::config('settings.site_name')."] ".
														Kohana::lang('notifications.admin_new_report.subject'),
													Kohana::lang('notifications.admin_new_report.message')
														."\n\n'".strtoupper($feed->feed_name)."'"
														."\n".$feed->feed_url
															);
															
				
				}
	}
	
}
