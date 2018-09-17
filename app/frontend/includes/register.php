<div id="login-register-password" class="mynfa-register-form">
	
	<?php
		 global $user_ID, $user_identity;
		
		if (!$user_ID) :
			
			?>

            <h3><?php _e('Register for this site!', 'mynfa'); ?></h3>
            <p><?php _e('Sign up now for the good stuff.', 'mynfa'); ?></p>
			
			<?php
			
			if (!empty($_POST)) {
				include_once 'logics/register-user.php';
				
				if (!empty($nfa_registration_errors)) {
					echo '<ul class="nfa-registration-errors">';
					foreach ($nfa_registration_errors->get_error_messages() as $error) {
						echo '<li>' . $error . '</li>';
					}
					echo '</ul>';
				}
			}
			
			
			?>

            <form method="post"
                  action="<?php echo site_url('register') ?>"
                  class="wp-user-form">
                <div class="username">
                    <label for="user_login"><?php _e('Username'); ?>: </label>
                    <input type="text" name="user_login"
                        autofocus class="input form-control"  value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20"
                           id="user_login" tabindex="101"/>
                </div>
                <div class="password">
                    <label for="user_email"><?php _e('Your Email'); ?>: </label>
                    <input type="text" name="user_email" class="input form-control"
                           value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25"
                           id="user_email" tabindex="102"/>
                </div>
                <div class="first_name">
                    <label for="first_name"><?php _e('First Name', 'mynfa') ?>:<br/>
                        <input type="text" name="first_name" id="first_name" class="input form-control"
                               value="<?php echo esc_attr($first_name); ?>" size="25" tabindex="103"/></label>
                </div>
                <div class="last_name">
                    <label for="last_name"><?php _e('Last Name', 'mynfa') ?>:<br/>
                        <input type="text" name="last_name" id="last_name" class="input form-control"
                               value="<?php echo esc_attr($last_name); ?>" size="25"  tabindex="104" /></label>
                </div>
				<?php
					wp_nonce_field('mynfa-registration-action-' . $_SERVER['REMOTE_ADDR'], 'mynfa-registration-nonce', true, true);
				?>
                <div class="login_fields">
					<?php do_action('register_form'); ?>
                    <input type="submit" name="user-submit" value="<?php _e('Sign up!'); ?>"
                           class="user-submit btn btn-info" tabindex="105"/>
					<?php
						if (isset($new_user_id) && !empty($new_user_id))
							echo '<p>' . __('Your account is created but pending for admin approval. Once admin approve your account. You will get an email to set your password.', 'mynfa') . '</p>';
                     ?>
                </div>
            </form>
		
		<?php else: // is logged in  ?>

    <div class="sidebox">
        <h3><?php _e('Welcome,', 'mynfa'); ?><?php echo $user_identity; ?></h3>
        <div class="usericon">
			<?php global $userdata;
				echo get_avatar($userdata->ID, 60); ?>

        </div>
        <div class="userinfo">
            <p>You&rsquo;re logged in as <strong><?php echo $user_identity; ?></strong></p>
            <p>
                <a href="<?php echo wp_logout_url('index.php'); ?>"><?php _e('Log out', 'mynfa'); ?></a> |
				<?php if (current_user_can('manage_options')) {
					echo '<a href="' . admin_url() . '">' . __('Admin') . '</a>';
				} else {
					echo '<a href="' . admin_url() . 'profile.php">' . __('Profile') . '</a>';
				} ?>

            </p>
        </div>
    </div>
	
	<?php endif; ?>

</div>