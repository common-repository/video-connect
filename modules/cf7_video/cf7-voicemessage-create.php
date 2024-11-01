<?php
/**
 * 
 */
class Dna88_CF7VideoMessageCreate
{
	public function __construct(){
		/** Add AJAX callback. */
		add_action( 'wp_ajax_dna88_cf7wpvideomessage_send', [$this, 'dna88_cf7wpvideomessage_send'] );
		add_action( 'wp_ajax_nopriv_dna88_cf7wpvideomessage_send', [$this, 'dna88_cf7wpvideomessage_send'] );
	}
	/**
	 * Process AJAX requests from frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 **/
	public function dna88_cf7wpvideomessage_send() {

		/** Verifies the Ajax request to prevent processing requests external of the blog. */
		check_ajax_referer( 'wpvideomessage-nonce', 'nonce' );

		/** Exit if no data to process. */
		if ( empty( $_POST ) ) {wp_die(); }

		$response = array();
		$response['status'] = 'fail';

		/** Get  Form ID. */
		$cForm_id =  filter_input(INPUT_POST, 'cform-id', FILTER_SANITIZE_NUMBER_INT );

		/** Save Audio file. */
		$video_file_path = $this->save_audio_file( $cForm_id );

		/** Create dna88_video_record record. */
		$post_id = $this->create_record( $cForm_id, $video_file_path );

		$dna88_video_path = get_post_meta( $post_id, 'dna88_wpvm_vmwpmdp_videomssg_audio', true );

		$audio_url = str_replace(
			wp_normalize_path( untrailingslashit( ABSPATH ) ),
			site_url(),
			wp_normalize_path( $dna88_video_path )
		);

		//esc_url_raw( $audio_url );

		if( $post_id ){
			$response['status'] = 'ok';
			$response['record_id'] = $post_id;
			$response['type'] =  'dna88_wpvideomessage';
			$response['video_file_path'] =  esc_url_raw( $audio_url );
		}

		/** Fire event to send email notification. */
		do_action( 'qcvideo_clr_record_added', $post_id );

		// echo 'ok';
		wp_send_json($response);

		wp_die();
	}

	/**
	 * Create qcvoicemsg_record record.
	 *
	 * @param int $cForm_id - ID of  Form.
	 * @param string $video_file_path - Full path to audio file.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return int - post_id
	 **/
	private function create_record( $cForm_id, $video_file_path ) {

		/**  Form. */
		$cForm = get_post( $cForm_id );

		/** Create record. */
		$post_id =wp_insert_post( [
			'post_type'     => 'dna_videomsg_record',
			'post_title'    => 'Recorded Video ',
			'post_status'   => 'publish',
		] );

		/** Fill meta fields. */
		if ( $post_id ) {

			/** Save audio file. */
			update_post_meta( $post_id, 'dna88_wpvm_vmwpmdp_videomssg_audio',wp_slash( $video_file_path ) );

			/** Save audio sample rate. */
			$sample_rate = filter_input(INPUT_POST, 'dna88_vmwpmdp-wpvideomessage-video-sample-rate', FILTER_SANITIZE_STRING );
			update_post_meta( $post_id, 'dna88_wpvm_vmwpmdp_videomssg_audio_sample_rate', $sample_rate );

			/** Save  Form ID. */
			update_post_meta( $post_id, 'vmwpmdp_cform_id', $cForm_id );

			/** Prepare Additional fields. */
			$fields_fb = get_post_meta( $cForm_id, 'vmwpmdp_additional_fields_fb', true );
			$fields_fb = json_decode( $fields_fb, true ); // Array with fields params.

		}

		return $post_id;

	}

	/**
	 * Save recorded audio file.
	 *
	 **/
	private function save_audio_file( $cForm_id ) {
		
		// if ( empty( $_FILES['vmwpmdp-wpvideomessage-audio'] ) ) { return false; }
		if ( empty( $_FILES['video_file'] ) ) { return false; }

		/** Create file name for audio file. */
		$file_path = $this->prepare_audio_name( $cForm_id );

		/** Check file mime type. */
		$mime = mime_content_type( $_FILES['video_file']['tmp_name'] );
		$file_tmp_name = $_FILES['video_file']['tmp_name'];

		/** Looks like uploading some shit. */
		if ( ! in_array( $mime, [ 'video/webm', 'video/x-matroska', 'video/mp4', 'application/octet-stream' ] ) ) {

			// Remove temporary audio file. 
			wp_delete_file( $file_tmp_name );

			wp_die(); // Emergency exit.
		}


       wp_mkdir_p( trailingslashit(wp_upload_dir()['basedir'] ) . 'wpvideomessage/' );

		/** Save audio file. */
		file_put_contents( $file_path, file_get_contents( $file_tmp_name ), FILE_APPEND );

		/** Remove temporary audio file. */
		wp_delete_file( $file_tmp_name );

		return $file_path;

	}

	/**
	 * Prepare unique file name for wav audio file.
	 *
	 **/
	private function prepare_audio_name( $cForm_id ) {

		/** Prepare File name. */
		$upload_dir     =wp_get_upload_dir();
		$upload_basedir = $upload_dir['basedir'] . '/wpvideomessage/'; // Path to upload folder.

		$unique_counter = 0;
		$file_name = $this->build_file_name( $cForm_id, $unique_counter );

		/** We do not need collisions. */
		$f_path = $upload_basedir . $file_name;
		if ( file_exists( $f_path ) ) {

			do {
				$unique_counter++;
				$file_name = $this->build_file_name( $cForm_id, $unique_counter );
				$f_path = $upload_basedir . $file_name;
			} while ( file_exists( $f_path ) );

		}

		$f_path =wp_normalize_path( $f_path );
		$f_path = str_replace( ['/', '\\'], DIRECTORY_SEPARATOR, $f_path );

		return $f_path;

	}

	/**
	 * Build file name.
	 *
	 **/
	private function build_file_name( $cForm_id, $unique_counter ) {

		// return 'wpvideomessage-' . $cForm_id . '-' . $unique_counter . '.webm';
		return 'wpvideomessage-' . $cForm_id . '-' . gmdate( 'Y-m-d\TH-i-s\Z' ) . '-' . $unique_counter . '.mp4';
	}
}


new Dna88_CF7VideoMessageCreate();