<?php 
    require_once('install.php');
    global $install;
    
    //check if swift is installed?.
    if( $install->is_ushahidi_installed())
    {
        header('Location:../');
    }
   
    $header = $install->_include_html_header();
    print $header;
 ?>
<body>
<div id="swift_login_container">
    <div id="swift_login_logo"><img src="../media/img/admin/logo_login.png" /></div>
    <div id="swift_login" class="clearfix">
    
	<p>Welcome to SwiftRiver Version 0.0.9 Rumba!  This is an <strong>Alpha</strong> release for evaluation purposes only, it's highly unfinished, unstable and should not be used in a production environment without additional consultation. You've been warned. ;-) Please direct questions to jon [at] ushahidi.com. All things considered, begin the installation process:</p>
		
	<a href="basic_summary.php" class="two-col-box tc-left btn-box">
		<span class="btn-box-title">BASIC INSTALLATION</span>
		<span class="btn-box-content">Simple and fast (you might even say <em>swiftly</em>).  All you need is your website's root directory and your database information.  Choose this option if you want to get up and running quickly, and you can always configure everything else later.</span>
		<span class="last btn-action">Proceed with basic &rarr;</span>
	</a>
	<!-- <a href="advanced_summary.php" class="two-col-box tc-right btn-box">
		<span class="btn-box-title">ADVANCED INSTALLATION</span>
		<span class="btn-box-content">Get all the basic settings completed through this 5-step process.  This includes server, map, site name and contact details.</span>
		<span class="last btn-action">Proceed with advanced &rarr;</span><br /><br />
	</a> -->
	
	<!-- Generic Box
	<div class="two-col-box tc-right">
		<h2>Title</h2>
		<p></p>
		<p class="last"><a href="#" class="btn">Proceed &rarr;</a></p>
	</div> -->    

        	
	</div>

</div>
</body>
</html>
