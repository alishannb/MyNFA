<?php
	/**
	 * Created by PhpStorm.
	 * User: ali.shan
	 * Date: 9/5/2018
	 * Time: 5:12 PM
	 */
	
	namespace app\frontend;
	
	if (! defined('ABSPATH'))
		exit;
	
	if (! class_exists('NFADashboard')){
		class NFADashboard
		{
			private static $NFADashboard = NULL;
			
			public function __construct ()
			{
				add_shortcode('mynfa-dashboard', [$this, 'render_dashboard']);
			}
			
			public function render_dashboard ()
			{
				
				$file = 'includes/user-dashboard.php';
				
				if (isset(wp_get_current_user()->roles) && is_array(wp_get_current_user()->roles)) {
					if (in_array('administrator', wp_get_current_user()->roles)) {
						$file =  'includes/admin-dashboard.php';
					}
				}
				
				ob_start();
				include_once $file;
				echo ob_get_clean();
			}
			
			public static function singleton ()
			{
				if (self::$NFADashboard instanceof self)
					return self::$NFADashboard;
				
				return self::$NFADashboard = new self;
			}
		}
	}