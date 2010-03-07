<?php
/**
 * Main view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Admin Dashboard Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General
 * Public License (LGPL)
 */
 
 
?>

				<!-- main body -->
				<div id="main" class="clearingfix">
					<div id="mainmiddle" class="floatbox withright">
				
						<!-- right column -->
						<div id="right" class="clearingfix">
					
							<!-- category filters -->
							<div class="cat-filters clearingfix">
								<strong><?php echo Kohana::lang('ui_main.category_filter');?></strong>
							</div>
						
							<ul class="category-filters">
								
								<li><a  <?php	if ($selected_category == 0 )echo" class='active' " ; ?>  id="cat_0" href="/main/index/category/0/page/1"><div class="swatch" style="background-color:#<?php echo $default_map_all;?>"></div><div class="category-title">ALL SOURCES</div></a></li>
								<?php
									foreach ($categories as $category => $category_info)
									{
										$setactive = $selected_category == $category? " class='active' " :"" ;
										$category_title = $category_info[0];
										$category_color = $category_info[1];
										echo '<li><a '.$setactive.' href="/main/index/category/'.$category.'/page/1/" id="cat_'. $category .'"><div class="swatch" style="background-color:#'.$category_color.'"></div><div class="category-title">'.$category_title.'</div></a></li>';
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
							
							<?php
							if ($layers)
							{
								?>
								<!-- Layers (KML/KMZ) -->
								<div class="cat-filters clearingfix" style="margin-top:20px;">
									<strong><?php echo Kohana::lang('ui_main.layers_filter');?></strong>
								</div>
								<ul class="category-filters" >
									<?php
									foreach ($layers as $layer => $layer_info)
									{
										$layer_name = $layer_info[0];
										$layer_color = $layer_info[1];
										$layer_url = $layer_info[2];
										$layer_file = $layer_info[3];
										$layer_link = (!$layer_url) ?
											url::base().'media/uploads/'.$layer_file :
											$layer_url;
										echo '<li><a href="#" id="layer_'. $layer .'"
										onclick="switchLayer(\''.$layer.'\',\''.$layer_link.'\',\''.$layer_color.'\'); return false;"><div class="swatch" style="background-color:#'.$layer_color.'"></div>
										<div>'.$layer_name.'</div></a></li>';
									}
									?>
								</ul>
								<!-- /Layers -->
								<?php
							}
							?>
								<div class="cat-filters clearingfix" style="Text-align:center;padding:20px;background: #CCCCCC;" >
								<form method="POST" name="verocityfrm" id="verocityfrm" action="<?php echo url::base().'main/verocity/'.$selected_category ; ?>" >

										<H5 >VERACITY SLIDER</H5>		 
									<br/>			
									<div id="slider-range" ></div><br/><span style="color:#f6931f; font-weight:bold;">
										<input type="text"  name="verocity_min"  id="verocity_min" style="text-align:right; background-color:#CCCCCC;width:30px;border:0; color:#f6931f; font-weight:bold;" />%&nbsp;&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name="verocity_max"  id="verocity_max" style="text-align:right; background-color:#CCCCCC;width:30px;border:0; color:#f6931f; font-weight:bold;" />%</span><br/><br/>
										<input class="" type="submit" name="verocitysubmit" id="verocitysubmit" value=" Filter "/>
									
									</form>
								</div>		
							
							<br />
						
							<!-- additional content -->
							<div class="additional-content">
								<h5><?php echo Kohana::lang('ui_main.how_to_report'); ?></h5>
									Help Ushahidi to seperate signal from noice.<br/><br/>
								<ol>
									<?php if (!empty($phone_array)) 
									{ ?><li>Add or Click on tags to report accuracy<!-- <?php foreach ($phone_array as $phone) {
										echo "<strong>". $phone ."</strong>";
										if ($phone != end($phone_array)) {
											echo " or ";
										}
									} ?>-->
									</li><?php } ?>
									<li>Dispute Inaccuracies</li>
									<li>Mark irrelevant content as 'chatter'</li>
									<?php if (!empty($report_email)) 
									{ ?><li>Email reports to <a href="mailto:<?php echo $report_email?>"><?php echo $report_email?></a></li><?php } ?>
									<li>What to do more? <strong>Register</strong></li> to become a sweeper.
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
							<!-- / additional content -->
					
						</div>
						<!-- / right column -->
					
						<!-- content column -->
						<div id="content" class="clearingfix">
							<div class="floatbox">
							
								<!-- filters -->
			
								</div>
								<!-- / filters -->
								<div>
									<table class="table-list">
										<!--<thead>
											<tr>
												<th scope="col"><?php echo Kohana::lang('ui_main.title'); ?></th>
												<th scope="col"><?php echo Kohana::lang('ui_main.source'); ?></th>
												<th scope="col"><?php echo Kohana::lang('ui_main.date'); ?></th>
											</tr>
										</thead> -->
										<tbody>
											<?php
											
											foreach ($feeds as $feed)
											{
												$feed_id = $feed->id;
												$feed_title = text::limit_chars($feed->item_title, 40, '...', True);
												$feed_link = $feed->item_link;
												$feed_date = date('M j Y h:m', strtotime($feed->item_date));
												//$feed_source = text::limit_chars($feed->feed->item_name, 15, "...");
											?>
											<tr>
												<td  id="feed_row_<?php echo $feed_id ;?>" >
												<?php if(isset($_SESSION['auth_user'])){ ?>
													<a href="javascript:submitfeed_to_ushahidi('<?php echo $feed_id ;?>','<?php echo $feed->category_id ; ?>')"  >
												<?php } ?>
													<div style="padding:5px;width:35px;height:45px;border:1px solid #660033;Text-align:center; -moz-border-radius: 5px; -webkit-border-radius: 5px;">
												  	<img src="<?php echo url::base(); ?>/media/img/rssdark.png" alt="<?php echo $feed_title ?>" align="absmiddle" style="border:0" />
													<br/> 
													<span style="font-weight:bold;color:#660033">
															<label id="weight_<?php echo $feed_id; ?>" name="weight_<?php echo $feed_id; ?>" >
																	<?php if ($feed->weight == 0.00){ echo "_" ;}else{ echo round($feed->weight,0 )."%"; } ?>
															</label>
													</span>
													 </div>
													 <?php if(isset($_SESSION['auth_user'])){ ?>
													 		</a>
													 <?php } ?>
												</td>
												<td style="border-bottom:2px solid #AAAAAA;"   id="feed_row_<?php echo $feed_id ;?>" >
												 <?php echo $feed->item_description ;?>  ...
															<br/>
													<strong>Delivered: </strong> <?php echo $feed->item_date ; /*$testDate;*/ ?>&nbsp;&nbsp;&nbsp; 
													<strong>Source: </strong><?php echo $feed->item_source; ?>   <br/>
													<!-- to displace status of sumitted feed to ushahidi -->
													<label id="lblreport_<?php echo $feed_id; ?>" name="lblreport_<?php echo $feed_id; ?>" >
													</label>
																										
													 <form id="formtag<?php echo $feed_id ;?>" name="formtag<?php echo $feed_id ;?>"  method="POST" action="/main/tagging/feed/<?php echo $feed_id ; ?>/category/<?php echo $selected_category ;?>/page/<?php echo $current_page ; ?>" >
													 <a href="javascript:submit_tags('<?php echo $feed_id ;?>')" >
													 <img src="<?php echo url::base(); ?>/media/img/Tagbtn.png" alt="<?php echo $feed_title ?>" align="absmiddle" style="border:0" />
													 </a>
													 <input type=text id="tag_<?php echo $feed_id; ?>"  name="tag_<?php echo $feed_id; ?>" value="" />&nbsp;&nbsp;
													 	<label id="lbltags_<?php echo $feed_id; ?>" name="lbltags_<?php echo $feed_id; ?>" >
														  <?php echo $feed->tags; ?>	
														</label>												 
													 </form>
													 <?php if(isset($_SESSION['auth_user'])){ ?>
													 <div style="float:right">
													 <a href="<?php echo $feed->item_link; ?>" target="_blank">

													 <img src="<?php echo url::base(); ?>/media/img/page_icon.jpg" alt="<?php echo $feed_title ?>" align="absmiddle" style="border:0" />
													 </a>
													<a href="javascript:increment_feed_rating('<?php echo $feed_id ;?>','<?php echo $feed->category_id ; ?>')" > 
														<img src="<?php echo url::base(); ?>/media/img/swift_page_icon.jpg" alt="<?php echo $feed_title ?>" align="absmiddle" style="border:0" />
													 </a>
													<a href="javascript:decrement_feed_rating('<?php echo $feed_id ;?>','<?php echo $feed->category_id ; ?>')" > 
													  <img src="<?php echo url::base(); ?>/media/img/no_entry_icon.jpg" alt="<?php echo $feed_title ?>" align="absmiddle" style="border:0" />
													</a>
												<!--	<a href="javascript:mark_irrelevant('<?php echo $feed_id ;?>','<?php echo $feed->category_id ; ?>')" > 
													 <img src="<?php echo url::base(); ?>/media/img/qtnmark.jpg" alt="<?php echo $feed_title ?>" align="absmiddle" style="border:0" />
													</a> --> 
													 </div>
													 <?php } ?>
													 
													</td>
											</tr>
											<?php
											}
											?>
										</tbody>
									</table>
									<div style="align:bottom;">
									<?php echo $pagination; ?>
									</div>
									<!--<a class="more" href="<?php echo url::base() . 'feeds' ?>">View More...</a> -->
							</div>
								
							<!--	<div id="graph" class="graph-holder"></div> -->
							</div>
						</div>
						<!-- / content column -->
				
					</div>
				</div>
				<!-- / main body -->
			
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
						<!-- / left content block -->
				
						<!-- right content block -->
						<div class="content-block-right">
							<h5>ANALYTIC SUITE</h5>
							<table class="table-list">
									<?php
									foreach ($analyticSummary as $feedsum)
												{
														?>
												<tr>
												<td><h3> <?php echo $feedsum->count." of ".$feedsum->total."  ".$feedsum->title;  ?> </h3></td>
												</tr>
												<?php
												}		?>
								</table>
						</div>
						<!-- / right content block -->
				
					</div>
				
			
				</div>
				<!-- content -->
		
			</div>
		</div>
		<!-- / main body -->

	</div>
	<!-- / wrapper -->
