<?php
	
	if (!defined('ABSPATH')) exit;
	
	$posted_data = filter_input_array(INPUT_POST);
	
	
	if (! isset($_GET['delete_badge_id'])):
        if (!isset($posted_data['min-count'], $posted_data['max-count'], $posted_data['mynfa_badge_image_attachment_id'])
            || empty($posted_data['min-count']) || empty($posted_data['max-count']) || empty($posted_data['mynfa_badge_image_attachment_id'])) {
            $nfa_badges_error = new WP_Error('missing-fields', __("<strong>Error:</strong> Required fields are missing. Please fill all fields."));
            
            return $nfa_badges_error;
        }
    
        if (isset($posted_data['mynfa-badges-nonce']) && ! wp_verify_nonce($posted_data['mynfa-badges-nonce'], 'mynfa-badges-' . $_SERVER['REMOTE_ADDR'])){
            $nfa_badges_error = new WP_Error('nonce-mismatched', __("<strong>Error:</strong> Some internal error. Please refresh your page."));
            
            return $nfa_badges_error;
        }
	endif;
	
	
	$badges_data = unserialize(get_option(\app\backend\MyNFABadges::getBadgesOptionSlug()));
	
	if (isset($_GET['badge_id'])){
		unset($badges_data[(intval($_GET['badge_id']))]);
		$badges_data[intval($_GET['badge_id'])] = [
			'min-count'     => $posted_data['min-count'],
			'max-count'     => $posted_data['max-count'],
			'badge-image-id' => $posted_data['mynfa_badge_image_attachment_id']
		];
		
	}else if (isset($_GET['delete_badge_id']) && ($_GET['delete_badge_id'] !== FALSE)){
		
		unset($badges_data[(intval($_GET['delete_badge_id']))]);
		
	}else{
		$badges_data[] = [
			'min-count'     => $posted_data['min-count'],
			'max-count'     => $posted_data['max-count'],
			'badge-image-id' => $posted_data['mynfa_badge_image_attachment_id']
		];
	}
	
	sort($badges_data);
	
	update_option('mynfa_badges', serialize($badges_data));
	
	$badges_data = unserialize(get_option(\app\backend\MyNFABadges::getBadgesOptionSlug()));
	
	?>
<script>
	document.location.href = "<?php echo admin_url('admin.php?page=' . \app\backend\MyNFABadges::getBadgesPageSlug()); ?>";
</script>
	