<?php

/**
 * Template audio upload metabox
 */
$video_templalate = get_post_meta( $post->ID, 'qc_video_template', true );

$qc_video_call_to_action_text = get_post_meta( $post->ID, 'qc_video_call_to_action_text', true );
$qc_video_call_to_action_button_label = get_post_meta( $post->ID, 'qc_video_call_to_action_button_label', true );
$qc_video_call_to_action_url = get_post_meta( $post->ID, 'qc_video_call_to_action_url', true );
$qc_video_call_to_action_new_tab = get_post_meta( $post->ID, 'qc_video_call_to_action_new_tab', true );

$qc_video_color = get_post_meta( $post->ID, 'qc_video_color', true );

$templates = $this->get_templates();
$image = $templates['default']['image'];
if ( $video_templalate && '' !== $video_templalate ) {
    $image = $templates[$video_templalate]['image'];
}
?>
<div class="qc_video_template_wrapper" >
    <div class="qc_video_template_settings" >
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="qc_video_template_selector"><?php echo esc_html__( 'Select A Template:', 'video-widgets' ); ?></label>
                    </th>
                    <td>
                        <select id="qc_video_template_selector" name="qc_video_template">
                            <?php foreach( $templates as $template_key => $template ): ?>
                                <option value="<?php echo esc_attr( $template_key ); ?>" <?php echo ( $template_key == $video_templalate ? 'selected="selected"' : '' ); ?> > <?php echo esc_html( $template['name'] ); ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="qc_video_call_to_action" >
                    <th scope="row">
                        <label for="qc_video_call_to_action_text"><?php echo esc_html__( 'Call to Action Text:', 'video-widgets' ); ?></label>
                    </th>
                    <td>
                       <input type="text" value="<?php echo esc_html( $qc_video_call_to_action_text ); ?>" id="qc_video_call_to_action_text" name="qc_video_call_to_action_text" />
                    </td>
                </tr>
                <tr class="qc_video_call_to_action" >
                    <th scope="row">
                        <label for="qc_video_call_to_action_button_label"><?php echo esc_html__( 'Call to Action Button Label:', 'video-widgets' ); ?></label>
                    </th>
                    <td>
                       <input type="text" value="<?php echo esc_html( $qc_video_call_to_action_button_label ); ?>" id="qc_video_call_to_action_button_label" name="qc_video_call_to_action_button_label" />
                    </td>
                </tr>
                <tr class="qc_video_call_to_action" >
                    <th scope="row">
                        <label for="qc_video_call_to_action_button_label"><?php echo esc_html__( 'Call to Action Button URL:', 'video-widgets' ); ?></label>
                    </th>
                    <td>
                       <input type="text" value="<?php echo esc_url_raw( $qc_video_call_to_action_url ); ?>" id="qc_video_call_to_action_url" name="qc_video_call_to_action_url" />
                    </td>
                </tr>
                <tr class="qc_video_call_to_action" >
                    <th scope="row">
                        <label for="qc_video_call_to_action_button_label"><?php echo esc_html__( 'Link Open in New Tab:', 'video-widgets' ); ?></label>
                    </th>
                    <td>
                       <input type="checkbox" value="1" <?php echo ( $qc_video_call_to_action_new_tab == 1 ? 'checked="checked"' : '' ); ?> id="qc_video_call_to_action_new_tab" name="qc_video_call_to_action_new_tab" />
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="qc_video_template_preview">
        <img src="<?php echo esc_url_raw( $image ); ?>" alt="" />
    </div>
    
</div>