<?php
	/**
	 * Created by PhpStorm.
	 * User: ali.shan
	 * Date: 8/31/2018
	 * Time: 4:16 PM
	 */
	
	namespace app\common;
	
	
	use app\abstracts\MyNFA_Exception_Handler;
	use DrewM\MailChimp\MailChimp;
	
	if (!class_exists('MyNFA_MailChimp')) {
		class MyNFA_MailChimp
		{
			
			/**
			 * Useful Test variables.
			 * @param test_list_id string a18b1bc8b0
			 *
			 * */
			
			private static $mynfa_mailchimp = '';
			private $list_id;
			private static $member_count;
			private static $MyNFA_MailChimp = NULL;
			
			public function __construct ()
			{
				
				$this->list_id = mynfa_get_redux_value('mailchimp_list_id');
				self::$mynfa_mailchimp = new MailChimp(mynfa_get_redux_value('mailchimp_api_key'));
				
				self::setMemberCount(
					self::get_count_list_members($this->list_id)
				);
				
			}
			
			private static function get_count_list_members($list_id){
				$lists = self::$mynfa_mailchimp->get('lists');
				$lists = isset($lists['lists']) ? $lists['lists'] : [];
				
				$member_count = 0;
				
				foreach ($lists as $list) {
					if (intval($list['id']) === intval($list_id)){
						$member_count = $list['stats']['member_count'];
						break;
					}
				}
				
				return $member_count;
			}
			
			public function get_subscribers_by_uid ($uid, $list_id = '')
			{
				if (! empty($list_id))
					$this->list_id = $list_id;
				
				$subscribers = get_transient('mynfa_mc_subscribers');
				if ($subscribers === false){
					$subscribers = self::$mynfa_mailchimp->get("/lists/{$this->list_id}/members", ['count' => self::getMemberCount()]);
					set_transient('mynfa_mc_subscribers', $subscribers, 30);
				}
				$subscribers_valid_arr = isset($subscribers['members']) ? $subscribers['members'] : false;
				
				if (! $subscribers_valid_arr)
					return [];
				
				$subscribers_arr['members'] = [];
				
				foreach ($subscribers_valid_arr as $index => $subscriber) {
					
					$transient_mc_userid = get_transient('transient_mc_userid_' . $subscriber['merge_fields']['WP_REFERRA']);
					if ($transient_mc_userid === false){
						$transient_mc_userid = $this->get_uid_from_mailchimp_referral($subscriber['merge_fields']['WP_REFERRA']);
						set_transient('transient_mc_userid_' . $subscriber['merge_fields']['WP_REFERRA'], $transient_mc_userid, 0);
					}
					
					if (intval($transient_mc_userid) === intval($uid)){
						$subscribers_arr['members'][] = $subscriber;
					}
					
				}
				
				return $subscribers_arr;
			}
			
			public function get_current_user_subscribers ($list_id = '')
			{
				
				if (! is_user_logged_in())
					return [];
				
				if (! empty($list_id))
					$this->list_id = $list_id;
				
				$subscribers = get_transient('mynfa_mc_subscribers');
				if ($subscribers === false){
					$subscribers = self::$mynfa_mailchimp->get("/lists/{$this->list_id}/members", ['count' => self::getMemberCount()]);
					set_transient('mynfa_mc_subscribers', $subscribers, 30);
				}
				$subscribers_valid_arr = isset($subscribers['members']) ? $subscribers['members'] : false;
				
				if (! $subscribers_valid_arr)
					return [];
				
				$subscribers_arr['members'] = [];
				
				foreach ($subscribers_valid_arr as $index => $subscriber) {
					if (intval($this->get_uid_from_mailchimp_referral($subscriber['merge_fields']['WP_REFERRA'])) === get_current_user_id()){
						$subscribers_arr['members'][] = $subscriber;
					}
				}
				
				return $subscribers_arr;
			}
			
			public function get_all_subscribers ($list_id = '')
			{
				if (! empty($list_id))
					$this->list_id = $list_id;
				
				$subscribers = get_transient('mynfa_mc_subscribers');
				if ($subscribers === false){
					$subscribers = self::$mynfa_mailchimp->get("/lists/{$this->list_id}/members", ['count' => self::getMemberCount()]);
					set_transient('mynfa_mc_subscribers', $subscribers, 30);
				}
				
				
				$subscribers_valid_arr = isset($subscribers['members']) ? $subscribers['members'] : false;
				
				if (! $subscribers_valid_arr)
					return [];
				
				$subscribers_arr['members'] = [];
				
				foreach ($subscribers_valid_arr as $index => $subscriber) {
					
					$referral = $this->get_uid_from_mailchimp_referral($subscriber['merge_fields']['WP_REFERRA']);
					
					$user = get_transient('mynfa_wp_single_user_'.intval($referral));
					if ($user === false){
						$user = get_user_by('ID', intval($referral));
						set_transient('mynfa_wp_single_user_'.intval($referral), $user, 0);
					}
					$subscribers_arr['members'][] = array_merge(['referral_name' => $user->display_name, 'username' => $user->user_login, 'email' => $user->user_email], $subscriber);
					
				}
				
				
				return $subscribers_arr;
			}
			
			public function test_api ()
			{
				mynfa_debug(
					self::$mynfa_mailchimp->get('lists'),
					true,
					true
				);
			}
			
			public function create_field ($field_name, $field_title, $type = 'text', $size = 20, $list_id = '')
			{
				
				if (! empty($list_id))
					$this->list_id = $list_id;
				
				$field_name = str_replace(' ', '', $field_name);
				if (strlen($field_name) > 7){
					$error = MyNFA_Exception_Handler::handle("Field name length should be less than or equal to 7 characters.", '0x00001', __LINE__, __FILE__);
					
					return $error;
				}
				
				$output = self::$mynfa_mailchimp->post("/lists/{$this->list_id}/merge-fields", [
					'tag'	=>	$field_name,
					'name'	=>	$field_title,
					'type'	=>	$type,
					'size'	=>	$size,
				]);
				
				
				return $output;
			}
			
			public function create_user ($user_display_name, $user_email, $user_status = 'subscribed', $wp_user_id = 'n/a', $list_id)
			{
				if (! empty($list_id))
					$this->list_id = $list_id;
				
				
				$output = self::$mynfa_mailchimp->post("/lists/{$this->list_id}/members", [
					'email_address'	=>	$user_email,
					'status'	=>	$user_status,
					'wp_referral_uid'	=>	$user_display_name . ' (' . $wp_user_id . ')',
				]);
				
				
				return $output;
			}
			
			private function get_uid_from_mailchimp_referral ($referral)
			{
				$referral = explode(' (', $referral);
				
				if (isset($referral[1])){
					return trim($referral[1], ')');
				}
				
				return false;
			}
			public static function singleton ()
			{
				if (self::$MyNFA_MailChimp instanceof self)
					return self::getMyNFAMailChimp();
				
				return self::$MyNFA_MailChimp = new self;
				
			}
			
			/**
			 * @return mixed
			 */
			public static function getMemberCount ()
			{
				return self::$member_count;
			}
			
			/**
			 * @param mixed $member_count
			 */
			private static function setMemberCount ($member_count)
			{
				self::$member_count = $member_count;
			}
			
			/**
			 * @return null
			 */
			public static function getMyNFAMailChimp ()
			{
				return self::$MyNFA_MailChimp;
			}
			
		}
	}