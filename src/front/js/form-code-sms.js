/* global $, Form */

$(() => {
  const codeSmsForm = $('#js-formCodeSms');
  const smsCodeInput = $('#js-formCodeSmsInputCode');
  const submitButton = $('#js-formCodeSmsBtnSubmit');

  codeSmsForm.validate({
    highlight(element) {
      $(element).addClass('input_error');
    },
    unhighlight(element) {
      $(element).removeClass('input_error');
    },
    submitHandler(form) {
      const action = $(form).attr('action');
      const data = new FormData(form);

      if (!submitButton.is(':disabled')) {
        Form.send(action, data, submitButton);
      }
    },
    ignore: [],
    rules: {
      sms_code: {
        required: true,
        number: true,
        minlength: 4,
        normalizer(value) {
          return $.trim(value);
        },
      },
      terms: {
        required: true,
        plain_text: true,
      },
    },
    messages: {
      sms_code: {
        required: 'Это обязательное поле',
        number: 'Код должен содержать только цифры',
        minlength: 'Код должен содержать 4 цифры',
        pattern: 'Код должен содержать 4 цифры',
      },
      terms: {
        required: 'Ознакомьтесь с условиями',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
    },
    errorClass: 'error-text',
    errorElement: 'span',
  });

  smsCodeInput.inputmask('9999');

  codeSmsForm.on('keyup blur change', 'input, select, textarea', () => {
    Form.changeButtonStatus(codeSmsForm, submitButton);
  });
});
