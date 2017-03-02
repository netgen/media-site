import $ from 'jquery';
window.jQuery = $; //fix for bootstrap module referencing to global jQuery

require('swiper');
require('bootstrap-sass');

/* CHECK WHEN ELEMENT IS IN VIEWPORT  -----------------------------------------------*/
(function(){

    function isElementInViewport (el) {

        //special bonus for those using jQuery
        if (typeof jQuery === "function" && el instanceof jQuery) {
            el = el[0];
        }

        var rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
            rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
        );
    }

    window.isElementInViewport = isElementInViewport;

    var to;
    $(window).on('scroll', function(){
        to && clearTimeout(to);
        to = setTimeout(function(){
            $(window).trigger('scroll:end');
        }, 200);
    });

    $.fn.in_viewport = function(cb){
        return $(this).each(function(){
            var $this = $(this);

            if($this.hasClass('in_viewport')){return;}
            $(window).on('scroll:end', function(){
                if(isElementInViewport($this)){
                    $this.trigger('in_viewport')
                    cb && cb.call($this);
                }
            }).addClass('in_viewport');
        });
    }

})();
/* /CHECK WHEN ELEMENT IS IN VIEWPORT  -----------------------------------------------*/

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
                sources: sources,
                image: videoObject.data('image')
            }]
        });
    }
}
/* /JWPLAYER INIT  -----------------------------------------------*/

$(document).ready(function($) {
    var $loginform = $('form[name="loginform"]');
    $loginform.attr('action', $loginform.attr('action') + window.location.hash);

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
    $('.sushi-swiper.swiper-container').each(function(index) {
        var swiperId = 'sushiSwiper-' + (index + 1).toString();
        var data = $(this).data();
        $(this).attr('id', swiperId);
        sushiSwiper.push(
            new Swiper($(this), {
                pagination: '#' + swiperId + ' .swiper-pagination',
                paginationClickable: true,
                nextButton: '#' + swiperId + ' .swiper-button-next',
                prevButton: '#' + swiperId + ' .swiper-button-prev',
                slidesPerView: data.slidesPerView,
                slidesPerGroup: data.slidesPerView,
                spaceBetween: 30,
                loop: data.loop,
                effect: data.effect,
                autoplay: data.autoplay*1000,
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
        var data = $(this).data();
        $(this).attr('id', swiperId);
        defaultSwiper.push(
            new Swiper($(this), {
                pagination: '#' + swiperId + ' .swiper-pagination',
                paginationClickable: true,
                nextButton: '#' + swiperId + ' .swiper-button-next',
                prevButton: '#' + swiperId + ' .swiper-button-prev',
                preloadImages: false,
                loop: data.loop,
                effect: data.effect,
                autoplay: data.autoplay*1000,
                lazyLoading: true,
                lazyLoadingInPrevNext: true,
                lazyLoadingOnTransitionStart: true
            })
        );
    });
    var relatedSwiper = [];
    $('.related-multimedia.swiper-container').each(function(index) {
        var swiperId = 'relatedMultimediaSwiper-' + (index + 1).toString();
        var data = $(this).data();
        $(this).attr('id', swiperId);
        relatedSwiper.push(
            new Swiper($(this), {
                pagination: '#' + swiperId + ' .swiper-pagination',
                paginationClickable: true,
                nextButton: '#' + swiperId + ' .swiper-button-next',
                prevButton: '#' + swiperId + ' .swiper-button-prev',
                preloadImages: false,
                loop: data.loop,
                effect: data.effect,
                autoplay: data.autoplay*1000,
                lazyLoading: true,
                lazyLoadingInPrevNext: true,
                lazyLoadingOnTransitionStart: true,
                autoHeight: true,
                onLazyImageReady: function(swiper){
                    swiper.updateAutoHeight();
                }
            })
        );
    });
    /* /idangero.us swiper */

    /* header actions */
    (function($,c,b){$.map("click dblclick mousemove mousedown mouseup mouseover mouseout change select submit keydown keypress keyup".split(" "),function(d){a(d)});a("focusin","focus"+b);a("focusout","blur"+b);$.addOutsideEvent=a;function a(g,e){e=e||g+b;var d=$(),h=g+"."+e+"-special-event";$.event.special[e]={setup:function(){d=d.add(this);if(d.length===1){$(c).bind(h,f)}},teardown:function(){d=d.not(this);if(d.length===0){$(c).unbind(h)}},add:function(i){var j=i.handler;i.handler=function(l,k){l.target=k;j.apply(this,arguments)}}};function f(i){$(d).each(function(){var j=$(this);if(this!==i.target&&!j.has(i.target).length){j.triggerHandler(e,[i.target])}})}}})($,document,"outside"); //plugin for click outside
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

    /* get video poster */
    var getVideoPoster = function(el, service){
        var videoID = el.attr('data-id'),
            thumbname = el.attr('data-thumbname');
        switch (service) {
            case 'dailymotion':
                var url = 'https://api.dailymotion.com/video/' + videoID + '?fields=' + thumbname;
                var getUrl = function(obj){
                    return obj[thumbname];
                };
                break;
            case 'vimeo':
                var url = 'https://vimeo.com/api/v2/video/' + videoID + '.json';
                var getUrl = function(obj){
                    return obj[0][thumbname];
                };
                break;
        }
        $.ajax({
            type:'GET',
            url: url,
            jsonp: 'callback',
            dataType: 'jsonp',
            success: function(data){
                el.attr('src', getUrl(data));
            }
        });
    };
    $('img.vimeo-poster').each(function(){
        getVideoPoster($(this), 'vimeo');
    });
    $('img.dailymotion-poster').each(function(){
        getVideoPoster($(this), 'dailymotion');
    });
    /* /get video poster */

});
