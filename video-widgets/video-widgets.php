<?php


/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}



/*
* Define some global constants
*/
if ( ! defined( 'QC_videoWIDGET_VERSION' ) ) {
    define( 'QC_videoWIDGET_VERSION', '1.0.0' );
}
if ( ! defined( 'QC_videoWIDGET_BASE' ) ) {
    define( 'QC_videoWIDGET_BASE', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'QC_videoWIDGET_PLUGIN_DIR' ) ) {
    define( 'QC_videoWIDGET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'QC_videoWIDGET_PLUGIN_URL' ) ) {
    define( 'QC_videoWIDGET_PLUGIN_URL', plugin_dir_url(__FILE__) );
}
if ( ! defined( 'QC_videoWIDGET_PLUGIN_TEMPLATE_URL' ) ) {
    define( 'QC_videoWIDGET_PLUGIN_TEMPLATE_URL', plugin_dir_url(__FILE__) . 'templates/' );
}
if ( ! defined( 'QC_videoWIDGET_ASSETS_URL' ) ) {
    define( 'QC_videoWIDGET_ASSETS_URL', QC_videoWIDGET_PLUGIN_URL . 'assets/' );
}


defined('ABSPATH') or die("No direct script access!");


if ( ! class_exists( 'QC_video_Widgets' ) ) {

    final class QC_video_Widgets {

        private static $instance;
        public $animations;
        
        private function __construct() {

            add_action( 'plugins_loaded', [$this, 'load_textdomain'] );
            add_action( 'init', [ $this, 'register_cpt' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts'] );
            add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts'] );
            add_shortcode( 'videoconnect_widget', [ $this, 'render_shortcode'] );
	     
            if ( is_admin() ) {
                add_action( 'load-post.php',     [ $this, 'meta_box_setup' ] );
                add_action( 'load-post-new.php', [ $this, 'meta_box_setup' ] );
                add_action( 'save_post', [ $this, 'save_post' ], 10, 2 );

                add_filter('manage_wp_videomsg_record_posts_columns', [ $this, 'table_columns_head' ] );
                add_action('manage_wp_videomsg_record_posts_custom_column', [ $this, 'table_columns_content' ], 10, 2);

            }

            add_action('wp_ajax_qcld_video_save', [ $this, 'save_audio' ]);
            $this->animations = ['bounce', 'flash', 'pulse', 'rubberBand', 'shakeX', 'shakeY', 'headShake', 'swing', 'tada', 'wobble', 'jello', 'heartBeat', 'backInDown', 'backInLeft', 'backInRight', 'backInUp', 'backOutDown', 'backOutLeft', 'backOutRight', 'backOutUp', 'bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp', 'bounceOut', 'bounceOutDown', 'bounceOutLeft', 'bounceOutRight', 'bounceOutUp', 'fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInRight', 'fadeInRightBig', 'fadeInUp', 'fadeInUpBig', 'fadeInTopLeft', 'fadeInTopRight', 'fadeInBottomLeft', 'fadeInBottomRight', 'fadeOut', 'fadeOutDown', 'fadeOutDownBig', 'fadeOutLeft', 'fadeOutLeftBig', 'fadeOutRight', 'fadeOutRightBig', 'fadeOutUp', 'fadeOutUpBig', 'fadeOutTopLeft', 'fadeOutTopRight', 'fadeOutBottomRight', 'fadeOutBottomLeft', 'flip', 'flipInX', 'flipInY', 'flipOutX', 'flipOutY', 'lightSpeedInRight', 'lightSpeedInLeft', 'lightSpeedOutRight', 'lightSpeedOutLeft', 'rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight', 'rotateOut', 'rotateOutDownLeft', 'rotateOutDownRight', 'rotateOutUpLeft', 'rotateOutUpRight', 'hinge', 'jackInTheBox', 'rollIn', 'rollOut', 'zoomIn', 'zoomInDown', 'zoomInLeft', 'zoomInRight', 'zoomInUp', 'zoomOut', 'zoomOutDown', 'zoomOutLeft', 'zoomOutRight', 'zoomOutUp', 'slideInDown', 'slideInLeft', 'slideInRight', 'slideInUp', 'slideOutDown', 'slideOutLeft', 'slideOutRight', 'slideOutUp'];

            add_action( 'woocommerce_process_product_meta', [ $this,'QC_videoWIDGET_save_fields']);
            add_action( 'woocommerce_before_single_product', [ $this, 'qc_video_record_disply_on_product_page' ] );
            add_filter( 'woocommerce_product_data_tabs',  [ $this,'qc_video_widget_product_tab'], 10, 1 );
            add_action( 'woocommerce_product_data_panels', [ $this,'qc_video_widget_tab_data'] );
        
        }
        public function qc_video_widget_product_tab( $default_tabs ) {
            $default_tabs['custom_tab'] = array(
                'label'   =>  __( 'video widget', 'domain' ),
                'target'  =>  'video_widget_tab_data',
            
                'class'   => array()
            );
            return $default_tabs;
        }
        public function qc_video_widget_tab_data() {
            $args = array(  
                'post_type' => 'wp_videomsg_record',
                'post_status' => 'publish',
            );
            $list_video = new WP_Query( $args ); 
            $option_value = ['' =>'select'];
            
            foreach($list_video->get_posts() AS $value){
                $option_value[$value->ID] = $value->post_title;
            }
            echo '<div id="video_widget_tab_data" class="panel woocommerce_options_panel"><div class="options_group">';
            woocommerce_wp_select(array(
                'id' => 'video_data_select',
                'label' => __('video widget', 'woocommerce'),
                'options' => $option_value,
            ));
            woocommerce_wp_select(array(
                'id' => 'video_data_position',
                'label' => __('video data position', 'woocommerce'),
                'options' => array("woocommerce_before_single_product_summary"=>"woocommerce before single product summary", "woocommerce_single_product_summary"=>"woocommerce single product summary","woocommerce_before_add_to_cart_form"=>"woocommerce before add to cart form","woocommerce_before_variations_form"=>"woocommerce before variations form","woocommerce_before_add_to_cart_button"=>"woocommerce before add to cart button","woocommerce_before_single_variation"=> "woocommerce before single variation","woocommerce_single_variation"=>"woocommerce single variation","woocommerce_before_add_to_cart_quantity"=>"woocommerce before add to cart quantity","woocommerce_after_add_to_cart_quantity"=>"woocommerce after add to cart quantity","woocommerce_after_single_variation"=>"woocommerce after single variation","woocommerce_after_add_to_cart_button"=>"woocommerce after add to cart button","woocommerce_after_variations_form"=>"woocommerce after variations form","woocommerce_after_add_to_cart_form"=>"woocommerce after add to cart form","woocommerce_product_meta_start"=>"woocommerce product meta start","woocommerce_product_meta_end" => "woocommerce product meta end","woocommerce_share"=>"woocommerce share","woocommerce_after_single_product_summary"=>"woocommerce after single product summary"),
            ));
            echo '</div></div>';
        }
        
        public function QC_videoWIDGET_save_fields( $id ){
            update_post_meta( $id, 'video_data_select', $_POST['video_data_select'] );
            update_post_meta( $id, 'video_data_position', $_POST['video_data_position'] );
        }
        public function qc_video_record_disply_on_product_page(){
            global $post,$product;
            $id = $product->get_id();
            
            $video_data = get_post_meta( $id, 'video_data_select',true);
            $video_data_position = get_post_meta( $id, 'video_data_position',true);
            if(!empty($video_data_position)){
                add_action( $video_data_position, [ $this, 'video_record_displied' ] );
            }
        }
        public function video_record_displied(){
            global $post,$product;
            $id = $product->get_id();
            
            $video_data = get_post_meta( $id, 'video_data_select',true);
            $video_shortcode = '[videoconnect_widget id='.$video_data.']';
            echo do_shortcode($video_shortcode);
        }
        public function admin_scripts() {
            global $post, $pagenow, $typenow;

           // if( 'wp_videomsg_record' === $typenow ) {
                wp_enqueue_media();
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_style( 'qc-vmwpmdp-audio',  QC_videoWIDGET_ASSETS_URL . 'css/video_admin.css', array(), QC_videoWIDGET_VERSION );
                wp_enqueue_script( 'qc-vmwpmdp-recorder-js', QC_videoWIDGET_ASSETS_URL .  'js/recorder.js', ['jquery'], QC_videoWIDGET_VERSION, true );
                wp_enqueue_script( 'qc-vmwpmdp-audio-js', QC_videoWIDGET_ASSETS_URL .  'js/video_admin.js', ['jquery', 'wp-color-picker', 'jquery-ui-tabs'], QC_videoWIDGET_VERSION, true );

                $video_obj = array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'capture_duration'  => (get_option('stt_sound_duration') && get_option('stt_sound_duration') != '' ? get_option('stt_sound_duration') : MINUTE_IN_SECONDS * 10 ),
                    'post_id' => ( isset( $post->ID ) ? $post->ID : 0 ),
                    'templates' => $this->get_templates()
                );
                wp_localize_script('qc-vmwpmdp-audio-js', 'video_obj', $video_obj);
           // }

        }

        public function frontend_scripts() {

		
            wp_register_style( 'qc_video_font_awesome', QC_videoWIDGET_ASSETS_URL . 'css/font-awesome.min.css', array(), QC_videoWIDGET_VERSION );
            wp_register_style( 'qc_video_animate_css', QC_videoWIDGET_ASSETS_URL . 'css/animate.min.css', array(), QC_videoWIDGET_VERSION );
            wp_register_style( 'qc-vmwpmdp-video-front',  QC_videoWIDGET_ASSETS_URL . 'css/video_frontend.css', array(), QC_videoWIDGET_VERSION );
            /*wp_enqueue_style( 'qc-vmwpmdp-fancybox-front',  QC_videoWIDGET_ASSETS_URL . 'css/jquery.fancybox.min.css', array(), QC_videoWIDGET_VERSION );
            wp_enqueue_script( 'qc-vmwpmdp-fancybox-js-frontend', QC_videoWIDGET_ASSETS_URL .  'js/jquery.fancybox.min.js', ['jquery'], QC_videoWIDGET_VERSION, true );*/

            wp_enqueue_style( 'qc-vmwpmdp-magnific-front',  QC_videoWIDGET_ASSETS_URL . 'css/magnific-popup.css', array(), QC_videoWIDGET_VERSION );
            wp_enqueue_script( 'qc-vmwpmdp-magnific-js-frontend', QC_videoWIDGET_ASSETS_URL .  'js/jquery.magnific-popup.min.js', ['jquery'], QC_videoWIDGET_VERSION, true );
	        
            wp_register_script( 'qc-vmwpmdp-video-js-frontend', QC_videoWIDGET_ASSETS_URL .  'js/video_frontend.js', ['jquery'], QC_videoWIDGET_VERSION, true );
        }




        public function get_templates() {
            return array(
                'default'    => array(
                    'name' => esc_html__( 'Default', 'video-widgets' ),
                    'image' => esc_url_raw( QC_videoWIDGET_PLUGIN_URL . 'templates/admin/images/default-template.png' )
                )
            );
        }

        public function load_textdomain() {
            load_plugin_textdomain( 'video-widgets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

        /**
         * Set up meta boxes
         *
         * @return void
         */
        public function meta_box_setup():void {
            /* Add meta boxes on the 'add_meta_boxes' hook. */
            add_action( 'add_meta_boxes', [ $this, 'add_post_meta_boxes' ] );
        }

        public function add_post_meta_boxes() {

            add_meta_box(
                'audio-post-class',                                 // Unique ID
                esc_html__( 'video options', 'video-widgets' ),    // Title
                [ $this, 'render_video_options_meta_box' ],         // Callback function
                'wp_videomsg_record',                               // Admin page (or post type)
                'normal',                                           // Context
                'default'                                           // Priority
            );

            // add_meta_box(
            //     'audio-post-class-template',                                 // Unique ID
            //     esc_html__( 'Template Settings', 'video-widgets' ),    // Title
            //     [ $this, 'render_video_template_meta_box' ],         // Callback function
            //     'wp_videomsg_record',                               // Admin page (or post type)
            //     'normal',                                           // Context
            //     'default'                                           // Priority
            // );

            add_meta_box(
                'audio-post-class-shortcode',                                 // Unique ID
                esc_html__( 'Shortcode', 'video-widgets' ),    // Title
                [ $this, 'render_video_shortcode_meta_box' ],         // Callback function
                'wp_videomsg_record',                               // Admin page (or post type)
                'side',                                           // Context
                'default'                                           // Priority
            );

            // add_meta_box(
            //     'audio-post-class-color',                                 // Unique ID
            //     esc_html__( 'Choose Template Color', 'video-widgets' ),    // Title
            //     [ $this, 'render_video_color_meta_box' ],         // Callback function
            //     'wp_videomsg_record',                               // Admin page (or post type)
            //     'side',                                           // Context
            //     'default'                                           // Priority
            // );

            // add_meta_box(
            //     'audio-post-class-animation',                                 // Unique ID
            //     esc_html__( 'Choose Element Animation', 'video-widgets' ),    // Title
            //     [ $this, 'render_video_animation_meta_box' ],         // Callback function
            //     'wp_videomsg_record',                               // Admin page (or post type)
            //     'side',                                           // Context
            //     'default'                                           // Priority
            // );

        }

        public function render_video_color_meta_box( $post ) {
            $qc_video_color = get_post_meta( $post->ID, 'qc_video_color', true );
            $qc_video_play_bg_color = get_post_meta( $post->ID, 'qc_video_play_bg_color', true );
            $qc_video_play_color = get_post_meta( $post->ID, 'qc_video_play_color', true );
            ?>
                <label for="qc_video_color" >Template Color:</label><br>
                <input class="qc_video_color" type="text" value="<?php echo esc_html( $qc_video_color ); ?>" id="qc_video_color" name="qc_video_color" />
                <br>
                <label for="qc_video_play_color" >Play Button Color:</label><br>
                <input class="qc_video_color" type="text" value="<?php echo esc_html( $qc_video_play_color ); ?>" id="qc_video_play_color" name="qc_video_play_color" />
                <br>
                <label for="qc_video_play_bg_color" >Play Button Background Color:</label><br>
                <input class="qc_video_color" type="text" value="<?php echo esc_html( $qc_video_play_bg_color ); ?>" id="qc_video_play_bg_color" name="qc_video_play_bg_color" />
            <?php
        }

        public function render_video_animation_meta_box( $post ) {
            $qc_video_animation = get_post_meta( $post->ID, 'qc_video_animation', true );
            ?>
                <label for="qc_video_animation" >Element Animation:</label><br>
                <select name="qc_video_animation" id="qc_video_animation" >
                    <option value="">None</option>
                    <?php 
                    foreach( $this->animations as $animation ) {
                        echo '<option value="'. esc_attr( $animation ) .'" '. ( $qc_video_animation == $animation ? 'selected="selected"' : '' ) .' >'. esc_html( $animation ) .'</option>';
                    }
                    ?>
                </select>
            <?php
        }

        public function save_post( $post_id, $posts ) {
            if ( isset( $_POST['qc_video_url'] ) && '' !== $_POST['qc_video_url'] ) {
                $audio_url = esc_url_raw( $_POST['qc_video_url'] );
                update_post_meta( $post_id, 'qc_video_url', $audio_url );
            } else {
                delete_post_meta( $post_id, 'qc_video_url' );
            }
            if ( isset( $_POST['qc_video_mode'] ) && '' !== $_POST['qc_video_mode'] ) {
                $audio_url = sanitize_text_field( $_POST['qc_video_mode'] );
                update_post_meta( $post_id, 'qc_video_mode', $audio_url );
            } else {
                delete_post_meta( $post_id, 'qc_video_mode' );
            }
            if ( isset( $_POST['qc_video_template'] ) && '' !== $_POST['qc_video_template'] ) {
                $qc_video_template = sanitize_text_field( $_POST['qc_video_template'] );
                update_post_meta( $post_id, 'qc_video_template', $qc_video_template );
            }
            if ( isset( $_POST['qc_video_call_to_action_text'] ) && '' !== $_POST['qc_video_call_to_action_text'] ) {
                $qc_video_call_to_action_text = sanitize_text_field( $_POST['qc_video_call_to_action_text'] );
                update_post_meta( $post_id, 'qc_video_call_to_action_text', $qc_video_call_to_action_text );
            }
            if ( isset( $_POST['qc_video_call_to_action_button_label'] ) && '' !== $_POST['qc_video_call_to_action_button_label'] ) {
                $qc_video_call_to_action_button_label = sanitize_text_field( $_POST['qc_video_call_to_action_button_label'] );
                update_post_meta( $post_id, 'qc_video_call_to_action_button_label', $qc_video_call_to_action_button_label );
            }
            if ( isset( $_POST['qc_video_color'] ) && '' !== $_POST['qc_video_color'] ) {
                $qc_video_color = sanitize_text_field( $_POST['qc_video_color'] );
                update_post_meta( $post_id, 'qc_video_color', $qc_video_color );
            }
            if ( isset( $_POST['qc_video_call_to_action_url'] ) && '' !== $_POST['qc_video_call_to_action_url'] ) {
                $qc_video_call_to_action_url = esc_url_raw( $_POST['qc_video_call_to_action_url'] );
                update_post_meta( $post_id, 'qc_video_call_to_action_url', $qc_video_call_to_action_url );
            }
            if ( isset( $_POST['qc_video_call_to_action_new_tab'] ) && '' !== $_POST['qc_video_call_to_action_new_tab'] ) {
                $qc_video_call_to_action_new_tab = sanitize_text_field( $_POST['qc_video_call_to_action_new_tab'] );
                update_post_meta( $post_id, 'qc_video_call_to_action_new_tab', $qc_video_call_to_action_new_tab );
            } else {
                delete_post_meta( $post_id, 'qc_video_call_to_action_new_tab' );
            }

            if ( isset( $_POST['qc_video_play_bg_color'] ) && '' !== $_POST['qc_video_play_bg_color'] ) {
                $qc_video_play_bg_color = sanitize_text_field( $_POST['qc_video_play_bg_color'] );
                update_post_meta( $post_id, 'qc_video_play_bg_color', $qc_video_play_bg_color );
            }
            if ( isset( $_POST['qc_video_play_color'] ) && '' !== $_POST['qc_video_play_color'] ) {
                $qc_video_play_color = sanitize_text_field( $_POST['qc_video_play_color'] );
                update_post_meta( $post_id, 'qc_video_play_color', $qc_video_play_color );
            }
            
            if ( isset( $_POST['qc_video_animation'] ) ) {
                $qc_video_animation = sanitize_text_field( $_POST['qc_video_animation'] );
                update_post_meta( $post_id, 'qc_video_animation', $qc_video_animation );
            }
            
            
        }

        public function render_video_options_meta_box( $post ) {
            $template = QC_videoWIDGET_PLUGIN_DIR . 'templates/admin/video_options.php';
            if ( file_exists( $template ) ) {
                include_once $template; 
            }
        }

        public function render_video_template_meta_box( $post ) {
            $template = QC_videoWIDGET_PLUGIN_DIR . 'templates/admin/video_templalate.php';
            if ( file_exists( $template ) ) {
                include_once $template; 
            }
        }

        public function render_shortcode( $atts ) {
            
            $attributes = shortcode_atts( array(
                'id' => 0
            ), $atts );

            extract( $attributes );

            if ( 0 != $id ) {

                // Configuration area.
                $featured_img_url = get_the_post_thumbnail_url( $id, 'full' );

                if ( empty( $featured_img_url ) ) {
                    $featured_img_url = QC_videoWIDGET_ASSETS_URL . 'images/thinking.png';
                }

                $audio_url = get_post_meta( $id, 'qc_video_url', true );
                $video_templalate = get_post_meta( $id, 'qc_video_template', true );
                $video_mode = get_post_meta( $id, 'qc_video_mode', true );
                if ( ! $video_templalate || '' === $video_templalate ) {
                    $video_templalate = 'default';
                }
                $template = QC_videoWIDGET_PLUGIN_DIR . 'templates/frontend/'.$video_templalate.'.php';
                if ( $video_templalate === 'call_to_action' ) {
                    $qc_video_call_to_action_text = get_post_meta( $id, 'qc_video_call_to_action_text', true );
                    $qc_video_call_to_action_button_label = get_post_meta( $id, 'qc_video_call_to_action_button_label', true );
                    $qc_video_call_to_action_url = get_post_meta( $id, 'qc_video_call_to_action_url', true );
                    $qc_video_call_to_action_new_tab = get_post_meta( $id, 'qc_video_call_to_action_new_tab', true );
                }

                $qc_video_animation = get_post_meta( $id, 'qc_video_animation', true );
                wp_register_style( 'qc-vmwpmdp-video-front',  QC_videoWIDGET_ASSETS_URL . 'css/video_frontend.css', array(), QC_videoWIDGET_VERSION );
                wp_enqueue_style( 'qc-vmwpmdp-video-front' );
                // styles & scripts
                wp_enqueue_script( 'qc-vmwpmdp-video-js-frontend' );
               
                wp_enqueue_style( 'qc_video_font_awesome' );
                wp_enqueue_style( 'qc_video_animate_css' );

                $qc_video_color = get_post_meta( $id, 'qc_video_color', true );
                $qc_video_play_bg_color = get_post_meta( $id, 'qc_video_play_bg_color', true );
                $qc_video_play_color = get_post_meta( $id, 'qc_video_play_color', true );
              
                $custom_css = '';
       /*        if($video_mode && $video_mode == 'qcvc_portrait_mode'){ 
                    $custom_css .= ".fancybox-content{
                        height: 0 !important 
                    }
                    .fancybox-iframe {
                        max-width: 420px !important;
                        height: 500px !important;
                    }";
                }
                 if($video_mode == 'qcvc_landscape_mode'){
                    $custom_css .= ".fancybox-content{
                        height: 0 !important 
                    }
                        .fancybox-iframe {
                            max-width: 760px !important;
                            height: 420px !important;
                        }";
                }*/
               if($video_mode && $video_mode == 'qcvc_portrait_mode'){ 
                    $custom_css .= ".mfp-content{
                        max-width: 420px !important;
                        height: 500px !important;
                    }";
                }
                 if($video_mode == 'qcvc_landscape_mode'){
                    $custom_css .= ".mfp-content{
                            max-width: 760px !important;
                        }";
                }
               
                wp_add_inline_style( 'qc-vmwpmdp-video-front', $custom_css );

                if ( file_exists( $template ) ) {
                    ob_start();
                    include $template; 
                    $data = ob_get_clean();
                    return $data;
                } else {
                    return esc_html__( 'Template does not exists!', 'video-widgets' );
                }
            }
        }

        public function save_audio() {

            $response['status'] = 'failed';

            if ( isset( $_FILES['audio_data'] ) ) {
                $file_name = 'qc_video_'.time().'.mp3';
                $file = wp_upload_bits( $file_name, null, @file_get_contents( $_FILES['audio_data']['tmp_name'] ) );
                if ( FALSE === $file['error'] ) {
                    $response['status'] = 'success';
                    $response['url'] =  $file['url'];
                }else{
                    $response['message'] = $file['error'];
                }
                
            }
            echo json_encode($response);
            die();
        }

        public function render_video_shortcode_meta_box($post) {
            echo '<input id="qc_video_shortcode" type="text" value="[videoconnect_widget id='. $post->ID .']">';
            ?>
            <div class="qc_tooltip">
                <div onclick="qc_myFunction()" onmouseout="qc_outFunc()">
                    <span class="qc_tooltiptext" id="qc_myTooltip"><?php echo esc_html__( 'Copy to clipboard', 'video-widgets' ); ?></span>
                    <span class="dashicons dashicons-admin-page"></span>
                </div>
            </div>
            <?php
        }

        /**
         * Register Audio posts
         *
         * @return void
         */
        public function register_cpt() {
            register_post_type('wp_videomsg_record', [
                'public'              => false,
                'labels'              => [
                    'name'                  => esc_html__( 'video Widgets', 'video-widgets' ),
                    'singular_name'         => esc_html__( 'video Widget', 'video-widgets' ),
                    'add_new'               => esc_html__( 'Add New', 'video-widgets' ),
                    'add_new_item'          => esc_html__( 'Add New video Widget', 'video-widgets' ),
                    'new_item'              => esc_html__( 'New video Widget', 'video-widgets' ),
                    'edit_item'             => esc_html__( 'Edit video Widget', 'video-widgets' ),
                    'view_item'             => esc_html__( 'View video Widget', 'video-widgets' ),
                    'view_items'            => esc_html__( 'View video Widget', 'video-widgets' ),
                    'search_items'          => esc_html__( 'Search video Widget', 'video-widgets' ),
                    'not_found'             => esc_html__( 'No Audio found', 'video-widgets' ),
                    'not_found_in_trash'    => esc_html__( 'No Audio found in Trash', 'video-widgets' ),
                    'all_items'             => esc_html__( 'All video Widgets', 'video-widgets' ),
                    'archives'              => esc_html__( 'Audio Archives', 'video-widgets' ),
                    'attributes'            => esc_html__( 'Audio Attributes', 'video-widgets' ),
                    'insert_into_item'      => esc_html__( 'Insert to video Message for Record', 'video-widgets' ),
                    'uploaded_to_this_item' => esc_html__( 'Uploaded to this video Message for  Record', 'video-widgets' ),
                    'menu_name'             => esc_html__( 'video Widgets', 'video-widgets' ),
                ],
                'menu_icon'             => 'dashicons-microphone',
                'exclude_from_search'   => true,
                'publicly_queryable'    => false,
                'menu_position'         => false,
                'show_in_rest'          => false,
                'show_in_menu'          => false,
                'supports'              => [ 'title', 'thumbnail' ],
               // 'capabilities'          => [ 'create_posts' => true ],
                'map_meta_cap'          => true,
                'show_ui'               => true,
            ] );
            
        }

        public function table_columns_head( $defaults ) {
            $new_columns['cb'] = '<input type="checkbox" />';
            $new_columns['title'] = __( 'Title' );
            $new_columns['shortcode'] = esc_html__( 'Shortcode', 'video-widgets' );

            $new_columns['date'] = __('Date');
            return $new_columns;
        }

        public function table_columns_content( $column_name, $post_ID ) {
            if ( 'shortcode' == $column_name ) {
                echo '<input class="qc_video_shortcode_elem" type="text" value="[videoconnect_widget id='. esc_attr( $post_ID ) .']">';
            }
        }

        /**
         * Singleton class instance
         *
         * @return QC_video_Widgets
         */
        public static function get_instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof QC_video_Widgets ) ) {
                self::$instance = new QC_video_Widgets;
            }
            return self::$instance;
        }

    }
}

// Start the plugin
QC_video_Widgets::get_instance();
