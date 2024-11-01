<?php
defined('ABSPATH') or die("No direct script access!");

add_action( 'load-post.php', 'dna88_wp_video_comment_meta_boxes_setup' );
add_action( 'load-post-new.php', 'dna88_wp_video_comment_meta_boxes_setup' );

if (!function_exists('dna88_wp_video_comment_meta_boxes_setup')) {
    function dna88_wp_video_comment_meta_boxes_setup() {

    	/** Add meta boxes on the 'add_meta_boxes' hook. */
    	add_action( 'add_meta_boxes', 'dna88_wp_video_comment_add_meta_boxes' );

    }
}

if (!function_exists('dna88_wp_video_comment_add_meta_boxes')) {
    function dna88_wp_video_comment_add_meta_boxes() {

    	/** Options metabox. */
    	add_meta_box( 'dna88-options-mbox', esc_html__( 'Record Options', 'dna88-wp-video' ), 'dna88_wp_video_comment_render_player', 'dna_videomsg_record', 'normal', 'default' );

    }
}


if (!function_exists('dna88_wp_video_comment_render_player')) {
    function dna88_wp_video_comment_render_player( $dna88_video_record ) {

	    /** Get Audio path value from meta if it's exist. */
		$dna88_video_path = get_post_meta( $dna88_video_record->ID, 'dna88_wpvm_vmwpmdp_videomssg_audio', true );
		if ( empty( $dna88_video_path ) ) { return; }

		?>
        <tr>
            <th scope="row">
                <label><?php esc_html_e( 'Video Message:', 'dna88-wp-video' ); ?></label>
            </th>
            <td>
                <div>
                    <?php 
                    //$audio_url = $dna88_video_path; 
					$audio_url = str_replace(
						wp_normalize_path( untrailingslashit( ABSPATH ) ),
						site_url(),
						wp_normalize_path( $dna88_video_path )
					);

                    ?>
		            <video src="<?php echo esc_url( $audio_url ); ?>" controls width="400"></video>
                    <div class="dna88-wpvideomessage_clr-video-info">
                        <span class="dashicons dashicons-download" title="<?php esc_html_e( 'Download Video', 'dna88-wp-video' ); ?>"></span>
                        <a href="<?php echo esc_url( $audio_url ); ?>" download=""><?php esc_html_e( 'Download Video', 'dna88-wp-video' ); ?></a>
                    </div>
                </div>
            </td>
        </tr>
		<?php

    }

}

if (!function_exists('dna88_wp_video_comment_form_settings_metabox')) {
     function dna88_wp_video_comment_form_settings_metabox( $dna88_video_record ) {

    	/** Get  Form for current Record. */
    	$cform_id = get_post_meta( $dna88_video_record->ID, 'dna88_cform_id', true );

    	?><p><a href="<?php echo admin_url( 'post.php?post=' . $cform_id . '&action=edit&classic-editor' ); ?>"><?php esc_html_e( 'Go to Form Settings', 'dna88-wp-video' ); ?></a></p><?php

    }
}

// Our custom post type function
if (!function_exists('dna88_wp_video_comment_posttype')) {
    function dna88_wp_video_comment_posttype() {
     
        register_post_type('dna_videomsg_record', [
            'public'              => false,
            'labels'              => [
                'name'                  => esc_html__( 'Video Message for WordPress Records', 'dna88-wp-video' ),
                'singular_name'         => esc_html__( 'Video Message for WordPress Records', 'dna88-wp-video' ),
                'add_new'               => esc_html__( 'Add New', 'dna88-wp-video' ),
                'add_new_item'          => esc_html__( 'Add New', 'dna88-wp-video' ),
                'new_item'              => esc_html__( 'New Video Message for WordPress Record', 'dna88-wp-video' ),
                'edit_item'             => esc_html__( 'Edit Video Message for WordPress Record', 'dna88-wp-video' ),
                'view_item'             => esc_html__( 'View Video Message for WordPress Record', 'dna88-wp-video' ),
                'view_items'            => esc_html__( 'View Video Message for WordPress Record', 'dna88-wp-video' ),
                'search_items'          => esc_html__( 'Search Video Message for WordPress Records', 'dna88-wp-video' ),
                'not_found'             => esc_html__( 'No Video Message for WordPress Records found', 'dna88-wp-video' ),
                'not_found_in_trash'    => esc_html__( 'No Video Message for WordPress Records found in Trash', 'dna88-wp-video' ),
                'all_items'             => esc_html__( 'Video Records', 'dna88-wp-video' ),
                'archives'              => esc_html__( 'Video Message for WordPress Records Archives', 'dna88-wp-video' ),
                'attributes'            => esc_html__( 'Video Message for WordPress Record Attributes', 'dna88-wp-video' ),
                'insert_into_item'      => esc_html__( 'Insert to Video Message for WordPress Record', 'dna88-wp-video' ),
                'uploaded_to_this_item' => esc_html__( 'Uploaded to this Video Message for WordPress Record', 'dna88-wp-video' ),
                'menu_name'             => esc_html__( 'Video Records', 'dna88-wp-video' ),
            ],
            'menu_icon'             => '',
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'menu_position'         => false,
            'show_in_rest'          => false,
            'show_in_menu'          => false,
            'supports'              => [ 'title' ],
            'capabilities'          => [ 'create_posts' => false ],
            'map_meta_cap'          => true,
            'show_ui'               => true,
            'rewrite'               => array(
                        'slug'      => 'video-connect'
            )
        ] );
       
        // flush_rewrite_rules();
        
    }
    add_action( 'init', 'dna88_wp_video_comment_posttype' );

} 