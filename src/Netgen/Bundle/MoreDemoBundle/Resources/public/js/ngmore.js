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
    $('.sushi-swiper.swiper-container').each(function(index) {
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

    /* AJAX CONTENT GRID COLLECT ALL SETTINGS---------------------------------------------*/
    function getAllSettingsForAjaxContentGrid(id){
        var $ajaxContentBlockData = $('.ajax-content-block-'+id);
        var ajaxURL = $ajaxContentBlockData.data("url");
        var ajaxData = {
            page: parseInt($ajaxContentBlockData.data("pagenb")),
            loadingText: $ajaxContentBlockData.data("loading-text"),
            totalCount: $ajaxContentBlockData.data('total-count')
        };

        var nextPage = ajaxData.page + 1;

        if(ajaxData.page) {
            ajaxURL = ajaxURL + '?page=' + nextPage;
        }

        return { 'ajaxURL': ajaxURL, 'ajaxData': ajaxData };
    }
    /* /AJAX CONTENT GRID COLLECT ALL SETTINGS--------------------------------------------*/


    /* RUN AJAX ON GRIDS ------------------------------------------------------------*/
    function runAjaxOnGrids(id){
        var data = getAllSettingsForAjaxContentGrid(id);
        var resultsDisplayedCount = $('.ajax-block-item-result-'+id).length;
        var ajaxBlock = $('.ajax-content-block-'+id);
        var ajaxStatusReports = $('.ajax-status-reports-'+id);
        var resultsRegion = $('.results-region-'+id);
        var loadOn = $('.ajax-load-on-type-'+id);

        if (ajaxStatusReports.data('no-more') === 'true' || ajaxStatusReports.hasClass('loading')) {
            return;
        }

        if (resultsDisplayedCount >= data.ajaxData.totalCount){
            ajaxStatusReports.data('no-more', 'true');
            return;
        }

        ajaxStatusReports.addClass('loading');

        $.ajax(data.ajaxURL, {
            type: 'get',
            dataType: 'html',
            beforeSend: function()
            {
                if (loadOn) {
                    loadOn.hide();
                }
                $.blockUI({message: '<h3 class="loading-spinner">'+ data.ajaxData.loadingText +'</h3>'});
            },
            success: function(results)
            {
                $.unblockUI();

                $('.ajax-content-block-controls-'+id).remove();
                resultsRegion.append(results);

                var currentlyDisplayedCount = $('.ajax-block-item-result-'+id).length;
                ajaxBlock.data('pagenb', data.ajaxData.page + 1);

                if ( currentlyDisplayedCount >= data.ajaxData.totalCount){
                    $('.'+id).show();
                    ajaxStatusReports.data('no-more', 'true');
                    return;
                }
                ajaxStatusReports.removeClass('loading');

                if (loadOn) {
                    loadOn.show();
                }
            }
        });
    }
    /* /RUN AJAX ON GRIDS ----------------------------------------------------------*/
    /* AJAX SEARCH LOAD MORE ON SCROLL---------------------------------------------*/
    $('.load-on-scroll').in_viewport(function(){
        var blockId = $(this).data('block-id');
        runAjaxOnGrids(blockId);
    });
    /* /AJAX SEARCH LOAD MORE ON SCROLL--------------------------------------------*/

    /* AJAX SEARCH LOAD MORE ON BUTTON---------------------------------------------*/
    $(document).on('click', '.load-on-button', function(){
        var blockId = $(this).data('block-id');
        runAjaxOnGrids(blockId);
    });
    /* /AJAX SEARCH LOAD MORE ON BUTTON---------------------------------------------*/

    /* AJAX SEARCH LOAD MORE WITH PAGINATION----------------------------------------*/
    $(document).on('click', '.load-on-paginate li', function(event){
        event.preventDefault();
        var selectedPage = $(this);
        var ajaxUrl = selectedPage.find('a:first').attr('href');
        var pagerDiv = selectedPage.closest('div .load-on-paginate');
        var blockId = pagerDiv.data('block-id');

        var loadingText = $('.ajax-content-block-'+blockId).data("loading-text")

        $.ajax(ajaxUrl, {
            type: 'get',
            dataType: 'html',
            beforeSend: function()
            {
                $.blockUI({message: '<h3 class="loading-spinner">'+ loadingText +'</h3>'});
            },
            success: function(results)
            {
                var resultsRegion = $('.results-region-'+blockId);
                //pagerDiv.html(results.pagerHtml);
                $.unblockUI();
                resultsRegion.html(results);
                resultsRegion.addClass('displayed-'+blockId);
                resultsRegion.removeClass('hidden');
            }
        });

    });
    /* /AJAX SEARCH LOAD MORE WITH PAGINATION---------------------------------------*/
});
