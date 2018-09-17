<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	
	if (!function_exists('mynfa_debug')) {
		
		/**
		 * This function is use for debugging purpose. Get the string or array or object and print for debugging purpose.
		 * @param string $value Any value of any type, You can pass array as well
		 * @param bool   $print If false, value will return else echo. DEFAULT: true
		 * @param bool	$exit If true, system will exit. Default: false.
		 *
		 * @return string return var_export string OR echo if $print is true or exit if $exit is true
		 */
		function mynfa_debug ($value = '', $print = true, $exit = false)
		{
			
			$html = '<tt><pre>' . print_r($value, true) . '</pre></tt>';
			
			if ($print)
				echo $html;
			else
				return $html;
			
			
			if ($exit)
				wp_die('Exiting from ' . __FUNCTION__);
			
			
			return ''; // House Keeping
			
		}
	}
	
	if (! function_exists('mynfa_get_redux_value')){
		
		/**
		 * This function check if value is found in ReduxFramework settings object against a particular key.
		 * @param string       $key ReduxFramework input field id.
		 * @param string       $value ReduxFramework value (in case of checkboxes)
		 * @param string $default_time_zone Pass the default time zone if you are debugging. By default ASIA/KARACHI
		 * @param bool   $dubugging Do you want to debug the value? If true, system will print value and exit.
		 *
		 * @return Mixed|bool Return string if value is found and not empty against key, False otherwise.
		 */
		function mynfa_get_redux_value($key,  $default_time_zone = "ASIA/KARACHI", $dubugging = false){
			global $redux_mynfa_options;
			
			
			date_default_timezone_set($default_time_zone);
			
			if ($dubugging)
				mynfa_debug($redux_mynfa_options, true, true);
			
			/*
			 * if key exist and value found then return the value. [May be an array in case of Radio buttons]
			 * */
			
			if (array_key_exists($key, $redux_mynfa_options) && !empty($redux_mynfa_options[ $key ]))
				return $redux_mynfa_options[$key];
			
			return false;
		}
	}
	
	// Small Tweaks
	if (! function_exists('mynfa_disable_dashboard_access')){
		function mynfa_disable_dashboard_access($redirect_to, $request, $user ){
			
			if (isset($user->roles) && is_array($user->roles)) {
				//check for subscribers
				if (in_array('subscriber', $user->roles)) {
					// redirect them to another URL, in this case, the homepage
					$redirect_to =  home_url('/dashboard');
				}
			}
			
			return $redirect_to;
		}
		
		add_filter('login_redirect', 'mynfa_disable_dashboard_access', 10, 3);
	}
	
	if (! function_exists('mynfa_hide_admin_bar')){
		function mynfa_hide_admin_bar(){
			add_filter('show_admin_bar', '__return_false');
		}
		
		//add_action('init', 'mynfa_hide_admin_bar');
	}
	
	if (! function_exists('nfa_get_all_subscribers_users')){
		function nfa_get_all_subscribers_users(){
			$subscribers_users = get_transient('mynfa_wp_subscribers_users');
			if ($subscribers_users === false){
				$subscribers_users = get_users( [ 'role__in' => [ 'subscriber' ] ] );
				set_transient('mynfa_wp_subscribers_users', $subscribers_users, 3600);
			}
			
			return $subscribers_users;
		}
	}
	
	
	if (! function_exists('nfa_sort_callback')){
		function nfa_sort_callback($item1,$item2)
		{
			if ($item1['count'] == $item2['count']) return 0;
			return ($item1['count'] < $item2['count']) ? 1 : -1;
		}
	}
	
	