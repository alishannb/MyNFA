<?php
	
	if (!defined('ABSPATH')) exit;
	
	
	$current_badge_data = $badges_data[ (intval($_GET['badge_id'])) ]

?>

<div class="create-new-fields-wrapper">

    <h2 class="wp-heading-inline"><?php _e('Edit Item', 'mynfa'); ?></h2>
	<?php include_once 'badge-error.php'; ?>
    
    <form action="<?php echo admin_url('admin.php?page=' . \app\backend\MyNFABadges::getBadgesPageSlug()); ?>&badge_id=<?php echo intval($_GET['badge_id']); ?>"
          method="post">

        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="min-count"><?php _e('Starting Count', 'mynfa'); ?></label>
                </th>
                <td>
                    <input min="0" step="1" type="number" id="min-count" value="<?php echo $current_badge_data['min-count']; ?>" class="regular-text min-count" name="min-count" placeholder="50"/>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="max-count"><?php _e('Ending Count', 'mynfa'); ?></label>
                </th>
                <td>
                    <input min="0" step="1" type="number" id="max-count" value="<?php echo $current_badge_data['max-count']; ?>" class="regular-text max-count" name="max-count" placeholder="100"/>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="badge-image"><?php _e('Badge Image', 'mynfa'); ?></label>
                </th>
                <td>
                    <img id='mynfa-badge-image-preview'
                         src="<?php echo wp_get_attachment_url($current_badge_data['badge-image-id']); ?>" width='100'
                         height='100' style='max-height: 100px; width: 100px;'><br/>
                    <input id="mynfa_badge_upload_image_button" type="button" class="button"
                           value="<?php _e('Upload image'); ?>"/>
                    <input type='hidden' name='mynfa_badge_image_attachment_id' id='mynfa_badge_image_attachment_id'
                           value="<?php echo $current_badge_data['badge-image-id']; ?>">
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