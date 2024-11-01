<?php
/**
 * Plugin Name: Video Connect
 * Plugin URI: https://wordpress.org/plugins/video-connect/
 * Description: Video Message for WordPress Contact Form 7
 * Version: 3.6.0
 * Author: dna88
 * Author URI: https://dna88.com/
 * Text Domain: dna88-wp-video
 * Requires at least: 4.6
 * Tested up to: 6.6.2
 * Requires PHP: 5.6
 * License: GPL2
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if ( ! defined( 'dna88_wp_video_comment_plugin_dir_url' ) ) {
    define('dna88_wp_video_comment_plugin_dir_url', plugin_dir_url(__FILE__));
}

if ( ! defined( 'dna88_wp_video_comment_assets_url' ) ) {
    define('dna88_wp_video_comment_assets_url', dna88_wp_video_comment_plugin_dir_url . "assets");
}

if ( ! defined( 'dna88_wp_video_bubble_comment_assets_url' ) ) {
    define('dna88_wp_video_bubble_comment_assets_url', dna88_wp_video_comment_plugin_dir_url . "video-widgets/assets");
}

if ( ! defined( 'dna88_wp_video_comment_IMG_URL' ) ) {
    define('dna88_wp_video_comment_IMG_URL', dna88_wp_video_comment_assets_url . "/images");
}

if ( ! defined( 'dna88_wp_video_comment_file_dir' ) ) {
    define('dna88_wp_video_comment_file_dir', dirname(__FILE__));
}

if ( ! defined( 'QC_VIDEOWIDGET_PLUGIN_URL' ) ) {
    define( 'QC_VIDEOWIDGET_PLUGIN_URL', plugin_dir_url(__FILE__) );
}

if ( ! defined( 'QC_VIDEOWIDGET_VERSION' ) ) {
    define( 'QC_VIDEOWIDGET_VERSION', '1.4.0' );
}

if ( ! defined( 'QC_VIDEOWIDGET_PLUGIN_DIR' ) ) {
    define( 'QC_VIDEOWIDGET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

//Include required files
require_once( 'wp-video-comment-view.php' );
require_once( plugin_dir_path(__FILE__) . 'video-widgets/video-widgets.php' );
require_once( plugin_dir_path(__FILE__) . 'video-bubble.php' );
if ( get_option('dna88_wp_video_comment_enable') ==  1 ) {
    require_once( plugin_dir_path(__FILE__) . 'modules/cf7_video/cf7-videomessage-shortcode.php' );
    require_once( plugin_dir_path(__FILE__) . 'modules/cf7_video/cf7-videomessage-create.php' );
    require_once( plugin_dir_path(__FILE__) . 'modules/cf7_video/cf7.php' );
}
require_once( plugin_dir_path(__FILE__) . 'wp-video-comment-product.php' );

require_once( plugin_dir_path(__FILE__) . 'class-dna88-free-plugin-upgrade-notice.php' );

/*
* Post Type settings area
*/
class Dna88_wp_video_Area_Controller {
    
    function __construct(){

        add_action( 'plugins_loaded', [$this, 'dna88_wp_load_textdomain'] );
        add_action( 'admin_menu', array($this,'dna88_wp_video_admin_menu') );
        add_action( 'admin_init', array($this, 'dna88_wp_video_register_plugin_settings') );
        add_action( 'activated_plugin', array( $this, 'dna88_wp_video_activation_redirect') );

    }

    public function dna88_wp_load_textdomain() {
        load_plugin_textdomain( 'dna88-wp-video', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    public function dna88_wp_video_admin_menu(){

        add_menu_page(
            __('Video Connect','dna88-wp-video'), 
            __('Video Connect','dna88-wp-video'), 
            'manage_options',
            'dna88-video-record', 
            array($this, 'dna88_wp_video_settings_page'),
            'dashicons-video-alt2', 
            '25' 
        );

        add_submenu_page(
            'dna88-video-record',
            __('CF7 Settings'),
            __('CF7 Settings'),
            'manage_options',
            'dna88-video-record',
            ''
        );
        add_submenu_page(
            'dna88-video-record',
            __('All Video Widgets'),
            __('All Video Widgets'),
            'manage_options',
            'edit.php?post_type=wp_videomsg_record',
            ''
        );
        add_submenu_page(
            'dna88-video-record',
            __('Add new Video Widget'),
            __('Add new Video Widget'),
            'manage_options',
            'post-new.php?post_type=wp_videomsg_record',
            ''
        );

        add_submenu_page(
            'dna88-video-record',
            __( 'Product Video', 'textdomain' ),
            __( 'Product Video', 'textdomain' ),
            'manage_options',
            'wpvideoproduct_settings',
            array($this, 'dna88_wp_product_video_callback')
        );

        add_submenu_page(
            'dna88-video-record',
            __('Video Greetings'),
            __('Video Greetings'),
            'manage_options',
            'wp_video_bubbles',
            array($this, 'dna88_wp_video_bubbles_callback')
        );

      /*  add_submenu_page(
            'dna88-video-record',
            __('All Video Bubble'),
            __('All Video Bubble'),
            'manage_options',
            'edit.php?post_type=wp_video_bubbles',
            ''
        );*/

       /* add_submenu_page(
            'dna88-video-record',
            __( 'CF7 Integration', 'textdomain' ),
            __( 'CF7 Integration', 'textdomain' ),
            'manage_options',
            'wpvideomessage_help',
            array($this, 'dna88_wp_video_help_callback')
        );*/
        
        
    }


    public function dna88_wp_video_help_callback(){
        ob_start();
        wp_enqueue_style('dna88-wp-video-help-page-css',  dna88_wp_video_comment_assets_url . "/css/wp-video-comment-help.css" );
        require_once('wp-video-comment-help.php');
    }


    
    public function dna88_wp_video_activation_redirect( $plugin ) {

        $screen = get_current_screen();

        if( ( isset( $screen->base ) && $screen->base == 'plugins' ) && $plugin == plugin_basename( __FILE__ ) ) {
            exit(wp_redirect( admin_url( 'admin.php?page=dna88-video-record') ) );
        }
    }
    
    public function dna88_wp_video_register_plugin_settings(){    


        $args = array(
            'type' => 'string', 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => NULL,
        );  

        $args_email = array(
            'type' => 'string', 
            'sanitize_callback' => 'sanitize_email',
            'default' => NULL,
        );     

        if( isset($_POST['dna88_wp_video_comment_recording_time'])  ) { 
        
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_enable', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_recording_time', $args );

            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_text_title', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_text_record_msg', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_text_record_listen', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_text_record_available', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_text_delete_video', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_text_delete_video_msg', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_text_delete_video_cancel', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_text_delete_video_delete', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_text_unavilable', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_comment_lang_http_unavilable', $args );

        }


        if( isset($_POST['dna88_wp_video_single_product_width'])  ) {

            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_shop_enable', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_single_product_enable', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_allow_fullscreen_play', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_allow_auto_play', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_allow_video_title', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_allow_mute', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_allow_loop', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_controls', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_single_product_width', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_single_product_height', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_shop_page_width', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_shop_page_height', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_popup_button_enable', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_popup_button_text', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_popup_button_bg_color', $args );
            register_setting( 'dna88-video-records-settings-group', 'dna88_wp_video_popup_button_text_color', $args );
        }


        if( isset($_POST['qc_video_bubble_url'])  ) {

            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_url', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_mode', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_logo', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_show_img', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_bg_color', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_border_color', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_allow_fullscreen_play', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_allow_auto_play', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_allow_video_title', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_allow_replay', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_allow_mute', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_play_pause', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_show_home_page', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_show_posts', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_show_pages', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_text', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_position', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_width', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_height', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_use_video', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_show_pages_list', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_right_browser_window', $args );
            register_setting( 'dna88-video-records-settings-group', 'qc_video_bubble_bottom_browser_window', $args );
        }

        
    }
    
    
    public function dna88_wp_video_settings_page(){
        
    ?>
    <style type="text/css">
        .form-table-webcam{
            margin-top: 15px;
            background: #fff;
            border-radius: 2px;
            box-shadow: 0 0 0 1px rgb(0 0 0 / 7%), 0 1px 1px rgb(0 0 0 / 4%);
        }
        .form-table-webcam tr{
            border-bottom: 1px solid #eee;
            display: block;
        }
        .form-table-webcam th{
            padding: 20px 10px 20px 20px;
        }
        .form-table-webcam td{
            padding: 20px 10px 20px 20px;
            width: 80%;
        }
        .form-table-webcam .dna88_lan_text{
            width: 100%;
        }
    </style>
    <div class="wrap" id="tabs">
        <h1><?php esc_html_e('Contact Form 7 Settings','dna88-wp-video') ?></h1>
    
        <ul class="nav-tab-wrapper dna88_nav_container">
            <li style="margin-bottom:0px"><a class="nav-tab dna88_click_handle nav-tab-active" href="#tab-1"><?php esc_html_e('Settings','dna88-wp-video') ?></a></li>
            <li style="margin-bottom:0px"><a class="nav-tab dna88_click_handle" href="#tab-2"><?php esc_html_e('CF7 Integration Help','dna88-wp-video') ?></a></li>
        </ul>
        <div id="tab-1">
            <form method="post" action="options.php">
                <?php settings_fields( 'dna88-video-records-settings-group' ); ?>
                <?php do_settings_sections( 'dna88-video-records-settings-group' ); ?>
                <div >
                    <?php if(!is_ssl()){ ?> 
                    <h3 style="color:indianred;"><i><?php esc_html_e('** Video message requires SSL certificate to work. Please make sure that your site uses SSL (https://).','dna88-wp-video') ?></i></h3>
                    <?php  } ?>
                    <table class="form-table form-table-webcam" >
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Enable','dna88-wp-video'); ?></th>
                            <td>
                                <input type="checkbox" name="dna88_wp_video_comment_enable" size="100" value="<?php echo (get_option('dna88_wp_video_comment_enable')!=''? esc_attr( get_option('dna88_wp_video_comment_enable')) : '1' ); ?>" <?php echo (get_option('dna88_wp_video_comment_enable') == '' ? esc_attr( get_option('dna88_wp_video_comment_enable')): esc_attr( 'checked="checked"' )); ?>  />  
                                <i><?php esc_html_e('Enable this option a new field will be added with Comments ','dna88-wp-video') ?></i>                           
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Recording Max Time','dna88-wp-video') ?></th>
                            <td>
                                <input type="number" name="dna88_wp_video_comment_recording_time" style="width:100px" size="100" value="<?php echo (get_option('dna88_wp_video_comment_recording_time')!=''?esc_attr( get_option('dna88_wp_video_comment_recording_time')):''); ?>"  /> <b><i><?php esc_html_e('Seconds','dna88-wp-video') ?></i></b>
                                
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('You can leave a live video record with your message. Connect your webcam and press the button below.','dna88-wp-video') ?></th>
                            <td>
                                <input type="text" name="dna88_wp_video_comment_lang_text_title" class="dna88_lan_text" value="<?php echo (get_option('dna88_wp_video_comment_lang_text_title')!=''?esc_attr( get_option('dna88_wp_video_comment_lang_text_title')):'You can leave a live video record with your message. Connect your camera and press the button below.'); ?>"  /> 
                                
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Record Message','dna88-wp-video') ?></th>
                            <td>
                                <input type="text" name="dna88_wp_video_comment_lang_text_record_msg" class="dna88_lan_text" value="<?php echo (get_option('dna88_wp_video_comment_lang_text_record_msg')!=''?esc_attr( get_option('dna88_wp_video_comment_lang_text_record_msg')):'Record Message'); ?>"  /> 
                                
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Record, Review, Save','dna88-wp-video') ?></th>
                            <td>
                                <input type="text" name="dna88_wp_video_comment_lang_text_record_listen" class="dna88_lan_text" value="<?php echo (get_option('dna88_wp_video_comment_lang_text_record_listen')!=''?esc_attr( get_option('dna88_wp_video_comment_lang_text_record_listen')):'Record, Review, Save'); ?>"  /> 
                                
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Delete video?','dna88-wp-video') ?></th>
                            <td>
                                <input type="text" name="dna88_wp_video_comment_lang_text_delete_video" class="dna88_lan_text" value="<?php echo (get_option('dna88_wp_video_comment_lang_text_delete_video')!=''?esc_attr( get_option('dna88_wp_video_comment_lang_text_delete_video')):'Delete video?'); ?>"  /> 
                                
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('If you have not saved this recording, you will not be able to restore it.','dna88-wp-video') ?></th>
                            <td>
                                <input type="text" name="dna88_wp_video_comment_lang_text_delete_video_msg" class="dna88_lan_text" value="<?php echo (get_option('dna88_wp_video_comment_lang_text_delete_video_msg')!=''?esc_attr( get_option('dna88_wp_video_comment_lang_text_delete_video_msg')):'If you have not saved this recording, you will not be able to restore it.'); ?>"  /> 
                                
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Cancel','dna88-wp-video') ?></th>
                            <td>
                                <input type="text" name="dna88_wp_video_comment_lang_text_delete_video_cancel" class="dna88_lan_text" value="<?php echo (get_option('dna88_wp_video_comment_lang_text_delete_video_cancel')!=''?esc_attr( get_option('dna88_wp_video_comment_lang_text_delete_video_cancel')):'Cancel'); ?>"  /> 
                                
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Delete','dna88-wp-video') ?></th>
                            <td>
                                <input type="text" name="dna88_wp_video_comment_lang_text_delete_video_delete" class="dna88_lan_text" value="<?php echo (get_option('dna88_wp_video_comment_lang_text_delete_video_delete')!=''?esc_attr( get_option('dna88_wp_video_comment_lang_text_delete_video_delete')):'Delete'); ?>"  /> 
                                
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Video camera is not available, Connect your camera or webcam','dna88-wp-video') ?></th>
                            <td>
                                <input type="text" name="dna88_wp_video_comment_lang_text_unavilable" class="dna88_lan_text" value="<?php echo (get_option('dna88_wp_video_comment_lang_text_unavilable')!=''?esc_attr( get_option('dna88_wp_video_comment_lang_text_unavilable')):'Video camera is not available, Connect your camera or webcam'); ?>"  /> 
                                
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Please use https:// to load the website. Insecure connection will not work for video recording','dna88-wp-video') ?></th>
                            <td>
                                <input type="text" name="dna88_wp_video_comment_lang_http_unavilable" class="dna88_lan_text" value="<?php echo (get_option('dna88_wp_video_comment_lang_http_unavilable')!=''?esc_attr( get_option('dna88_wp_video_comment_lang_http_unavilable')):'Please use https:// to load the website. Insecure connection will not work for video recording'); ?>"  /> 
                                
                            </td>
                        </tr>
                        
                    </table>
                </div>
                
                <?php submit_button(); ?>

            </form>
            
        </div>
        <div id="tab-2">
            <?php
                ob_start();
                wp_enqueue_style('dna88-wp-video-help-page-css',  dna88_wp_video_comment_assets_url . "/css/wp-video-comment-help.css" );
                require_once('wp-video-comment-help.php');
            ?>
        </div>
    </div>

    <?php
    }
    
    
    public function dna88_wp_product_video_callback(){
        
    ?>
    <style type="text/css">
        .form-table-webcam{
            margin-top: 15px;
            background: #fff;
            border-radius: 2px;
            box-shadow: 0 0 0 1px rgb(0 0 0 / 7%), 0 1px 1px rgb(0 0 0 / 4%);
        }
        .form-table-webcam tr{
            border-bottom: 1px solid #eee;
            display: block;
        }
        .form-table-webcam th{
            padding: 20px 10px 20px 20px;
        }
        .form-table-webcam td{
            padding: 20px 10px 20px 20px;
            width: 80%;
        }
        .form-table-webcam .dna88_lan_text{
            width: 100%;
        }
        .form-table-webcam .dna88_pro_feature{
            background:indianred; 
            color:#fff;
            padding:1px 5px;
            border-radius: 4px;
        }

    </style>
    <div class="wrap">
        <h1><?php esc_html_e('Video Connect Settings','dna88-wp-video') ?></h1>

        <p><i><?php esc_html_e('Go to Edit a Product and you will find the option to assign a video for that product on the right side panel.','dna88-wp-video') ?></i></p>
        <form method="post" action="options.php">
            <?php settings_fields( 'dna88-video-records-settings-group' ); ?>
            <?php do_settings_sections( 'dna88-video-records-settings-group' ); ?>
            <div >
                <table class="form-table form-table-webcam" >
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Enable Video on Shop Page','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="dna88_wp_video_shop_enable" size="100" value="<?php echo (get_option('dna88_wp_video_shop_enable')!=''? esc_attr( get_option('dna88_wp_video_shop_enable')) : '1' ); ?>" <?php echo (get_option('dna88_wp_video_shop_enable') == '' ? esc_attr( get_option('dna88_wp_video_shop_enable')): esc_attr( 'checked="checked"' )); ?>  disabled="disabled" />  
                            <i><?php esc_html_e('Check to enable video for your stor/shop page ','dna88-wp-video') ?></i> <span class="dna88_pro_feature"><?php esc_html_e('Pro Feature','dna88-wp-video'); ?></span>                         
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Enable Video Product Page','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="dna88_wp_video_single_product_enable" size="100" value="<?php echo (get_option('dna88_wp_video_single_product_enable')!=''? esc_attr( get_option('dna88_wp_video_single_product_enable')) : '1' ); ?>" <?php echo (get_option('dna88_wp_video_single_product_enable') == '' ? esc_attr( get_option('dna88_wp_video_single_product_enable')): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Check to enable video for single product page ','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Allow Fullscreen Play','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="dna88_wp_video_allow_fullscreen_play" size="100" value="<?php echo (get_option('dna88_wp_video_allow_fullscreen_play')!=''? esc_attr( get_option('dna88_wp_video_allow_fullscreen_play')) : '1' ); ?>" <?php echo (get_option('dna88_wp_video_allow_fullscreen_play') == '' ? esc_attr( get_option('dna88_wp_video_allow_fullscreen_play')): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Allow video to fullscreen control enable/disable ','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Allow Autoplay','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="dna88_wp_video_allow_auto_play" size="100" value="<?php echo (get_option('dna88_wp_video_allow_auto_play')!=''? esc_attr( get_option('dna88_wp_video_allow_auto_play')) : '1' ); ?>" <?php echo (get_option('dna88_wp_video_allow_auto_play') == '' ? esc_attr( get_option('dna88_wp_video_allow_auto_play')): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Allow video to autoplay control enable/disable.','dna88-wp-video') ?></i>

                            <i style="color:indianred"><?php esc_html_e('( Please also enable Mute Video to make sure Auto play works )','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Hide Video Title','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="dna88_wp_video_allow_video_title" size="100" value="<?php echo (get_option('dna88_wp_video_allow_video_title')!=''? esc_attr( get_option('dna88_wp_video_allow_video_title')) : '1' ); ?>" <?php echo (get_option('dna88_wp_video_allow_video_title') == '' ? esc_attr( get_option('dna88_wp_video_allow_video_title')): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Hide video title and other information','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Mute Video','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="dna88_wp_video_allow_mute" size="100" value="<?php echo (get_option('dna88_wp_video_allow_mute')!=''? esc_attr( get_option('dna88_wp_video_allow_mute')) : '1' ); ?>" <?php echo (get_option('dna88_wp_video_allow_mute') == '' ? esc_attr( get_option('dna88_wp_video_allow_mute')): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Mute video','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Video Loop','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="dna88_wp_video_allow_loop" size="100" value="<?php echo (get_option('dna88_wp_video_allow_loop')!=''? esc_attr( get_option('dna88_wp_video_allow_loop')) : '1' ); ?>" <?php echo (get_option('dna88_wp_video_allow_loop') == '' ? esc_attr( get_option('dna88_wp_video_allow_loop')): esc_attr( 'checked="checked"' )); ?>   disabled="disabled"/>  
                            <i><?php esc_html_e('Allow Video Loop','dna88-wp-video') ?></i> <span class="dna88_pro_feature"><?php esc_html_e('Pro Feature','dna88-wp-video'); ?></span>                       
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Video Controls','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="dna88_wp_video_controls" size="100" value="<?php echo (get_option('dna88_wp_video_controls')!=''? esc_attr( get_option('dna88_wp_video_controls')) : '1' ); ?>" <?php echo (get_option('dna88_wp_video_controls') == '' ? esc_attr( get_option('dna88_wp_video_controls')): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Hide Video Controls','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Width for Single Product Page (Video)','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="dna88_wp_video_single_product_width" style="width:100px" size="100" value="<?php echo (get_option('dna88_wp_video_single_product_width')!=''?esc_attr( get_option('dna88_wp_video_single_product_width')):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply width 100% )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Height for Single Product Page (Video)','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="dna88_wp_video_single_product_height" style="width:100px" size="100" value="<?php echo (get_option('dna88_wp_video_single_product_height')!=''?esc_attr( get_option('dna88_wp_video_single_product_height')):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply height auto )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>

                    
                </table>
            </div>

            
            <?php submit_button(); ?>

        </form>
        
    </div>

    <?php
    }



    public function dna88_wp_video_bubbles_callback(){
       

$video_url = get_option(  'qc_video_bubble_url' );

$upload_video_url = get_option(  'qc_upload_video_bubble_url' );

$qc_video_bubble_mode = get_option(  'qc_video_bubble_mode' );
$is_video_uploaded = ( $video_url && '' !== $video_url ? true : false );
$is_upload_video_uploaded = ( $upload_video_url && '' !== $upload_video_url ? true : false );

$qc_video_bubble_bg_color = get_option(  'qc_video_bubble_bg_color' );
$qc_video_bubble_border_color = get_option(  'qc_video_bubble_border_color' );
$qc_video_bubble_logo = get_option(  'qc_video_bubble_logo' );


?>
<style type="text/css">
    .dna88_pro_feature {
        background: indianred;
        color: #fff;
        padding: 1px 5px;
        border-radius: 4px;
    }
</style>
<div class="wrap">
        <h1><?php esc_html_e('Video Bubble Settings','dna88-wp-video') ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'dna88-video-records-settings-group' ); ?>
                <?php do_settings_sections( 'dna88-video-records-settings-group' ); ?>
<div class="qc_video_bubble_wrapper" >



    <div class="qc_video_bubble_bubble_recorder_wrap">
        <table class="form-table">
            <tbody>
                    
                <tr valign="top">
                    <th scope="row"><?php echo esc_html( 'Put your youtube url here' ); ?></th>
                    <td>
                    
                        <div class="qc_video_bubble_container">

                            <div class="qc_video_bubble_upload_wrap">
                                <div class="qc_video_bubble_upload_content">
                                    <label><?php echo esc_html( 'Put your youtube url here' ); ?></label>
                                    <input type="text" value="<?php echo ( $is_video_uploaded ? $video_url : '' ); ?>" name="qc_video_bubble_url" id="qc_video_bubble_url" class="videoconnect_videourl_input"/></br></br>
                                </div>
                            </div>

                            
                        </div>                        
                    </td>
                </tr>
                    
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Use Video','dna88-wp-notice') ?></th>
                    <td>
                    
                        <label class="radio-inline" style="padding-right: 15px;">
                            <input id="qc_video_bubble_use_video" type="radio" name="qc_video_bubble_use_video" value="qc_youtube" <?php echo ((get_option(  'qc_video_bubble_use_video' ) == 'qc_youtube'  || get_option(  'qc_video_bubble_use_video' ) == '' ) ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Youtube Video','dna88-wp-notice') ?> </label> 
                    
                        <label class="radio-inline">
                            <input id="qc_video_bubble_use_video" type="radio" name="qc_video_bubble_use_video" value="qc_video"  disabled>
                            <?php esc_html_e('Uploaded Video','dna88-wp-notice') ?> <span class="dna88_pro_feature"><?php esc_html_e('Pro Feature','dna88-wp-notice') ?></span></label>                             
                    </td>
                </tr>
                    
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Hello!','dna88-wp-video') ?></th>
                    <td>
                        <input type="text" name="qc_video_bubble_text" style="width:100px" size="100" value="<?php echo (get_option(  'qc_video_bubble_text' )!=''?esc_attr( get_option(  'qc_video_bubble_text' )):''); ?>"  /> <b><i><?php esc_html_e('Text','dna88-wp-video') ?></i></b> 
                        
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Hide Video Title','dna88-wp-video'); ?></th>
                    <td>
                        <input type="checkbox" name="qc_video_bubble_allow_video_title" size="100" value="<?php echo (get_option(  'qc_video_bubble_allow_video_title' )!=''? esc_attr( get_option(  'qc_video_bubble_allow_video_title' )) : '1' ); ?>" <?php echo (get_option(  'qc_video_bubble_allow_video_title' ) == '' ? esc_attr( get_option(  'qc_video_bubble_allow_video_title' )): esc_attr( 'checked="checked"' )); ?>  />  
                        <i><?php esc_html_e('Hide video title and other information','dna88-wp-video') ?></i>                           
                    </td>
                </tr>

                    
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Show Position','dna88-wp-notice') ?></th>
                    <td>
                    
                        <label class="radio-inline" style="padding-right: 15px;">
                            <input id="qc_video_bubble_position" type="radio" name="qc_video_bubble_position" value="qc_left" <?php echo ((get_option(  'qc_video_bubble_position' ) == 'qc_left') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Left','dna88-wp-notice') ?> </label>
                    
                        <label class="radio-inline">
                            <input id="qc_video_bubble_position" type="radio" name="qc_video_bubble_position" value="qc_right" <?php echo ((get_option(  'qc_video_bubble_position' ) == 'qc_right' || get_option(  'qc_video_bubble_position' ) == '') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Right ','dna88-wp-notice') ?> </label>                              
                    </td>
                </tr>

                    
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Floating Icon Show','dna88-wp-notice') ?></th>
                    <td>
                    
                        <label class="radio-inline" style="padding-right: 15px;">
                            <input id="qc_video_bubble_show_img" type="radio" name="qc_video_bubble_show_img" value="qc_image" <?php echo ((get_option(  'qc_video_bubble_show_img' ) == 'qc_image') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Image','dna88-wp-notice') ?> </label>
                    
                        <label class="radio-inline">
                            <input id="qc_video_bubble_show_img" type="radio" name="qc_video_bubble_show_img" value="qc_video" <?php echo ((get_option(  'qc_video_bubble_show_img' ) == 'qc_video' || get_option(  'qc_video_bubble_show_img' ) == '') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Video ','dna88-wp-notice') ?> </label>                              
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="qc_video_bubble_logo"><?php echo esc_html__( 'Floating Icon Image:', 'voice-widgets' ); ?></label>
                    </th>
                    <td>
                        <a class="button button-default button-large" id="qc_video_bubble_upload_logo" href="#"><span class="dashicons dashicons-upload"></span> <?php echo esc_html__( 'Upload Image', 'voice-widgets' ); ?></a>
                       <input type="hidden" value="<?php echo esc_attr( $qc_video_bubble_logo ); ?>" id="qc_video_bubble_logo" name="qc_video_bubble_logo" />
                       <div class="qc_video_bubble_logo_image_wrap">
                           <img src="<?php echo esc_attr( $qc_video_bubble_logo ); ?>" id="qc_video_bubble_logo_image" <?php if(isset($qc_video_bubble_logo)&& empty($qc_video_bubble_logo)){ ?> style="display: none;" <?php } ?> >
                       </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="qc_video_bubble_bg_color"><?php echo esc_html__( 'Background Color:', 'voice-widgets' ); ?></label>
                    </th>
                    <td>
                       <input type="text" value="<?php echo esc_url_raw( $qc_video_bubble_bg_color ); ?>" 
                       id="qc_video_bubble_bg_color" name="qc_video_bubble_bg_color" class="qc_video_bubble_color" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="qc_video_bubble_border_color"><?php echo esc_html__( 'Border Color:', 'voice-widgets' ); ?></label>
                    </th>
                    <td>
                       <input type="text" value="<?php echo esc_url_raw( $qc_video_bubble_border_color ); ?>" 
                       id="qc_video_bubble_border_color" name="qc_video_bubble_border_color" class="qc_video_bubble_color" />
                    </td>
                </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Replay Video','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="qc_video_bubble_allow_replay" size="100" value="<?php echo (get_option(  'qc_video_bubble_allow_replay' )!=''? esc_attr( get_option(  'qc_video_bubble_allow_replay' )) : '1' ); ?>" <?php echo (get_option(  'qc_video_bubble_allow_replay' ) == '' ? esc_attr( get_option(  'qc_video_bubble_allow_replay' )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Replay video','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Video Play/Pause','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="qc_video_bubble_play_pause" size="100" value="<?php echo (get_option(  'qc_video_bubble_play_pause' )!=''? esc_attr( get_option(  'qc_video_bubble_play_pause' )) : '1' ); ?>" <?php echo (get_option(  'qc_video_bubble_play_pause' ) == '' ? esc_attr( get_option(  'qc_video_bubble_play_pause' )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Video Play/Pause Control','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Allow Fullscreen Play','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="qc_video_bubble_allow_fullscreen_play" size="100" value="<?php echo (get_option(  'qc_video_bubble_allow_fullscreen_play' )!=''? esc_attr( get_option(  'qc_video_bubble_allow_fullscreen_play' )) : '1' ); ?>" <?php echo (get_option(  'qc_video_bubble_allow_fullscreen_play' ) == '' ? esc_attr( get_option(  'qc_video_bubble_allow_fullscreen_play' )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Allow video to fullscreen control enable/disable ','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Video Replay','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="qc_video_bubble_allow_replay" size="100" value="<?php echo (get_option(  'qc_video_bubble_allow_replay' )!=''? esc_attr( get_option(  'qc_video_bubble_allow_replay' )) : '1' ); ?>" <?php echo (get_option(  'qc_video_bubble_allow_replay' ) == '' ? esc_attr( get_option(  'qc_video_bubble_allow_replay' )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Video Replay','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Mute Video','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="qc_video_bubble_allow_mute" size="100" value="<?php echo (get_option(  'qc_video_bubble_allow_mute' )!=''? esc_attr( get_option(  'qc_video_bubble_allow_mute' )) : '1' ); ?>" <?php echo (get_option(  'qc_video_bubble_allow_mute' ) == '' ? esc_attr( get_option(  'qc_video_bubble_allow_mute' )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Mute video','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Width','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="qc_video_bubble_width" style="width:100px" size="100" value="<?php echo (get_option(  'qc_video_bubble_width' )!=''?esc_attr( get_option(  'qc_video_bubble_width' ) ):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply width 170px )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Height','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="qc_video_bubble_height" style="width:100px" size="100" value="<?php echo (get_option(  'qc_video_bubble_height' )!=''?esc_attr( get_option(  'qc_video_bubble_height' ) ):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply Height 170px )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Sidebar of the Browser Window','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="qc_video_bubble_right_browser_window" style="width:100px" size="50" value="<?php echo (get_option(  'qc_video_bubble_right_browser_window' )!=''?esc_attr( get_option(  'qc_video_bubble_right_browser_window' ) ):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply Default )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Bottom of the Browser Window','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="qc_video_bubble_bottom_browser_window" style="width:100px" size="100" value="<?php echo (get_option(  'qc_video_bubble_bottom_browser_window' )!=''?esc_attr( get_option(  'qc_video_bubble_bottom_browser_window' ) ):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply Default )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>

                    <tr valign="top">
                      <th scope="qc_video_bubble_row"><?php  esc_html_e( 'Loading Control Options', 'voice-widgets' ); ?></th>
                      <td><span class="dna88_pro_feature"><?php esc_html_e('Pro Feature','dna88-wp-notice') ?></span></td>
                    </tr>

            </tbody>
        </table>

        
                
                <?php submit_button(); ?>

            </form>

    </div>

</div>
</div>

<?php 
    }
    
    
}
new Dna88_wp_video_Area_Controller();



/*****************************************************
 * Plugin default data set when activation.
 *****************************************************/
register_activation_hook(__FILE__, 'dna88_wp_video_activation_options');
if (!function_exists('dna88_wp_video_activation_options')) {
    function dna88_wp_video_activation_options(){
        
        if(!get_option('dna88_wp_video_comment_enable')) {
            update_option('dna88_wp_video_comment_enable', 1);
        }
        
        if(!get_option('dna88_wp_video_comment_recording_time')) {
            update_option('dna88_wp_video_comment_recording_time', 10000);
        }
        
        if(!get_option('dna88_wp_video_comment_lang_text_title')) {
            update_option('dna88_wp_video_comment_lang_text_title', 'You can leave a live video record with your message. Connect your camera and press the button below.');
        }
        
        if(!get_option('dna88_wp_video_comment_lang_text_record_msg')) {
            update_option('dna88_wp_video_comment_lang_text_record_msg', 'Record Message');
        }
        
        if(!get_option('dna88_wp_video_comment_lang_text_record_listen')) {
            update_option('dna88_wp_video_comment_lang_text_record_listen', 'Record, Review, Save');
        }
        
        if(!get_option('dna88_wp_video_comment_lang_text_unavilable')) {
            update_option('dna88_wp_video_comment_lang_text_unavilable', 'Video camera is not available, Connect your camera or webcam');
        }
        
        if(!get_option('dna88_wp_video_comment_lang_http_unavilable')) {
            update_option('dna88_wp_video_comment_lang_text_unavilable', 'Please use https:// to load the website. Insecure connection will not work for video recording');
        }
        
        if(!get_option('dna88_wp_video_single_product_enable')) {
            update_option('dna88_wp_video_single_product_enable', 1);
        }
        if(!get_option('dna88_wp_video_allow_fullscreen_play')) {
            update_option('dna88_wp_video_allow_fullscreen_play', 1);
        }
        if(!get_option('dna88_wp_video_allow_auto_play')) {
            update_option('dna88_wp_video_allow_auto_play', 1);
        }
        if(!get_option('dna88_wp_video_allow_video_title')) {
            update_option('dna88_wp_video_allow_video_title', 1);
        }
        if(!get_option('dna88_wp_video_allow_mute')) {
            update_option('dna88_wp_video_allow_mute', 1);
        }
        if(!get_option('dna88_wp_video_controls')) {
            update_option('dna88_wp_video_controls', 1);
        }

       wp_mkdir_p( trailingslashit(wp_upload_dir()['basedir'] ) . 'wpvideomessage/' );

    }


}


/* Ajax search */
add_action('wp_ajax_dna88_wpvideomessage_delete', 'dna88_wpvideomessage_delete');
add_action('wp_ajax_nopriv_dna88_wpvideomessage_delete', 'dna88_wpvideomessage_delete');
if ( ! function_exists( 'dna88_wpvideomessage_delete' ) ) {
    function dna88_wpvideomessage_delete(){

        check_ajax_referer( 'wpvideomessage-nonce', 'nonce' );
        $post_id = isset($_POST['post_id']) ? trim(sanitize_text_field($_POST['post_id'])) : '';

        if(empty($post_id)){
           wp_die();
        }

        $dna88_video_path = get_option( $post_id, 'dna88_wpvm_vmwpmdp_videomssg_audio' );

        if( !empty($dna88_video_path) && file_exists( $dna88_video_path ) ){
           wp_delete_file( $dna88_video_path );
        }


        delete_post_meta( $post_id, 'dna88_wpvm_vmwpmdp_videomssg_audio' );
        delete_post_meta( $post_id, 'dna88_wpvm_vmwpmdp_videomssg_audio_sample_rate' );
        delete_post_meta( $post_id, 'vmwpmdp_cform_id' );

        wp_delete_post( $post_id );

        //echowp_send_json($post_id);
        wp_die();

    }
}


//add_action( 'admin_notices', 'dna88_wpvideomessage_pro_notice',100 );
if ( ! function_exists( 'dna88_wpvideomessage_pro_notice' ) ) {
    function dna88_wpvideomessage_pro_notice(){
        global $pagenow, $typenow;

        $screen = get_current_screen();

        if( (isset($screen->base) && ( $screen->base == 'toplevel_page_dna88-video-record' || $screen->base == 'video-connect_page_wpvideoproduct_settings'  || $screen->base == 'video-connect_page_wp_video_bubbles' )) || (isset($typenow) && ($typenow == 'wp_videomsg_record')) ){
        ?>
        <div id="message" class="notice notice-info is-dismissible" style="padding:4px 0px 0px 4px;background:#C13825;">
            <?php
                printf(
                    __('%s  %s  %s', 'dna88-wp-video'),
                    '<a href="'.esc_url('https://www.dna88.com/product/video-connect/').'" target="_blank">',
                    '<img src="'.esc_url(dna88_wp_video_comment_IMG_URL).'/4th-of-july.gif" >',
                    '</a>'
                );
            ?>
        </div>
<?php

        }

    }
}

if ( ! function_exists( 'dna88_load_single_template' ) ) {
    function dna88_load_single_template( $template ) {
        global $post;

        if( get_post_type($post) == null ){
            flush_rewrite_rules();
        }

        if (get_post_type($post) == 'dna_videomsg_record' ) {
            $file_name = 'single-dna_videomsg_record.php';
            $template = dna88_wp_video_comment_file_dir . '/template/' . $file_name;
        }

        return $template;
    }
    add_filter( 'single_template', 'dna88_load_single_template', 99);
}
