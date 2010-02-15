<?
class url extends url_Core 
{/*
	public static function get_query_string(Array $queryVars, $keepOld = false) 
	{
			$current = input::instance()->get();		
			if($keepOld)
				 $new = array_merge($current, $queryVars);
			else $new = $queryVars;
				return http_build_query($new);
	}
	
	/*
	 * Add a new variable to the query string
	 * or overwrites the value, if already exist
	 */
	public static function add_to_query_string($param, $new_value)
	{
		$parameters = input::instance()->get();
		$parameters[$param] = $new_value;
		return url::base().url::current().'?'.http_build_query($parameters);
	}

	/*
	 * Remove a variable from the query string
	 */
	public static function remove_from_query_string($param)
	{
		$parameters = input::instance()->get();
		unset($parameters[$param]);
		return url::base().url::current().'?'.http_build_query($parameters);
	}

	
	
}
?>