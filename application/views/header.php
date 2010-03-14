<?php 
/**
 * Header view page.
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title><?php echo $site_name; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<?php 
		echo html::stylesheet('media/css/style','',true);
		echo html::stylesheet('media/css/jquery-ui-themeroller', '', true);
		echo "<!--[if lte IE 7]>".html::stylesheet('media/css/iehacks','',true)."<![endif]-->";
		echo "<!--[if IE 7]>".html::stylesheet('media/css/ie7hacks','',true)."<![endif]-->";
		echo "<!--[if IE 6]>".html::stylesheet('media/css/ie6hacks','',true)."<![endif]-->";

	// Load OpenLayers before jQuery!
	if ($map_enabled == 'streetmap') {
		echo html::script('media/js/OpenLayers', true);
		echo "<script type=\"text/javascript\">OpenLayers.ImgPath = '".url::base().'media/img/openlayers/'."';</script>";
		//echo 'STREET!';
	}
	
	// Load jQuery
	echo html::script('media/js/jquery', true);
	echo html::script('media/js/jquery.ui.min', true);
	
	// Other stuff to load only we have the map enabled
	if ($map_enabled) {
		echo $api_url . "\n";
		if ($main_page || $this_page == 'alerts') {
			echo html::script('media/js/selectToUISlider.jQuery', true);
		}
		if ($main_page) {
			echo html::script('media/js/jquery.flot', true);
			echo html::script('media/js/timeline', true);
			echo "<!--[if IE]>".
				html::script('media/js/excanvas.pack', true)
				."<![endif]-->";
		}
	}
	
	if ($validator_enabled) {
		echo html::script('media/js/jquery.validate.min');
	}
	
	if ($photoslider_enabled) {
		echo html::script('media/js/photoslider');
		echo html::stylesheet('media/css/photoslider');
	}
	
	if( $videoslider_enabled ) {
		echo html::script('media/js/coda-slider.pack');
		echo html::stylesheet('media/css/videoslider');
	}
	
	// Load ProtoChart
	if ($protochart_enabled)
	{
		echo "<script type=\"text/javascript\">jQuery.noConflict()</script>";
		echo html::script('media/js/protochart/prototype', true);
		echo '<!--[if IE]>';
		echo html::script('media/js/protochart/excanvas-compressed', true);
		echo '<![endif]-->';
		echo html::script('media/js/protochart/ProtoChart', true);
	}
	
	if ($allow_feed == 1) {
		echo "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"http://" . $_SERVER['SERVER_NAME'] . "/feed/\" title=\"RSS2\" />";
	}
	
	//Custom stylesheet
	echo html::stylesheet(url::base().'themes/'.$site_style."/style.css");

	echo html::stylesheet(url::base().'media/css/jquery-ui-1.7.2.custom.css');
	echo html::script('media/js/jquery-1.3.2.min', true);
	echo html::script('media/js/ui.core.js', true); 
	echo html::script('media/js/ui.slider.js', true); 	
	?>
	
	<style type="text/css">
		#demo-frame > div.demo { padding: 10px !important; };
	</style>
	<script type="text/javascript">
$(function() {
		$("#slider-range").slider({
			range: true,
			min: 0,
			max: 100,
			<?php 
					if(isset( $_SESSION['verocity_min']) && isset( $_SESSION['verocity_max'])){
			 echo	"	values: [".$_SESSION['verocity_min']." , ".$_SESSION['verocity_max']." ],";
			}else
			{ ?> 
						values: [20, 100],
				<?php
			}
				?>

			slide: function(event, ui) {
				$("#verocity_min").val(ui.values[0]);
			  $("#verocity_max").val(ui.values[1]);
			}
		});
		$("#verocity_min").val( $("#slider-range").slider("values", 0));
		$("#verocity_max").val( $("#slider-range").slider("values", 1));
	});

	</script>


	<!--[if IE 6]>
	<script type="text/javascript" src="js/ie6pngfix.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('img, ul, ol, li, div, p, a');</script>
	<![endif]-->
	<script type="text/javascript">
		var addthis_config = {
		   ui_click: true
		}	
		
		<?php echo $js2 . "\n"; ?>
		
	</script>
</head>

<body id="page">
	<!-- begin wrapper -->
	<div class="rapidxwpr floatholder">

		<!-- header -->
		<div id="header">
			<!-- searchbox -->
			<div id="searchbox">
				<a class="share addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pub=xa-4aee423643f8276e">Share</a>
					<form id="login" name="login" method="POST" action="<?php echo url::base() . "login" ?>">
				<!-- languages -->
						<?php if(isset($_SESSION['auth_user'])){ ?>
							
							<div class="language-box">
								<strong>		<?php echo Kohana::lang('ui_admin.welcome');echo $_SESSION['auth_user']->name; ?>! &nbsp;&nbsp;</strong>
							 </div>
							<div class="search-form">
				      			<a href="<?php echo url::base() ;?>admin/log_out"><?php echo Kohana::lang('ui_admin.logout');?></a>
				      </div>
						<?php }else{ ?>
						
				<!-- / login -->
				<div class="language-box">				
						<input type="text" name="username" id="username" class="login_text" size="10" value="Username" />					
				</div>
				<div class="search-form">
						<ul>
							<li><input name="password" type="password" class="login_text" id="password" size="10" value="password" /></li>
							<li><input type="image" src="<?php echo url::base() ;?>media/img/right_arrow.jpg" alt="submit" /></li>
						</ul>					
				</div>
					<?php } ?>
				<!-- / searchform -->
				</form>
			</div>
			<!-- / searchbox -->
		
			<!-- logo -->
			<div id="logo">
				<h1><?php echo $site_name; ?></h1>
				<span><?php echo $site_tagline; ?></span>
			</div>
			<!-- / logo -->
		</div>
		<!-- / header -->

		<!-- begin major main body -->
		<div id="middle">
			<div class="background layoutleft">
		
				<!-- mainmenu -->
				<div id="mainmenu" class="clearingfix">
					<ul>
						<li><a href="<?php echo url::base() . "main" ?>" <?php if ($this_page == 'home') echo 'class="active"'; ?>><?php echo Kohana::lang('ui_main.home'); ?></a></li>
						<li><a href="<?php echo url::base() . "#" ?>" <?php if ($this_page == 'reports') echo 'class="active"'; ?>><?php echo Kohana::lang('ui_main.reports'); ?></a></li>
						<li><a href="<?php echo url::base() . "reports/submit" ?>" <?php if ($this_page == 'reports_submit') echo 'class="active"'; ?>><?php echo Kohana::lang('ui_main.submit'); ?></a></li>
				<!-- Commenting out 'Download Reports' 
				<li><a href="<?php echo url::base() . "alerts" ?>" <?php if ($this_page == 'alerts') echo 'class="active"'; ?>><?php echo Kohana::lang('ui_main.alerts'); ?></a></li> 
				-->
						<?php
						// Contact Page
						if ($site_contact_page)
						{
							?>
							<li><a href="<?php echo url::base() . "contact" ?>" <?php if ($this_page == 'contact') echo 'class="active"'; ?>><?php echo Kohana::lang('ui_main.contact'); ?></a></li>
							<?php
						}
						
						// Help Page
						if ($site_help_page)
						{
							?>
							<li><a href="<?php echo url::base() . "help" ?>" <?php if ($this_page == 'help') echo 'class="active"'; ?>><?php echo Kohana::lang('ui_main.help'); ?></a></li>
							<?php
						}
						
						// Custom Pages
						foreach ($pages as $page)
						{
							$this_active = ($this_page == 'page_'.$page->id) ? 'class="active"' : '';
							echo "<li><a href=\"".url::base()."page/index/".$page->id."\" ".$this_active.">".$page->page_tab."</a></li>";
						}
						?>
					</ul>

				</div>
				<!-- / mainmenu -->
