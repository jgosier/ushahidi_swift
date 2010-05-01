<?php 
/**
 * This class acts like a controller.
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

require_once('form.php');

class Install
{

    private $database_file;

	private $install_directory;
	public function __construct()
	{
		global $form;
	    
	    $this->install_directory = dirname(dirname(__FILE__));
	    
		$this->_index();
	}

	public function _index()
	{
	   session_start();
	}

	/**
	 * Validates the form fields and does the necessary processing.
	 */
	public function _install_db_info( $username, $password, $host, $select_db_type,
	    $db_name, $table_prefix, $base_path )
    {
	    global $form;
	    //check for empty fields
	    if(!$username || strlen($username = trim($username)) == 0 ){
	        $form->set_error("username", "Please make sure to " .
	        		"enter the <strong>username</strong> of the database server.");
	    }

	    if( !$host || strlen($host = trim($host)) == 0 ){
	        $form->set_error("host","Please enter the <strong>host</strong> of the
	            database server." );
	    }

	    if( !$db_name || strlen($db_name = trim($db_name)) == 0 ){
	        $form->set_error("db_name","Please enter the <strong>name</strong> of your database.");
	    }

	    // load database.template.php and work from it.
		if(!file_exists('../application/config/database.template.php')){
		    $form->set_error("load_db_tpl","<strong>Oops!</strong> I need the file called " .
		    		"<code>database.template.php</code> to work
            from. Please make sure this file is in the <code>application/config/</code> folder.");
		}

                /*
                 * APALA - Removed htaccess check as its done in the combined installer

		// load .htaccess file and work with it.
		if(!file_exists('../.htaccess')){
		    $form->set_error("load_htaccess_file","<strong>Oops!</strong> I need a file called " .
		    		"<code>.htaccess</code> to work
            with. Please make sure this file is in the root directory of your Ushahidi files.");
		}
		
		if( !is_writable('../.htaccess')) {
		    $form->set_error('htaccess_perm',
			"<strong>Oops!</strong> Ushahidi is unable to write to the <code>.htaccess</code> file. " .
			"Please change the permissions of that file to allow write access (777).  " .
			"<p>Here are instructions for changing file permissions:</p>" .
			"<ul>" .
			"	<li><a href=\"http://www.washington.edu/computing/unix/permissions.html\">Unix/Linux</a></li>" .
			"	<li><a href=\"http://support.microsoft.com/kb/308419\">Windows</a></li>" .
			"</ul>");
		}
                */
		if( !is_writable('../application/config')) {
		    $form->set_error('permission',
			"<strong>Oops!</strong> Ushahidi is trying to create and/or edit a file called \"" .
			"database.php\" and is unable to do so at the moment. This is probably due to the fact " .
			"that your permissions aren't set up properly for the <code>config</code> folder. " .
			"Please change the permissions of that folder to allow write access (777).  " .
			"<p>Here are instructions for changing file permissions:</p>" .
			"<ul>" .
			"	<li><a href=\"http://www.washington.edu/computing/unix/permissions.html\">Unix/Linux</a></li>" .
			"	<li><a href=\"http://support.microsoft.com/kb/308419\">Windows</a></li>" .
			"</ul>");
		}
		
		if( !is_writable('../application/config/config.php')) {
		    $form->set_error('config_perm',
			"<strong>Oops!</strong> Ushahidi is trying to edit a file called \"" .
			"config.php\" and is unable to do so at the moment. This is probably due to the fact " .
			"that your permissions aren't set up properly for the <code>config.php</code> file. " .
			"Please change the permissions of that folder to allow write access (777).  " .
			"<p>Here are instructions for changing file permissions:</p>" .
			"<ul>" .
			"	<li><a href=\"http://www.washington.edu/computing/unix/permissions.html\">Unix/Linux</a></li>" .
			"	<li><a href=\"http://support.microsoft.com/kb/308419\">Windows</a></li>" .
			"</ul>"
			/* CB: Commenting this out... I think it's better if we just have them change the permissions of the specific
				files and folders rather than all the files
			"Alternatively, you could make the webserver own all the ushahidi files. On unix usually, you" .
			"issue this command <code>chown -R www-data:ww-data</code>");
			*/
			);
		}

		if(!$this->_make_connection($username, $password, $host)){
		    $form->set_error("connection","<strong>Oops!</strong>, We couldn't make a connection to
		    the database server with the credentials given. Please make sure they are correct.");
		}

	    /**
	     * error exists, have user correct them.
	     */
	   if( $form->num_errors > 0 ) {
	        return 1;

	   } else {

	        $this->_add_config_details($base_path);
			
			$this->_add_htaccess_entry($base_path);
			
		    $this->_add_db_details( $username, $password, $host, $select_db_type,
		       $db_name, $table_prefix );

		    $this->_import_sql($username, $password, $host,$db_name);
		    $this->_chmod_folders();
	        
	        $sitename = $this->_get_url();
		    $url = $this->_get_url();
		    $configure_stats = $this->_configure_stats($sitename, $url, $host, $username, $password, $db_name);
	        
	        return 0;
	   }
	}
	
	/**
	 * Validates general settings fields and then add details to 
	 * the settings table.
	 */
	public function _general_settings($site_name, $site_tagline, $default_lang, $site_email)
	{
		global $form;
	    //check for empty fields
	    if(!$site_name || strlen($site_name = trim($site_name)) == 0 ){
	        $form->set_error("site_name", "Please make sure to " .
	        		"enter a <strong>site name</strong>.");
	    } else {
	    	$site_name = stripslashes($site_name);
	    }
	    
	    if(!$site_tagline || strlen($site_tagline = trim($site_tagline)) == 0 ){
	        $form->set_error("site_tagline", "Please make sure to " .
	        		"enter a <strong>site tagline</strong>.");
	    } else {
	    	$site_tagline = stripslashes($site_tagline);
	    }
	    
	    /* Email error checking */
      	if(!$site_email || strlen($site_email = trim($site_email)) == 0){
        	$form->set_error("site_email", "Please enter a <strong>site email address</strong>.");
      	} else{
         	/* Check if valid email address */
         	$regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                 ."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                 ."\.([a-z]{2,}){1}$";
         	if(!ereg($regex,$site_email)){
            	$form->set_error("site_email", "Please enter a valid email address. ex: johndoe@email.com.");
         	}
         	$site_email = stripslashes($site_email);
      	}
      	
      	/**
	     * error exists, have user correct them.
	     */
	   	if( $form->num_errors > 0 ) {
	        return 1;

	   	} else {
	   		$this->_add_general_settings($site_name, $site_tagline, $default_lang, $site_email);
	   		return 0;	
	   	}
	    
	}
	
	public function _map_info($map_provider, $map_api_key )
	{
		global $form;
		//check for empty fields
	    if(!$map_api_key || strlen($map_api_key = trim($map_api_key)) == 0 ){
	        $form->set_error("map_provider_api_key", "Please make sure to " .
	        		"enter an<strong> api key</strong> for your map provider.");
	    } else {
	    	$map_api_key = stripslashes($map_api_key);
	    }
	    
	    /**
	     * error exists, have user correct them.
	     */
	   	if( $form->num_errors > 0 ) {
	        return 1;

		} else {
			$this->_add_map_info($map_provider, $map_api_key );
			return 0;
		}
	}
	
	public function _mail_server($alert_email, $mail_username,$mail_password,
		$mail_port,$mail_host,$mail_type,$mail_ssl ){
		
		global $form;
	    //check for empty fields
	    if(!$alert_email || strlen($alert_email = trim($alert_email)) == 0 ){
	        $form->set_error("site_alert_email", "Please make sure to " .
	        		"enter a <strong>site alert email address</strong>.");
	    }

	    if( !$mail_username || strlen($mail_username = trim($mail_username)) == 0 ){
	        $form->set_error("mail_server_username","Please enter the <strong>user name</strong> of your mail server." );
	    }

	    if( !$mail_password || strlen($mail_password = trim($mail_password)) == 0 ){
	        $form->set_error("mail_server_pwd","Please enter the <strong>password</strong> for your email account.");
	    }
	    
	    if(!$mail_port|| strlen($mail_port = trim($mail_port)) == 0 ){
	        $form->set_error("mail_server_port", "Please make sure to " .
	        		"enter the <strong>port</strong> for your mail server.");
	    }
	    
	    if(!$mail_host|| strlen($mail_host = trim($mail_host)) == 0 ){
	        $form->set_error("mail_server_host", "Please make sure to " .
	        		"enter the <strong>host</strong> of the mail server.");
	    }
	    
	    /**
	     * error exists, have user correct them.
	     */
	   	if( $form->num_errors > 0 ) {
	        return 1;

		} else {
			$this->_add_mail_server_info( $alert_email, $mail_username,$mail_password,
						$mail_port,$mail_host,$mail_type,$mail_ssl );
			return 0;
		}

	}
	
	/**
	 * gets the URL
	 */
	 private function _get_url()
	 {
	 	global $_SERVER;
	 	if ($_SERVER["SERVER_PORT"] != "80") {
			$url = $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		
		return 'http://'.substr($url,0,stripos($url,'/installer/'));
	 }

	/**
	 * adds the database details to the config/database.php file.
	 */
	private function _add_db_details( $username, $password, $host,
	    $select_db_type, $db_name, $table_prefix )
	{

	    $database_file = @file('../application/config/database.template.php');
	    $handle = @fopen('../application/config/database.php', 'w');
	    foreach( $database_file as $line_number => $line )
	    {   
	    	switch( trim(substr( $line,0,14 )) ) {
	            case "'type'     =":
	                fwrite($handle, str_replace("'mysql'","'".
	                    $select_db_type."'",$line ));
	                break;

	            case "'user'     =":
	                fwrite($handle, str_replace("'username'","'".
	                    $username."'",$line ));
	                break;
	            case "'pass'     =":
	                fwrite($handle, str_replace("'password'","'".
	                    $password."'",$line));
	                break;

	            case "'host'     =":
	                fwrite($handle, str_replace("'localhost'","'".
	                    $host."'",$line));
	                break;

	            case "'database' =":
	                fwrite($handle, str_replace("'db'","'".
	                    $db_name."'",$line));
	                break;

	            case "'table_prefix":
	                fwrite($handle, str_replace("''","'".
	                    $table_prefix."'",$line));
	                break;

	            default:
	                fwrite($handle, $line);
	        }
	    }

	    fclose($handle);
	    //for security reasons change permission on the file to 666
	    chmod('../application/config/database.php',0666);
	}

	/**
	 * adds the site_name to the application/config/config.php file
	 */
	private function _add_config_details( $base_path )
	{
	    $config_file = @file('../application/config/config.template.php');
        $handle = @fopen('../application/config/config.php', 'w');
		
	    foreach( $config_file as $line_number => $line )
	    {
	        if( !empty( $base_path ) )
            {
	            switch( trim(substr( $line,0,23 )) ) {
	                case "\$config['site_domain']":
	                    fwrite($handle, str_replace("/","/".
	                    $base_path."/",$line ));
	                break;

	                default:
	                    fwrite($handle, $line);
	                }
	        }else {
	           fwrite($handle, $line);
	        }
	    }

	}
	
	/**
	 * Adds the right RewriteBase entry to the .htaccess file.
	 * 
	 * @param base_path - the base path.
	 */
	private function _add_htaccess_entry($base_path) {
		$htaccess_file = file('../.htaccess');
		$handle = fopen('../.htaccess','w');
			
		foreach($htaccess_file as $line_number => $line ) {
			if( !empty($base_path) ) {
				switch( trim( substr($line, 0, 12 ) ) ) {
					case "RewriteBase":
						fwrite($handle, str_replace("/","/".$base_path,$line));
						break;
						
					default:
						fwrite($handle,$line);
				}
			} else {
				fwrite($handle,$line);
			}	
		}	
		
	} 

	/**
	 * Imports sql file to the database.
	 */
	private function _import_sql($username, $password, $host,$db_name)
	{
	    $connection = @mysql_connect("$host", "$username", "$password");
	    $db_schema = @file_get_contents('../sql/swift.sql');

	    $result = @mysql_query('CREATE DATABASE '.$db_name);
	    
	    // select newly created db
	    @mysql_select_db($db_name,$connection);
	    /**
	     * split by ; to get the sql statement for creating individual
	     * tables.
	     */
	    $tables = explode(';',$db_schema);
		
	    foreach($tables as $query) {
	   
	        $result = @mysql_query($query,$connection);
	    }

	    @mysql_close( $connection );
	    
	}
	
	/**
	 * Adds general settings detail to the db.
	 * @param site_name - site name.
	 * @param site_tagline - site name.
	 * @param defaul_lang - default language.
	 * @param site_email - site email.
	 */
	private function _add_general_settings($site_name, $site_tagline, $default_lang, $site_email) {
		$connection = @mysql_connect($_SESSION['host'],$_SESSION['username'], $_SESSION['password']);
		@mysql_select_db($_SESSION['db_name'],$connection);
		@mysql_query('UPDATE `settings` SET `site_name` = \''.mysql_escape_string($site_name).
		'\', site_tagline = \''.mysql_escape_string($site_tagline).'\', site_language= \''.mysql_escape_string($default_lang).'\' , site_email= \''.mysql_escape_string($site_email).'\' ');
		@mysql_close($connection);		
	}
	
	/**
	 * Adds google map api key to the settings table.
	 * @param map_provider - map provider.
	 * @param map_api_key - map api key
	 */
	private function _add_map_info($map_provider, $map_api_key ){
		//TODO modularize the db connection part.
		$connection = @mysql_connect($_SESSION['host'],$_SESSION['username'], $_SESSION['password']);
		@mysql_select_db($_SESSION['db_name'],$connection);
		
		@mysql_query('UPDATE `settings` SET `default_map` = \''.mysql_escape_string($map_provider).
		'\', api_google = \''.mysql_escape_string($map_api_key).'\' ');
		@mysql_close($connection);
	}
	
	/**
	 * Adds mail server details to the settings table.
	 * 
	 */
	private function _add_mail_server_info( $alert_email, $mail_username,$mail_password,
		$mail_port,$mail_host,$mail_type,$mail_ssl ) {
		
		$connection = @mysql_connect($_SESSION['host'],$_SESSION['username'], $_SESSION['password']);
		@mysql_select_db($_SESSION['db_name'],$connection);
		
		@mysql_query('UPDATE `settings` SET `alerts_email` = \''.mysql_escape_string($alert_email).
		'\', `email_username` = \''.mysql_escape_string($mail_username).'\' , `email_password` = \''.mysql_escape_string($mail_password).'\'' .
				', `email_port` = \''.mysql_escape_string($mail_port).'\' , `email_host` = \''.mysql_escape_string($mail_host).'\' ' .
						', `email_servertype` = \''.mysql_escape_string($mail_type).'\' , `email_ssl` = \''.mysql_escape_string($mail_ssl).'\' ');
		@mysql_close($connection);
	}

	/**
	 * check if we can make connection to the db server with the credentials
	 * given.
	 */
	private function _make_connection($username, $password, $host)
	{
	    $connection = @mysql_connect("$host", "$username", "$password");
		if( $connection ) {
		    @mysql_close( $connection );
		    return TRUE;
		}else {
		    @mysql_close( $connection );
		    return FALSE;
		}
	}
	
	/**
	 * Set up stat tracking
	 */
	private function _configure_stats($sitename, $url, $host, $username, $password, $db_name)
	{
		$stat_url = 'http://tracker.ushahidi.com/px.php?task=cs&sitename='.urlencode($sitename).'&url='.urlencode($url);
		
		$xml = simplexml_load_string($this->_curl_req($stat_url));
		$stat_id = (string)$xml->id[0];
		$stat_key = (string)$xml->key[0];
		
		if($stat_id > 0){
			$connection = @mysql_connect("$host", "$username", "$password");
			@mysql_select_db($db_name,$connection);
			@mysql_query('UPDATE `settings` SET `stat_id` = \''.mysql_escape_string($stat_id).'\', `stat_key` = \''.mysql_escape_string($stat_key).'\' WHERE `id` =1 LIMIT 1;');
			@mysql_close($connection);
			
			return $stat_id;
		}
		
		return false;		
	}

	/**
	 * Change permissions on the cache, logs, and upload folders.
	 */
	private function _chmod_folders()
	{
	    @chmod('../application/cache',0777);
	    @chmod('../application/logs',0777);
	    @chmod('../media/uploads',0777);
	}
	
	/**
	 * check if ushahidi has been installed.
	 */
	public function is_ushahidi_installed()
	{
	    /**
	     * Check if config file exists.
	     */
	    $is_installed = true;
	    if( file_exists('../application/config/database.php') )
	    {

	        $database_file = file('../application/config/database.php');

			if( preg_match( "/username/",$database_file[22] ) &&
				preg_match( "/password/",$database_file[23] ) ){

				$is_installed = false;
			}

	    } else {
	        $is_installed = false;
	    }

	    return $is_installed;
	}
	
	/**
	 * Helper function to send a cURL request
	 * @param url - URL for cURL to hit
	 */
	public function _curl_req( $url )
	{
		// Make sure cURL is installed
		if (!function_exists('curl_exec')) {
			throw new Kohana_Exception('stats.cURL_not_installed');
			return false;
		}
		
		$curl_handle = curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$url);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,15); // Timeout set to 15 seconds. This is somewhat arbitrary and can be changed.
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1); //Set curl to store data in variable instead of print
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);
		
		return $buffer;
	}
	
	/**
	 * Check if relevant directories are writable.
	 */
	public function _check_writable_dir() {
		global $form;
		
		//Check to see if the .htaccess file exists
		$htaccessDir = '..';
		$htaccessFilePath = $htaccessDir . '/.htaccess';
		if( file_exists($htaccessFilePath)) {
			//if it does then check its writable
			if( !is_writable('../.htaccess')) {
			    $form->set_error('htaccess_perm',
				"<strong>Oops!</strong> Ushahidi is unable to write to your <code>.htaccess</code> file. " .
				"Please change the permissions of that file to allow write access (777).  ");
			}
		} else {
			//if the .htaccess file does not exsits
			//Check if the .htaccess directory is writable
			if (!is_writable($htaccessDir)) {
				//If not then complain about it
				$form->set_error('htaccess_perm',
				"<strong>Oops!</strong> You are missing a <code>.htaccess</code> file in the root directory " .
				"and Ushahidi was unable to create one for you Please change the permissions of the root " .
				"directory (777) or create one yourself.  ");
			} else {
				//If possible, create the file
				$this->_write_basic_htaccess_file($htaccessFilePath);
			}	
		}

		if( !is_writable('../application/config')) {
		    $form->set_error('config_folder_perm',
			"<strong>Oops!</strong> Ushahidi needs the <code>application/config</code> folder to be writable. ".
			"Please change the permissions of that folder to allow write access (777).  ");
		}
		
		//Check if the config.php file exists
		$configDir = '../application/config';
		$configFilePath = $configDir . '/config.php';
		if( file_exists($configFilePath)) {
			//If it does then check to see if its writable	
			if( !is_writable('../application/config/config.php')) {
			    $form->set_error('config_file_perm',
				"<strong>Oops!</strong> Ushahidi is unable to write to <code>application/config/config.php</code> file. " .
				"Please change the permissions of that file to allow write access (777).  ");
			}
		} else {
			//If it does not exists, check to see if the confi dir is writable
			if( !is_writable($configDir)) {
			    $form->set_error('config_file_perm',
				"<strong>Oops!</strong> You are missing a <code>application/config/config.php</code> file " .
				"and Ushahidi was uanble to create one for you. Please change the permissions of the " .
			    "directory <code>application/config</code> (777) or create one your self.  ");
			} else {
				//If possible create the file
				$file = fopen($configFilePath, 'w');
				fclose($file);
			}
		}
		
		//Check to see if the cache directory is writable
		$cacheParentDir = '../application'; 
		$cacheDir = $cacheParentDir . '/cache';
		if( file_exists($cacheDir)) {
			//If the directory exists, check to see if its writable
			if( !is_writable('../application/cache')) {
			    $form->set_error('cache_perm',
				"<strong>Oops!</strong> Ushahidi needs <code>application/cache</code> folder to be writable. ".
				"Please change the permissions of that folder to allow write access (777).  ");
			}
		} else {
			//If it does not exist, try to create it
			if( !is_writable($cacheParentDir)) {
			    $form->set_error('cache_perm',
				"<strong>Oops!</strong> You are missing the directory <code>application/cache</code> and ".
				"Ushahidi was unable to create one for you. Please change the permissions of the " .
			    "<code>application</code> folder (777) or create one yourself.  ");
			} else {
				mkdir($cacheDir);
			}
		}
		
		//check to see if the logs directory is writable
		$logsParentDir = '../application';
		$logsDir = $logsParentDir . '/logs';
		if( file_exists($logsDir)) {
			//If the directory exists, check to see if it is writable
			if( !is_writable($logsDir)) {
			    $form->set_error('logs_perm',
				"<strong>Oops!</strong> Ushahidi needs <code>application/logs</code> folder to be writable. " .
				"Please change the permissions of that folder to allow write access (777). ");
			}
		} else {
			//If not, try to create it
			if( !is_writable($logsParentDir)) {
			    $form->set_error('logs_perm',
				"<strong>Oops!</strong> You are missing the directory <code>application/logs</code> and ".
				"Ushahidi was unable to create one for you. Please change the permissions of the " .
			    "<code>application</code> folder (777) or create one yourself.  ");
			} else {
				mkdir($logsDir);
			}
		}
		
		//Check to see if the uploads directory is writable
		$uploadsParentDir = '../media';
		$uploadsDir = $uploadsParentDir . '/uploads';
		if( file_exists($uploadsDir)) {
			//if the directory exists, check to see if tis writable
			if( !is_writable($uploadsDir)) {
			    $form->set_error('uploads_perm',
				"<strong>Oops!</strong> Ushahidi needs <code>media/uploads</code> folder to be writable. " .
				"Please change the permissions of that folder to allow write access (777). ");
			}
		} else {
			//If not then try to create it
			if( !is_writable($uploadsParentDir)) {
			    $form->set_error('uploads_perm',
				"<strong>Oops!</strong> You are missing the directory <code>media/uploads</code> and ".
				"Ushahidi was unable to create one for you. Please change the permissions of the " .
			    "<code>uploads</code> folder (777) or create one yourself.  ");
			} else {
				mkdir($uploadsDir);
			}		
		}
		
		
		/**
	     * error exists, have user correct them.
	     */
	   if( $form->num_errors > 0 ) {
	        return 1;

	   } else {
	   		return 0;
	   }
			
	}
	
	/**
	 * Adds header details to the installer html pages.
	 */
	public function _include_html_header() {
		/*TODO make title tag configurable*/
		$header = <<<HTML
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>Database Connections / Ushahidi Web Installer</title>
				<link href="../media/css/admin/login.css" rel="stylesheet" type="text/css" />
			</head>
			<script src="../media/js/jquery.js" type="text/javascript" charset="utf-8"></script>
			<script src="../media/js/login.js" type="text/javascript" charset="utf-8"></script>
			</head>
HTML;
		return $header;

	}
	
	/**
	 * Gets the current directory ushahidi is installed in.
	 */
	public function _get_base_path($request_uri) {
		return substr( substr($request_uri,0,stripos($request_uri,'/installer/')) ,1);
		
	}
	

	public function _write_basic_htaccess_file($filePath)
	{
		//Define the basic .htaccess file
		$fileLines = array (
			"Options +FollowSymlinks\n",
			"\n",
			"# Turn on URL rewriting\n",
			"RewriteEngine On\n",
			"\n",
			"# Installation directory\n",
			"RewriteBase /\n",
			"\n",
			"# Begin Kohana rewrite settings\n",
			"# - Forbidden access to these directories\n",
			"RewriteRule ^(application|modules|system) - [F,L]\n", 
			"\n",
			"# - Passthrough for files that exists\n",
			"RewriteCond %{REQUEST_FILENAME} !-f\n",
			"# - Passthrough for directories that exists\n",
			"RewriteCond %{REQUEST_FILENAME} !-d\n",
			"\n",
			"# - Redirect all else to index.php\n",
			"RewriteRule .* index.php/$0 [PT,L]\n",
			"# End Kohana rewrite settings\n",
			"\n",
			"# Protect the htaccess from being viewed\n",
			"<Files .htaccess>\n",
			"order allow,deny\n",
			"deny from all\n",
			"</Files>\n",
			"\n",
			"# Don't show directory listings for URLs which map to a directory.\n",
			"#Options -Indexes"
		);
		
		//Open the file
		$file = fopen($filePath, 'w');
		//write out the lines
		foreach($fileLines as $line) {
			fwrite($file, $line);
		}
		//close the handle
		fclose($file);
	}
}

$install = new Install();
$form = new Form();

?>