<?php
	
	namespace app\common;
	
	use app\backend\MyNFABadges;
	use app\backend\MyNFASettings;
	use app\frontend\MyNFALogin;
	use app\frontend\NFADashboard;
	use app\frontend\NFARegister;
	use app\frontend\NFASubscription;
	use DrewM\MailChimp\MailChimp;
	
	if (!defined('ABSPATH'))
		exit;
	
	//@todo: update class and file name.
	if (!class_exists('MyNFA_Init')) {
		class MyNFA_Init
		{
			
			private static $pluginpath;
			public static $nfa_init_obj;
			
 		
			public function __construct ()
			{
				// DO NOT DELETE THESE
				add_action('init', [$this, 'constants'], 5);
				add_action('init', [$this, 'inc_debug_abstract_class'], 5);
				add_action('init', [$this, 'inc_traits'], 5);
				
				// insert other classes here using init hook
				
				// FrontEnd
				add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
				add_action('init', [$this, 'mynfa_subscription']);
				add_action('init', [$this, 'mynfa_dashbaord']);
				add_action('init', [$this, 'nfa_login'], 0);
				add_action('init', [$this, 'mynfa_registration']);
				
				// Backend
				if (is_admin()){
					add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
					add_action('init', [$this, 'settings_page'], 0);
					add_action('init', [$this, 'nfa_badges']);
				}
				
				add_action('init', [$this, 'mynfa_mailchimp']);
			}
			
			public function admin_enqueue_scripts ()
			{
				wp_enqueue_style('mynfa-plugin-backend-style', MyNFA__PLUGIN_NAME__BACKEND_URL . '/assets/css/mynfa-plugin-backend.css');
			}
			
			public function nfa_badges ()
			{
				return MyNFABadges::singleton();
			}
			
			public function settings_page ()
			{
				return MyNFASettings::singleton();
			}
			
			public function nfa_login ()
			{
				return MyNFALogin::singleton();
			}
			
			public function mynfa_dashbaord ()
			{
				return NFADashboard::singleton();
			}
			
			public function mynfa_subscription ()
			{
				return NFASubscription::singleton();
			}
			
			public function mynfa_registration ()
			{
				return NFARegister::singleton();
			}
			
			public function mynfa_mailchimp ()
			{
				return MyNFA_MailChimp::singleton();
			}
			
			public function enqueue_scripts ()
			{
				global $post;
				
				wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
				wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css');
				wp_enqueue_style('data-tables', '//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css');
				wp_enqueue_style('mynfa-plugin', MyNFA__PLUGIN_NAME__FRONTEND_URL . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'mynfa-plugin.css');
				
				
				
				wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', ['jquery'], '4.1.3', true);
				wp_enqueue_script('select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.full.min.js', ['jquery'], '4.0.6', true);
				wp_enqueue_script('data-tables-js', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', ['jquery'], '1.10.19', true);
				wp_enqueue_script('chartjs', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js', ['jquery'], '2.7.2', true);
				wp_enqueue_script('mynfa-plugin', MyNFA__PLUGIN_NAME__FRONTEND_URL . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'mynfa-plugin.js', ['jquery'], time(), true);
				
				if ($post->post_name === 'subscribe'){
					$all_users = $this->mynfa_subscription()->get_users_for_select2();
					wp_localize_script('mynfa-plugin', 'mynfa_plugin', ['users_record' => $all_users]);
				}
				
			}
			
			/*
			 * @todo: Update plugin name in all below strings "__PLUGIN_NAME__"
			 *
			 * @todo: search all entries that have "__PLUGIN_NAME__" - Update according to your plugin name.
			 *
			 *
			 * */
			public function constants ()
			{
				define('MyNFA__PLUGIN_NAME__PLUGIN_FILE_PATH', self::getPluginpath());
				
				define('MyNFA__PLUGIN_NAME__PLUGIN_PATH', dirname(self::getPluginpath()));
				define('MyNFA__PLUGIN_NAME__PLUGIN_URL', plugins_url('', self::getPluginpath()));
				
				define('MyNFA__PLUGIN_NAME__FRONTEND_PATH', MyNFA__PLUGIN_NAME__PLUGIN_FILE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'frontend');
				define('MyNFA__PLUGIN_NAME__FRONTEND_URL', trailingslashit(MyNFA__PLUGIN_NAME__PLUGIN_URL) . 'app/frontend');
				
				define('MyNFA__PLUGIN_NAME__BACKEND_PATH', MyNFA__PLUGIN_NAME__PLUGIN_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'backend');
				define('MyNFA__PLUGIN_NAME__BACKEND_URL', trailingslashit(MyNFA__PLUGIN_NAME__PLUGIN_URL) . 'app/backend');
				
				define('MyNFA__PLUGIN_NAME__DEBUGGING_PATH', MyNFA__PLUGIN_NAME__PLUGIN_PATH . DIRECTORY_SEPARATOR . 'debugging');
				define('MyNFA__PLUGIN_NAME__DEBUGGING_URL', trailingslashit(MyNFA__PLUGIN_NAME__PLUGIN_URL) . 'debugging');
				
				define('MyNFA__PLUGIN_NAME__ABSTRACT_PATH', MyNFA__PLUGIN_NAME__PLUGIN_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'abstracts');
				define('MyNFA__PLUGIN_NAME__ABSTRACT_URL', trailingslashit(MyNFA__PLUGIN_NAME__PLUGIN_URL) . 'app/abstracts');
				
				define('MyNFA__PLUGIN_NAME__TRAITS_PATH', MyNFA__PLUGIN_NAME__PLUGIN_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'traits');
				define('MyNFA__PLUGIN_NAME__TRAITS_URL', trailingslashit(MyNFA__PLUGIN_NAME__PLUGIN_URL) . 'app/traits');
				
				
				define('MyNFA__PLUGIN_NAME__VERSION', '1.0.0');
				define('MyNFA__PLUGIN_NAME__MIN_PHP_VER', '5.6');
				define('MyNFA__PLUGIN_NAME__TEXT_DOMAIN', 'therightsol');
			}
			
			
			public function inc_debug_abstract_class ()
			{
				if (file_exists(MyNFA__PLUGIN_NAME__ABSTRACT_PATH . DIRECTORY_SEPARATOR . 'MyNFA_Exception_Handler.php'))
					require_once MyNFA__PLUGIN_NAME__ABSTRACT_PATH . DIRECTORY_SEPARATOR . 'MyNFA_Exception_Handler.php';
			}
			
			public function inc_traits ()
			{
				if (file_exists(MyNFA__PLUGIN_NAME__TRAITS_PATH . DIRECTORY_SEPARATOR . 'MyNFA_Queries.php'))
					require_once MyNFA__PLUGIN_NAME__TRAITS_PATH . DIRECTORY_SEPARATOR . 'MyNFA_Queries.php';
			}
			
			public static function plugin_activated ()
			{
				/*
				 * DO NOT DELETE BELOW CHECKS.
				 *
				 * */
				
				if ( version_compare( get_bloginfo('version'), '4.6', '<') )  {
					$message = "Sorry! Impossible to activate plugin. <br />";
					$message .= "This Plugin requires at least WP Version 4.6";
					die( $message );
				}
				
				if (version_compare(PHP_VERSION, '5.5', '<')){
					$message = "Sorry! Impossible to activate plugin. <br />";
					$message .= "This Plugin requires minimum PHP Version 5.5.0";
					die( $message );
				}
				
				
				
				
				
				/*
				 * DO WHAT YOU WANT ON PLUGIN ACTIVATION.
				 *
				 * */
				
				
			}
			
			
			
			public static function plugin_deactivated ()
			{
				
				/*
				 * DO WHAT YOU WANT ON PLUGIN DEACTIVATION.
				 *
				 * */
				
			}
			
			public static function app ($filepath)
			{
				register_activation_hook($filepath, [MyNFA_Init::class, 'plugin_activated']);
				register_deactivation_hook($filepath, [MyNFA_Init::class, 'plugin_deactivated']);
				
				
				self::$nfa_init_obj = new self;
				self::$nfa_init_obj->setPluginpath($filepath);
				
				return self::$nfa_init_obj;
			}
			
			
			
			
			/*---------------------------- Setters and Getters ------------------------------------------------------*/
			
			/**
			 * @return mixed
			 */
			public static function getPluginpath ()
			{
				return self::$pluginpath;
			}
			
			/**
			 * @param mixed $pluginpath
			 */
			protected static function setPluginpath ($pluginpath)
			{
				self::$pluginpath = $pluginpath;
			}
			
		}
	}