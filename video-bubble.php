<?php


/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}


defined('ABSPATH') or die("No direct script access!");


if ( ! class_exists( 'Qc_video_bubble_free_Widgets' ) ) {

    final class Qc_video_bubble_free_Widgets {

        private static $instance;
        public $animations;
        
        private function __construct() {

            add_action( 'plugins_loaded', [$this, 'load_textdomain'] );
            //add_action( 'init', [ $this, 'register_cpt' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts'] );

            global $Dna88_wp_video_connect_init;
            //if($Dna88_wp_video_connect_init->is_valid() ){
                add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts'] );
               // add_shortcode( 'videoconnect_widget', [ $this, 'render_shortcode'] );

                add_action('wp_footer', [ $this, 'render_shortcode']);

           // }

            add_action('wp_ajax_qcld_video_save', [ $this, 'save_audio' ]);
            $this->animations = ['bounce', 'flash', 'pulse', 'rubberBand', 'shakeX', 'shakeY', 'headShake', 'swing', 'tada', 'wobble', 'jello', 'heartBeat', 'backInDown', 'backInLeft', 'backInRight', 'backInUp', 'backOutDown', 'backOutLeft', 'backOutRight', 'backOutUp', 'bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp', 'bounceOut', 'bounceOutDown', 'bounceOutLeft', 'bounceOutRight', 'bounceOutUp', 'fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInRight', 'fadeInRightBig', 'fadeInUp', 'fadeInUpBig', 'fadeInTopLeft', 'fadeInTopRight', 'fadeInBottomLeft', 'fadeInBottomRight', 'fadeOut', 'fadeOutDown', 'fadeOutDownBig', 'fadeOutLeft', 'fadeOutLeftBig', 'fadeOutRight', 'fadeOutRightBig', 'fadeOutUp', 'fadeOutUpBig', 'fadeOutTopLeft', 'fadeOutTopRight', 'fadeOutBottomRight', 'fadeOutBottomLeft', 'flip', 'flipInX', 'flipInY', 'flipOutX', 'flipOutY', 'lightSpeedInRight', 'lightSpeedInLeft', 'lightSpeedOutRight', 'lightSpeedOutLeft', 'rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight', 'rotateOut', 'rotateOutDownLeft', 'rotateOutDownRight', 'rotateOutUpLeft', 'rotateOutUpRight', 'hinge', 'jackInTheBox', 'rollIn', 'rollOut', 'zoomIn', 'zoomInDown', 'zoomInLeft', 'zoomInRight', 'zoomInUp', 'zoomOut', 'zoomOutDown', 'zoomOutLeft', 'zoomOutRight', 'zoomOutUp', 'slideInDown', 'slideInLeft', 'slideInRight', 'slideInUp', 'slideOutDown', 'slideOutLeft', 'slideOutRight', 'slideOutUp'];
        
        }

      
        public function admin_scripts() {
            global $post, $pagenow, $typenow;

            if( 'wp_video_bubbles' === $typenow ) {
                
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_media();
                wp_enqueue_style( 'qc-vmwpmdp-audio',  dna88_wp_video_bubble_comment_assets_url . '/css/video_admin.css', array(), QC_VIDEOWIDGET_VERSION );
                wp_enqueue_script( 'qc-vmwpmdp-recorder-js', dna88_wp_video_bubble_comment_assets_url .  '/js/recorder.js', ['jquery'], QC_VIDEOWIDGET_VERSION, true );
                wp_enqueue_script( 'qc-vmwpmdp-audio-js', dna88_wp_video_bubble_comment_assets_url .  '/js/video_admin.js', ['jquery', 'wp-color-picker', 'jquery-ui-tabs', 'media-upload'], QC_VIDEOWIDGET_VERSION, true );

                $voice_obj = array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'capture_duration'  => (get_option('stt_sound_duration') && get_option('stt_sound_duration') != '' ? get_option('stt_sound_duration') : MINUTE_IN_SECONDS * 10 ),
                    'post_id' => ( isset( $post->ID ) ? $post->ID : 0 ),
                    'templates' => $this->get_templates()
                );
                wp_localize_script('qc-vmwpmdp-audio-js', 'voice_obj', $voice_obj);
            }

        }

        public function frontend_scripts() {

		
            wp_register_style( 'qc_video_bubble_font_awesome', dna88_wp_video_bubble_comment_assets_url . '/css/font-awesome.min.css', array(), QC_VIDEOWIDGET_VERSION );
            wp_register_style( 'qc_video_bubble_animate_css', dna88_wp_video_bubble_comment_assets_url . '/css/animate.min.css', array(), QC_VIDEOWIDGET_VERSION );
            wp_register_style( 'qc_video_bubble_front',  dna88_wp_video_bubble_comment_assets_url . '/css/video_bubble_frontend.css', array(), QC_VIDEOWIDGET_VERSION );
           // wp_enqueue_style( 'qc_video_bubble_front' );
            //wp_enqueue_style( 'qc-vmwpmdp-fancybox-front',  dna88_wp_video_bubble_comment_assets_url . 'css/jquery.fancybox.min.css', array(), QC_VIDEOWIDGET_VERSION );
            //wp_enqueue_script( 'qc-vmwpmdp-fancybox-js-frontend', dna88_wp_video_bubble_comment_assets_url .  'js/jquery.fancybox.min.js', ['jquery'], QC_VIDEOWIDGET_VERSION, true );
            wp_enqueue_style( 'qc-vmwpmdp-magnific-front',  dna88_wp_video_bubble_comment_assets_url . '/css/magnific-popup.css', array(), QC_VIDEOWIDGET_VERSION );
            wp_enqueue_script( 'qc-vmwpmdp-magnific-js-frontend', dna88_wp_video_bubble_comment_assets_url .  '/js/jquery.magnific-popup.min.js', ['jquery'], QC_VIDEOWIDGET_VERSION, true );
	        
            
        }




        public function get_templates() {
            return array(
                'default'    => array(
                    'name' => esc_html__( 'Default', 'voice-widgets' ),
                    'image' => esc_url_raw( QC_VIDEOWIDGET_PLUGIN_URL . 'templates/admin/images/default-template.png' )
                ),
                'call_to_action' => array(
                    'name'  => esc_html__( 'Call to Action', 'voice-widgets' ),
                    'image' => esc_url_raw( QC_VIDEOWIDGET_PLUGIN_URL . 'templates/admin/images/call_to_action.png' )
                ),
                // 'wave_animation' => array(
                //     'name'  => esc_html__( 'Audio Wave Animation', 'voice-widgets' ),
                //     'image' => esc_url_raw( QC_VIDEOWIDGET_PLUGIN_URL . 'templates/admin/images/wave_animation.png' )
                // ),
                'image_play' => array(
                    'name'  => esc_html__( 'Image with Play Button', 'voice-widgets' ),
                    'image' => esc_url_raw( QC_VIDEOWIDGET_PLUGIN_URL . 'templates/admin/images/image_play.png' )
                ),
                'play_button_only' => array(
                    'name'  => esc_html__( 'Play Button Only', 'voice-widgets' ),
                    'image' => esc_url_raw( QC_VIDEOWIDGET_PLUGIN_URL . 'templates/admin/images/play_button_only.png' )
                )
            );
        }

        public function load_textdomain() {
            load_plugin_textdomain( 'voice-widgets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

        
        public function render_shortcode() {

            $qc_video_bubble_animation = get_option(  'qc_video_bubble_animation' );
            wp_register_style( 'qc_video_bubble_front',  dna88_wp_video_bubble_comment_assets_url . '/css/video_bubble_frontend.css', array(), QC_VIDEOWIDGET_VERSION );
            wp_enqueue_style( 'qc_video_bubble_front' );
            // styles & scripts

            $qc_video_bubble_use_video = get_option(  'qc_video_bubble_use_video' );
            
            wp_register_script( 'qc_video_bubble_frontend_js', dna88_wp_video_bubble_comment_assets_url .  '/js/video_youtube_bubble_frontend.js', ['jquery'], QC_VIDEOWIDGET_VERSION, true );
           
            wp_enqueue_script( 'qc_video_bubble_frontend_js' );
           
            wp_enqueue_style( 'qc_video_bubble_font_awesome' );
            wp_enqueue_style( 'qc_video_bubble_animate_css' );

            $qc_video_bubble_color = get_option(  'qc_video_bubble_color' );
            $qc_video_bubble_play_bg_color = get_option(  'qc_video_bubble_play_bg_color' );
            $qc_video_bubble_play_color = get_option(  'qc_video_bubble_play_color' );
            $qc_video_bubble_logo = get_option(  'qc_video_bubble_logo' );
            $qc_video_bubble_show_img = get_option(  'qc_video_bubble_show_img' );
          
            $qc_video_bubble_url = get_option(  'qc_video_bubble_url' );
            $qc_upload_video_bubble_url = get_option(  'qc_upload_video_bubble_url' );
            $qc_video_bubble_position = get_option(  'qc_video_bubble_position' );
            $qc_video_bubble_text = get_option(  'qc_video_bubble_text' );
            $qc_video_bubble_allow_video_title = get_option(  'qc_video_bubble_allow_video_title' );
            $qc_video_bubble_allow_fullscreen_play = get_option(  'qc_video_bubble_allow_fullscreen_play' );
            $qc_video_bubble_allow_mute = get_option(  'qc_video_bubble_allow_mute' );
            $qc_video_bubble_allow_mute = get_option(  'qc_video_bubble_allow_mute' );
            $qc_video_bubble_allow_replay = get_option(  'qc_video_bubble_allow_replay' );
            $qc_video_bubble_play_pause = get_option(  'qc_video_bubble_play_pause' );

            $qc_video_bubble_width = get_option(  'qc_video_bubble_width' );
            $qc_video_bubble_height = get_option(  'qc_video_bubble_height' );
            $qc_video_bubble_border_color = get_option(  'qc_video_bubble_border_color' );
            $qc_video_bubble_bg_color = get_option(  'qc_video_bubble_bg_color' );

            $qc_video_bubble_right_browser_window = get_option(  'qc_video_bubble_right_browser_window' );
            $qc_video_bubble_bottom_browser_window = get_option(  'qc_video_bubble_bottom_browser_window' );




            if ( empty( $qc_video_bubble_logo ) ) {
                $qc_video_bubble_logo = dna88_wp_video_bubble_comment_assets_url . '/images/thinking.png';
                $qc_video_bubble_show_img = 'qc_image';
            }

            if( empty($qc_video_bubble_url) ){
                $qc_video_bubble_url = 'https://www.youtube.com/watch?v=nDG1_Mo1CHs';
            }

            $custom_css = '.qc_video_bubble_wrapper_:not(.qc_video_bubble_wrapper-full){
                width: '.$qc_video_bubble_width.'px;
                height: '.$qc_video_bubble_height.'px;
            }';

            if(!empty($qc_video_bubble_border_color)){
                $custom_css .= '.qc_video_bubble_wrapper_ .qc_video_bubble_logo_img{
                    border-color: '.$qc_video_bubble_border_color.'!important;
                }';
                $custom_css .= '.qc_video_bubble_wrapper_ video{
                    border-color: '.$qc_video_bubble_border_color.'!important;
                }';
              
            }

            if(!empty($qc_video_bubble_bg_color)){
                $custom_css .= '.qc_video_bubble_wrapper_.qc_video_bubble_wrapper-full{
                    background-color: '.$qc_video_bubble_bg_color.'!important;
                    border-radius: 10px;
                }';
            }

            if(!empty($qc_video_bubble_right_browser_window)){
                $custom_css .= '.qc_video_bubble_wrapper.qc_left{
                    left: '.$qc_video_bubble_right_browser_window.'px !important;
                }';
                $custom_css .= '.qc_video_bubble_wrapper.qc_right{
                    right: '.$qc_video_bubble_right_browser_window.'px !important;
                }';
              
            }

            if(!empty($qc_video_bubble_bottom_browser_window)){
                $custom_css .= '.qc_video_bubble_wrapper{
                    bottom: '.$qc_video_bubble_bottom_browser_window.'px !important;
                }';
              
            }
            
            //wp_enqueue_style( 'qc_video_bubble_front' );
            wp_add_inline_style( 'qc_video_bubble_front', $custom_css );


            $qc_product_youtube_url = str_replace('embed/','watch?v=', $qc_video_bubble_url);
            parse_str( parse_url( $qc_product_youtube_url, PHP_URL_QUERY ), $my_array_of_vars );
            $start = isset($my_array_of_vars['t']) ? "start=". (int) $my_array_of_vars['t'].'&': '';

            ?>

            <div id="qc_video_bubble_wrapper" class="qc_video_bubble_wrapper_ qc_video_bubble_wrapper qc_video_bubble_toggler <?php echo esc_attr($qc_video_bubble_position); ?>">

              
                <iframe id="qc_video_bubble_video" class="qc_video_bubble_video" src="https://www.youtube.com/embed/<?php echo $my_array_of_vars['v']; ?>?modestbranding=0&showinfo=0&controls=1&mute=0" frameborder="0" allow_fullscreen="1"></iframe>
               
                <?php if(!empty($qc_video_bubble_logo) && (isset($qc_video_bubble_show_img) && $qc_video_bubble_show_img == 'qc_image') ){ ?>
                    <img src="<?php echo esc_attr($qc_video_bubble_logo); ?>"  class="qc_video_bubble_logo_img">
                <?php } ?>
                <h4 id="qc_video_bubble_text" class="qc_video_bubble_text">
                <?php if(!empty($qc_video_bubble_text) && ($qc_video_bubble_allow_video_title != 1)){ ?>
                    <?php echo esc_attr($qc_video_bubble_text); ?>
                        
                <?php }else{ ?>
                    .
                <?php } ?>
                    </h4>

                <div class="qc_video_bubble_close">
                    <i class="fa fa-close"></i>
                </div>
                <div id="qc_video_bubble_full-btn" class="qc_video_bubble_fullbtn" style="display: block;">
                    <div class="qc_video_bubble_full-close">
                        <i class="fa fa-close"></i>
                    </div>
                    <?php if(!empty($qc_video_bubble_play_pause)){ ?>
                    <div id="qc_video_bubble_full-play" class="qc_video_bubble_full-play" style="display: none;">
                        <i class="fa fa-play"></i>
                    </div>
                    <div id="qc_video_bubble_full-pause" class="qc_video_bubble_full-pause" style="display: none;">
                        <i class="fa fa-pause"></i>
                    </div>
                    <?php } ?>
                    <div class="qc_video_bubble_media-action">
                        <?php if(!empty($qc_video_bubble_allow_replay)){ ?>
                        <div id="qc_video_bubble_full-replay" class="qc_video_bubble_full-replay" data-url="<?php echo esc_url($qc_upload_video_bubble_url); ?>">
                            <i class="fa fa-refresh"></i>
                        </div>
                        <?php } ?>
                        <?php if(!empty($qc_video_bubble_allow_mute)){ ?>
                        <div id="qc_video_bubble_full-volume" class="qc_video_bubble_full-volume" style="display: none;">
                            <i class="fa fa-volume-up"></i>
                        </div>
                        <div id="qc_video_bubble_full-mute" class="qc_video_bubble_full-mute" style="display: none;">
                            <i class="fa fa-volume-off"></i>
                        </div>
                        <?php } ?>
                        <?php if(!empty($qc_video_bubble_allow_fullscreen_play)){ ?>
                        <div id="qc_video_bubble_full-expand" class="qc_video_bubble_full-expand">
                            <i class="fa fa-expand"></i>
                        </div>
                        <?php } ?>
                    </div>
                 
                </div>
            </div>
            <?php 
           
            
        }

        public function save_audio() {

            $response['status'] = 'failed';

            if ( isset( $_FILES['audio_data'] ) ) {
                $file_name = 'qc_video_bubble_'.time().'.mp3';
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


        /**
         * Singleton class instance
         *
         * @return Qc_video_bubble_free_Widgets
         */
        public static function get_instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Qc_video_bubble_free_Widgets ) ) {
                self::$instance = new Qc_video_bubble_free_Widgets;
            }
            return self::$instance;
        }

    }
}

// Start the plugin
Qc_video_bubble_free_Widgets::get_instance();
