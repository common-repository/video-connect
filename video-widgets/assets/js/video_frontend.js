jQuery( document ).ready( function( $ ) {
    // do code here.
    jQuery( document ).on( 'click', '.qc_play_audio', function( e ) {
        e.preventDefault();
        var obj = $(this);
        obj.removeClass( 'qc_play_audio' ).addClass( 'qc_stop_audio' );
        obj.find( 'i' ).toggleClass("fa-play-circle fa-pause-circle");
        var audio_elem = obj.parent().parent().find( '.qc_video_audio audio' )[0];
        audio_elem.play();
    
        $( audio_elem ).on( 'ended', function(e) {
            $(audio_elem).unbind('ended');
            obj.removeClass( 'qc_stop_audio' ).addClass( 'qc_play_audio' );
            obj.find( 'i' ).toggleClass("fa-play-circle fa-pause-circle");
            wave_block( obj, 'close' );
        } )

        // for wave animation
        wave_block( obj, 'repeat' );

    } )
    jQuery( document ).on( 'click', '.qc_stop_audio', function( e ) {
        e.preventDefault();
        var obj = $(this);
        obj.removeClass( 'qc_stop_audio' ).addClass( 'qc_play_audio' );
        obj.find( 'i' ).toggleClass("fa-play-circle fa-pause-circle");
        var audio_elem = obj.parent().parent().find( '.qc_video_audio audio' )[0];
        audio_elem.pause();

        // for wave animation
        wave_block( obj, 'close' );
    } )

    function wave_block(obj, handle) {
        // for wave animation
        if ( obj.parent().parent().find( '.wave-block' ).length > 0 ) {
            obj.parent().parent().find( '.wave-block' ).attr( 'qc-data-animate', handle );
        }
    }
    
    $.fn.isInViewport = function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
    
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
    
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };
    function auto_animate_on_scroll() {
        $('.qc_video_wrapper').each(function(){
            if( $(this).isInViewport() ) {
                var animation_classname = 'animate__';
                var animation = $(this).attr('data-animation');
                if ( animation != '' ) {
                    animation_classname += animation;
                }
                if ( ! $(this).hasClass( animation_classname ) ) {
                    $(this).addClass( animation_classname );
                }
            }
        });
    }
    $(window).on('resize scroll', function() {
        auto_animate_on_scroll();
    });
    auto_animate_on_scroll();

    
    $('.qc_video_lightbox').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: true,
        fixedContentPos: true,
        midClick: true
    });

} )