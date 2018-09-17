<?php
	/**
	 * Created by PhpStorm.
	 * User: ali.shan
	 * Date: 9/6/2018
	 * Time: 3:16 PM
	 */
	
	namespace app\frontend;
	
	if (! defined('ABSPATH'))
		exit;
	
	if (! class_exists('MyNFALogin')){
		class MyNFALogin
		{
			private static $MyNFALogin = NULL;
			
			public function __construct ()
			{
				add_shortcode('nfa_login', [$this, 'render_login']);
			}
			
			public function render_login ()
			{
				ob_start();
				include_once 'includes/login-page.php';
				echo ob_get_clean();
			}
			
			public static function singleton ()
			{
				if (self::$MyNFALogin instanceof self)
					return self::$MyNFALogin;
				
				return self::$MyNFALogin = new self;
			}
		}
	}
	