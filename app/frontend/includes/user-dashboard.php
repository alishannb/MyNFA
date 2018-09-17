<?php

if (! defined('ABSPATH') ) exit;
	
	if (! is_user_logged_in()) :
		
		echo '<h3>You are not login.<br />Please <a href="'.site_url('/login').'">login</a> to continue';
		
		return;
	endif;


global $mynfa_obj;

$mail_chimp_subscribers = $mynfa_obj->mynfa_mailchimp()->get_current_user_subscribers();
$all_subscirbers = $mynfa_obj->mynfa_mailchimp()->get_all_subscribers();
$all_subscirbers = $all_subscirbers['members'];

$mc_subscribers = isset($mail_chimp_subscribers['members']) ? $mail_chimp_subscribers['members'] : false;
$current_user_referrals = count($mc_subscribers);

$badges = \app\backend\MyNFABadges::get_badges_options_array();
$user_badge_image_id = '';

foreach ($badges as $badge_arr){
    
    if ( intval($current_user_referrals) >= intval($badge_arr['min-count']) && intval($current_user_referrals) <=  intval($badge_arr['max-count'])){
        $user_badge_image_id = $badge_arr['badge-image-id'];
        break;
    }
}


?>

<div class="dashboard-wrapper">

    <div class="user-summary">
        <h2><?php echo __('Welcome ', 'mynfa') . ucfirst(wp_get_current_user()->display_name) . '(' . wp_get_current_user()->user_login . ')'. '!'; ?></h2>
    </div>

    <div class="chart-container">
        <div class="pie-chart-wrapper">
            <canvas id="pie-chart"  ></canvas>
        </div>
    </div>

    <div class="dashboard-summary">
        <h3><?php _e('My Referrals:', 'mynfa'); ?><small><?php echo $current_user_referrals; ?></small></h3>
        <div class="badge-wrapper">
            <img src="<?php echo wp_get_attachment_url($user_badge_image_id); ?>" />
        </div>
    </div>
    
    <div class="clear"></div>
    
	<div class="referrals-list">
		<table id="dashboard-referrals-table" class="responsive table table-striped table-bordered" style="width:100%">
			<thead>
			<tr>
				<th><?php _e('ID', 'mynfa'); ?></th>
				<th><?php _e('First Name', 'mynfa'); ?></th>
				<th><?php _e('Last Name', 'mynfa'); ?></th>
				<th><?php _e('Email', 'mynfa'); ?></th>
				<th><?php _e('Phone', 'mynfa'); ?></th>
				<th><?php _e('Registration Date', 'mynfa'); ?></th>
			</tr>
			</thead>
			<tbody>
				<?php if ($mc_subscribers) : ?>
					<?php foreach ($mc_subscribers as $index => $mc_subscriber) :  ?>
						<tr>
							<td><?php echo ($index + 1) ;?></td>
							<td><?php echo $mc_subscriber['merge_fields']['FNAME']; ?></td>
							<td><?php echo $mc_subscriber['merge_fields']['LNAME'];?></td>
							<td><?php echo $mc_subscriber['email_address'];?></td>
							<td><?php echo $mc_subscriber['merge_fields']['PHONE']; ?></td>
							<td><?php echo  date('m-d-Y', strtotime($mc_subscriber['timestamp_opt'])); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
				
					<tr>
						<td colspan="6">
							<?php _e('No Subscriber found', 'mynfa'); ?>
						</td>
					</tr>
				
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
    jQuery(document).ready(function ($){
        new Chart(document.getElementById("pie-chart"), {
            type: 'pie',
            data: {
                labels: ["<?php echo wp_get_current_user()->display_name; ?>", "ALL Referrals"],
                datasets: [{
                    label: "Referrals",
                    backgroundColor: ["#3e95cd", "#8e5ea2"],
                    data: [<?php echo $current_user_referrals; ?>,<?php echo count($all_subscirbers); ?>]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Total Referrals Chart'
                }
            }
        });
        
    });
</script>