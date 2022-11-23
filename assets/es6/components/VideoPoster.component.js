export default class VideoPoster {
    constructor(element, options) {
        this.el = element;
        this.options = options;
        this.videoId = element.dataset.id;
        this.thumbname = element.dataset.thumbname;
        this.service = false;
        this.url = false;
        this.getUrl = false;

        this.onInit();
    }

    async onInit() {
        this.setService();
        this.setUrls();
        await this.fetchPoster();
        this.setPosterLinks();
    }

    setService() {
        if(this.el.classList.contains(this.options.vimeoClass)) {
            this.service = 'vimeo'
        } else if(this.el.classList.contains(this.options.dailymotionClass)) {
            this.service = 'dailymotion'
        }
    }

    setUrls() {
        if (this.service === 'dailymotion') {
            this.url = `https://api.dailymotion.com/video/${this.videoId}?fields=${this.thumbname}`;
            this.getUrl = (obj) => obj[this.thumbname];
        } else if (this.service === 'vimeo') {
            this.url = `https://vimeo.com/api/v2/video/${this.videoId}.json`;
            this.getUrl = (obj) => obj[0][this.thumbname];
        }
    }

    async fetchPoster() {
        const self = this;

        return await fetch(self.url, {
                method: 'GET'
            })
            .then(res => res.json())
            .then((data) => {
                self.el.setAttribute('src', self.getUrl(data));
            })
            .catch((err) => {
                console.log('Error: ', err)
            })
    }

    setPosterLinks() {
        const link = this.el.closest(this.options.posterLinkElement);
        if(!link) return;

        link.setAttribute('href', this.el.src)
    }
}
