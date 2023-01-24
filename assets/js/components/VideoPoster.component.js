export default class VideoPoster {
  constructor(element, options) {
    this.element = element;
    this.options = options;
    this.videoId = element.dataset.id;
    this.thumbname = element.dataset.thumbname;
    this.service = false;
    this.url = false;
    this.getUrl = false;

    this.init();
  }

  async init() {
    this.setService();
    this.setUrls();
    await this.fetchPoster();
    this.setPosterLinks();
  }

  setService() {
    if (this.element.classList.contains(this.options.vimeoClass)) {
      this.service = 'vimeo';
    } else if (this.element.classList.contains(this.options.dailymotionClass)) {
      this.service = 'dailymotion';
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
    await fetch(this.url)
      .then((res) => res.json())
      .then((data) => {
        this.element.src = this.getUrl(data);
      })
      .catch(console.error);
  }

  setPosterLinks() {
    const link = this.element.closest(this.options.posterLinkElement);
    if (link === null) {
      return;
    }

    link.href = this.element.src;
  }
}
