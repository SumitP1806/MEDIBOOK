document.addEventListener('submit', function (event) {
  const form = event.target;
  if (form.matches('[data-confirm]') && !window.confirm(form.dataset.confirm)) {
    event.preventDefault();
  }
});
document.querySelectorAll('input[type="date"]').forEach((input) => {
  if (input.min) input.setAttribute('min', input.min);
});
