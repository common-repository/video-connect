jQuery( document ).ready( function( $ ) {

"use strict";

  let qc_video_bubbleVideo = document.getElementById("qc_video_bubble_video");

  qc_video_bubbleVideo.autoplay = true;
  qc_video_bubbleVideo.muted = true;
  qc_video_bubbleVideo.loop = true;

  $(window).scroll(function() {
   
    var scroll = scrollY;
    if (scroll > 1) {
      $(".qc_video_bubble_wrapper").addClass("qc_video_bubble_wrapper-resize");
    } else {
      $(".qc_video_bubble_wrapper").removeClass("qc_video_bubble_wrapper-resize");
    }

  });

  $(document).on('click', '.qc_video_bubble_video, .qc_video_bubble_text, .qc_video_bubble_logo_img', function(e){

    //e.preventDefault();
    var currentDom = $(this);
    var qc_video_bubbleWrapper = currentDom.closest('.qc_video_bubble_wrapper');
    var qc_video_bubbleVideo = qc_video_bubbleWrapper.find(".qc_video_bubble_video");

    qc_video_bubbleVideo.prop('muted', false);

    if(!qc_video_bubbleWrapper.find('.qc_video_bubble_wrapper-full')){
      qc_video_bubbleVideo.currentTime = 0;
    }

    qc_video_bubbleWrapper.addClass("qc_video_bubble_wrapper-full");

    //qc_video_bubbleWrapper.toggleClass("play-video");

    qc_video_bubbleWrapper.find(".qc_video_bubble_full-mute").css({'display': 'none'});
    qc_video_bubbleWrapper.find(".qc_video_bubble_full-volume").css({'display': 'flex'});
    qc_video_bubbleWrapper.find(".qc_video_bubble_full-close").css({'display': 'flex'});

    if (qc_video_bubbleWrapper.find(".play-video")) {
      qc_video_bubbleVideo.get(0).play();
      qc_video_bubbleWrapper.find(".qc_video_bubble_full-play").css({'display': 'flex'});
    } else {
      qc_video_bubbleVideo.get(0).pause();
      qc_video_bubbleWrapper.find(".qc_video_bubble_full-play").css({'display': 'flex'});
    }
    qc_video_bubbleWrapper.find(".qc_video_bubble_fullbtn").css({'display': 'block'});

    

  });

  $(document).on('click', '.qc_video_bubble_full-close', function(e){

   // e.preventDefault();
    var currentDom = $(this);
    var qc_video_bubbleWrapper = currentDom.closest('.qc_video_bubble_wrapper');
    var qc_video_bubbleVideo = qc_video_bubbleWrapper.find(".qc_video_bubble_video");

    qc_video_bubbleWrapper.removeClass("qc_video_bubble_wrapper-full");
    qc_video_bubbleWrapper.removeClass("play-video");
    qc_video_bubbleWrapper.find(".qc_video_bubble_full-volume").css({'display': 'none'});
    qc_video_bubbleWrapper.find(".qc_video_bubble_full-mute").css({'display': 'none'});
    qc_video_bubbleWrapper.find(".qc_video_bubble_full-play").css({'display': 'none'});
    qc_video_bubbleWrapper.find(".qc_video_bubble_full-pause").css({'display': 'none'});

    qc_video_bubbleVideo.prop('muted', true);
    qc_video_bubbleVideo.get(0).play();
    $(this).css({'display': 'none'});
    
  });

  // CLOSE TOTAL qc_video_bubble
  $(document).on('click', '.qc_video_bubble_close', function(e){

   // e.preventDefault();
    var currentDom = $(this);
    var qc_video_bubbleWrapper = currentDom.closest('.qc_video_bubble_wrapper');
    qc_video_bubbleWrapper.css({'display': 'none'});

  });

  // VOLUME UP
  $(document).on('click', '.qc_video_bubble_full-volume', function(e){

    //e.preventDefault();
    var currentDom = $(this);
    var qc_video_bubbleWrapper = currentDom.closest('.qc_video_bubble_wrapper');
    var qc_video_bubbleVideo = qc_video_bubbleWrapper.find(".qc_video_bubble_video");

    qc_video_bubbleWrapper.find(".qc_video_bubble_full-mute").css({'display': 'flex'});
    qc_video_bubbleVideo.prop('muted', true);
    currentDom.css({'display': 'none'});

  });

  // VOLUME MUTE
  $(document).on('click', '.qc_video_bubble_full-mute', function(e){

    //e.preventDefault();
    var currentDom = $(this);
    var qc_video_bubbleWrapper = currentDom.closest('.qc_video_bubble_wrapper');
    var qc_video_bubbleVideo = qc_video_bubbleWrapper.find(".qc_video_bubble_video");

    qc_video_bubbleWrapper.find(".qc_video_bubble_full-volume").css({'display': 'flex'});
    //qc_video_bubbleVideo.muted = false;
    qc_video_bubbleVideo.prop('muted', false);
    currentDom.css({'display': 'none'});

  });

  // VIDEO EXPAND
  $(document).on('click', '.qc_video_bubble_full-expand', function(e){

    // e.preventDefault();
    var currentDom = $(this);
    var qc_video_bubbleWrapper = currentDom.closest('.qc_video_bubble_wrapper');
    var qc_video_bubbleVideo = qc_video_bubbleWrapper.find(".qc_video_bubble_video");

      var vid = qc_video_bubbleVideo[0];
      //vid.play();
      if (vid.requestFullscreen) {
        vid.requestFullscreen();
      } else if (vid.mozRequestFullScreen) {
        vid.mozRequestFullScreen();
      } else if (vid.webkitRequestFullscreen) {
        vid.webkitRequestFullscreen();
      }
      
  });

  // VIDEO REPLY
  $(document).on('click', '.qc_video_bubble_full-replay', function(e){

   // e.preventDefault();
    var currentDom = $(this);
    var qc_video_bubbleWrapper = currentDom.closest('.qc_video_bubble_wrapper');
    var qc_video_bubbleVideo = qc_video_bubbleWrapper.find(".qc_video_bubble_video");
    qc_video_bubbleVideo.currentTime = 0;

    qc_video_bubbleVideo.get(0).load();
      
  });

  // VIDEO PLAY
  $(document).on('click', '.qc_video_bubble_full-play', function(e){

   // e.preventDefault();
    var currentDom = $(this);
    var qc_video_bubbleWrapper = currentDom.closest('.qc_video_bubble_wrapper');
    var qc_video_bubbleVideo = qc_video_bubbleWrapper.find(".qc_video_bubble_video");

    qc_video_bubbleWrapper.find(".qc_video_bubble_full-pause").css({'display': 'flex'});
    qc_video_bubbleVideo.get(0).pause();
    currentDom.css({'display': 'none'});
      
  });

  // VIDEO PAUSE
  $(document).on('click', '.qc_video_bubble_full-pause', function(e){

   // e.preventDefault();
    var currentDom = $(this);
    var qc_video_bubbleWrapper = currentDom.closest('.qc_video_bubble_wrapper');
    var qc_video_bubbleVideo = qc_video_bubbleWrapper.find(".qc_video_bubble_video");

    qc_video_bubbleWrapper.find(".qc_video_bubble_full-play").css({'display': 'flex'});
      qc_video_bubbleVideo.get(0).play();
      currentDom.css({'display': 'none'});
      
  });




});