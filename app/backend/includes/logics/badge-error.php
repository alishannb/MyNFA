<?php
	
	if (!defined('ABSPATH')) exit;
	
	if (isset($nfa_badges_error) && $nfa_badges_error instanceof WP_Error) {
		
		echo '<ul class="errors-wrapper notice notice-error">';
		
		foreach ($nfa_badges_error->get_error_messages() as $error_message) {
			echo '<li>' . $error_message . '</li>';
		}
		
		echo '</ul>';
	}