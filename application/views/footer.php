<?php 
/**
 * Footer view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Team Swift <jon@ushahidi.com>  
 * @package    Ushahidi - http://github.com/ushahidi/Swiftriver
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
     
 
	<!-- footer -->
	<div id="footer" class="clearingfix">
 
		<div id="underfooter"></div>
 
		<!-- footer content -->
		<div class="rapidxwpr floatholder">
 
			<!-- footer credits -->
			<div class="footer-credits">
				Powered by <a href="http://www.ushahidi.com/"><img src="<?php echo url::base(); ?>/media/img/footer-logo.png" alt="Ushahidi" align="absmiddle" /></a>
			</div>
			<!-- / footer credits -->
		
			<!-- footer menu -->
			<div class="footermenu">
				<ul class="clearingfix">
					<li><a class="item1" href="<?php echo url::base() ?>"><?php echo Kohana::lang('ui_main.home'); ?></a></li>
					<li><a href="<?php echo url::base() . "reports/submit" ?>"><?php echo Kohana::lang('ui_main.report_an_incident'); ?></a></li>
					<li><a href="http://swift.ushahidi.com"><?php echo Kohana::lang('ui_main.about'); ?></a></li>
					<li><a href="http://ushahidi.com/contact" title="contact Swiftriver" >Contact</a></li>
					<li><a href="http://blog.ushahidi.com/index.php/category/swift-river/" title="our blog" >Swift River Blog</a></li>
					<li><a href="http://groups.google.com/group/swiftriver" title="support forums" >Support</a></li>
					<li><a href="http://swift.ushahidi.com/extend/" title="extending swift" >Extend</a></li>
					<li><a href="http://http://github.com/ushahidi/Swiftriver" title="github" >Github</a></li>
					<li><a href="http://swift.ushahidi.com/doc/" title="documentation" >Documentation</a></li>
				</ul>
				<p><?php echo Kohana::lang('ui_main.copyright'); ?></p>
			</div>
			<!-- / footer menu -->
 		</div>
		<!-- / footer content -->
 
	</div>
	<!-- / footer -->
 
	<!--<img src="<?php echo $tracker_url; ?>" -->
	<?php echo $ushahidi_stats; ?>
	<?php echo $google_analytics; ?>
	
	<!-- Task Scheduler -->
	<img src="<?php echo url::base() . 'scheduler'; ?>" height="0" width="0" border="0" />
 
    <!-- script for share button -->
    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=swiftriver"></script>	
</body>
</html>