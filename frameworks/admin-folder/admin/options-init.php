<?php
	
	/**
	 * For full documentation, please visit: http://docs.reduxframework.com/
	 * For a more extensive sample-config file, you may look at:
	 * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
	 */
	
	if (!class_exists('Redux')) {
		return;
	}
	
	
	// This is your option name where all the Redux data is stored.
	//@todo: always update option name for each plugin.
	$opt_name = "redux_mynfa_options";
	
	/**
	 * ---> SET ARGUMENTS
	 * All the possible arguments for Redux.
	 * For full documentation on arguments, please refer to:
	 * https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
	 * */
	
	//$theme = wp_get_theme(); // For use with some settings. Not necessary.
	
	$args = [
		'opt_name'              => $opt_name,
		'use_cdn'               => true,
		'display_name'          => 'MyNFA Settings',
		'display_version'       => false,
		'page_slug'             => 'mynfa-plugin-settings',
		'page_title'            => 'Settings',
		'update_notice'         => false,
		'admin_bar'             => false,
		'menu_type'             => 'submenu',
		'menu_title'            => 'Settings',
		'allow_sub_menu'        => true,
		'page_parent'           => 'mynfa', // 'edit.php?post_type=',
		'page_parent_post_type' => 'mynfa', // 'edit.php?post_type=',
		'customizer'            => true,
		'default_mark'          => '*',
		'hints'                 => [
			'icon'          => 'el el-adjust-alt',
			'icon_position' => 'left',
			'icon_color'    => 'lightgray',
			'icon_size'     => 'normal',
			'tip_style'     => [
				'color' => 'light',
			],
			'tip_position'  => [
				'my' => 'top left',
				'at' => 'bottom right',
			],
			'tip_effect'    => [
				'show' => [
					'duration' => '500',
					'event'    => 'mouseover',
				],
				'hide' => [
					'duration' => '500',
					'event'    => 'mouseleave unfocus',
				],
			],
		],
		'output'                => true,
		'output_tag'            => true,
		'settings_api'          => true,
		'cdn_check_time'        => '1440',
		'compiler'              => true,
		'page_permissions'      => 'manage_options',
		'save_defaults'         => true,
		'show_import_export'    => true,
		'database'              => 'options',
		'transient_time'        => '3600',
		'network_sites'         => true,
	];
	
	// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
	$args['share_icons'][] = [
		'url'   => 'https://www.facebook.com/',
		'title' => 'Like us on Facebook',
		'icon'  => 'el el-facebook'
	];
	$args['share_icons'][] = [
		'url'   => 'https://twitter.com/',
		'title' => 'Follow us on Twitter',
		'icon'  => 'el el-twitter'
	];
	$args['share_icons'][] = [
		'url'   => '',
		'title' => 'Find us on LinkedIn',
		'icon'  => 'el el-linkedin'
	];
	
	Redux::setArgs($opt_name, $args);
	
	/*
	 * ---> END ARGUMENTS
	 */
	
	/*
	 * ---> START HELP TABS
	 */
	
	
	/*
	 *
	 * ---> START SECTIONS
	 *
	 */
	
	
	Redux::setSection($opt_name, [
		'title' => __('Debugging', 'redux-framework-demo'),
		'id'    => 'debugging-main-section',
		'desc'  => __('Debugging main section.', 'redux-framework-demo'),
		'icon'  => 'el el-wrench',
	]);
	
	Redux::setSection($opt_name, [
		'title'      => __('Turn On Debugging', 'redux-framework-demo'),
		'id'         => 'debugging-inner-section',
		'desc'       => __('Select which type of debugging you want to enable.', 'redux-framework-demo'),
		'subsection' => true,
		'fields'     => [
			[
				'id'      => 'debugging_checkboxes',
				'type'    => 'radio',
				'title'   => __('Do you want to save warning, debug and error logs?', 'redux-framework-demo'),
				
				//Must provide key => value pairs for multi checkbox options
				'options' => [
					'yes'   => 'Yes',
					'no'  => 'No'
				],
				
				//See how default has changed? you also don't need to specify opts that are 0.
				'default' => 'no'
			],
			[
				'id'          => 'debugging_file_path',
				'type'        => 'text',
				'title'       => __('Debugging File Path', 'redux-framework-demo'),
				'description' => '<a target="_blank" href="' . wp_slash(plugin_dir_url(dirname(dirname(__DIR__)))) . 'debugging/read-log.php?sc=ML6%9t{6W^$2dsaR3/mY9F@">Click here to read file</a>',
				'default'     => dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'logs.html',
				'readonly'    => true
			]
		]
	]);
	
	
	// General Section
	Redux::setSection($opt_name, [
		'title' => __('Mail Chimp', 'redux-framework-demo'),
		'id'    => 'mail-chimp-main-section',
		'desc'  => __('Mail Chimp main section.', 'redux-framework-demo'),
		'icon'  => 'el el-wrench',
	]);
	Redux::setSection($opt_name, [
		'title'      => __('API Keys', 'redux-framework-demo'),
		'id'         => 'mail-chimp-inner-section',
		'desc'       => __('Use API Keys for different services.', 'redux-framework-demo'),
		'subsection' => true,
		'fields'     => [
			[
				'id'          => 'mailchimp_api_key',
				'type'        => 'text',
				'title'       => __('Enter the Mail Chimp API Key', 'redux-framework-demo'),
				'default'     => '71c7eb34060c657c7fd90813118a86a9-us19',
				'description' => 'You can get Mail Chimp API key from <a href="https://mailchimp.com/help/about-api-keys/" target="_blank">Here</a>'
			],
			[
				'id'          => 'mailchimp_sign_up_form_html',
				'type'        => 'textarea',
				'title'       => __('Enter the Mail Chimp Sign Up form HTML', 'redux-framework-demo'),
				'default'     => '',
				'description' => 'You can get Mail Chimp Sign Up Form html from <a href="https://mailchimp.com/help/add-a-signup-form-to-your-website/" target="_blank">Here</a>'
			],
			[
				'id'          => 'mailchimp_list_id',
				'type'        => 'text',
				'title'       => __('Enter the list ID that you want to connect with this plugin.', 'redux-framework-demo'),
				'default'     => '',
				'description' => 'You can get Mail Chimp List ID from <a href="https://mailchimp.com/help/find-your-list-id/" target="_blank">Here</a>'
			],
		]
	]);
	
	/*
	 * <--- END SECTIONS
	 */
