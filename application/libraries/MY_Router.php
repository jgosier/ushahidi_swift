<?php defined('SYSPATH') or die('No direct script access.');
 
class Router extends Router_Core {
 
	public static function setup()
	{

		parent::setup();
	/*		
			$route_conditions = array('action'=>'[a-zA-Z_-]+[0-9]*[a-zA-Z_-]*','format'=>'html|xml|json|result','id'=>'[0-9]+','name'=>'[a-zA-Zs]+','misc' => '([a-zA-Z]+(    :)[a-zA-Z0-9,]+(/){0,1})+');
 		
		  $defaults = array(
      'controller' => 'spl',
      'action'     => 'index',
      'format'     => null,
      'id' => null,
      'name' => null,
      'misc' => array()
    );
 
 
 	 	parent::Route::set('default', '(<controller>(/<action>)(/<id>)(/<name>))(/<misc>)(.<format>)',$route_conditions)->defaults($defaults);
			*/

	}
	
 
}
 
?>

