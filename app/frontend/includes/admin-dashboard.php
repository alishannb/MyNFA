<?php

if (! defined('ABSPATH') ) exit;

if (! is_user_logged_in()) :
    
    echo '<h3>You are not login. Please <a href="'.site_url('/login').'">login</a> to continue';
    
    return;
endif;


global $mynfa_obj;

$mail_chimp_subscribers = $mynfa_obj->mynfa_mailchimp()->get_current_user_subscribers();
$mc_subscribers = isset($mail_chimp_subscribers['members']) ? $mail_chimp_subscribers['members'] : false;

$all_subscribers = $mynfa_obj->mynfa_mailchimp()->get_all_subscribers();
$all_mc_subscribers = isset($all_subscribers['members']) ? $all_subscribers['members'] : false;

$users = nfa_get_all_subscribers_users();

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
		<h3><?php _e('My Referrals:', 'mynfa'); ?><small><?php echo count($mc_subscribers); ?></small></h3>
		
	</div>

    <div class="clear"></div>

    <ul class="nav nav-tabs dashboard-tabs">
        <li class="nav-item"><a class="nav-link active" href="#my-referrals" role="tab" aria-controls="my-referrals" aria-selected="true"><?php _e('My Referrals:', 'mynfa'); ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#all-referrals" role="tab" aria-controls="all-referrals"><?php _e('All Referrals', 'mynfa'); ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#all-affiliates-wrapper" role="tab" aria-controls="all-affiliates-wrapper"><?php _e('All Affiliates', 'mynfa'); ?></a></li>
    </ul>

    <div class="tab-content">
        <div id="my-referrals" class="tab-pane fade show active" role="tabpanel" aria-labelledby="my-referrals">
            <div class="referrals-list">
                <table id="dashboard-referrals-table" class="dt responsive table table-striped table-bordered" style="width:100%">
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
						<?php foreach ($mc_subscribers as $index => $mc_subscriber) : ?>
                            <tr>
                                <td><?php echo ($index + 1) ;?></td>
                                <td><?php echo $mc_subscriber['merge_fields']['FNAME']; ?></td>
                                <td><?php echo $mc_subscriber['merge_fields']['LNAME'];?></td>
                                <td><?php echo $mc_subscriber['email_address'];?></td>
                                <td><?php echo $mc_subscriber['merge_fields']['PHONE']; ?></td>
                                <td><?php echo  date('m-d-Y', strtotime($mc_subscriber['timestamp_opt'])); ?></td>
                            </tr>
						<?php endforeach; ?>
					<?php /*else: */?><!--

                        <tr>
                            <td colspan="6">
								<?php /*_e('No Subscriber found', 'mynfa'); */?>
                            </td>
                        </tr>-->
		
					<?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="all-referrals" class="tab-pane fade" role="tabpanel" aria-labelledby="all-referrals">
            <div class="referrals-list">
                <table id="dashboard-all-referrals-table" class="dt responsive table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th><?php _e('ID', 'mynfa'); ?></th>
                        <th><?php _e('First Name', 'mynfa'); ?></th>
                        <th><?php _e('Last Name', 'mynfa'); ?></th>
                        <th><?php _e('Email', 'mynfa'); ?></th>
                        <th><?php _e('Phone', 'mynfa'); ?></th>
                        <th><?php _e('Registration Date', 'mynfa'); ?></th>
                        <th><?php _e('Referral User', 'mynfa'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php if ($all_mc_subscribers) : ?>
						<?php foreach ($all_mc_subscribers as $index => $mc_subscriber) : ?>
                            <tr>
                                <td><?php echo ($index + 1) ;?></td>
                                <td><?php echo $mc_subscriber['merge_fields']['FNAME']; ?></td>
                                <td><?php echo $mc_subscriber['merge_fields']['LNAME'];?></td>
                                <td><?php echo $mc_subscriber['email_address'];?></td>
                                <td><?php echo $mc_subscriber['merge_fields']['PHONE']; ?></td>
                                <td><?php echo  date('m-d-Y', strtotime($mc_subscriber['timestamp_opt'])); ?></td>
                                <td class="referrer-details">
                                    <p class="referrer-name"><?php echo  $mc_subscriber['referral_name']; ?></p>
                                </td>
                            </tr>
						<?php endforeach; ?>
					<?php /*else: */?><!--

                        <tr>
                            <td colspan="7">
								<?php /*_e('No Subscriber found', 'mynfa'); */?>
                            </td>
                        </tr>-->
		
					<?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="all-affiliates-wrapper" class="tab-pane fade" role="tabpanel" aria-labelledby="all-affiliates-wrapper">
            <div class="affiliates-list">
                <table id="dashboard-all-affiliates-table" class="dt responsive table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th><?php _e('ID', 'mynfa'); ?></th>
                        <th><?php _e('First Name', 'mynfa'); ?></th>
                        <th><?php _e('Last Name', 'mynfa'); ?></th>
                        <th><?php _e('Email', 'mynfa'); ?></th>
                        <th><?php _e('Registration Date', 'mynfa'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php if (count($users) > 0) : ?>
						<?php foreach ($users as $index => $user) : ?>
                            <tr>
                                <td><?php echo ($index + 1) ;?></td>
                                <td><?php echo $user->first_name; ?></td>
                                <td><?php echo $user->last_name; ?></td>
                                <td><?php echo $user->user_email; ?></td>
                                <td><?php echo  date('m-d-Y', strtotime($user->user_registered)); ?></td>
                            </tr>
						<?php endforeach; ?>
					<?php /*else: */?><!--

                        <tr>
                            <td colspan="5">
								<?php /*_e('No User found', 'mynfa'); */?>
                            </td>
                        </tr>-->
		
					<?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
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
                    data: [<?php echo count($mc_subscribers); ?>,<?php echo count($all_mc_subscribers); ?>]
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



