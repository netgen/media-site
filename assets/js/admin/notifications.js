document.addEventListener('DOMContentLoaded', function () {
  const notificationContainer = document.getElementById('flashNotices');

  document.querySelectorAll('.btn-icon-close').forEach((el) =>
    el.addEventListener('click', (e) => {
      e.target.parentElement.style.display = 'none';
    })
  );

  if (notificationContainer) {
    setTimeout(function () {
      notificationContainer.style.display = 'none';
    }, 8000);
  }
});
