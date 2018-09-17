<?php
	if (!defined('ABSPATH'))
		exit;
	
	wp_enqueue_media();
	$badges_data = unserialize(get_option(\app\backend\MyNFABadges::getBadgesOptionSlug()));
 
	if ($_POST || isset($_GET['delete_badge_id']))
		include_once 'logics/add-badge-item.php';
	
	// DO NOT MOVE THESE INCLUDES FROM THESE LINES.
	if (isset($_GET['badge_id'])):
		include_once 'logics/edit-badge-item.php';
		return;
	endif;
?>


<div class="wrap">
    

    <div class="available-items-wrapper">
        <h3 class="wp-heading-inline"><?php _e('Available Badges', 'mynfa'); ?></h3>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
                <th><?php _e('S. #', 'mynfa'); ?></th>
                <th><?php _e('Min Referrals', 'mynfa'); ?></th>
                <th><?php _e('Max Referrals', 'mynfa'); ?></th>
                <th><?php _e('Badge', 'mynfa'); ?></th>
                <th><?php _e('Management', 'mynfa'); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($badges_data as $index => $badge_data) : ?>
			    <tr>
                    <td><?php echo ($index + 1) ; ?></td>
                    <td><?php echo $badge_data['min-count'] ; ?></td>
                    <td><?php echo $badge_data['max-count'] ; ?></td>
                    <td class="referral-image-wrapper">
                        <a href="<?php echo wp_get_attachment_url($badge_data['badge-image-id']); ?>" target="_blank"><img src="<?php echo wp_get_attachment_url($badge_data['badge-image-id']); ?>" /></a>
                    </td>
                    <td>
                        <span class="edit">
                            <a href="<?php echo admin_url('admin.php?page='.\app\backend\MyNFABadges::getBadgesPageSlug().'&badge_id='. $index); ?>" data-arr-index="<?php echo $index; ?>"><?php _e('Edit', 'mynfa'); ?></a>
                        </span>
                        <span class="remove">
                            <a href="<?php echo admin_url('admin.php?page='.\app\backend\MyNFABadges::getBadgesPageSlug().'&delete_badge_id='. $index); ?>" data-arr-index="<?php echo $index; ?>"><?php _e('Remove', 'mynfa'); ?></a>
                        </span>
                    </td>
                </tr>
			
			<?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div class="create-new-fields-wrapper">
        <h2 class="wp-heading-inline"><?php _e('Add new Item', 'mynfa'); ?></h2>
		<?php include_once 'logics/badge-error.php'; ?>

        <form action="<?php echo admin_url('admin.php?page=' . \app\backend\MyNFABadges::getBadgesPageSlug()); ?>"
              method="post">
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="min-count"><?php _e('Starting Count', 'mynfa'); ?></label>
                </th>
                <td>
                    <input min="0" step="1" type="number" id="min-count" class="regular-text min-count" name="min-count" placeholder="50"/>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="max-count"><?php _e('Ending Count', 'mynfa'); ?></label>
                </th>
                <td>
                    <input min="0" step="1" type="number" id="max-count" class="regular-text max-count" name="max-count" placeholder="100"/>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="badge-image"><?php _e('Badge Image', 'mynfa'); ?></label>
                </th>
                <td>
                    <img id='mynfa-badge-image-preview'
                         src='<?php echo MyNFA__PLUGIN_NAME__BACKEND_URL; ?>/assets/images/no-image.png' width='100'
                         height='100' style='max-height: 100px; width: 100px;'><br/>
                    <input id="mynfa_badge_upload_image_button" type="button" class="button"
                           value="<?php _e('Upload image'); ?>"/>
                    <input type='hidden' name='mynfa_badge_image_attachment_id' id='mynfa_badge_image_attachment_id'
                           value=''>
                </td>
            </tr>
            </tbody>
        </table>
			<?php wp_nonce_field('mynfa-badges-' . $_SERVER['REMOTE_ADDR'], 'mynfa-badges-nonce', false, true); ?>
            
            <p class="submit">
                <input class="button button-primary" type="submit" value="Add Item"/>
            </p>
            
            
        </form>

    </div>


</div>
	
	
	
	
	
