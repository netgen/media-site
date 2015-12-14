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
            width: '100%',
            height: height,
            aspectratio: aspectRatio,
            autostart: videoObject.data('autostart'),
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
            var videoObjectClass = $(this).attr('id');
            if( $(this).prev().hasClass('video-config') && $(this).prev().hasClass( videoObjectClass ) )
            {
                jwplayer_init("." + videoObjectClass, false);
            }
            else{
                $(this).remove();
            }
        });
    /* /JWPLAYER GLOBAL INITIALIZATION -----------------------------------------------*/

    /* idangero.us swiper */
    var campaignSwiper = [];
    $('.block-view-campaign .swiper-container').each(function(index) {
        $(this).attr('id', 'campaignSwiper-' + (index + 1).toString());
        campaignSwiper.push(
            new Swiper($(this), {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
                setWrapperSize: true
            })
        );
    });
    /* /idangero.us swiper */

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
    var sliderVideoInit = function (sliderObject) {
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
    };

    if($(".block-view-campaign .royalSlider").length > 0) { // Only if at least one campaign royalslider exists
        $.each(campaignSlider, function(index, sliderObject){
            sliderVideoInit(sliderObject);
        });
    }
    if( $(".royalSlider.relatedMultimediaSlides").length > 0) { // Only if at least one instance of related multimedia royalslider exists
        $.each(relatedMultimediaSlider, function(index, sliderObject){
            sliderVideoInit(sliderObject);
        });
    }
    /* /ROYALSLIDER WITH JWPLAYER VIDEO  -----------------------------------------------*/

    /* header actions */
    (function($,c,b){$.map("click dblclick mousemove mousedown mouseup mouseover mouseout change select submit keydown keypress keyup".split(" "),function(d){a(d)});a("focusin","focus"+b);a("focusout","blur"+b);$.addOutsideEvent=a;function a(g,e){e=e||g+b;var d=$(),h=g+"."+e+"-special-event";$.event.special[e]={setup:function(){d=d.add(this);if(d.length===1){$(c).bind(h,f)}},teardown:function(){d=d.not(this);if(d.length===0){$(c).unbind(h)}},add:function(i){var j=i.handler;i.handler=function(l,k){l.target=k;j.apply(this,arguments)}}};function f(i){$(d).each(function(){var j=$(this);if(this!==i.target&&!j.has(i.target).length){j.triggerHandler(e,[i.target])}})}}})(jQuery,document,"outside"); //plugin for click outside
    (function(){
        var page = $('#page'),
            navToggle = $('#mainnav-toggle'),
            searchToggle = $('#searchbox-toggle'),
            searchForm = $('#header-search'),
            searchInput = searchForm.find('input.search-query'),
            pageToggleClass = function(e, classToToggle, classToRemove) {
                e.preventDefault();
                page.toggleClass(classToToggle);
                if(classToRemove) {
                    page.removeClass(classToRemove);
                }
            };
        /* toggle mobile menu */
        navToggle.on('click', function(e){
            pageToggleClass(e, 'mainnav-active');
        });
        /* toggle searchbox */
        searchToggle.on('click', function(e){
            pageToggleClass(e, 'searchbox-active', 'mainnav-active');
            searchInput.focus();
        });
        searchForm.on('clickoutside', function(e){
            page.removeClass('searchbox-active');
        });
        searchInput.on('input', function(){
            if($(this).val() != ''){
                searchForm.addClass('filled');
            } else {
                searchForm.removeClass('filled');
            }
        });

        /* toggle mobile sumbmenu */
        var mainNav = $('#main-nav').find('ul.navbar-nav'),
            submenuTrigContent = $('<i class="submenu-trigger"></i>');
        mainNav.find('.menu_level_1').before(submenuTrigContent).parent('li').attr('data-submenu', 'true');
        mainNav.on('click', 'i.submenu-trigger', function(){
            $(this).parent('li').toggleClass('submenu-active');
        });
    })();

    /* /header actions */

    /* get vimeo poster */
    $('div.vimeo-poster').each(function(){
        var el = $(this),
            videoID = el.attr('data-id');
        $.ajax({
            type:'GET',
            url: 'http://vimeo.com/api/v2/video/' + videoID + '.json',
            jsonp: 'callback',
            dataType: 'jsonp',
            success: function(data){
                var thumbnail_src = data[0].thumbnail_large;
                el.append('<img src="' + thumbnail_src + '">');
            }
        });
    });
    /* /get vimeo poster */

});
