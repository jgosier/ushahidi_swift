<?php
/**
 * Feedback Forms js file.
 *
 * Handles javascript stuff related to feedback function.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
function mark_tag_false(tagid,feedid)
{
		var tag = document.getElementById('tag_'+feedid);
	  var theurl	= '/main/Ajax_mark_tag_false/'+tagid+'/'+feedid ; 
		tag.value = "";
		showtags(feedid,theurl);
}
function showtags(feedid,theurl)
{
 				$.ajax( //ajax request starting
				 	{
		       url: theurl, //send the ajax request 
           type:"POST",//request is a POSt request
		       dataType: "json",//expect json as 
		       success: function(data) //trigger this on success
			   	 {
				   		document.getElementById('lbltags_'+feedid).innerHTML = data['tags'];
				   }			   
		    });		 
}
function submit_tags(id)
{  
	  var tag = document.getElementById('tag_'+id);
	  var theurl	= '/main/Ajax_tagging/'+id+'/'+tag.value; 
		tag.value = "";
		showtags(id,theurl);	     
	}

function submitfeed_to_ushahidi(id,cat)
{  
	   var theurl	= '/main/submit_report_via_API/'+id+'/'+cat; 		 	  
			    $.ajax( //ajax request starting
				 	{
		       url: theurl, //send the ajax request to student/delete/$id
           type:"POST",//request is a POSt request
		       dataType: "json",//expect json as 
		       success: function(data) //trigger this on success
			   	 {  //in the future this is suposed to make the this feed this disappear.
				   		document.getElementById('lblreport_'+id).innerHTML = data['message'];
				   		if(typeof data['weight'] != "undefined" )//typeof x=="undefined"
						 	{	
								 document.getElementById('weight_'+id).innerHTML = data['weight']+'%';
						 	}
							 disable_feed_links(id);
				   }			   
		    });			
}

function disable_feed_links(id)
{
		document.getElementById('feed_link_'+id).setAttribute('href','#');
		document.getElementById('irrelevant_link_'+id).setAttribute('href','#');
		document.getElementById('increase_ratting_link_'+id).setAttribute('href','#');
		document.getElementById('reduce_ratting_link_'+id).setAttribute('href','#');
}
function increment_feed_rating(id,cat)
{  
	   var theurl	= '/main/increment_source_rating/'+id+'/'+cat; 	  
		change_feed(id,theurl);    
}
	
function decrement_feed_rating(id,cat)
{  
	   var theurl	= '/main/decrement_source_rating/'+id+'/'+cat; 	
		 change_feed(id,theurl);  			  
}
function change_feed(id,theurl)
{
	  $.ajax( //ajax request starting
				 	{
		       url: theurl, //send the ajax request to student/delete/$id
           type:"POST",//request is a POSt request
		       dataType: "json",//expect json as 
		       success: function(data) //trigger this on success
			   	 {
				   		document.getElementById('weight_'+id).innerHTML = data['weight']+'%';
				   }			   
		    });		   
}

function mark_irrelevant(id,cat)
{  
	   var theurl	= '/main/mark_irrelevant/'+id+'/'+cat+'/'; 	  
			   $.ajax( //ajax request starting
				 	{
		       url: theurl, //send the ajax request to student/delete/$id
           type:"POST",//request is a POSt request
		       dataType: "json",//expect json as 
		       success: function(data) //trigger this on success
			   	 {
				   		document.getElementById('lblreport_'+id).innerHTML = data['message']; 	
				   		disable_feed_links(id);
				   }			   
		    });		    
	}
	
	
