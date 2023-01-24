/* global google */

export default class GoogleMap {
  constructor(element) {
    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
      console.group('Google Maps error');
      console.info('Global "google" or "google.maps" is not defined.');
      console.log({ google });
      console.groupEnd();

      return;
    }

    this.element = element;
    this.latitude = parseFloat(this.element.dataset.latitude);
    this.longitude = parseFloat(this.element.dataset.longitude);
    this.zoom = parseInt(this.element.dataset.zoom, 10);
    this.mapType = this.element.dataset.mapType;
    this.showMarker = this.element.dataset.showMarker;

    this.init();
  }

  init() {
    this.map = new google.maps.Map(this.element, {
      center: { lat: this.latitude, lng: this.longitude },
      zoom: this.zoom,
      mapTypeId: google.maps.MapTypeId[this.mapType],
      scrollwheel: true,
    });

    if (this.showMarker) {
      this.marker = new google.maps.Marker({
        position: { lat: this.latitude, lng: this.longitude },
        map: this.map,
        title: '',
      });
    }
  }
}
