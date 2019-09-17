/* global $, Form */

$(() => {
  const profileShopSecondStepForm = $('#js-formProfileShopSecondStep');
  const submitButton = $('#js-formProfileShopSecondStepBtnSubmit');

  profileShopSecondStepForm.validate({
    highlight(element) {
      const customFileLabel = $(element).prev('.custom-file-label');

      if (customFileLabel.length) {
        customFileLabel.addClass('input_error');
      } else {
        $(element).addClass('input_error');
      }
    },
    unhighlight(element) {
      const customFileLabel = $(element).prev('.custom-file-label');

      if (customFileLabel.length) {
        customFileLabel.removeClass('input_error');
      } else {
        $(element).removeClass('input_error');
      }

      $(element).next('.error-text').hide();
    },
    submitHandler(form) {
      const action = $(form).attr('action');
      const data = new FormData(form);

      if (!submitButton.is(':disabled')) {
        Form.send(action, data, submitButton);
      }
    },
    rules: {
      'document_contract[]': {
        required() {
          return profileShopSecondStepForm.find('[name="document_contract[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
      'document_questionnaire_fl_115[]': {
        required() {
          return profileShopSecondStepForm.find('[name="document_questionnaire_fl_115[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
      'document_joining_application[]': {
        required() {
          return profileShopSecondStepForm.find('[name="document_joining_application[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
    },
    messages: {
      'document_contract[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
      'document_questionnaire_fl_115[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
      'document_joining_application[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
    },
    errorClass: 'error-text',
    errorElement: 'span',
  });
});
