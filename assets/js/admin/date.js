import flatpickr from 'flatpickr';

const date = document.querySelector('.js-date-filter');

if (date) {
  flatpickr(date, {
    mode: 'range',
    minDate: '2022-01-01',
    dateFormat: 'Y-m-d',
    wrap: true,

    onChange(selectedDates) {
      // eslint-disable-next-line no-empty
      if (!selectedDates || selectedDates.length < 1) return;

      const dateFrom = new Date(selectedDates[0]);
      dateFrom.setHours(0, 0, 0, 0);

      let dateTo = selectedDates[0];
      if (selectedDates.length > 1) {
        // eslint-disable-next-line prefer-destructuring
        dateTo = selectedDates[1];
      }
      dateTo = new Date(dateTo);
      dateTo.setHours(23, 59, 59, 0);

      const formattedDateFrom = dateFrom.toISOString().split('.')[0];
      const formattedDateTo = dateTo.toISOString().split('.')[0];

      document.getElementById('dateFromHidden').value = formattedDateFrom;
      document.getElementById('dateToHidden').value = formattedDateTo;
    },
  });
}
