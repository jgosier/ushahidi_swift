<?php
/**
 * Main view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Team Swift <jon@ushahidi.com> 
 * @package    Ushahidi - http://github.com/ushahidi/Swiftriver
 * @module     Admin Dashboard Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
 
 
?>

			<!-- main body -->
			<div id="main" class="clearingfix">
			<!-- begin main middle body -->	
			<div id="mainmiddle" class="floatbox withright">
				
			<!-- right column -->
						<div id="right" class="clearingfix">
					
						<!-- category filters -->
						<div class="cat-filters clearingfix">
								<strong><?php echo Kohana::lang('ui_main.category_filter');?></strong>
						</div>
						
							<ul class="category-filters">			
								<li><a  <?php  if ($selected_category == 0 )echo" class='active' " ; ?>  id="cat_0" href="<?php echo url::base() ; ?>main/index/category/0/page/1"><div class="category-title">ALL SOURCES</div></a></li>
								<?php
									foreach ($categories as $category => $category_info)
									{
										$setactive = $selected_category == $category? " class='active' " :"" ;
										$category_title = $category_info[0];
										$category_color = $category_info[1];
										echo '<li><a '.$setactive.' href="'.url::base().'main/index/category/'.$category.'/page/1/" id="cat_'. $category .'"><div class="swatch" style="background-color:#'.$category_color.'"></div><div class="category-title">'.$category_title.'</div></a></li>';
										// Get Children
										echo '<div class="hide" id="child_'. $category .'">';
										foreach ($category_info[2] as $child => $child_info)
										{
											$child_title = $child_info[0];
											$child_color = $child_info[1];
											echo '<li style="padding-left:20px;"><a href="#" id="cat_'. $child .'"><div class="swatch" style="background-color:#'.$child_color.'"></div><div class="category-title">'.$child_title.'</div></a></li>';
										}
										echo '</div>';
									}
								?>
							</ul>
							<!-- / category filters -->
					<div class="cat-filters clearingfix" style="Text-align:center;padding:20px;background: #CCCCCC;" >
							<form method="POST" name="veracityfrm" id="veracityfrm" action="<?php echo url::base().'main/veracity/'.$selected_category ; ?>" >

					<H5>VERACITY SLIDER</H5>		 
									<br/>			
					<div id="slider-range" ></div><br/><span style="color:#333333; font-weight:bold;">
					<input type="text"  name="veracity_min"  id="veracity_min" style="text-align:right; background-color:#CCCCCC;width:30px;border:0; color:#333333; font-weight:bold;" />%&nbsp;&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;&nbsp;
					<input type="text" name="veracity_max"  id="veracity_max" style="text-align:right; background-color:#CCCCCC;width:30px;border:0; color:#333333; font-weight:bold;" />%</span><br/><br/>
					<input type="image" src="<?php echo url::base() ;?>media/img/submit_btn.png" name="veracitysubmit" id="veracitysubmit" value="Submit" />
									
							</form>
					</div>		
							
							<br />
						
							<!-- Instruction Panel -->
							<div class="additional-content">
								<h5><?php echo Kohana::lang('ui_main.how_to_report'); ?></h5>
									Help us to separate signal from noise.<br/><br/>
								<ol>
									<li><strong>Register</strong> to become a <em>sweeper</em>.</li>
									<li>Tag content</li>
									<li>Flag inaccurate items</li>
									<li>Mark irrelevant Content as <em>Chatter</em></li>
									<li>Filter items by their <em>Veracity Score</em></li>
								<!--
									<?php if (!empty($twitter_hashtag_array)) 
												{ ?><li>By sending a tweet with the hashtag/s <?php foreach ($twitter_hashtag_array as $twitter_hashtag) {
									echo "<strong>". $twitter_hashtag ."</strong>";
									if ($twitter_hashtag != end($twitter_hashtag_array)) {
										echo " or ";
									}
									} ?></li><?php } ?>
									<li>By <a href="<?php echo url::base() . 'reports/submit/'; ?>">filling a form</a> at the website</li>
										-->
								</ol>								
							</div>
							<!-- End Instruction Panel, Additional Content -->					
						</div>
			<!-- / right column -->
					
			<!-- begin content column -->
			<div id="content" class="clearingfix">
			<!-- end filters -->
			<!-- Begin Content Tables -->
			<div>	
			<?php echo $feed_item_list ; ?>  
			</div>
			<!-- End Content Tables -->	
			<!-- End Pagination -->						
			<div style="align:bottom;">
						<?php echo $pagination; ?>
			</div>
			<!-- End Pagination -->	
			<!--	<a class="more" href="<?php echo url::base() . 'feeds' ?>">View More...</a> -->				
			<!--	<div id="graph" class="graph-holder"></div> -->
			</div>
			<!-- end content column -->
				
			</div>
			<!-- end main middle body -->
			</div>
			<!-- end main body -->
			
			<!-- content -->
			<div class="content-container">
			
					<!-- content blocks -->
					<div class="content-blocks clearingfix">
				
			<!-- left content block -->
			<div class="content-block-left">
							<h5><?php echo Kohana::lang('ui_main.incidents_listed'); ?></h5>
							<table class="table-list">
								<thead>
								<!--	<tr>
										<th scope="col" class="title"><?php echo Kohana::lang('ui_main.title'); ?></th>
										<th scope="col" class="location"><?php echo Kohana::lang('ui_main.location'); ?></th>
										<th scope="col" class="date"><?php echo Kohana::lang('ui_main.date'); ?></th>
									</tr> -->
								</thead>
								<tbody>
									<?php
	 								if ($feedcounts == 0)
									{
									?>
									<tr><td colspan="3">No Feeds In The System</td></tr>

									<?php
									}
									foreach ($feedsummary as $feedsum)
									{
											?>
									<tr>
										<td><a href="<?php echo $feedsum->feed_url; ?>" target="_BLANK"> <?php echo $feedsum->feed_name; ?></a></td>
										<td><?php echo $feedsum->total;  ?></td>
									</tr>
									<?php
									}
									?>
								</tbody>
							</table>
			</div>
			<!-- end left content block -->
				
			<!-- right content block -->
			<div class="content-block-right">
							<h5>ANALYTIC SUITE</h5>
							<table class="table-list">
									<?php
									foreach ($analyticSummary as $feedsum)
												{
														?>
												<tr>
												<td><h2> <?php echo $feedsum->count." of ".$feedsum->total."  ".$feedsum->title;  ?> </h2></td>
												</tr>
												<?php
												}		?>
								</table>
			</div>
			<!-- end right content block -->
				
					</div>
				
			
			</div>
			<!-- end content -->
		
			</div>
	</div>
	<!-- end major main body -->

	</div>
	<!-- end wrapper -->
