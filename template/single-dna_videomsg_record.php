<?php get_header(); ?>

<?php 

	wp_enqueue_style('dna88_player_css', plugin_dir_url(__FILE__) . 'style.css');
    /** Get Audio path value from meta if it's exist. */
	$dna88_video_path = get_post_meta( get_the_ID(), 'dna88_wpvm_vmwpmdp_videomssg_audio', true );
	if ( empty( $dna88_video_path ) ) { return; }

	?>
	<div class="qcld_player_wrap">
		
        <div class="qcld_player_content">
        	<h2><?php esc_html_e( 'Video Message:', 'dna88-wp-video' ); ?></h2>
            <?php 
			$audio_url = str_replace(
				wp_normalize_path( untrailingslashit( ABSPATH ) ),
				site_url(),
				wp_normalize_path( $dna88_video_path )
			);

            ?>
            <video src="<?php echo esc_url( $audio_url ); ?>" controls width="600"></video>
            <div class="dna88-player-video-info">
                <span class="dashicons dashicons-download" title="<?php esc_html_e( 'Download Video', 'dna88-wp-video' ); ?>"></span>
                <a href="<?php echo esc_url( $audio_url ); ?>" download=""><?php esc_html_e( 'Download Video', 'dna88-wp-video' ); ?></a>
            </div>
        </div>
	           
    </div>

<?php get_footer(); ?>