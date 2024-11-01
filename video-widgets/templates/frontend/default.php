<div class="qc_video_wrapper wave_animation <?php echo esc_attr( $video_templalate ); ?> <?php echo esc_attr( $video_templalate . '_' . esc_attr($id) ); ?> animate__animated" data-animation="<?php echo $qc_video_animation; ?>">
    <div class="qc_video_animation" >
        <img src="<?php echo esc_url_raw( $featured_img_url ); ?>" alt="" />
        
    </div>
    
    <div class="qc_video_video" style="display:none">
        <video class="qc-audio-front" controls src="<?php echo esc_url_raw( $audio_url ); ?>"></video>
    </div>
    
    <a class="qc_video_lightbox" data-fancybox="video-gallery" href="<?php echo esc_url_raw( $audio_url ); ?>"><i class="fa fa-video-camera" aria-hidden="true"></i>
</a>
</div>
