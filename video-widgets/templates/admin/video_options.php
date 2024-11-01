<?php

/**
 * Template audio upload metabox
 */

$video_url = get_post_meta( $post->ID, 'qc_video_url', true );

$qc_video_mode = get_post_meta( $post->ID, 'qc_video_mode', true );
$is_video_uploaded = ( $video_url && '' !== $video_url ? true : false );

?>
<div class="qc_video_wrapper" >
    <div class="qc_video_container">
        <label><?php echo esc_html( 'Put your youtube url here' ); ?></label></br></br>
        <input type="text" value="<?php echo ( $is_video_uploaded ? $video_url : '' ); ?>" name="qc_video_url" id="qc_video_url" class="videoconnect_videourl_input"/></br></br>
        <label><?php echo esc_html( 'Select video type' ); ?></label></br></br>
        <input type="radio" name="qc_video_mode" value="qcvc_portrait_mode" id="qcvc_portrait_mode" <?php echo ($qc_video_mode == 'qcvc_portrait_mode') ? 'checked' : '' ;?>>
        <label for="qcvc_portrait_mode"> <?php echo esc_html( 'Portrait mode' ); ?></label><br>
        <input type="radio" name="qc_video_mode" value="qcvc_landscape_mode" id="qcvc_landscape_mode" <?php echo ($qc_video_mode == 'qcvc_landscape_mode') ? 'checked' : '' ;?>>
        <label for="qcvc_landscape_mode"> <?php echo esc_html( 'Landscape mode' ); ?></label><br>
    </div>
</div>