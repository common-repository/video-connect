<?php

class Dna88_CF7wpvideomessage{

    public function __construct(){

        add_action( 'wpcf7_init', [$this, 'dna88_wpvideomessage_add_form_tag'] );

        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Tag Generator Button

        add_action('admin_init', [$this, 'dna88_videomessage_tag_generator']);

        add_filter( 'wpcf7_posted_data', [$this, 'wpcf7_video_before_send_mail_function'], 11, 1 );

    }

   
    function wpcf7_video_before_send_mail_function( $posted_data ) {
        global $post;
        // $submission = WPCF7_Submission::get_instance();
        // $posted_data = $submission->get_posted_data();
        // var_dump($posted_data);
        // wp_die();
        $html = '';
                
        foreach ($posted_data as $post_key => $data) {

            if( $post_key == 'dna88_wpvideomessage' ){
                $video_data = json_decode($data, true);
                
                if( isset($video_data['record_id']) && !empty($video_data['record_id']) ){
                    $record = $video_data['record_id'];
                    $view_link = get_permalink($record); 
                    $post_link = admin_url(). 'post.php?post='.$record.'&action=edit'; 
                    
                    $video_path = get_post_meta($record, 'dna88_wpvm_vmwpmdp_videomssg_audio', true);
                    if( $video_path ){
                        $video_url = $this->abs_path_to_url( $video_path );
                        $html .= esc_html("Video Message Link", 'dna88-wp-video') . "\n";
                        $html .= $view_link . "\n \n";
                        $html .= esc_html("Edit or View Video message", 'dna88-wp-video'). "\n";
                        $html .=  $post_link;
                    }

                }
            }
        }


        $posted_data['dna88_wpvideomessage'] = $html;
        
        return $posted_data;
    }

    public function abs_path_to_url( $path = '' ) {

        $upload_dir =wp_get_upload_dir();
		$url = str_replace(
			wp_normalize_path( $upload_dir['basedir'] ),
			$upload_dir['baseurl'],
			wp_normalize_path( $path )
		);
       
        return esc_url_raw( $url );
    }



    public function enqueue_styles(){

       wp_enqueue_style('dna88_wpvideomessage_css', plugin_dir_url(__FILE__) . 'css/cf7.css');

    }

    public function enqueue_scripts(){

       wp_enqueue_script('dna88_wpvideomessage_js', plugin_dir_url(__FILE__) . 'js/cf7.js', array('jquery'), false,  true);
       wp_add_inline_script( 'dna88_wpvideomessage_js', 
            '
            var dna88_cf7_ajaxurl = "' . admin_url('admin-ajax.php') . '";
            var dna88_cf7_ajax_nonce = "'.wp_create_nonce( 'wpvideomessage-nonce' ).'";
            var lang_text_unavilable = "'.get_option( 'dna88_wp_video_comment_lang_text_unavilable' ).'";
            var lang_http_unavilable = "'.get_option( 'dna88_wp_video_comment_lang_http_unavilable' ).'";
            var dna88_max_recording_time = "'.get_option( 'dna88_wp_video_comment_recording_time' ).'";
            ' 
        );

    }



    public function dna88_wpvideomessage_add_form_tag() {

      wpcf7_add_form_tag( 'dna88_wpvideomessage', [$this,'dna88_wpvideomessage_form_tag_handler'] ); 
      // "clock" is the type of the form-tag

    }

    

    public function dna88_wpvideomessage_form_tag_handler( $tag ) {

        $tag = new WPCF7_FormTag($tag);
        $form_id = $tag->get_option('form_id', '', true);
        $name = '';
        if( isset($tag['options'][0]) && !empty($tag['options'][0]) ){
            $name = $tag['options'][0];
        }

        return do_shortcode('[cf7wpvideomessage name="'.$name.'" id="'.$form_id.'"]');

    }



    public function dna88_videomessage_tag_generator(){

        // wpcf7_add_tag_generator( $name, $title, $elm_id, $callback, $options = array() )

        if (! function_exists( 'wpcf7_add_tag_generator'))

            return;



        wpcf7_add_tag_generator(

            'dna88_wpvideomessage',

            __('WP Video Message', 'dna88-wp-video'),

            'dna88_wpvideomessage',

            [$this, 'dna88_wpvideomessage_tab_generator_cb']

        );

    }

    

    public function dna88_wpvideomessage_tab_generator_cb($args){

        $args =wp_parse_args( $args, array() );

        $type = 'dna88_wpvideomessage';

        $description = __( "Generate a videomessage tag to display the videomessage recorder on the form.", 'dna88-wp-video' );

        ?>
            <div class="insert-box">
                <input type="text" name="<?php echo esc_attr($type); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />
                <div class="submitbox">
                    <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'dna88-wp-video' ) ); ?>" />
                </div>
                <br class="clear" />
            </div>

        <?php
    }

}



new Dna88_CF7wpvideomessage();



 

 



