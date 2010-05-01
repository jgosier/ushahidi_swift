<?php 
/**
 * Feeds $form page.
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
				<h2>
					<a href="<?php echo url::base() . 'admin/manage' ?>">Categories</a>
					<a href="<?php echo url::base() . 'admin/manage/forms' ?>">Forms</a>
					<a href="<?php echo url::base() . 'admin/manage/organizations' ?>">Organizations</a>
					<a href="<?php echo url::base() . 'admin/manage/pages' ?>">Pages</a>
					<a href="<?php echo url::base() . 'admin/feeds' ?>" class="active">News Feeds</a>
					<span>(<a href="#add">Add New</a>)</span>
					<a href="<?php echo url::base() . 'admin/manage/layers' ?>">Layers</a>
					<a href="<?php echo url::base() . 'admin/manage/reporters' ?>">Reporters</a>
				</h2>
				
				<!-- tabs -->
				<div class="tabs">
					<!-- tabset -->
					<ul class="tabset">
						<li><a href="<?php echo url::base() . 'admin/feeds' ?>" class="active">Feeds</a></li>
						<li><a href="<?php echo url::base() . 'admin/manage/feeds_items' ?>">Feed Items</a></li>
					</ul>
					
					<!-- tab -->
					<div class="tab">
						<ul>
							<li><a href="javascript:refreshFeeds();">REFRESH NEWS FEEDS</a></li><span id="feeds_loading"></span>
						</ul>
					</div>
				</div>
				<?php
				if ($form_error) {
				?>
					<!-- red-box -->
					<div class="red-box">
						<h3>Error!</h3>
						<ul>
						<?php
						foreach ($errors as $error_item => $error_description)
						{
							// print "<li>" . $error_description . "</li>";
							print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
						}
						
						
						?>						
						
						
						</ul>
					</div>
				<?php
				}

				if ($form_saved) {
				?>
					<!-- green-box -->
					<div class="green-box">
						<h3>The Feeds Have Been <?php echo $form_action; ?>!</h3>
					</div>
				<?php
				}
				?>
				<!-- report-table -->
				<div class="report-form">
				 		<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'feedForm', 'name' => 'feedForm', 'class' => 'gen_forms')); ?>
						<input type="hidden" name="action" id="action" value="">
						<input type="hidden" name="feed_id" id="feed_id_action" value="">
						<div class="table-holder">
							<table class="tables" border=0>
								<thead>
									<tr>
										<th width="40%" ></th>
										<th width="40%" ></th>
										<th width="20%" ></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td  ><h4>ADD TWITER NAMES<div style="float:right">&nbsp;&nbsp;&nbsp;&nbsp; </div></h4></th>
										<td ><h4>ADD BLOG FEEDS<div style="float:right">T&nbsp;&nbsp;&nbsp;&nbsp; </div></h4></th>
										<td  ><h4>SET EMAIL ADDRESS</h4></th>
									</tr>
									<?php
											
											for( $i = 1 ; $i < 7 ;$i++)
											{
										?>
										<tr>
											<td  >
												<?php $cat = "TWITTER"; ?>
															<input type="hidden" name="<?php echo $cat."ID".$i; ?>" 
																					id="<?php echo $cat."ID".$i; ?>" 
																					value="<?php echo $form[$cat.'ID'.$i] ?>" />
												<?php			print form::input($cat.'feed_url'.$i,$form[$cat.'feed_url'.$i] , '  class="text long3" '); ?>
												<?php print form::checkbox($cat.'weight'.$i,$form[$cat.'weight'.$i], $form[$cat.'weight'.$i] >= 100?TRUE:FALSE,' class="check-box"'); ?>											
												<input type="hidden" name="<?php echo $cat."weight_hf".$i; ?>" 
																					id="<?php echo $cat."weight_hf".$i; ?>" 
																					value="<?php echo $form[$cat.'weight'.$i] ?>" />
											</td>	
											<td   >
												<?php $cat = "BLOGS"; ?>
													<input type="hidden" name="<?php echo $cat."ID".$i; ?>" 
																					id="<?php echo $cat."ID".$i; ?>" 
																					value="<?php echo $form[$cat.'ID'.$i] ?>" />
												<?php		print form::input($cat.'feed_url'.$i,$form[$cat.'feed_url'.$i] , '   class="text long3" '); ?>
												<?php print form::checkbox($cat.'weight'.$i,$form[$cat.'weight'.$i], $form[$cat.'weight'.$i] >= 100?TRUE:FALSE,' class="check-box"'); ?>			
													<input type="hidden" name="<?php echo $cat."weight_hf".$i; ?>" 
												id="<?php echo $cat."weight_hf".$i; ?>" 
												value="<?php echo $form[$cat.'weight'.$i] ?>" />
										
											</td>
											<td>
														<?php  
																	
																	if ($i == 1 || $i == 5)
																	{
																		$cat = $i == 1?"EMAIL":"SMS";
																		
																		//Note the first value of phone number was assigned to id 1 from the start.
																		print form::input($cat,$form[$cat] , '   class="text long3" ');	
																	}
																	if ($i == 4 )
																	{
																		echo "<H4>SET PHONE NUMBER</H4>";
																	}
														?>									
											
											 </td>
										</tr>
											<?php } ?>	
										<tr>
										<td ><br/><h4><div style="float:right">T&nbsp;&nbsp;&nbsp;&nbsp; </div>ADD NEWS FEEDS</h4></th>
										<td valign=bottom><br/><h4><div style="float:right;padding-right:20px">Update<br/> frequency<br/>(Minutes)</div>OTHER FEED SOURCES</h4></th>
										<td  ><br/><h4>ADD TWITER SEARCH<!--<div style="float:right">T&nbsp;&nbsp;&nbsp;&nbsp; </div>--><!--SMS --></h4></th>
									</tr>
									<?php 
											for( $i = 1 ; $i < 7 ;$i++)
											{ ?>
									
										<tr>
											<td  >
														
															<?php $cat = "NEWS"; ?>
																<input type="hidden" name="<?php echo $cat."ID".$i; ?>" 
																								id="<?php echo $cat."ID".$i; ?>" 
																								value="<?php echo $form[$cat.'ID'.$i] ?>" />
															<?php
																		print form::input($cat.'feed_url'.$i,$form[$cat.'feed_url'.$i] , '   class="text long3" '); ?>
															<?php print form::checkbox($cat.'weight'.$i,$form[$cat.'weight'.$i], $form[$cat.'weight'.$i] >= 100?TRUE:FALSE,' class="check-box"'); ?>	
																<input type="hidden" name="<?php echo $cat."weight_hf".$i; ?>" 
																id="<?php echo $cat."weight_hf".$i; ?>" 
																value="<?php echo $form[$cat.'weight'.$i] ?>" />			
											
											</td>																						
											<td  >
													<?php //This update period is at the bottom on the in the browser just a note.
													 			$cat = "OTHERS";  ?>
														<DIV style="float:right;padding-right:10px;">
								                 <?php $selection = array('1'=>'1','15'=>'15','30'=>'30','45'=>'45','60'=>'60');
														 		 print form::dropdown($cat."updatePeriod".$i, $selection, $form[$cat."updatePeriod".$i] ); ?>
														 <?php		if($form[$cat."ID".$i] != '0') { ?>
															  <a id="RemoveChannel" href='<?php echo url::base()."/admin/feeds/Removefeed/".$form[$cat."ID".$i]; ?>' class='tagged'  title='remove channel' >
																 <img src="<?php echo url::base(); ?>/media/img/x_btn.png" alt="X" align="absmiddle" style="border:0" width="18" />
																</a>
														<?php } else { ?> &nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>		
														</DIV>
														<?php
															if(!empty($form[$cat.'feed_url'.$i])){
																	$tempfeedurl = text::limit_chars($form[$cat.'feed_url'.$i],40,'...',False);
																  $tempfeedurl = text::limit_words($tempfeedurl, 1, '...', True);															
																	echo $tempfeedurl ;
															}else{ ?>
														<?php		print form::input($cat.'feed_url'.$i,$form[$cat.'feed_url'.$i] , '   class="text long3" '); ?>
													<!--	<?php print form::checkbox($cat.'weight'.$i,$form[$cat.'weight'.$i], $form[$cat.'weight'.$i] >= 100?TRUE:FALSE,' class="check-box"'); ?>	
														-->	<?php } ?>
													<input type="hidden" name="<?php echo $cat."weight_hf".$i; ?>" 
														id="<?php echo $cat."weight_hf".$i; ?>" 
														value="<?php echo $form[$cat.'weight'.$i] ?>" />
										</td>
										<td  >
												<?php $cat = "hashtag"; ?>
												<?php		print form::input($cat.$i,$form[$cat.$i] , '   class="text long3" '); ?>
												<!--	<?php //print form::checkbox($cat.'weight'.$i,$form[$cat.'weight'.$i], $form[$cat.'weight'.$i]== 1?TRUE:FALSE,' class="check-box"'); ?>	-->
										</td>
										</tr>
										<?php
									}
									?>
									<tr><td></td><td></td><td>
									<div class="tab_form_item">
							&nbsp;<br />
							<input type="image" src="<?php echo url::base() ?>media/img/admin/btn-save.gif" class="save-rep-btn" />
						</div>
						<?php print form::close(); ?>	
						</td></tr>
								</tbody>
							</table>
						</div>
					<?php print form::close(); ?>
				</div>
				
			</div>
