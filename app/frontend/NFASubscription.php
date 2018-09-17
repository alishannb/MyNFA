<?php
	/**
	 * Created by PhpStorm.
	 * User: ali.shan
	 * Date: 9/4/2018
	 * Time: 2:40 PM
	 */
	
	namespace app\frontend;
	
	
	use app\common\MyNFA_Init;
	use app\common\MyNFA_MailChimp;
	use DrewM\MailChimp\MailChimp;
	
	if (! class_exists('NFASubscription')){
		class NFASubscription
		{
			private static $NFASubscription = NULL;
			
			public function __construct ()
			{
				add_shortcode('nfa_generate_subscription_form', [$this, 'generate_form']);
			}
			
			public function generate_form ()
			{
				echo mynfa_get_redux_value('mailchimp_sign_up_form_html');
			}
			
			/**
			 *
			 * @return: array
			 *
			 */
			public function get_all_users ( $display_name_key = 'name' )
			{
				
				/*$users_arr = get_transient('mynfa_all_users_' . $display_name_key);
				if ($users_arr !== false)
					return $users_arr;*/
				
				$users = get_users();
				$users_arr = [];
				
				foreach ($users as $user){
					
					/*
					 * pw_user_status =>
					 * 					denied,
					 * 					approved
					 * */
					
					
					if (key_exists('administrator', $user->caps) || (strtolower(get_user_meta($user->ID, 'pw_user_status', true)) === 'approved'))
						$users_arr[] = [
							'id'	=>	 $user->ID,
							$display_name_key	=> 	 $user->display_name
						];
				}
				
				//set_transient('mynfa_all_users_' . $display_name_key, $users_arr, 3600);
				return $users_arr;
			}
			
			public function get_users_for_select2 ()
			{
				global $mynfa_obj;
				
				
				/*$users = get_transient('mynfa_users');
				if ($users === false){
					$users = $this->get_all_users('text');
					set_transient('mynfa_users', $users, 3600);
				}*/
				$users = $this->get_all_users('text');
				
				
				
				$users_arr = [];
				
				foreach ($users as $user){
					$subscribers = $mynfa_obj->mynfa_mailchimp()->get_subscribers_by_uid($user['id']);
					$count = count($subscribers['members']);
					
					$users_arr[] = [
						'id'	=> $user['text'] . ' (' . $user['id'] . ')',
						'text'	=> $user['text'] . ' (' . $count . ')',
						'count'	=> $count
					];
				}
				
				usort($users_arr, 'nfa_sort_callback');
				
				
				$first_user[] = [
					'id'	=> 0,
					'text'	=> '--- Select Users --- '
				] ;
				
				
				$select2_users = array_merge($first_user, $users_arr);
				
				return $select2_users;
			}
			
			public static function singleton ()
			{
				if (self::$NFASubscription instanceof self)
					return self::$NFASubscription;
				
				return self::$NFASubscription = new self;
			}
			
		}
	}