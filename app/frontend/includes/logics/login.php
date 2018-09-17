<?php
	
	if (!defined('ABSPATH')) exit;
	
	//@todo: there is some error in Nonce and user is not logging in.
	
	
	$posted_data = filter_input_array(INPUT_POST);
	
	
	if (!isset($posted_data['user_email'], $posted_data['user_password']) || empty($posted_data['user_email']) || empty($posted_data['user_password'])) {
		
		$nfa_login_errors = new WP_Error('required_field_error', __('<strong>ERROR</strong>: All fields are required. Please fill all fields.', 'mynfa'));
		
		return $nfa_login_errors; // No need to continue
	}
	
	if (!isset($posted_data['mynfa-login-form-nonce']) || !wp_verify_nonce($posted_data['mynfa-login-form-nonce'], 'mynfa-login-form-' . $_SERVER['REMOTE_ADDR'])) {
		
		$nfa_login_errors = new WP_Error('nonce-not-match', __('<strong>ERROR</strong>: Some internal error occurred.', 'mynfa'));
		
		return $nfa_login_errors; // no need to continue.
	}
	
	if (!is_email($posted_data['user_email'])) {
		
		$nfa_login_errors = new WP_Error('email_not_valid', __('<strong>ERROR</strong>: Email is not valid. Email should be valid.', 'mynfa'));
		
		
		return $nfa_login_errors; // no need to continue.
	}
	
	if (!email_exists($posted_data['user_email'])) {
		
		$nfa_login_errors = new WP_Error('user_not_exists', __('<strong>ERROR</strong> Provided Username/password was wrong.', 'mynfa'));
		
		return $nfa_login_errors; // no need to continue.
	}
	
	
	$user = wp_signon(
		[
			'user_password' => $posted_data['user_password'],
			'user_login'    => $posted_data['user_email']
		], false
	);
	
	
	if (is_wp_error($user))
		return $nfa_login_errors = $user;
	
	 
	
	
//	wp_clear_auth_cookie();
//	wp_set_current_user( $user->ID, $user->user_login );
//	wp_set_auth_cookie( $user->ID, true, false );
	
	/*do_action( 'wp_login', $user->user_login );
	
	mynfa_debug($user, true, true);*/
	
	