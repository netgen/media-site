import flatpickr from 'flatpickr';

export default class Date {
  constructor(element) {
    this.element = element;

    this.init();
  }

  init() {
    flatpickr(this.element, {
      mode: 'range',
      minDate: '2022-01-01',
      dateFormat: 'Y-m-d',
      wrap: true,

      onChange(selectedDates) {
        if (!selectedDates || selectedDates.length < 1) {
          return;
        }

        const dateFrom = selectedDates[0];
        dateFrom.setHours(0, 0, 0, 0);

        let dateTo = selectedDates[0];
        if (selectedDates.length > 1) {
          // eslint-disable-next-line prefer-destructuring
          dateTo = selectedDates[1];
        }

        dateTo.setHours(23, 59, 59, 0);

        const formattedDateFrom = dateFrom.toISOString().split('.')[0];
        const formattedDateTo = dateTo.toISOString().split('.')[0];

        document.getElementById('dateFromHidden').value = formattedDateFrom;
        document.getElementById('dateToHidden').value = formattedDateTo;
      },
    });
  }
}
