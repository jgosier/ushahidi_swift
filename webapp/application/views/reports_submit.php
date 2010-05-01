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
						<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'reportForm', 'name' => 'reportForm', 'class' => 'gen_forms')); ?>
						<input type="hidden" name="latitude" id="latitude" value="<?php echo $form['latitude']; ?>">
						<input type="hidden" name="longitude" id="longitude" value="<?php echo $form['longitude']; ?>">
						<div class="big-block">
							<h1><?php echo Kohana::lang('ui_main.reports_submit_new'); ?></h1>
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

			
								<div class="report_optional">
									<h3><?php echo Kohana::lang('ui_main.reports_optional'); ?></h3>
									<div class="report_row">
											 <h4><?php echo Kohana::lang('ui_main.reports_first'); ?></h4>
											 <?php print form::input('person_first', $form['person_first'], ' class="text long"'); ?>
									</div>
									<div class="report_row">
										<h4><?php echo Kohana::lang('ui_main.reports_last'); ?></h4>
										<?php print form::input('person_last', $form['person_last'], ' class="text long"'); ?>
									</div>
									<div class="report_row">
										<h4><?php echo Kohana::lang('ui_main.reports_email'); ?></h4>
										<?php print form::input('person_email', $form['person_email'], ' class="text long"'); ?>
									</div>
									<!-- News Fields -->
									<div id="divNews" class="report_row">
										<h4><?php echo Kohana::lang('ui_main.reports_news'); ?></h4>
										<?php
											$this_div = "divNews";
											$this_field = "incident_news";
											$this_startid = "news_id";
											$this_field_type = "text";
											if (empty($form[$this_field]))
											{
												$i = 1;
												print "<div class=\"report_row\">";
												print form::input($this_field . '[]', '', ' class="text long2"');
												print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
												print "</div>";
											}
											else
											{
												$i = 0;
												foreach ($form[$this_field] as $value) {
												print "<div class=\"report_row\" id=\"$i\">\n";

												print form::input($this_field . '[]', $value, ' class="text long2"');
												print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
												if ($i != 0)
												{
													print "<a href=\"#\" class=\"rem\"	onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
												}
												print "</div>\n";
												$i++;
											}
										}
										print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
									?>
									</div>
									<!-- Video Fields -->
									<div id="divVideo" class="report_row">
										<h4><?php echo Kohana::lang('ui_main.reports_video'); ?></h4>
										<?php
											$this_div = "divVideo";
											$this_field = "incident_video";
											$this_startid = "video_id";
											$this_field_type = "text";

											if (empty($form[$this_field]))
											{
												$i = 1;
												print "<div class=\"report_row\">";
												print form::input($this_field . '[]', '', ' class="text long2"');
												print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
												print "</div>";
											}
											else
											{
												$i = 0;
												foreach ($form[$this_field] as $value) {
													print "<div class=\"report_row\" id=\"$i\">\n";

													print form::input($this_field . '[]', $value, ' class="text long2"');
													print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
													if ($i != 0)
													{
														print "<a href=\"#\" class=\"rem\"	onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
													}
													print "</div>\n";
													$i++;
												}
											}
											print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
										?>
									</div>

									<!-- Photo Fields -->
									<div id="divPhoto" class="report_row">
										<h4><?php echo Kohana::lang('ui_main.reports_photos'); ?></h4>
										<?php
											$this_div = "divPhoto";
											$this_field = "incident_photo";
											$this_startid = "photo_id";
											$this_field_type = "file";

											if (empty($form[$this_field]['name'][0]))
											{
												$i = 1;
												print "<div class=\"report_row\">";
												print form::upload($this_field . '[]', '', ' class="file long2"');
												print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
												print "</div>";
											}
											else
											{
												$i = 0;
												foreach ($form[$this_field]['name'] as $value) 
												{
													print "<div class=\"report_row\" id=\"$i\">\n";

													// print "\"<strong>" . $value . "</strong>\"" . "<BR />";
													print form::upload($this_field . '[]', $value, ' class="file long2"');
													print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
													if ($i != 0)
													{
														print "<a href=\"#\" class=\"rem\"	onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
													}
													print "</div>\n";
													$i++;
												}
											}
											print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
										?>

									</div>

									<div class="report_row">
										<input name="submit" type="submit" value="<?php echo Kohana::lang('ui_main.reports_btn_submit'); ?>" class="btn_submit" /> 
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
