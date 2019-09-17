/* global $ */

$(() => {
  const modal = $('#js-modalSendRequest');

  if (modal.data('open')) {
    modal.modal({
      backdrop: 'static',
      keyboard: false,
    });
  }
});
