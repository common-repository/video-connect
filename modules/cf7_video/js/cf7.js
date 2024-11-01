jQuery(document).ready(function($){


  var constraints = { "video": { width: { max: 320 } }, "audio" : true };
  var theStream;
  var recorder;
  var recordedChunks = [];
  var dna88_countdownInterval;

  function start_recording() {
    navigator.mediaDevices.getUserMedia(constraints)
        .then(gotMedia)
        .catch(e => { console.error('getUserMedia() failed: ' + e); });
  }

  function gotMedia(stream) {
    theStream = stream;
    //var video = document.querySelector('#video_record');
    var video = document.getElementById("video_record");
    dna88_createCountdown();

    setTimeout(() => {

      dna88_max_recording_stop();
        
    }, dna88_max_recording_time * 1000 );
    
    //console.log(video);
    video.srcObject = stream;
    video.muted = true;
    try {
      recorder = new MediaRecorder(stream);
    } catch (e) {
      console.error('Exception while creating MediaRecorder: ' + e);
      return;
    }
    
    recorder.ondataavailable = function( event ) {
      recordedChunks.push(event.data);
    };
    recorder.start(100);
  }



    $(document).on('click','.start_recording',function (e) {
      
      // e.preventDefault();

      if(theStream) {
        theStream.getTracks().forEach(track => track.stop()) 
      }

      var protocol = window.location.href.indexOf("https://");

      if( protocol !== 0 ){
        alert(lang_http_unavilable);
        return;
      }

      $('.wpcf7-submit').prop("disabled", true);

      // use the proper vendor prefix
      navigator.getMedia = ( navigator.getUserMedia || 
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia
                        );



      if(navigator.getMedia){

        navigator.getMedia({video: true}, function() {
          // webcam is available

          var constraints = { "video": { width: { max: 320 }, facingMode: 'environment' }, "audio" : true };
          navigator.mediaDevices.getUserMedia({ video: true, audio : true })
            .then(gotMedia)
            .catch(e => { 
              alert('webcam is not available. ' + e); 
            });

            $('.start_recording').hide();
            $('.dna88_record_notification').hide();
            $('.stop_recording').show();
            $('.dna88_preview_wrap').hide();
            $('.video_record').show();

            $('.dna88_voice_countdown').remove();
            $('.dna88_record_wrap').append('<div class="dna88_voice_countdown"></div>');

            //dna88_createCountdown();

            // dna88_max_recording_time
            localStorage.setItem("dna88_max_record_time_check",  1 );

            //setTimeout(dna88_max_recording_stop, dna88_max_recording_time);

            // setTimeout(() => {

            //   dna88_max_recording_stop();
                
            // }, dna88_max_recording_time * 1000 );


        }, function() {
          // webcam is not available
          $('.dna88_record_notification').text(lang_text_unavilable);
          //return false;
        });

      }else{

          var constraints = { "video": { width: { max: 320 }, facingMode: 'environment' }, "audio" : true };
          navigator.mediaDevices.getUserMedia({ video: true, audio : true })
            .then(gotMedia)
            .catch(e => { 
              alert('webcam is not available. ' + e); 
            });

            $('.start_recording').hide();
            $('.dna88_record_notification').hide();
            $('.stop_recording').show();
            $('.dna88_preview_wrap').hide();
            $('.video_record').show();

            $('.dna88_voice_countdown').remove();
            $('.dna88_record_wrap').append('<div class="dna88_voice_countdown"></div>');

            //dna88_createCountdown();

            // dna88_max_recording_time
            // localStorage.setItem("dna88_max_record_time_check",  1 );

            // setTimeout(dna88_max_recording_stop, dna88_max_recording_time);
            localStorage.setItem("dna88_max_record_time_check",  1 );

            // setTimeout(() => {

            //   dna88_max_recording_stop();
                
            // }, dna88_max_recording_time * 1000 );

      }

  });


  function dna88_createCountdown(){
    
    const countdownElement = document.querySelector( '.dna88_voice_countdown' );
    /** Reset previously countdowns. */
    clearInterval( dna88_countdownInterval );
    let maxDuration = dna88_max_recording_time;
    let countdown = dna88_max_recording_time;
    let isCountdownPaused = false;
    let resetMinutes = Math.floor( maxDuration / 60 );
    let resetSeconds = maxDuration - resetMinutes * 60;
    countdownElement.innerHTML = resetMinutes + ':' + resetSeconds;

    /** Start new countdown. */
    dna88_countdownInterval = setInterval( function () {

        if ( isCountdownPaused ) { return; } // Pause.

        countdown--;

        /** If timer lower than 0 Stop recording. */
        if ( maxDuration !== 0 && countdown < 0 ) {
          //$(".stop_recording").trigger('click');
        }

        let minutes = Math.floor( countdown / 60 );
        let seconds = countdown - minutes * 60;
        countdownElement.innerHTML = minutes + ':' + seconds;

    }, 1000 );

  }

    function dna88_max_recording_stop() {
        
        if( localStorage.getItem("dna88_max_record_time_check") == 1 ){

          $(".stop_recording").trigger('click');

        }


    }


    $(document).on('click','.stop_recording',function (e) {
      //e.preventDefault();
      if ( typeof recorder !== 'undefined' && recorder !== null ) {
        recorder.stop();
      }
      if( typeof theStream !== 'undefined' && theStream !== null ) {
        theStream.getTracks().forEach(track => { track.stop(); });
      }

      var blob = new Blob(recordedChunks, {type: "video/mp4"});

      var url =  URL.createObjectURL(blob);
      
      clearInterval( dna88_countdownInterval );
      $('.dna88_voice_countdown').remove();

      $('.dna88_wp_video_comment_url').val(url);
      $('.dna88_record_wrap').hide();
      $('.dna88_record_notification').hide();
      $('.dna88_preview').attr("src", url);
      $('.dna88_preview_wrap').show();
      $('.dna88_preview_loading_box').remove();
      $('.dna88_preview_wrap').append('<div class="dna88_preview_loading_box"><div class="dna88_preview_spinner-box"><div class="dna88_preview_circle-border"><div class="dna88_preview_circle-core"></div></div> </div></div>');

      $('.wpcf7-submit').prop("disabled", true);

      let metadata = {
        type: 'video/mp4'
      };
      
      var myFormData = new FormData();
      myFormData.append('video_file', blob, "foo.mp4" );
      myFormData.append('cform-id', 1000);
      myFormData.append('nonce', dna88_cf7_ajax_nonce);
      myFormData.append('action', 'dna88_cf7wpvideomessage_send');

      $.ajax({
        url: dna88_cf7_ajaxurl,
        data: myFormData,
        processData: false,
        contentType: false,
        type: 'POST',
        enctype: 'multipart/form-data',
        success: function(res){

          $('.dna88_wpvideomessage').val(JSON.stringify(res));
          $('.dna88-remove-video-confirmation-delete').attr('data-post-id', res.record_id);

          localStorage.setItem("dna88_max_record_time_check",  false );
          localStorage.setItem("dna88_record_submit_check",  1 );
          recordedChunks = [];
          $('.dna88_preview_loading_box').remove();

          $('.wpcf7-submit').prop("disabled", false);
        }
      });

  });


    $(document).on('click','.dna88_remove_preview',function (e) {
      //e.preventDefault();
      
      $('.dna88_preview_wrap .dna88-remove-video-confirmation').addClass('active-confirmation');

    });


    $(document).on('click','.wpcf7-submit',function (e) {
      //e.preventDefault();
      localStorage.setItem("dna88_record_submit_check",  false );

    });

    $(document).on('click','.dna88-remove-video-confirmation-cancel',function (e) {
      e.preventDefault();
     // recorder.stop();
      //theStream.getTracks().forEach(track => { track.stop(); });
      $('.dna88_preview_wrap .dna88-remove-video-confirmation').removeClass('active-confirmation');

    });

    $(document).on('click','.dna88-remove-video-confirmation-delete',function (e) {
      e.preventDefault();
      //recorder.stop();
      //theStream.getTracks().forEach(track => { track.stop(); });
      $('.dna88_preview_wrap .dna88-remove-video-confirmation').removeClass('active-confirmation');
      var theRecorder;
      $('.dna88_preview_wrap').hide();
      $('.stop_recording').hide();
      $('.start_recording').show();
      $('.dna88_record_wrap').show();
      $('.dna88_record_notification').show();

      var post_id = $(this).attr('data-post-id');

      if( localStorage.getItem("dna88_record_submit_check" ) == 1 ){

        var data = {
          'post_id':  post_id,
          'nonce':    dna88_cf7_ajax_nonce,
          'action':   'dna88_wpvideomessage_delete',
        };

        jQuery.post(dna88_cf7_ajaxurl, data, function (response) {

          // console.log(response);

        });

      }


    });

    $(document).on('click','.dna88-video-confirmation-record',function (e) {
      //e.preventDefault();
      $(this).hide();
      $('.dna88_wp_video_wrap').removeClass('dna88_wp_video_wrap_active');

    });




});