<?php
	/**
	 * Created by PhpStorm.
	 * User: ali.shan
	 * Date: 9/6/2018
	 * Time: 9:20 PM
	 */
	
	namespace app\backend;
	
	if (! defined('ABSPATH'))
		exit;
	
	if (! class_exists('MyNFABadges')){
		class MyNFABadges
		{
			
			private static $badges_page_slug = 'mynfa-badges';
			private static $badges_option_slug = 'mynfa_badges';
			private static $MyNFABaddges = NULL;
			
			public function __construct ()
			{
				add_action('admin_menu', [$this, 'add_badges_page']);
				add_action( 'admin_footer', [$this, 'media_selector_print_scripts'] );
			}
			
			public function add_badges_page ()
			{
				add_submenu_page(MyNFASettings::getSettingsMenuSlug(), __('Badges', 'mynfa'), __('Badges', 'mynfa'), 'manage_options', self::getBadgesPageSlug(), [$this, 'add_badges_page_render']);
			}
			
			public function add_badges_page_render ()
			{
				ob_start();
				include_once 'includes/badges.php';
				echo ob_get_clean();
			}
			
			
			public function media_selector_print_scripts()
			{
				$my_saved_attachment_post_id = get_option('media_selector_attachment_id', 0);
				
				?>
				<script type='text/javascript'>
                    
                    jQuery(document).ready(function ($) {
                        if ($('#mynfa_badge_upload_image_button').length < 1)
                            return;
                        
                        
                        
                        // Uploading files
                        var file_frame;
                        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                        var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                        jQuery('#mynfa_badge_upload_image_button').on('click', function (event) {
                            event.preventDefault();
                            // If the media frame already exists, reopen it.
                            if (file_frame) {
                                // Set the post ID to what we want
                                file_frame.uploader.uploader.param('post_id', set_to_post_id);
                                // Open frame
                                file_frame.open();
                                return;
                            } else {
                                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                                wp.media.model.settings.post.id = set_to_post_id;
                            }
                            // Create the media frame.
                            file_frame = wp.media.frames.file_frame = wp.media({
                                title: 'Select a badge image to upload',
                                button: {
                                    text: 'Use this badge'
                                },
                                multiple: false	// Set to true to allow multiple files to be selected
                            });
                            // When an image is selected, run a callback.
                            file_frame.on('select', function () {
                                // We set multiple to false so only get one image from the uploader
                                attachment = file_frame.state().get('selection').first().toJSON();
                                // Do something with attachment.id and/or attachment.url here
                                $('#mynfa-badge-image-preview').attr('src', attachment.url).css('width', 'auto');
                                $('#mynfa_badge_image_attachment_id').val(attachment.id);
                                // Restore the main post ID
                                wp.media.model.settings.post.id = wp_media_post_id;
                            });
                            // Finally, open the modal
                            file_frame.open();
                        });
                        // Restore the main ID when the add media button is pressed
                        jQuery('a.add_media').on('click', function () {
                            wp.media.model.settings.post.id = wp_media_post_id;
                        });
                    });
				</script>
			<?php
			
			}
			
			public static function get_badges_options_array ()
			{
                return (array) unserialize(get_option(self::getBadgesOptionSlug()));
			}
			
			public static function singleton ()
			{
                if (self::$MyNFABaddges instanceof self)
                    return self::$MyNFABaddges;
                
                return self::$MyNFABaddges = new self;
			}
			
			
			/**
			 * @return string
			 */
			public static function getBadgesPageSlug ()
			{
				return self::$badges_page_slug;
			}
			
			/**
			 * @return string
			 */
			public static function getBadgesOptionSlug ()
			{
				return self::$badges_option_slug;
			}
			
			
			
			
		}
	}
	