'use strict';

$(document).ready(function() {

  // заменяет символы поля пароля на '*'
  $( "#sms" ).keyup(function() {
    var replacedValue = $(this).val().replace(/[\s\S]/g, "*");
    $(this).val(replacedValue);
  });

  var phoneNumber = $('#phone');
  // var date = $('#birth');
  // var passportNumber = $('#passport');
  var codeSms = $('#sms');
  var terms = $('#terms');
  var match = $('#match');
  var email = $('#email');

  var requiredWithOneAddress = ['last-name', 'name', 'patronymic', 'birth', 'passport', 'issyed-by', 'date-of-issue', 'record-index', 'record-city', 'record-building', 'record-street', 'record-apartment', 'email', 'patronymic', 'TIN', 'company-name', 'phone', 'sms'];

  var currentAddress = ['current-index', 'current-city', 'current-building', 'current-street', 'current-apartment']; 

  var requiredWithTwoAdresses = $.merge($.merge([], requiredWithOneAddress), currentAddress);
  var required = requiredWithOneAddress;

  var errorsTexts = 
  {
    birth: 'Введите дату в формате xx.xx.xxxx',
    passport: 'Введите номер паспорта в формате 01 02 343543',
    email: 'Введите e-mail в формате ivanov@gmail.com',
    phone: 'Введите номер телефона в формате +65765766700'
  };

  var setErrors = function(array) {
    $.each(array, function(index, val) {
      $('#valid-' + index).text(val);
      $('#' + index).addClass('input_error');
    })
  };

  var removeErrors = function() {
    $('.error-text').text('');
    $('input').removeClass('input_error');
  };

  var setError = function(fieldName) {
    $('#valid-' + fieldName).text(errorsTexts[fieldName]);
    $('#' + fieldName).addClass('input_error');
  }

  var setErrorForRequiredField = function(fieldName) {
    $('#valid-' + fieldName).text('Это обязательное поле');
    $('#' + fieldName).addClass('input_error');
  }

  var removeError = function(fieldName) {
    $('#valid-' + fieldName).text('');
    $('#' + fieldName).removeClass('input_error');
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

  $('#terms').click(function() {
    if ($(this).is(':checked')) {
      $('#valid-terms').text('');
    } else {
      $('#valid-terms').text('Пожалуйста, отметьте согласие на обработку персональных данных');
    }
  })

  $('#match').click(function() {

    if ($(this).is(':checked')) {

      $.each(currentAddress, function(index, val) {
        $('#' + val).attr('disabled', true);
        $('#' + val).removeClass('input_error');
        $('#valid-' + val).text('');

        $.each(currentAddress, function(index, val) {
          var addressValue = 'record-' + val.split('-').pop();
          $('#' + val).val($('#' + addressValue).val());
        })

      })
      required = requiredWithOneAddress;
    } else {
      $.each(currentAddress, function(index, val) {
        $('#' + val).attr('disabled', false);
        $('#' + val).val('');
      })
      required = requiredWithTwoAdresses;
    }

  });

  $('input').blur(function() {
    var value = $(this).val();
    var name = $(this)[0].name;

    if (value !== '') {

      if (name === 'birth') {
        validateDate(value) ? removeError(name) : setError(name);
      } else if (name === 'passport') {
          validatePassportNumber(value) ? removeError(name) : setError(name);
      } else if (name === 'email') {
          validateEmail(value) ? removeError(name) : setError(name);
      } else if (name === 'phone') {
          validatePhoneNumber(value) ? removeError(name) : setError(name);
      } else {
        removeError(name);

        if ($('#match').is(':checked')) {
          $.each(currentAddress, function(index, val) {
            var addressValue = 'record-' + val.split('-').pop();

            if (name === addressValue) {
              $('#' + val).val($('#' + addressValue).val());
            }
          })
        }

      }
    } else {
      $.each(required, function(index, val) {
        name == val ? setErrorForRequiredField(name) : '';
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
      removeError('sms');
      $('#valid-terms').text('');
    } else {

      value === '' ? setErrorForRequiredField('sms') : removeError('sms');

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
    removeErrors();

    $(this).find('input').each(function() {
      data[$(this)[0].name] = $(this).val();
    })

    $.each(required, function(index, val) {
      if (data[val] === '') {
        errors[val] = 'Это обязательное поле';
      }
    })

    if (data['passport'] !== '') {
      var value = data['passport'];
      validatePassportNumber(value) ? '' : errors['passport'] = errorTexts['passport'];
    }

    if (data['birth'] !== '') {
      var value = data['birth'];
      validateDate(value) ? '' : errors['birth'] = errorTexts['birth'];
    }

    if (data['email'] !== '') {
      var value = data['email'];
      validateEmail(value) ? '' : errors['email'] = 'Введите e-mail в формате ivanov@gmail.com';
    }

    if (!$.isEmptyObject(errors)) {
      setErrors(errors);
      event.preventDefault();
    } else {
      $('#application-modal').modal();
      event.preventDefault();
    }
    
  });

  $('.anketa-shop').submit(function(event) {
    var errors = {};
    var data = {};
    removeErrors();

    $(this).find('input').each(function() {
      data[$(this)[0].name] = $(this).val();
    })

    $.each(required, function(index, val) {
      if (data[val] === '') {
        errors[val] = 'Это обязательное поле';
      }
    })

    if (data['email'] !== '') {
      var value = data['email'];
      validateEmail(value) ? '' : errors['email'] = 'Введите e-mail в формате ivanov@gmail.com';
    }

    if (!$.isEmptyObject(errors)) {
      setErrors(errors);
      event.preventDefault();
    } else {
      // event.preventDefault();
    }
    
  });

});