<?php 

defined('ABSPATH') or die("No direct script access!");

// Adding a custom Meta container to admin products pages
add_action( 'add_meta_boxes', 'dna88_wp_create_custom_video_meta_box' );
if ( ! function_exists( 'dna88_wp_create_custom_video_meta_box' ) ){

    function dna88_wp_create_custom_video_meta_box(){

        add_meta_box(
            'dna88_wp_custom_video_meta_box',
            __( 'Product Video', 'voice-widgets' ),
            'dna88_wp_add_custom_video_content_meta_box',
            'product',
            'side',
            'default'
        );
    }
}

//  Custom metabox content in admin product pages
if ( ! function_exists( 'dna88_wp_add_custom_video_content_meta_box' ) ){

    function dna88_wp_add_custom_video_content_meta_box( $post ){

        $video_url = get_post_meta( $post->ID, 'qc_product_url', true );
        $is_video_uploaded = ( $video_url && '' !== $video_url ? true : false );

        woocommerce_wp_select( array(
            'id'          => 'qc_product_video_select',
            'value'       => get_post_meta( $post->ID, 'qc_product_video_select', true ),
            'label'       => 'Video Type',
            'options'     => array( '' => 'Please Select', 'youtube' => 'Youtube', 'vimeo' => 'Vimeo', 'daily_motion' => 'Daily Motion', 'self_hosted' => 'Uploaded' ),
        ) );
     
        echo '<div class="qc_product_video_wrapper qc_product_video_content">
                <div class="qc_product_video_container">
                    <p><span style="background:indianred; color:#fff;padding:1px 5px;border-radius: 4px;">Pro Feature</span></p>
                    <div class="qc_product_video_upload_main" id="qc_product_main" '.( $is_video_uploaded ? 'style="display:none"':'' ).'>
                       <a class="button button-default button-large" id="qc_product_upload" href="#"><span class="dashicons dashicons-upload"></span> '.esc_html__( "Upload Video", "voice-widgets" ).'</a>
                    </div>
                </div>
                <input type="hidden" value="'.( $is_video_uploaded ? $video_url : '' ).'" name="qc_product_url" id="qc_product_url" />
            </div>';

        echo '<div class="qc_product_video_youtube qc_product_video_content">';
   
        woocommerce_wp_text_input(
            array(
                'id'            => 'qc_product_youtube_url',
                'value'         => get_post_meta( get_the_ID(), 'qc_product_youtube_url', true ),
                'label'         => __( 'Youtube', 'woocommerce' ),
                'placeholder'   => 'URL',
                'description' => __( 'Youtube Video Link', 'woocommerce' ),
                'desc_tip'    => true
            )
        );

        echo '</div>';

        echo '<div class="qc_product_video_vimeo qc_product_video_content">';
   
        woocommerce_wp_text_input(
            array(
                'id'            => 'qc_product_vimeo_url',
                'value'         => get_post_meta( get_the_ID(), 'qc_product_vimeo_url', true ),
                'label'         => __( 'Vimeo', 'woocommerce' ).' '.'<span style="background:indianred; color:#fff;padding:1px 5px;border-radius: 4px;">Pro Feature</span>',
                'placeholder'   => 'URL',
                'description' => __( 'Pro Feature', 'woocommerce' ),
                'desc_tip'    => true
            )
        );

        echo '</div>';

        echo '<div class="qc_product_video_daily_motion qc_product_video_content">';
   
        woocommerce_wp_text_input(
            array(
                'id'            => 'qc_product_daily_motion_url',
                'value'         => get_post_meta( get_the_ID(), 'qc_product_daily_motion_url', true ),
                'label'         => __( 'Daily Motion', 'woocommerce' ),
                'placeholder'   => 'URL',
                'description' => __( 'Daily Motion Video Link', 'woocommerce' ),
                'desc_tip'    => true
            )
        );

        echo '</div>';

        echo '<input type="hidden" name="qc_product_field_nonce" value="' . wp_create_nonce() . '">';
    }
}

//Save the data of the Meta field
add_action( 'save_post', 'dna88_wp_save_custom_video_content_meta_box', 10, 1 );
if(!function_exists( 'dna88_wp_save_custom_video_content_meta_box')){

    function dna88_wp_save_custom_video_content_meta_box( $post_id ) {

        // Check if our nonce is set.
        if ( ! isset( $_POST[ 'qc_product_field_nonce' ] ) ) {
            return $post_id;
        }
        $nonce = $_REQUEST[ 'qc_product_field_nonce' ];
        //Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // Check the user's permissions.
        if ( 'product' == $_POST[ 'post_type' ] ){
            if ( ! current_user_can( 'edit_product', $post_id ) )
                return $post_id;
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }
        // Sanitize user input and update the meta field in the database.
        update_post_meta( $post_id, 'qc_product_video_select', wp_kses_post($_POST[ 'qc_product_video_select' ]) );
        update_post_meta( $post_id, 'qc_product_youtube_url', wp_kses_post($_POST[ 'qc_product_youtube_url' ]) );
        update_post_meta( $post_id, 'qc_product_daily_motion_url', wp_kses_post($_POST[ 'qc_product_daily_motion_url' ]) );
    }
}


// add filter for that targets only single product main image
$dna88_wp_video_single_product_enable = get_option('dna88_wp_video_single_product_enable');

if($dna88_wp_video_single_product_enable == 1){
	add_filter( 'woocommerce_single_product_image_thumbnail_html', 'dna88_wp_new_product_image', 10, 2 );
}

if(!function_exists( 'dna88_wp_new_product_image')){
    function dna88_wp_new_product_image( $html, $post_thumbnail_id ) {
        // New Image source set here (target the field where you have the image)
        $qc_product_video_select = get_post_meta( get_the_id(), 'qc_product_video_select', true );
        $qc_product_youtube_url = get_post_meta( get_the_id(), 'qc_product_youtube_url', true );
        $qc_product_daily_motion_url = get_post_meta( get_the_id(), 'qc_product_daily_motion_url', true );


        $single_product_width = get_option('dna88_wp_video_single_product_width') ? get_option('dna88_wp_video_single_product_width').'px' :'100%';
        $single_product_height = get_option('dna88_wp_video_single_product_height') ? get_option('dna88_wp_video_single_product_height').'px' :'auto';
        $allow_fullscreen = get_option('dna88_wp_video_allow_fullscreen_play') ? 'allowfullscreen' :'';
        $allow_auto_play = get_option('dna88_wp_video_allow_auto_play') ? 'autoplay=1' :'autoplay=0';
        $allow_video_title = get_option('dna88_wp_video_allow_video_title') ? 1 :0;
        $allow_mute = get_option('dna88_wp_video_allow_mute') ? 1 :0;
        $allow_loop = get_option('dna88_wp_video_allow_loop') ? 0 :0;
        $allow_controls = get_option('dna88_wp_video_controls') ? 0 :1;



        $featured_image = get_post_thumbnail_id(get_the_id());

        if ( $post_thumbnail_id == $featured_image ){

            if( $qc_product_video_select != 'daily_motion' && !empty($qc_product_youtube_url) ) {

                $qc_product_youtube_url = str_replace('embed/','watch?v=', $qc_product_youtube_url);
                parse_str( parse_url( $qc_product_youtube_url, PHP_URL_QUERY ), $my_array_of_vars );
                $start = isset($my_array_of_vars['t']) ? "start=". (int) $my_array_of_vars['t'].'&': '';
                $html = '<iframe width="'.$single_product_width.'" height="'.$single_product_height.'" src="https://www.youtube.com/embed/'.$my_array_of_vars['v'].'?'.$start.''.$allow_auto_play.'&modestbranding=1&showinfo='.$allow_video_title.'&controls='.$allow_controls.'&&loop='.$allow_loop.'&mute='.$allow_mute.'" frameborder="0" '.$allow_fullscreen.'></iframe>';

            }else if( $qc_product_video_select == 'daily_motion' && !empty($qc_product_daily_motion_url) ) {

                $videoId = explode('/',$qc_product_daily_motion_url);
                $video_link = 'https://www.dailymotion.com/embed/video/'.end($videoId).'?'.$allow_auto_play.'&title='.$allow_video_title.'&mute='.$allow_mute.'&controls='.$allow_controls.'&info=0&related=0&loop='.$allow_loop.'';
                $html = '<iframe frameborder="0" src="'.esc_url($video_link).'"  width="'.$single_product_width.'" height="'.$single_product_height.'"  '.$allow_fullscreen.'> </iframe>';

            }

            return $html;
        }


        return '';
    }
}


if(!function_exists( 'dna88_wp_cart_product_image')){
    function dna88_wp_cart_product_image( $_product_img, $cart_item, $cart_item_key ) {

        $img_id = get_post_meta( $cart_item['product_id'], 'qc_product_url', true );

        $qc_product_video_select = get_post_meta( $cart_item['product_id'], 'qc_product_video_select', true );
        $qc_product_youtube_url = get_post_meta( $cart_item['product_id'], 'qc_product_youtube_url', true );
        $qc_product_daily_motion_url = get_post_meta( $cart_item['product_id'], 'qc_product_daily_motion_url', true );


        $allow_fullscreen = get_option('dna88_wp_video_allow_fullscreen_play') ? 'allowfullscreen' :'';
        $allow_auto_play = get_option('dna88_wp_video_allow_auto_play') ? 'autoplay=1' :'autoplay=0';
        $allow_video_title = get_option('dna88_wp_video_allow_video_title') ? 0 :1;
        $allow_mute = get_option('dna88_wp_video_allow_mute') ? 1 :0;
        $allow_loop = get_option('dna88_wp_video_allow_loop') ? 0 :0;
        $allow_controls = get_option('dna88_wp_video_controls') ? 0 :1;

        if(  $qc_product_video_select != 'daily_motion' && !empty($qc_product_youtube_url) ) {

            $qc_product_youtube_url = str_replace('embed/','watch?v=', $qc_product_youtube_url);
            parse_str( parse_url( $qc_product_youtube_url, PHP_URL_QUERY ), $my_array_of_vars );
            $start = isset($my_array_of_vars['t']) ? "start=". (int) $my_array_of_vars['t'].'&': '';
            return '<iframe width="70px" height="70px" src="https://www.youtube.com/embed/'.$my_array_of_vars['v'].'?'.$start.''.$allow_auto_play.'&modestbranding=1&showinfo='.$allow_video_title.'&controls='.$allow_controls.'&loop='.$allow_loop.'&mute='.$allow_mute.'" frameborder="0" '.$allow_fullscreen.'></iframe>';

        }else if( $qc_product_video_select == 'daily_motion' && !empty($qc_product_daily_motion_url) ) {

            $videoId = explode('/',$qc_product_daily_motion_url);
            $video_link = 'https://www.dailymotion.com/embed/video/'.end($videoId).'?'.$allow_auto_play.'&title='.$allow_video_title.'&mute='.$allow_mute.'&controls='.$allow_controls.'&info=0&related=0&loop='.$allow_loop.'';

            return '<div class="qc_popup_modal_wrap"><a href="'.esc_url($video_link).'" class="qc_popup_video" product-id="'.$product->get_id().'">'.$button_text.'</a></div>';

        }else{

            $attachment_id = $_product_img;
            return  $attachment_id;
        }

    }
}

$dna88_wp_video_single_product_enable = get_option('dna88_wp_video_single_product_enable');
if( $dna88_wp_video_single_product_enable == 1){
	add_filter( 'woocommerce_cart_item_thumbnail', 'dna88_wp_cart_product_image', 10, 3 );

}