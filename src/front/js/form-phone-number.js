/* global $, Form */

$(() => {
  const phoneNumberForm = $('#js-formPhoneNumber');
  const phoneInput = $('#js-formPhoneNumberInputPhone');
  const submitButton = $('#js-formPhoneNumberBtnSubmit');

  phoneInput.inputmask('+7(999)999-99-99');

  phoneNumberForm.validate({
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
    rules: {
      phone: {
        required: true,
        phone_ru: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
    },
    messages: {
      phone: {
        required: 'Это обязательное поле',
        phone_ru: 'Введите номер в формате +7(xxx)xxx-xx-xx',
      },
    },
    errorClass: 'error-text',
    errorElement: 'span',
  });

  phoneNumberForm.on('keyup blur change', 'input, select, textarea', () => {
    Form.changeButtonStatus(phoneNumberForm, submitButton);
  });
});
