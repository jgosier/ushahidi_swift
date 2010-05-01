<?php defined('SYSPATH') or die('No direct script access.');

/**
 * This controller is used to list/ view and feeds reports
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   Reports Controller  
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Feeds_Controller extends Main_Controller {

	function __construct()
	{
		parent::__construct();

		// Javascript Header
		$this->template->header->validator_enabled = TRUE;
	}

/** #########  Apala required changes ########## 
		settings should come for api the lines below	in the function index()
		
*/
		
	/**
	 * Displays all reports.
	 */
	public function index() 
	{
		$this->template->header->this_page = 'feeds';
		$this->template->content = new View('feeds');
		
		// Pagination
/** #########  Apala required changes ########## DB Call below. ::factory('feed_item')*/
		$pagination = new Pagination(array(
                      'query_string' => 'page',
                      'items_per_page' => (int) Kohana::config('settings.items_per_page'),
                      'total_items' => ORM::factory('feed_item')
                                       ->count_all()
                      ));
/** #########  Apala required changes ########## DB Call below.  ::factory('feed_item')*/
		$feeds = ORM::factory('feed_item')
                     ->orderby('item_date', 'desc')
                     ->find_all( (int) Kohana::config('settings.items_per_page'), 
                                 $pagination->sql_offset);
		
		$this->template->content->feeds = $feeds;
		
		//Set default as not showing pagination. Will change below if necessary.
		$this->template->content->pagination = ''; 
		
		// Pagination and Total Num of Report Stats
		if($pagination->total_items == 1)
		{
			$plural = '';
		}
		else
		{
			$plural = 's';
		}
		if ($pagination->total_items > 0)
		{
			$current_page = ($pagination->sql_offset/ (int) Kohana::config('settings.items_per_page')) + 1;
			$total_pages = ceil($pagination->total_items/ (int) Kohana::config('settings.items_per_page'));
			
			if($total_pages > 1)
			{ // If we want to show pagination
				$this->template->content->pagination_stats = '(Showing '
                     .$current_page.' of '.$total_pages
                     .' pages of '.$pagination->total_items.' feeds'.$plural.')';
				
                $this->template->content->pagination = $pagination;
			}
			else
			{ // If we don't want to show pagination
				$this->template->content->pagination_stats = '('.$pagination->total_items.' feed'.$plural.')';
			}
		}
		else
		{
			$this->template->content->pagination_stats = '('.$pagination->total_items.' feed'.$plural.')';
		}
		
	} 
	
} // End Reports
