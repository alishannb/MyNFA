<?php 

if (! defined('ABSPATH') ) exit;

//@todo: pending custom login logics.


if (! is_user_logged_in()) :
	
	if (! empty($_POST))
		require_once 'logics/login.php';

	if (isset($nfa_login_errors) && !empty($nfa_login_errors) && is_a($nfa_login_errors, WP_Error::class)){
		echo '<ul class="login-errors">';
		foreach ($nfa_login_errors->get_error_messages() as $error_message)
			echo '<li>' . $error_message . '</li>';
		echo '</ul>';
	}
	
?>
    <div class="login-form">
	    <?php wp_login_form( array('redirect' => home_url('/dashboard')) ); ?>
    </div>

<?php else : ?>

<div class="login-wrapper">
	<h3><?php _e('You are already logged in.', 'mynfa'); ?></h3>
</div>

<?php endif; ?>