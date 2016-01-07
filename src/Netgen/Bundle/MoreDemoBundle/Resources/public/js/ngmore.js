/* JWPLAYER INIT  -----------------------------------------------*/

function jwplayer_init( videoObjectClass, videoObject ){

    if(videoObject === false) {
        videoObject = $(videoObjectClass);
    }
    if(videoObject.length) {
        var sources = false,
            videoId = videoObject.data('video_player_id'),
            aspectRatio = '16:9',
            width = '100%';

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
    var sushiSwiper = [];
    $('.sushiSwiper.swiper-container').each(function(index) {
        var swiperId = 'sushiSwiper-' + (index + 1).toString();
        $(this).attr('id', swiperId);
        sushiSwiper.push(
            new Swiper($(this), {
                pagination: '#' + swiperId + ' .swiper-pagination',
                paginationClickable: true,
                nextButton: '#' + swiperId + ' .swiper-button-next',
                prevButton: '#' + swiperId + ' .swiper-button-prev',
                slidesPerView: 3,
                slidesPerGroup: 3,
                spaceBetween: 30,
                breakpoints: {
                    991: {
                        slidesPerView: 2,
                        slidesPerGroup: 2
                    },
                    480: {
                        slidesPerView: 1,
                        slidesPerGroup: 1
                    }
                }
            })
        );
    });
    var defaultSwiper = [];
    $('.default-swiper.swiper-container').each(function(index) {
        var swiperId = 'defaultSwiper-' + (index + 1).toString();
        $(this).attr('id', swiperId);
        defaultSwiper.push(
            new Swiper($(this), {
                pagination: '#' + swiperId + ' .swiper-pagination',
                paginationClickable: true,
                nextButton: '#' + swiperId + ' .swiper-button-next',
                prevButton: '#' + swiperId + ' .swiper-button-prev',
                preloadImages: false,
                lazyLoading: true,
                lazyLoadingInPrevNext: true,
                lazyLoadingOnTransitionStart: true
            })
        );
    });
    var relatedSwiper = [];
    $('.related-multimedia.swiper-container').each(function(index) {
        var swiperId = 'relatedMultimediaSwiper-' + (index + 1).toString();
        $(this).attr('id', swiperId);
        relatedSwiper.push(
            new Swiper($(this), {
                pagination: '#' + swiperId + ' .swiper-pagination',
                paginationClickable: true,
                nextButton: '#' + swiperId + ' .swiper-button-next',
                prevButton: '#' + swiperId + ' .swiper-button-prev',
                preloadImages: false,
                lazyLoading: true,
                lazyLoadingInPrevNext: true,
                lazyLoadingOnTransitionStart: true
            })
        );
    });
    /* /idangero.us swiper */

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
            url: 'https://vimeo.com/api/v2/video/' + videoID + '.json',
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
