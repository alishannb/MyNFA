<?php
	/**
	 * Created by PhpStorm.
	 * User: ali.shan
	 * Date: 9/3/2018
	 * Time: 10:28 PM
	 */
	
	namespace app\frontend;
	
	
	if (! class_exists('NFARegister')){
		class NFARegister
		{
			private static $NFARegister = NULL;
			
			public function __construct ()
			{
				add_shortcode('nfa_registration', [$this, 'registration']);
			}
			
			public function registration ()
			{
				ob_start();
				include_once 'includes/register.php';
				echo ob_get_clean();
			}
			
			public static function singleton ()
			{
				if (self::$NFARegister instanceof self)
					return self::$NFARegister;
				
				return self::$NFARegister = new self;
			}
		}
		
	}