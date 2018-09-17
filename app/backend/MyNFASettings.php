<?php
	/**
	 * Created by PhpStorm.
	 * User: ali.shan
	 * Date: 9/6/2018
	 * Time: 9:22 PM
	 */
	
	namespace app\backend;
	
	if (! defined('ABSPATH'))
		exit;
	
	if (! class_exists('MyNFASettings')){
		class MyNFASettings
		{
			
			private static $settings_menu_slug = 'mynfa';
			private static $MyNFASettings = NULL;
			
			public function __construct ()
			{
				add_action('admin_menu', [$this, 'create_mynfa_settings_page'], 0);
			}
			
			public function create_mynfa_settings_page ()
			{
				add_menu_page(__('My NFA', 'mynfa'), __('My NFA', 'mynfa'), 'manage_options', self::getSettingsMenuSlug(), [$this, 'mynfa_settings_page_callback'], 'dashicons-admin-tools');
			}
			
			public function mynfa_settings_page_callback ()
			{
				ob_start();
				
				?>

					<div class="wrap">
						<h2 class="title">
							<?php echo sprintf("You can set Plugin Options by clicking on Settings Page or by Clicking <a href='".admin_url('admin.php?page=mynfa-plugin-settings')."' >Here</a>", 'mynfa'); ?>
						</h2>
						<div>
							<ul class="notice notice-success">
								<li>
									<p><?php _e('You can Enable / Disable debugging [For Developers Only].'); ?></p>
								</li>
								<li>
									<p><?php _e('You can add Mail Chimp API Key, Mail Chimp Form embedded code and Mail Chimp List ID.'); ?></p>
								</li>
								<li>
									<p><a href="<?php echo admin_url('admin.php?page=mynfa-plugin-settings'); ?>"><?php _e('Go to settings page.'); ?></a></p>
								</li>
							</ul>
						</div>
					</div>

				<?php
				
				echo ob_get_clean();
			}
			
			public static function singleton ()
			{
				if (self::$MyNFASettings instanceof self)
					return self::$MyNFASettings;
				
				return self::$MyNFASettings = new self;
			}
			
			/**
			 * @return string
			 */
			public static function getSettingsMenuSlug ()
			{
				return self::$settings_menu_slug;
			}
			
			
		}
	}
	