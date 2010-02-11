<?php 
/**
 * Reports submit view page.
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
				<div id="content">
					<div class="content-bg">
						<!-- start report form block -->
						<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'feedForm', 'name' => 'feedForm', 'class' => 'gen_forms')); ?>
						<div class="big-block">
							<h1><?php echo Kohana::lang('ui_admin.feed_new_title'); ?></h1>
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
							?>
							<div style="border:0px solid #000">
								<div class="feed_row" style="padding:10px" >
									<span style="width:130px" ><?php echo Kohana::lang('ui_admin.feed_name'); ?></span>
									<?php print form::input('feed_name', $form['feed_name'], '  style="width:200px" '); ?>
								</div>
								<div class="feed_row" style="padding:5px">
									<span style="width:130px" ><?php echo Kohana::lang('ui_admin.feed_url'); ?></span>
									<?php print form::input('feed_url', $form['feed_url'], '  style="width:200px"  ') ?>
								</div>								
								<div class="feed_row" style="padding:5px">
									<span style="width:130px" ><?php echo Kohana::lang('ui_admin.feed_active'); ?></span>
									<?php print form::checkbox('feed_active', $form['feed_active'],' class="check-box"'); ?>									
								</div>
								<div class="feed_row" style="padding:5px">
									<span style="width:130px" ><?php echo Kohana::lang('ui_admin.feed_category'); ?></span>
									<?php
									//	form::dropdown($data, $options, $selected)
								  //	echo form::dropdown('feed_category[]', $categories, $category_checked, ' class="select"');							
			
										//format categories for 2 column display
											echo "<select name='feed_category' id='feed_category' style='width:200px' >";
											foreach ($categories as $category)
											{
												echo "\n<option value=".$category->id;
												if ($form['feed_category'] == $category->id)
												{
													 echo " selected='selected' ";
												}
													echo " >";
												//echo form::select('feed_category[]', $category, $category_checked, ' class="select"');
												echo $category->category_title;
												echo "<option>";				
											}
													print "</select>\n";
										?>
							
								</div>
								<div class="report_row" style="padding:5px">
									<input name="submit" type="submit" value="<?php echo Kohana::lang('ui_admin.feed_submit'); ?>" class="btn_submit" /> 
								</div>
							</div>

						</div>
						<?php print form::close(); ?>
						<!-- end report form block -->
					</div>
				</div>
			</div>
		</div>
	</div>
