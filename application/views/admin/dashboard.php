<?php 
/**
 * Dashboard view page.
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
			<div class="bg">
				<h2><?php echo $title; ?></h2>
				<!-- column -->
				<div class="column">
					
					<!-- box -->
					<div class="box">
						<h3>Reports Timeline</h3>
						<ul class="inf" style="margin-bottom:10px;">
							<li class="none-separator">View:<a href="<?php print url::base() ?>admin/dashboard/?range=1">Today</a></li>
							<li><a href="<?php print url::base() ?>admin/dashboard/?range=31">Past Month</a></li>
							<li><a href="<?php print url::base() ?>admin/dashboard/?range=365">Past Year</a></li>
						</ul>
						<div class="chart-holder" style="clear:both;padding-left:5px;">
							<?php echo $report_chart; ?>	
							<?php if($failure != ''){ ?>
								<div class="red-box" style="width:400px;">
									<h3>Error!</h3>
									<ul><li><?php echo $failure; ?></li></ul>
								</div>
							<?php } ?>
						</div>
					</div>
					
					<!-- Incoming Media -->
					<div class="info-container">
						<div class="i-c-head">
								<h3>Incoming Media</h3>
								<ul>
									<li class="none-separator"><a href="<?php echo url::base() . 'admin/manage/feeds' ?>">View All</a></li>
									<li><a href="#" class="rss-icon">rss</a></li>
								</ul>
							</div>
							<?php
							foreach ($feeds as $feed)
							{
								$feed_id = $feed->id;
								$feed_title = $feed->item_title;
								$feed_description = text::limit_chars(strip_tags($feed->item_description), 150, '...', True);
								$feed_link = $feed->item_link;
								$feed_date = date('M j Y', strtotime($feed->item_date));
								$feed_source = "NEWS";
								?>
								<div class="post">
									<h4><a href="<?php echo $feed_link; ?>" target="_blank"><?php echo $feed_title ?></a></h4>
									<em class="date"><?php echo $feed_source; ?> - <?php echo $feed_date; ?></em>
									<p><?php echo $feed_description; ?></p>
								</div>
								<?php
							}
							?>
							<a href="<?php echo url::base() . 'admin/manage/feeds' ?>" class="view-all">View All Incoming Media</a>
					</div>
				</div>
				<div class="column-1">
					<!-- Begin Quick Stats -->
					<div class="box">
						<h3>Quick Stats</h3>
						<ul class="nav-list">
							<li>
								<a href="<?php echo url::base() . 'admin/manage/feeds' ?>" class="media">Monitored Feeds</a>
								<strong><?php echo $incoming_media; ?></strong>
							</li>
							<li>
								<a href="<?php echo url::base() . 'admin/manage' ?>" class="categories">Channels</a>
								<strong><?php echo $categories; ?></strong>
							</li>
							<li>
								<a href="#" class="locations">Locations</a>
								<strong><?php echo $locations; ?></strong>
							</li>
							<li>
								<a href="<?php echo url::base() . 'admin/messages' ?>" class="messages">Messages</a>
								<strong><?php echo $message_count; ?></strong>
								<ul>
									<?php
									foreach ($message_services as $service) {
										echo "<li><a href=\"".url::base() . 'admin/messages/index/'.$service['id']."\">".$service['name']."</a><strong>(".$service['count'].")</strong></li>";
									}
									?>
								</ul>
							</li>
						</ul>
					</div>
					<!-- End Quick Stats -->
			</div>

