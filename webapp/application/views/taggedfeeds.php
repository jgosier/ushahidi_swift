<?php
/**
 * Tagged feed view page.
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
			<!-- begin main middle body -->	
			<div id="mainmiddle" class="floatbox withright">
				
			<!-- right column -->
						<div id="right" class="clearingfix">
					
						<!-- category filters -->
						<div class="cat-filters clearingfix">
								<strong>List of Tags</strong>
						</div>
						
							<P > <!--class="category-filters" -->			
							<?php
									foreach ($taglist as $tag)
									{										
										echo '<strong><a  href="'.url::base().'taggedfeeds/index/page/1/tag/'.$tag->tags.'/" >
										'.$tag->tags.'</strong></a>('.$tag->number.')&nbsp;&nbsp;';								
									}
								?>
							</P>
							<!-- / category filters -->

							
							<br />
	
						</div>
			<!-- / right column -->
					
			<!-- begin content column -->
			<div id="content" class="clearingfix">
			<!-- end filters -->
			<!-- Begin Content Tables -->
			<div>	
			<h2>Feeds with tag : <?php echo $tagtext ;?> </h2>

				<!-- place holder for feed items -->
				<?php echo $feed_item_list ; ?>	
										<!-- End Pagination -->						
			
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