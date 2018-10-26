'use strict';

$(document).ready(function () {
  var location = window.location.href;
  var cur_url = location.split('/').pop().split('.').shift();

  var seconds = $('.seconds');
  var minutes = $('.minutes');
  var secondsValue = 59;
  var minutesValue = 2;
  
  if (cur_url === 'code-sms') {
    seconds.css('width', '20');
    
    // начать повторы с интервалом 1 сек
    var secondsInterval = setInterval(function() {
      seconds.text(secondsValue);
      secondsValue -= 1;
      
      if (secondsValue < 10) {
        seconds.text('0' + secondsValue);
      }
      
    }, 1000);

    // через 60 сек остановить повторы
    setTimeout(function() {
      clearInterval(secondsInterval);
      $('#code-sms-link').attr('href', '#');
      $('#code-sms-link').removeClass('link_disabled');
    }, 59000);
  };

  $('#application-modal').click(function () {
    $('#application-modal').modal('show');
  })
  
  $('#application-modal').on('show.bs.modal', function (e) {
    minutes.css('width', '20');
    seconds.css('width', '35');

    // начать повторы с интервалом 1 сек
    var minutesInterval = setInterval(function() {

      seconds.text(secondsValue);
      minutes.text(minutesValue);

      secondsValue -= 1;
      minutesValue;

      if (secondsValue < 10 && secondsValue !== 0) {
        seconds.text('0' + secondsValue);
      } else if (secondsValue === 0) {
          seconds.text('0' + secondsValue);
          secondsValue = 60;
          minutesValue -= 1;
        } else {
          seconds.text(secondsValue);
        }

      if (minutesValue === 0) {
        minutesValue = 0;
      }

    }, 1000);

    // через 3 мин остановить повторы
    setTimeout(function() {
      clearInterval(minutesInterval);
    }, 179000);

    $('#application-modal').on('hide.bs.modal', function (e) {
      clearInterval(minutesInterval);
      secondsValue = 59;
      minutesValue = 2;
      seconds.text(secondsValue);
      minutes.text(minutesValue);
    })

  })

})
