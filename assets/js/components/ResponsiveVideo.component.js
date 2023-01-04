export default class VideoHandler {
    constructor(element) {
        this.element = element;
        this.onInit();
    }

    onInit() {

        //add source to video tag
        function addSourceToVideo(element, src) {
            var source = document.createElement('source');
            source.src = src;
            source.type = 'video/mp4';
            element.appendChild(source);
        }

        //determine screen size and init mobile or desktop video
        function whichSizeVideo(element, src) {
            var windowWidth = window.innerWidth ? window.innerWidth : $(window).width();
            if (windowWidth >= 768) {
                if(src.hasAttribute('data-desktop-vid'))
                    addSourceToVideo( element, src.dataset.desktopVid);
            } else {
                if(src.hasAttribute('data-mobile-vid'))
                    addSourceToVideo(element, src.dataset.mobileVid);
            }
        }

        //init only if page has videos
        function videoSize() {
            //get all videos
            var video = document.querySelectorAll('video.js-responsive-video')
            if (video !== undefined) {
            video.forEach(function(element, index) {
                whichSizeVideo(
                    element, //element
                    element  //src locations
                );
            });
            }
        }
        videoSize();

    }
}
