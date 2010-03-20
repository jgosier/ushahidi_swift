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

class Taggedfeeds_Controller extends Main_Controller {

    public function __construct()
    {
        parent::__construct();	

    }
    
    
   
/**
This is the index function called by default.

*/
    public function index($page,$page_no,$tagname="",$tagtext = "")
    {		
        $this->template->header->this_page = 'Tags';
        $this->template->content = new View('taggedfeeds');
        
        $this->template->content->auth = null;
        
       if(isset( $_SESSION['auth_user']))
       {
         $this->template->content->auth = $_SESSION['auth_user'] ;
			 }
					
					
        // Get all active unique tags.
       $db = new Database();
			 $taglist = $db->query(" SELECT tags,count(id) as number FROM `tags`
			 												WHERE correct_yn = 1  AND
															 		((tablename = 'message' AND tagged_id IN (SELECT id FROM message WHERE submited_to_ushahidi = 0 ))
															 		OR (tablename = 'feed_item' AND tagged_id IN (SELECT id FROM feed_item WHERE submited_to_ushahidi = 0 )))
															   GROUP BY tags ORDER BY tags ");
       $this->template->content->taglist = $taglist;

		//remember whick tag we are listing.
			$this->template->content->tagtext = $tagtext	;

	// Filter By Category
		  $categoryYes = ( isset($tagtext) && !empty($tagtext) && $tagtext != '' );		
		  $tag_filter = $categoryYes	? 
			" INNER JOIN  tags t ON t.tagged_id = f.id AND t.tablename IN ('feed_item','message') AND t.correct_yn = 1 AND t.tags='".$tagtext."'  ":"";	  		
		  

		
		$sql = "	SELECT 
												f.id as id,
												item_title,
												item_description,
												item_link,												
												item_date, 
										 		a.weight as weight,
										 		a.feed_name as item_source,
										 		a.category_id as category_id,
										 		'feed_item' as tablename
												FROM feed_item f 
														 INNER JOIN feed a ON f.feed_id = a.id ".$tag_filter.
											"	WHERE f.submited_to_ushahidi = 0 
							UNION
				 	    SELECT 
											 f.id as id,
											 f.message as item_title,
											 f.message as item_description,
											 CASE r.service_id 
			 									WHEN 3 THEN CONCAT('http://twitter.com/',f.message_from,'/statuses/',f.service_messageid)
			 									ELSE '#'
			 									END as item_link,
											 f.message_date as item_date,
											 r.weight as weight,
											 f.message_from as item_source,
											 CASE r.service_id  WHEN 1 THEN 2 WHEN 2 THEN 10 ELSE 11 END as category_id,
											 'message' as tablename
											FROM message f  
													INNER JOIN reporter r ON r.id = f.reporter_id ".$tag_filter.
											"	WHERE  f.submited_to_ushahidi = 0 " ;											
		
			$sql .= " ORDER BY item_date desc ";	
	
			$db=new Database;
		  $Feedcounts1 =	$db->query(" SELECT count(f.id)as Total FROM message f INNER JOIN reporter r ON r.id = f.reporter_id ".$tag_filter);
			$Feedcounts2 =	$db->query("select count(f.id)as Total FROM feed_item f INNER JOIN feed a ON f.feed_id = a.id  ".$tag_filter);
	
			$Feedscount = 	$Feedcounts1[0]->Total + $Feedcounts2[0]->Total	;
					  
			$numItems_per_page =  Kohana::config('settings.items_per_page');
		
			$pagination = new Pagination(array(
				'base_url' => '/main/index/category/'.$tagtext ,
				'uri_segment' => 'page',
				'items_per_page' => (int) $numItems_per_page,
				'style' => 'digg',
				'total_items' => $Feedscount
				));
				

		
	  $Feedlist = $db->query($sql." Limit ".$numItems_per_page*($page_no - 1) ." , ".$numItems_per_page);
		// Get RSS News Feeds

		$this->template->content->current_page = $page_no;
					
			  // Get Summary
        // XXX: Might need to replace magic no. 8 with a constant
        $this->template->content->feedcounts = $Feedscount;        
        
        $feed_summary_sql = " SELECT fi.feed_name as feed_name ,
																CASE	fi.category_id
																	WHEN 1 THEN	concat('http://twitter.com/statuses/user_timeline/',fi.feed_url,'.rss') 
																	ELSE fi.feed_url
																END  as feed_url
																,count(fi.id) as total 
															FROM `feed` fi INNER JOIN feed_item f ON f.feed_id = fi.id ".$tag_filter."
															WHERE f.submited_to_ushahidi = 0  GROUP BY fi.feed_name ,feed_url
															UNION 
															SELECT  twitter_hashtags as feed_name, concat('http://twitter.com/search?q=', REPLACE(replace(s.twitter_hashtags,'#',''),',',' ' )) as 
															feed_url ,count(f.id) as total
															FROM settings s , message f ".$tag_filter." WHERE f.submited_to_ushahidi = 0  Group BY feed_name ,feed_url ";

		$this->template->content->feedsummary = $db->query($feed_summary_sql);
		
		$AnalyicQuery = " 
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
		$this->template->content->selected_category = $tagtext;
		$feedjs = new View('feed_functions_js');
		
		// Pack the javascript using the javascriptpacker helper		
		
		$this->template->header->js2 = $feedjs;
		
		//feed item content.
		$feed_item_template	= new View('feed_item');
		$this->template->content->feed_item_list = $feed_item_template; 
		$this->template->content->feed_item_list->feeds = $Feedlist; 

	}
	
	
} // End Main
