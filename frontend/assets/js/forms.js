'use strict';

$(document).ready(function() {

  $( "#sms" ).keyup(function() {
    var replacedValue = $(this).val().replace(/[\s\S]/g, "*");
    $(this).val(replacedValue);
  });

  var phoneNumber = $('#phone');
  var date = $('#birth');
  var passportNumber = $('#passport');
  var codeSms = $('#sms');
  var terms = $('#terms');
  var match = $('#match');
  var email = $('#email');

  var requiredWithOneAddress = ['last-name', 'name', 'patronymic', 'birth', 'passport', 'issyed-by', 'date-of-issue', 'record-index', 'record-city', 'record-building', 'record-street', 'record-apartment', 'email'];

  var currentAddress = ['current-index', 'current-city', 'current-building', 'current-street', 'current-apartment']; 

  var requiredWithTwoAdresses = $.merge($.merge([], requiredWithOneAddress), currentAddress);
  var required = requiredWithOneAddress;

  var setError = function(array) {
    $.each(array, function(index, val) {
      $('#valid-' + index).text(val);
      $('#' + index).addClass('input_error');
    })
  };

  var removeError = function() {
    $('.error-text').text('');
    $('input').removeClass('input_error');
  };

  var validateDate = function (value) {
    var array = value.split('.');
    var number1;
    var number2;
    var number3;

    array[0] ? number1 = array[0].split('').length : number1 = '';
    array[1] ? number2 = array[1].split('').length : number2 = '';
    array[2] ? number3 = array[2].split('').length : number3 = '';

    if (array.length === 3 && 
        array[0].search('[0-9]{2}') === 0 && 
        array[1].search('[0-9]{2}') === 0 && 
        array[2].search('[0-9]{4}') === 0 &&
        number1 === 2 && number2 === 2 && number3 === 4) {
      return true;
    } else {
      return false;
    }
  };

  var validatePassportNumber = function (value) {
    var array = value.split(' ');
    var number1;
    var number2;
    var number3;

    array[0] ? number1 = array[0].split('').length : number1 = '';
    array[1] ? number2 = array[1].split('').length : number2 = '';
    array[2] ? number3 = array[2].split('').length : number3 = '';

    if (array.length === 3 &&
        array[0].search('[0-9]{2}') === 0 &&
        array[1].search('[0-9]{2}') === 0 && 
        array[2].search('[0-9]{6}') === 0 &&
        number1 === 2 && number2 === 2 && number3 === 6) {
      return true;
    } else {
      return false;
    }
  };

  var validatePhoneNumber = function (value) {
    var array = value.split('+');

    if (array.length === 2 &&
        array[1].search('[0-9]{11}') === 0) {
      return true;
    } else {
      return false;
    }
  };

  var validateEmail = function (value) {
    var emailPattern = /^[a-z0-9_-]+@[a-z0-9-]+\.[a-z]{2,6}$/i;

    if (value.search(emailPattern) === 0) {
      return true;
    } else {
      return false;
    }
  };

  match.click(function() {

    if (match.is(':checked')) {
      $.each(currentAddress, function(index, val) {
        $('#' + val).attr('disabled', true);
        $('#' + val).removeClass('input_error');
        $('#valid-' + val).text('');
        // автоматическое заполнение current address
        // var value = $('#record-' + val.split('-').pop()).val();
        // console.log(value);
        // $('#current-' + val.split('-').pop()).val(value);
      })
      required = requiredWithOneAddress;
    } else {
      $.each(currentAddress, function(index, val) {
        $('#' + val).attr('disabled', false);
      })
      required = requiredWithTwoAdresses;
    }

  });

  $('input').blur(function() {
    var value = $(this).val();
    var name = $(this)[0].name;

    if (value !== '') {

      if (name === 'birth') {

        if(validateDate(value)) {
          $('#valid-birth').text('');
          date.removeClass('input_error');
          // $('#application').attr('disabled', false);
        } else {
          $('#valid-birth').text('Введите дату в формате xx.xx.xxxx');
          date.addClass('input_error');
          // $('#application').attr('disabled', true);
        }

      } else if (name === 'passport') {

        if (validatePassportNumber(value)) {
          $('#valid-passport').text('');
          passportNumber.removeClass('input_error');
        } else {
          $('#valid-passport').text('Введите номер паспорта в формате 01 02 343543');
          passportNumber.addClass('input_error');
        }

      } else if (name === 'email') {

        if (validateEmail(value)) {
          $('#valid-email').text('');
          email.removeClass('input_error');
        } else {
          $('#valid-email').text('Введите e-mail в формате ivanov@gmail.com');
          email.addClass('input_error');
        }

      } else if (name === 'phone') {
          console.log(name);
          if (validatePhoneNumber(value)) {
          $('#valid-phone').text('');
          phoneNumber.removeClass('input_error');
        } else {
          $('#valid-phone').text('Введите номер телефона в формате +65765766700');
          phoneNumber.addClass('input_error');
        }

      } else {
        
        $('#valid-' + name).text('');
        $('#' + name).removeClass('input_error');
      }
      
    } else {
      $.each(required, function(index, val) {
        if (name == val) {
          $('#valid-' + name).text('Это обязательное поле');
          $('#' + name).addClass('input_error');
        }
      })
    }
    
  });

  $('.phone-number').submit(function(event) {
    var value = phoneNumber.val();

    if (value !== '') {

      if(validatePhoneNumber(value)) {
        
      } else {
        event.preventDefault();
      }
    } else {
      $('#valid-phone').text('Это обязательное поле');
      phoneNumber.addClass('input_error');
      event.preventDefault();
    }
  });

  $('.code-sms__form').submit(function(event) {
    var value = codeSms.val();

    if (value !== '' && terms.is(':checked')) {
      $('#valid-sms').text('');
      codeSms.removeClass('input_error');
      $('#valid-terms').text('');
    } else {

      if (value === '') {
        $('#valid-sms').text('Это обязательное поле');
        codeSms.addClass('input_error');
      } else {
        $('#valid-sms').text('');
        codeSms.removeClass('input_error');
      }

      if (!terms.is(':checked')) {
        $('#valid-terms').text('Пожалуйста, отметьте согласие на обработку персональных данных');
      } else {
        $('#valid-terms').text('');
      }

      event.preventDefault();
    }
  });

  $('.anketa-application').submit(function(event) {
    var errors = {};
    var data = {};
    removeError();

    // var data = $('.anketa-application').serializeArray();

    $('.anketa-application').find('input').each(function() {
      data[$(this)[0].name] = $(this).val();
    })

    $.each(required, function(index, val) {
      if (data[val] === '') {
        errors[val] = 'Это обязательное поле';
      }
    })

    if (data['passport'] !== '') {
      var value = data['passport'];
      validatePassportNumber(value) ? '' : errors['passport'] = 'Введите номер паспорта в формате 01 02 343543';
    }

    if (data['birth'] !== '') {
      var value = data['birth'];
      validateDate(value) ? '' : errors['birth'] = 'Введите дату в формате xx.xx.xxxx';
    }

    if (data['email'] !== '') {
      var value = data['email'];
      validateEmail(value) ? '' : errors['email'] = 'Введите e-mail в формате ivanov@gmail.com';
    }

    if (!$.isEmptyObject(errors)) {
      setError(errors);
      event.preventDefault();
    } else {
      $('#application-modal').modal();
      event.preventDefault();
    }
    
  });

});