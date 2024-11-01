<?php



/** Exit if accessed directly. */

if ( ! defined( 'ABSPATH' ) ) {

	header( 'Status: 403 Forbidden' );

	header( 'HTTP/1.1 403 Forbidden' );

	exit;

}



/**

 * SINGLETON: Class used to implement shortcodes.

 *

 * @since 1.0.0

 **/

final class Dna88_VideoMessageShortcodes {



	/**

	 * The one true Shortcodes.

	 *

	 * @var Shortcodes

	 * @since 1.0.0

	 **/

	private static $instance;



	/**

	 * Sets up a new Shortcodes instance.

	 *

	 * @since 1.0.0

	 * @access public

	 **/

	private function __construct() {



		/** Initializes plugin shortcodes. */

		add_action( 'init', [$this, 'shortcodes_init'] );



	}



	/**

	 * Initializes shortcodes.

	 *

	 * @since 1.0.0

	 * @access public

	 * @return void

	 **/

	public function shortcodes_init() {



		/** Add plugin shortcode [wpvideomessage id=""]. Works everywhere on site. */

		add_shortcode( 'cf7wpvideomessage', [ $this, 'videomssg_shortcode' ] );



	}



	/**

	 * Add  by shortcode [wpvideomessage].

	 *

	 * @param array $atts - Shortcodes attributes.

	 *

	 * @since  1.0.0

	 * @access public

	 * @return string

	 **/

	public function videomssg_shortcode( $atts = [] ) {



		/** Filter shortcode attributes. */

		$atts = shortcode_atts( [
				'id' 	=> '',
				'title' => '',
				'name'	=>	''
			], $atts );

		ob_start();



       $lang_text_title = get_option('dna88_wp_video_comment_lang_text_title') ? get_option('dna88_wp_video_comment_lang_text_title') : 'You can leave a live video record with your message. Connect your camera and press the button below.';
       $lang_text_record_msg = get_option('dna88_wp_video_comment_lang_text_record_msg') ? get_option('dna88_wp_video_comment_lang_text_record_msg') : 'Record Message';
       $lang_text_record_available = get_option('dna88_wp_video_comment_lang_text_record_listen') ? get_option('dna88_wp_video_comment_lang_text_record_listen') : 'Record, Review, Save';
       $lang_text_delete_video = get_option('dna88_wp_video_comment_lang_text_delete_video') ? get_option('dna88_wp_video_comment_lang_text_delete_video') : 'Delete video?';
       $lang_text_delete_video_msg = get_option('dna88_wp_video_comment_lang_text_delete_video_msg') ? get_option('dna88_wp_video_comment_lang_text_delete_video_msg') : 'If you have not saved this recording, you will not be able to restore it.';
       $lang_text_delete_video_cancel = get_option('dna88_wp_video_comment_lang_text_delete_video_cancel') ? get_option('dna88_wp_video_comment_lang_text_delete_video_cancel') : 'Cancel';
       $lang_text_delete_video_delete = get_option('dna88_wp_video_comment_lang_text_delete_video_delete') ? get_option('dna88_wp_video_comment_lang_text_delete_video_delete') : 'Delete';

		?>

		<!-- Start WordPress Plugin -->

		<div class="dna88_wp_video_wrap">
			<div class="dna88_wp_video_msg_wrap">
				<h3><?php  esc_html_e($lang_text_title,'dna88-wp-video'); ?></h3>
	            <button class="dna88-video-confirmation-record" type="button"> <?php esc_html_e($lang_text_record_msg,'dna88-wp-video'); ?> </button> 
			</div>
		</div>

		<div class="dna88_wp_video_wrap dna88_wp_video_wrap_active">
	        <div class="dna88_preview_wrap">
	          <video class="dna88_preview" controls width="400"></video>
	          <div class="dna88_remove_preview"><i class="dna88_icon_close_preview"></i></div>

	          <div class="dna88-remove-video-confirmation"> 
	            <div class="dna88-remove-video-confirmation-title"><?php esc_html_e($lang_text_delete_video,'dna88-wp-video'); ?></div> 
	            <div class="dna88-remove-video-confirmation-desc"><?php esc_html_e($lang_text_delete_video_msg,'dna88-wp-video'); ?></div> 
	            <div class="dna88-remove-video-confirmation-buttons"> 
	              <button class="dna88-remove-video-confirmation-cancel" type="button"> <?php esc_html_e($lang_text_delete_video_cancel,'dna88-wp-video'); ?> </button> 
	              <button class="dna88-remove-video-confirmation-delete" type="button"> <?php esc_html_e($lang_text_delete_video_delete,'dna88-wp-video'); ?> </button> 
	            </div> 
	          </div>
	        </div>
	        <div class="dna88_record_wrap">
	          <video id="video_record" class="video_record" autoplay width="400"></video>
	          <div class="dna88_record_notification"> <?php esc_html_e($lang_text_record_available,'dna88-wp-video'); ?> </div> 
	          <div class="start_recording"><i class="dna88_icon_record"></i></div> 
	          <div class="stop_recording"><i class="dna88_icon_record_stop"></i></div>
	        </div>
	        <input type="hidden" class="dna88_wpvideomessage" name="dna88_wpvideomessage"/>
	        <br><br>
	      </div>

		<!-- End WordPress Plugin -->
		<?php

		return ob_get_clean();

	}

	
	

	/**

	 * Main Shortcodes Instance.

	 *

	 * Insures that only one instance of Shortcodes exists in memory at any one time.

	 *

	 * @static

	 * @return Shortcodes

	 * @since 1.0.0

	 **/

	public static function get_instance() {



		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {



			self::$instance = new self;



		}



		return self::$instance;



	}



} // End Class Shortcodes.



Dna88_VideoMessageShortcodes::get_instance();