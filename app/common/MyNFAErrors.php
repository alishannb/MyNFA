<?php
	/**
	 * Created by PhpStorm.
	 * User: ali.shan
	 * Date: 9/5/2018
	 * Time: 4:03 PM
	 */
	
	namespace app\common;
	
	if (! defined('ABSPATH'))
		exit;
	
	if (! class_exists('MyNFAErrors')){
		class MyNFAErrors
		{
			public $errors;
			
			public function __construct ()
			{
			
			}
			
			public function print_errors ()
			{
			
			}
		}
	}