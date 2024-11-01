<?php 
defined('ABSPATH') or die("No direct script access!");
?>
<div class="vmwpmdp-admin-panel">
  <h2><?php esc_html_e('Welcome to the Video Connect','dna88-wp-video'); ?></h2>
  <h4><?php esc_html_e('Getting Started','dna88-wp-video'); ?></h4>
</div>
<div class="dna88-accordion">
  <div class="dna88-accordion-item">
    <input type="checkbox" id="accordion1">
    <label for="accordion1" class="dna88-accordion-item-title"><span class="icon"></span><?php esc_html_e('What is Video Connect?','dna88-wp-video') ?></label>
    <div class="dna88-accordion-item-desc"> <?php esc_html_e('Video Connect is an addon for contact form 7. This plugin adds a video message recorder field to your CF7 forms. Your site users can record a video message that is saved in the WordPress backend and you can listen to that any time. Compatible with all Modern Browsers and a Beautiful modern User Interface.','dna88-wp-video'); ?></div>
  </div>

  <div class="dna88-accordion-item">
    <input type="checkbox" id="accordion2">
    <label for="accordion2" class="dna88-accordion-item-title"><span class="icon"></span> <?php esc_html_e('How can I enter video message field to my contact forms?','dna88-wp-video') ?></label>
    <div class="dna88-accordion-item-desc">
        <?php esc_html_e('Navigate to Contact form 7->Add/Edit a form in wp-admin and Press the WPVideo Message button to insert shortcode in the form.','dna88-wp-video'); ?><br><br>
        <img src="<?php echo esc_url( dna88_wp_video_comment_IMG_URL. '/screenshot-1.png'); ?>"/>
    </div>
  </div>

  <div class="dna88-accordion-item">
    <input type="checkbox" id="accordion3">
    <label for="accordion3" class="dna88-accordion-item-title"><span class="icon"></span><?php esc_html_e('How can I set video field with Contact from 7 email  setting?','dna88-wp-video') ?></label>
    <div class="dna88-accordion-item-desc">
        <?php esc_html_e('Navigate to Contact form 7->Add/Edit. Select the Mail tab and add [dna88_wpvideomessage] this shortcode with your mail body.','dna88-wp-video') ?><br><br>
        <img src="<?php echo esc_url( dna88_wp_video_comment_IMG_URL.'/screenshot-2.png'); ?>"/> 
    </div>
  </div>

  <div class="dna88-accordion-item">
    <input type="checkbox" id="accordion4">
    <label for="accordion4" class="dna88-accordion-item-title"><span class="icon"></span><?php esc_html_e('Where Can I Listen to my video Message?','dna88-wp-video'); ?></label>
    <div class="dna88-accordion-item-desc">
     <?php esc_html_e('If a user records a video message, you will receive a link in the email sent by Contact Form 7 Or you can find it on contact forms the sub menu of video message for wordpress','dna88-wp-video'); ?>
    </div>
  </div>

  <div class="dna88-accordion-item">
    <input type="checkbox" id="accordion5">
    <label for="accordion5" class="dna88-accordion-item-title"><span class="icon"></span><?php esc_html_e('I received a email but not video message?','dna88-wp-video'); ?></label>
    <div class="dna88-accordion-item-desc">
    <?php esc_html_e('Make sure you add this [dna88_wpvideomessage] shortcode to your contact form 7 mail body. Then if a user records a video message, you will receive a link in the email sent by Contact Form 7','dna88-wp-video'); ?>
    </div>
  </div>
   
  <div class="dna88-accordion-item">
    <input type="checkbox" id="accordion8">
    <label for="accordion8" class="dna88-accordion-item-title"><span class="icon"></span><?php esc_html_e('How can I emmbed a youtube video?','dna88-wp-video'); ?></label>
    <div class="dna88-accordion-item-desc">
      <?php esc_html_e('To add a video widget to your content like a page or post, you have to create a new video widget by clicking Add new video widget. Add the video and put the youtube URL on the Youtube URL field. Set the video player mode to landscape or portrait then publish or update it. Now copy your short code and past it to your desire section.','dna88-wp-video'); ?></br></br>
      <img src="<?php echo esc_url( dna88_wp_video_comment_IMG_URL.'/screenshot-3.png'); ?>"/> 
      <img src="<?php echo esc_url( dna88_wp_video_comment_IMG_URL.'/screenshot-4.png'); ?>"/> 
    </div>
  </div>