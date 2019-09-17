/* global $, Form, Toaster, Cookies */

$(() => {
  const timer = $('#js-profileClientTimer');
  const timerEnable = timer.data('timer-enable');

  if (!timerEnable) return;

  const timerEnd = timer.data('timer-end');
  const progressBar = $('#js-profileClientProgressBar');
  const progressBarInner = progressBar.find('div');
  const progressBarEndAfter = progressBar.data('end-after');
  const modalSendRequest = $('#js-modalSendRequest');
  const modalNoResponse = $('#js-modalNoResponse');

  timer.countdown(timerEnd * 1000)
    .on('update.countdown', function onUpdate(event) {
      $(this).html(event.strftime('%M:%S'));

      progressBarInner.width((progressBarEndAfter - event.offset.totalSeconds) * progressBar.width()
        / progressBarEndAfter);
    })
    .on('finish.countdown', function onFinish(event) {
      $(this).html(event.strftime('00:00'));
      $(this).parent().addClass('timer_flash');

      progressBarInner.width('100%');

      modalSendRequest.modal('hide');

      modalNoResponse.modal({
        backdrop: 'static',
        keyboard: false,
      });
    });
});
