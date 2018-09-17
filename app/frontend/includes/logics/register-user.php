<?php
	
	if (! defined('ABSPATH')) exit;
	
	$posted_data = filter_input_array(INPUT_POST);
	
	if (! isset($posted_data['user_login'], $posted_data['user_email'], $posted_data['first_name'], $posted_data['last_name']) || empty($posted_data['user_login']) || empty($posted_data['user_email']) || empty($posted_data['first_name']) || empty($posted_data['last_name'])){
		
		$nfa_registration_errors = new WP_Error( 'required_field_error', __( '<strong>ERROR</strong>: All fields are required. Please fill all fields.', 'mynfa' ) );
		
		return $nfa_registration_errors; // No need to continue
	}
	
	if (!isset($posted_data['mynfa-registration-nonce']) || !wp_verify_nonce($posted_data['mynfa-registration-nonce'], 'mynfa-registration-action-' . $_SERVER['REMOTE_ADDR'])) {
		
		$nfa_registration_errors = new WP_Error('nonce-not-match', __('<strong>ERROR</strong>: Some internal error occurred.', 'mynfa'));
		
		return $nfa_registration_errors; // no need to continue.
	}
	
	if (!is_email($posted_data['user_email'])) {
		
		$nfa_registration_errors = new WP_Error('email_not_valid', __('<strong>ERROR</strong>: Email is not valid. Email should be valid.', 'mynfa'));
		
		
		return $nfa_registration_errors; // no need to continue.
	}
	
	if (email_exists($posted_data['user_email'])) {
		
		$nfa_registration_errors = new WP_Error('user_exists', __('This email is already registered. Please login to continue.', 'mynfa'));
		
		return $nfa_registration_errors; // no need to continue.
	}
	
	$user_data = [
		'user_login' => $posted_data['user_login'],
		'user_email' => $posted_data['user_email'],
		'first_name'	=> $posted_data['first_name'],
		'last_name'	=> $posted_data['last_name']
	];
	
	
	
	// Register User.
	$new_user_id = wp_insert_user($user_data);