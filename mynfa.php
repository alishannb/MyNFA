<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	/**
	 * Network for Animals
	 *
	 * @package     NFA
	 * @author      Ali Shan
	 * @copyright   2017 © All rights are reserved
	 * @license     GPL-3.0+
	 *
	 * @wordpress-plugin
	 * Plugin Name: My Network for Animals
	 * Plugin URI:  http://presstigers.com
	 * Description: Custom WP Plugin for Network for Animals according to requirements..
	 * Version:     1.0.0
	 * Author:      Ali Shan
	 * Author URI:  #
	 * Text Domain: mynfa
	 * License:     GPL-3.0+
	 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
	 */
	
	use app\common\MyNFA_Init;
	
	global $mynfa_obj;
	
	if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php' ) ) {
		require_once 'vendor/autoload.php';
	}
	
	
	if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . 'frameworks' . DIRECTORY_SEPARATOR . 'admin-folder' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'admin-init.php' ) ) {
		require_once __DIR__ . DIRECTORY_SEPARATOR . 'frameworks' . DIRECTORY_SEPARATOR . 'admin-folder' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'admin-init.php';
	}
	
	
	$mynfa_obj = MyNFA_Init::app( __FILE__ );
	