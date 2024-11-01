<?php

/**
 * Template audio upload metabox
 */

$video_url = get_post_meta( $post->ID, 'qc_video_bubble_url', true );

$upload_video_url = get_post_meta( $post->ID, 'qc_upload_video_bubble_url', true );

$qc_video_bubble_mode = get_post_meta( $post->ID, 'qc_video_bubble_mode', true );
$is_video_uploaded = ( $video_url && '' !== $video_url ? true : false );
$is_upload_video_uploaded = ( $upload_video_url && '' !== $upload_video_url ? true : false );

$qc_video_bubble_bg_color = get_post_meta( $post->ID, 'qc_video_bubble_bg_color', true );
$qc_video_bubble_border_color = get_post_meta( $post->ID, 'qc_video_bubble_border_color', true );
$qc_video_bubble_logo = get_post_meta( $post->ID, 'qc_video_bubble_logo', true );


?>
<div class="qc_video_bubble_wrapper" >
    <div class="qc_video_bubble_container">

        <div class="qc_video_bubble_upload_wrap">
            <div class="qc_video_bubble_upload_content">
                <label><?php echo esc_html( 'Put your youtube url here' ); ?></label>
                <input type="text" value="<?php echo ( $is_video_uploaded ? $video_url : '' ); ?>" name="qc_video_bubble_url" id="qc_video_bubble_url" class="videoconnect_videourl_input"/></br></br>
            </div>
        </div>

        
    </div>


    <div class="qc_video_bubble_bubble_recorder_wrap">
        <table class="form-table">
            <tbody>
                    
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Use Video','dna88-wp-notice') ?></th>
                    <td>
                    
                        <label class="radio-inline">
                            <input id="qc_video_bubble_use_video" type="radio" name="qc_video_bubble_use_video" value="qc_video" <?php echo ((get_post_meta( $post->ID, 'qc_video_bubble_use_video', true ) == 'qc_video' || get_post_meta( $post->ID, 'qc_video_bubble_use_video', true ) == '') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Uploaded Video','dna88-wp-notice') ?> </label>    
                    
                        <label class="radio-inline" style="padding-right: 15px;">
                            <input id="qc_video_bubble_use_video" type="radio" name="qc_video_bubble_use_video" value="qc_youtube" <?php echo ((get_post_meta( $post->ID, 'qc_video_bubble_use_video', true ) == 'qc_youtube') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Youtube Video','dna88-wp-notice') ?> </label>                          
                    </td>
                </tr>
                    
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Hello!','dna88-wp-video') ?></th>
                    <td>
                        <input type="text" name="qc_video_bubble_text" style="width:100px" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_text', true )!=''?esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_text', true )):''); ?>"  /> <b><i><?php esc_html_e('Text','dna88-wp-video') ?></i></b> 
                        
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Hide Video Title','dna88-wp-video'); ?></th>
                    <td>
                        <input type="checkbox" name="qc_video_bubble_allow_video_title" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_video_title', true )!=''? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_video_title', true )) : '1' ); ?>" <?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_video_title', true ) == '' ? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_video_title', true )): esc_attr( 'checked="checked"' )); ?>  />  
                        <i><?php esc_html_e('Hide video title and other information','dna88-wp-video') ?></i>                           
                    </td>
                </tr>

                    
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Show Position','dna88-wp-notice') ?></th>
                    <td>
                    
                        <label class="radio-inline" style="padding-right: 15px;">
                            <input id="qc_video_bubble_position" type="radio" name="qc_video_bubble_position" value="qc_left" <?php echo ((get_post_meta( $post->ID, 'qc_video_bubble_position', true ) == 'qc_left') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Left','dna88-wp-notice') ?> </label>
                    
                        <label class="radio-inline">
                            <input id="qc_video_bubble_position" type="radio" name="qc_video_bubble_position" value="qc_right" <?php echo ((get_post_meta( $post->ID, 'qc_video_bubble_position', true ) == 'qc_right' || get_post_meta( $post->ID, 'qc_video_bubble_position', true ) == '') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Right ','dna88-wp-notice') ?> </label>                              
                    </td>
                </tr>

                    
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Action Button Show','dna88-wp-notice') ?></th>
                    <td>
                    
                        <label class="radio-inline" style="padding-right: 15px;">
                            <input id="qc_video_bubble_show_img" type="radio" name="qc_video_bubble_show_img" value="qc_image" <?php echo ((get_post_meta( $post->ID, 'qc_video_bubble_show_img', true ) == 'qc_image') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Image','dna88-wp-notice') ?> </label>
                    
                        <label class="radio-inline">
                            <input id="qc_video_bubble_show_img" type="radio" name="qc_video_bubble_show_img" value="qc_video" <?php echo ((get_post_meta( $post->ID, 'qc_video_bubble_show_img', true ) == 'qc_video' || get_post_meta( $post->ID, 'qc_video_bubble_show_img', true ) == '') ? esc_attr( 'checked="checked"' ): '' ); ?>>
                            <?php esc_html_e('Video ','dna88-wp-notice') ?> </label>                              
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="qc_video_bubble_logo"><?php echo esc_html__( 'Action Button:', 'voice-widgets' ); ?></label>
                    </th>
                    <td>
                        <a class="button button-default button-large" id="qc_video_bubble_upload_logo" href="#"><span class="dashicons dashicons-upload"></span> <?php echo esc_html__( 'Upload Image', 'voice-widgets' ); ?></a>
                       <input type="hidden" value="<?php echo esc_attr( $qc_video_bubble_logo ); ?>" 
                       id="qc_video_bubble_logo" name="qc_video_bubble_logo" />
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
                            <input type="checkbox" name="qc_video_bubble_allow_replay" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_replay', true )!=''? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_replay', true )) : '1' ); ?>" <?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_replay', true ) == '' ? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_replay', true )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Replay video','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Video Play/Pause','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="qc_video_bubble_play_pause" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_play_pause', true )!=''? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_play_pause', true )) : '1' ); ?>" <?php echo (get_post_meta( $post->ID, 'qc_video_bubble_play_pause', true ) == '' ? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_play_pause', true )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Video Play/Pause Control','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Allow Fullscreen Play','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="qc_video_bubble_allow_fullscreen_play" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_fullscreen_play', true )!=''? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_fullscreen_play', true )) : '1' ); ?>" <?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_fullscreen_play', true ) == '' ? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_fullscreen_play', true )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Allow video to fullscreen control enable/disable ','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Video Replay','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="qc_video_bubble_allow_replay" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_replay', true )!=''? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_replay', true )) : '1' ); ?>" <?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_replay', true ) == '' ? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_replay', true )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Video Replay','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Mute Video','dna88-wp-video'); ?></th>
                        <td>
                            <input type="checkbox" name="qc_video_bubble_allow_mute" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_mute', true )!=''? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_mute', true )) : '1' ); ?>" <?php echo (get_post_meta( $post->ID, 'qc_video_bubble_allow_mute', true ) == '' ? esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_allow_mute', true )): esc_attr( 'checked="checked"' )); ?>  />  
                            <i><?php esc_html_e('Mute video','dna88-wp-video') ?></i>                           
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Width','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="qc_video_bubble_width" style="width:100px" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_width', true )!=''?esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_width', true ) ):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply width 170px )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Height','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="qc_video_bubble_height" style="width:100px" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_height', true )!=''?esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_height', true ) ):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply Height 170px )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Right of the Browser Window','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="qc_video_bubble_right_browser_window" style="width:100px" size="50" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_right_browser_window', true )!=''?esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_right_browser_window', true ) ):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply Default )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Bottom of the Browser Window','dna88-wp-video') ?></th>
                        <td>
                            <input type="number" name="qc_video_bubble_bottom_browser_window" style="width:100px" size="100" value="<?php echo (get_post_meta( $post->ID, 'qc_video_bubble_bottom_browser_window', true )!=''?esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_bottom_browser_window', true ) ):''); ?>"  /> <b><i><?php esc_html_e('px','dna88-wp-video') ?></i></b> <i><?php esc_html_e('( Leave empty if you want to apply Default )','dna88-wp-video') ?></i>
                            
                        </td>
                    </tr>

                    <tr valign="top">
                      <th scope="qc_video_bubble_row"><?php  esc_html_e( 'Loading Control Options', 'voice-widgets' ); ?></th>
                      <td><div class="qc_video_bubble-blocks">
                          <div class="qc_video_bubble_row">
                            <div class="dna88-col-sm-4 text-right"> <span class="dna88-opt-title-font">
                              <?php  esc_html_e( 'Show on Home Page', 'voice-widgets' ); ?>
                              </span> </div>
                            <div class="dna88-col-sm-8">
                              <label class="radio-inline">
                                <input id="qc_video_bubble-show-home-page" type="radio"
                                                       name="qc_video_bubble_show_home_page"
                                                       value="on" <?php  echo esc_attr( (get_post_meta( $post->ID, 'qc_video_bubble_show_home_page', true ) == 'on' || get_post_meta( $post->ID, 'qc_video_bubble_show_home_page', true ) == '' ) ? 'checked' : ''); ?> >
                                <?php  esc_html_e( 'YES', 'voice-widgets' ); ?>
                              </label>
                              <label class="radio-inline">
                                <input id="qc_video_bubble-show-home-page" type="radio"
                                                       name="qc_video_bubble_show_home_page"
                                                       value="off" <?php echo esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_show_home_page', true ) == 'off' ? 'checked' : ''); ?>>
                                <?php  esc_html_e( 'NO', 'voice-widgets' ); ?>
                              </label>
                            </div>
                          </div>
                          <div class="qc_video_bubble_row">
                            <div class="dna88-col-sm-4 text-right"> <span class="dna88-opt-title-font">
                              <?php  esc_html_e( 'Show on Blog Posts', 'voice-widgets' ); ?>
                              </span> </div>
                            <div class="dna88-col-sm-8">
                              <label class="radio-inline">
                                <input class="qc_video_bubble-show-posts" type="radio"
                                                       name="qc_video_bubble_show_posts"
                                                       value="on" <?php echo esc_attr( (get_post_meta( $post->ID, 'qc_video_bubble_show_posts', true ) == 'on' || get_post_meta( $post->ID, 'qc_video_bubble_show_posts', true ) == '' ) ? 'checked' : ''); ?>>
                                <?php  esc_html_e( 'YES', 'voice-widgets' ); ?>
                              </label>
                              <label class="radio-inline">
                                <input class="qc_video_bubble-show-posts" type="radio"
                                                       name="qc_video_bubble_show_posts"
                                                       value="off" <?php echo esc_attr( get_post_meta( $post->ID, 'qc_video_bubble_show_posts', true ) == 'off' ? 'checked' : ''); ?>>
                                <?php  esc_html_e( 'NO', 'voice-widgets' ); ?>
                              </label>
                            </div>
                          </div>
                          <div class="qc_video_bubble_row">
                            <div class="dna88-col-sm-4 text-right"> <span class="dna88-opt-title-font">
                              <?php  esc_html_e( 'Show on  Pages', 'voice-widgets' ); ?>
                              </span> </div>
                            <div class="dna88-col-sm-8">
                              <label class="radio-inline">
                                <input class="qc_video_bubble-show-pages" type="radio"
                                                       name="qc_video_bubble_show_pages"
                                                       value="on" <?php echo esc_attr( ( get_post_meta( $post->ID, 'qc_video_bubble_show_pages', true ) == 'on' || get_post_meta( $post->ID, 'qc_video_bubble_show_pages', true ) == '' ) ? 'checked' : ''); ?>>
                                <?php  esc_html_e( 'All Pages', 'voice-widgets' ); ?>
                              </label>
                              <label class="radio-inline">
                                <input class="qc_video_bubble-show-pages" type="radio"
                                                       name="qc_video_bubble_show_pages"
                                                       value="off" <?php echo( get_post_meta( $post->ID, 'qc_video_bubble_show_pages', true ) == 'off' ? 'checked' : ''); ?>>
                                <?php  esc_html_e( 'Selected Pages Only', 'voice-widgets' ); ?>
                              </label>
                              <div id="qc_video_bubble-show-pages-list">
                                <ul class="checkbox-list">
                                  <?php
                                    $qc_video_bubble_pages = get_pages();
                                    $qc_video_bubble_select_pages = unserialize( get_post_meta( $post->ID, 'qc_video_bubble_show_pages_list', true ));
                                    if(empty($qc_video_bubble_select_pages)){
                                     $qc_video_bubble_select_pages = array();
                                    }
                                    foreach ($qc_video_bubble_pages as $qc_video_bubble_page) {
                                        ?>
                                  <li>
                                    <input id="qc_video_bubble_show_page_<?php echo $qc_video_bubble_page->ID; ?>"
                                       type="checkbox" name="qc_video_bubble_show_pages_list[]"
                                       value="<?php echo esc_attr($qc_video_bubble_page->ID); ?>" <?php echo(in_array($qc_video_bubble_page->ID, $qc_video_bubble_select_pages) == true ? 'checked' : ''); ?> >
                                    <label for="qc_video_bubble_show_page_<?php echo $qc_video_bubble_page->ID; ?>"> <?php echo esc_html($qc_video_bubble_page->post_title); ?></label>
                                  </li>
                                  <?php } ?>
                                </ul>
                              </div>
                            </div>
                          </div>
                         
                          
                            </div>
                        </td>
                    </tr>

            </tbody>
        </table>

    </div>

</div>