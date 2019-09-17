/* global $, Form, Toaster, Cookies */

$(() => {
  const timer = $('#js-codeSmsTimer');
  const timerEnd = timer.data('timer-end');
  const repeatLink = $('#js-codeSmsRepeatSend');
  const repeatText = $('#js-codeSmsRepeatText');

  timer.countdown(timerEnd * 1000)
    .on('update.countdown', function onUpdate(event) {
      $(this).html(event.strftime('%M:%S'));
    })
    .on('finish.countdown', function onFinish(event) {
      $(this).html(event.strftime('00:00'));
      repeatLink.removeClass('link_disabled');
      repeatText.hide();
    });

  repeatLink.on('click', function onClick() {
    if ($(this).hasClass('link_disabled')) return;

    const link = $(this);

    $.ajax({
      cache: false,
      dataType: 'json',
      method: 'POST',
      url: link.data('link-action'),
      success(data) {
        repeatLink.addClass('link_disabled');
        repeatText.show();

        Toaster.create(data.message.body, data.message.type);

        const newTimerEnd = Date.now() / 1000 + 180;

        Cookies.set('confirm_timer', newTimerEnd);

        timer.countdown(newTimerEnd * 1000)
          .on('update.countdown', function onUpdate(event) {
            $(this).html(event.strftime('%M:%S'));
          })
          .on('finish.countdown', function onFinish(event) {
            $(this).html(event.strftime('00:00'));
            repeatLink.removeClass('link_disabled');
            repeatText.hide();
          });
      },
      error() {
        Toaster.create('Произошла ошибка, попробуйте ещё раз.', 'error');
      },
    });
  });
});
