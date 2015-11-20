/* JWPLAYER INIT  -----------------------------------------------*/

function jwplayer_init( videoObjectClass, videoObject ){

    if(videoObject === false) {
        videoObject = $(videoObjectClass);
    }
    if(videoObject.length) {
        var sources = false,
            videoId = videoObject.data('video_player_id'),
            royalSliderContainer = videoObject.parents('.royalSlider'),
            aspectRatio = 1.77, /* default for 16:9 */
            width = '100%',
            height = '100%';

        if (royalSliderContainer.length) {
            aspectRatio = royalSliderContainer.width() / royalSliderContainer.height();
        }
        else {
            if (typeof videoObject.data('width') !== 'undefined') {
                width = videoObject.data('width');
            } else {
                width = videoObject.parent().width();
            }
            if (typeof videoObject.data('height') !== 'undefined') {
                height = videoObject.data('height');
            } else {
                height = videoObject.parent().height();
            }

            if( width.toString().indexOf('%') < 0 || height.toString().indexOf('%') < 0  ){
                aspectRatio = parseInt(width) / parseInt(height);
            }
        }

        aspectRatio += ':1';

        if( videoObject.data('videotype') == 'local' ) {
            sources = [{
                file: videoObject.data('file'),
                type: videoObject.data('mimetype')
            }];
        }
        else{
            sources = [{
                file: videoObject.data('file')
            }];
        }

        jwplayer(videoId).setup({
            primary: 'flash',
            width: width,
            height: height,
            aspectratio: aspectRatio,
            autostart: videoObject.data('autostart'),
            base: '/bundles/netgenmoredemo/js/jwplayer/',
            controlbar: [{ idlehide: 'true' }],
            playlist: [{
                sources: sources
            }]
        });
    }
}
/* /JWPLAYER INIT  -----------------------------------------------*/

$(document).ready(function($) {

    /* JWPLAYER GLOBAL INITIALIZATION -----------------------------------------------*/
        $('div.video-container').each(function(){
            if( $(this).prev().hasClass('video-config') && $(this).prev().hasClass( $(this).attr('id') ) )
            {
                jwplayer_init("." + $(this).attr('id'), false);
            }
            else{
                $(this).remove();
            }
        });
    /* /JWPLAYER GLOBAL INITIALIZATION -----------------------------------------------*/

    /* ROYALSLIDER ----------------------------------------------------------------*/
    $(".royalSlider.gallery").royalSlider({ /* Default gallery slider */
        fullscreen: {
            enabled: true,
            nativeFS: true
        },
        controlNavigation: 'thumbnails',
        autoScaleSlider: true,
        autoScaleSliderWidth: 770,
        autoScaleSliderHeight: 578,
        imageScaleMode: 'fill',
        imageAlignCenter: 'false',
        arrowsNavHideOnTouch: true,
        keyboardNavEnabled: true,
        thumbs: {
            autoCenter: false,
            firstMargin: false
        }
    });

    var campaignSlider = [];
    $(".block-view-campaign .royalSlider").each(function(index) {
        $(this).attr('id', 'campaignSlider-' + (index + 1).toString() );
        campaignSlider.push(
            $(this).royalSlider({
                /* Campaign slider */
                autoScaleSlider: true,
                autoScaleSliderWidth: 1170,
                autoScaleSliderHeight: 658,
                imageScaleMode: 'fill',
                imageAlignCenter: 'false',
                arrowsNavHideOnTouch: true,
                keyboardNavEnabled: true,
                navigateByClick: false,
                controlNavigation: 'none',
                video: {
                    // video options go gere
                    autoHideBlocks: true,
                    autoHideArrows: true,
                    autoHideControlNav: true
                }
            }).data('royalSlider')
        );
    });

    var relatedMultimediaSlider = [];
    $(".relatedMultimediaSlides.royalSlider").each(function(index) {
        $(this).attr('id', 'relatedMultimediaSlider-' + (index + 1).toString() );
        relatedMultimediaSlider.push(
            $(this).royalSlider({
                /* Related multimedia slider */
                controlNavigation: 'bullets',
                autoScaleSlider: true,
                autoScaleSliderWidth: 505,
                autoScaleSliderHeight: 337,
                imageScaleMode: 'fill',
                imageAlignCenter: 'false',
                arrowsNavHideOnTouch: true,
                keyboardNavEnabled: true,
                navigateByClick: false,
                video: {
                    autoHideBlocks: true,
                    autoHideArrows: true,
                    autoHideControlNav: true
                }
            }).data('royalSlider')
        );
    });
    /* /ROYALSLIDER -------------------------------------------------------------------*/

    /* ROYALSLIDER WITH JWPLAYER VIDEO  -----------------------------------------------*/
    if($(".block-view-campaign .royalSlider").length > 0) { // Only if at least one campaign royalslider exists
        $.each(campaignSlider, function (index, sliderObject) {
            var sliderId = sliderObject.slider.context.id;

            sliderObject.ev.on('rsOnCreateVideoElement', function (e, videoObjectClass) {
                var videoObject = $( "#" + sliderId + " ." + videoObjectClass );

                if (videoObject.length) {
                    var videoPlayerId = videoObject.data('video_player_id');
                    sliderObject.videoObj = $('<div id="' + videoPlayerId + '" class="rsVideoObj">Loading the player ...</div>');

                    setTimeout(function () {
                        jwplayer_init(videoObjectClass, videoObject);
                    }, 50);

                    $( "#" + sliderId ).addClass("rsNoDrag");
                }
            });

            sliderObject.ev.on('rsOnDestroyVideoElement', function (e) {
                $( "#" + sliderId ).removeClass("rsNoDrag");
            });
        });
    }

    if( $(".royalSlider.relatedMultimediaSlides").length > 0) { // Only if at least one instance of related multimedia royalslider exists
        $.each(relatedMultimediaSlider, function (index, sliderObject) {
            var sliderId = sliderObject.slider.context.id;
            sliderObject.ev.on('rsOnCreateVideoElement', function (e, videoObjectClass) {
                var videoObject = $( "#" + sliderId + " ." + videoObjectClass );

                if (videoObject.length) {
                    var videoPlayerId = videoObject.data('video_player_id');
                    sliderObject.videoObj = $('<div id="' + videoPlayerId + '" class="rsVideoObj">Loading the player ...</div>');

                    setTimeout(function () {
                        jwplayer_init(videoObjectClass, videoObject);
                    }, 50);

                    $( "#" + sliderId ).addClass("rsNoDrag");
                }
            });

            sliderObject.ev.on('rsOnDestroyVideoElement', function (e) {
                $( "#" + sliderId ).removeClass("rsNoDrag");
            });
        });
    }
    /* /ROYALSLIDER WITH JWPLAYER VIDEO  -----------------------------------------------*/

    /* header actions */
    $('#mainnav-toggle').on('click', function(e){
        e.preventDefault();
        $('#page').removeClass('searchbox-active').toggleClass('mainnav-active');
    })
    $('#searchbox-toggle').on('click', function(e){
        e.preventDefault();
        $('#page').removeClass('mainnav-active').toggleClass('searchbox-active');
    })
    /* /header actions */

});
