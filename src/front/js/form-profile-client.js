/* global $, Form, Dadata */

$(() => {
  const profileClientForm = $('#js-formProfileClient');

  const birthDateInput = $('#js-formProfileClientInputBirthDate');
  const isLastNameChangedInput = $('#js-formProfileClientInputIsLastNameChanged');
  const snilsInput = $('#js-formProfileClientInputSnils');
  const tinInput = $('#js-formProfileClientInputTin');
  const passportNumberInput = $('#js-formProfileClientInputPassportNumber');
  const passportDivisionCodeInput = $('#js-formProfileClientInputPassportDivisionCode');
  const passportIssuedByInput = $('#js-formProfileClientInputPassportIssuedBy');
  const passportIssuedDateInput = $('#js-formProfileClientInputPassportIssuedDate');
  const salaryInput = $('#js-formProfileClientInputSalary');
  const regZipCodeInput = $('#js-formProfileClientInputRegZipCode');
  const regCityInput = $('#js-formProfileClientInputRegCity');
  const regBuildingInput = $('#js-formProfileClientInputRegBuilding');
  const regStreetInput = $('#js-formProfileClientInputRegStreet');
  const regApartmentInput = $('#js-formProfileClientInputRegApartment');
  const isAddressMatchedInput = $('#js-formProfileClientInputIsAddressMatched');
  const factZipCodeInput = $('#js-formProfileClientInputFactZipCode');
  const factCityInput = $('#js-formProfileClientInputFactCity');
  const factBuildingInput = $('#js-formProfileClientInputFactBuilding');
  const factStreetInput = $('#js-formProfileClientInputFactStreet');
  const factApartmentInput = $('#js-formProfileClientInputFactApartment');
  const phoneInput = $('#js-formProfileClientInputPhone');
  const additionalPhoneInput = $('#js-formProfileClientInputAdditionalPhone');

  const previousLastNameWrapper = $('#js-formProfileClientWrapperPreviousLastName');

  const submitButton = $('#js-formProfileClientBtnSubmit');

  /**
   * Adds the error.
   *
   * @param {object} element - The jQuery element.
   *
   * @return {void}
   */
  function addError(element) {
    const customFileLabel = element.prev('.custom-file-label');

    if (customFileLabel.length) {
      customFileLabel.addClass('input_error');
    } else {
      element.addClass('input_error');
    }
  }

  /**
   * Removes the error.
   *
   * @param {object} element - The jQuery element.
   *
   * @return {void}
   */
  function removeError(element) {
    const customFileLabel = element.prev('.custom-file-label');

    if (customFileLabel.length) {
      customFileLabel.removeClass('input_error');
    } else {
      element.removeClass('input_error');
    }

    element.next('.error-text').hide();
  }

  /**
   * Sets the fact zip code.
   *
   * @return {void}
   */
  function setFactZipCodeInput() {
    if (isAddressMatchedInput.is(':checked')) {
      factZipCodeInput.val(regZipCodeInput.val());
    }
  }

  /**
   * Sets the fact city.
   *
   * @return {void}
   */
  function setFactCityInput() {
    if (isAddressMatchedInput.is(':checked')) {
      factCityInput.val(regCityInput.val());
    }
  }

  /**
   * Sets the fact building.
   *
   * @return {void}
   */
  function setFactBuildingInput() {
    if (isAddressMatchedInput.is(':checked')) {
      factBuildingInput.val(regBuildingInput.val());
    }
  }

  /**
   * Sets the fact street.
   *
   * @return {void}
   */
  function setFactStreetInput() {
    if (isAddressMatchedInput.is(':checked')) {
      factStreetInput.val(regStreetInput.val());
    }
  }

  /**
   * Sets the fact apartment.
   *
   * @return {void}
   */
  function setFactApartmentInput() {
    if (isAddressMatchedInput.is(':checked')) {
      factApartmentInput.val(regApartmentInput.val());
    }
  }

  /**
   * Sets the passport issued by input value.
   *
   * @param {string} value The value.
   *
   * @return {void}
   */
  function setPassportIssuedBy(value) {
    passportIssuedByInput.val(value);

    removeError(passportIssuedByInput);
  }

  setFactZipCodeInput();

  regZipCodeInput.on('change', () => {
    setFactZipCodeInput();
  });

  setFactCityInput();

  regCityInput.on('change', () => {
    setFactCityInput();
  });

  setFactBuildingInput();

  regBuildingInput.on('change', () => {
    setFactBuildingInput();
  });

  setFactStreetInput();

  regStreetInput.on('change', () => {
    setFactStreetInput();
  });

  setFactApartmentInput();

  regApartmentInput.on('change', () => {
    setFactApartmentInput();
  });

  birthDateInput.inputmask('99.99.9999');
  tinInput.inputmask('999999999999');
  snilsInput.inputmask('999-999-999 99');
  passportNumberInput.inputmask('99 99 999999');
  passportDivisionCodeInput.inputmask('999-999');
  passportIssuedDateInput.inputmask({ regex: '^(0[1-9]|[12]\\d|3[01])\\.((0[1-9]|1[0-2])\\.[12]\\d{3})$' });
  salaryInput.inputmask('9{1,6}');
  regZipCodeInput.inputmask('999999');
  factZipCodeInput.inputmask('999999');
  phoneInput.inputmask('+7(999)999-99-99');
  additionalPhoneInput.inputmask('+7(999)999-99-99');

  profileClientForm.validate({
    highlight(element) {
      addError($(element));
    },
    unhighlight(element) {
      removeError($(element));
    },
    submitHandler(form) {
      const action = $(form).attr('action');
      const data = new FormData(form);

      if (!submitButton.is(':disabled')) {
        Form.send(action, data, submitButton);
      }
    },
    rules: {
      last_name: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      first_name: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      middle_name: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      birth_date: {
        required: true,
        date_ru: true,
        full_age: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      birth_place: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      sex: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      previous_last_name: {
        required() {
          return isLastNameChangedInput.is(':checked');
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      tin: {
        required: true,
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      snils: {
        required: true,
        snils: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      passport_number: {
        required: true,
        passport: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      passport_division_code: {
        required: true,
        division_code: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      passport_issued_by: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      passport_issued_date: {
        required: true,
        date_ru: true,
        passport_issue_date: true,
        document_issue_date: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      workplace: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      salary: {
        required: true,
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      reg_zip_code: {
        required: true,
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      reg_city: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      reg_street: {
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      reg_building: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      reg_apartment: {
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      fact_zip_code: {
        required() {
          return !isAddressMatchedInput.is(':checked');
        },
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      fact_city: {
        required() {
          return !isAddressMatchedInput.is(':checked');
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      fact_street: {
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      fact_building: {
        required() {
          return !isAddressMatchedInput.is(':checked');
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      fact_apartment: {
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      email: {
        required: true,
        email: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      phone: {
        required: true,
        phone_ru: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      additional_phone: {
        required: false,
        phone_ru: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      photo_passport_main_spread: {
        accept: 'image/png,image/jpg,image/jpeg',
        maxsize: 10485760,
      },
      photo_client_face_with_passport_main_spread: {
        accept: 'image/png,image/jpg,image/jpeg',
        maxsize: 10485760,
      },
    },
    messages: {
      last_name: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      first_name: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      middle_name: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      birth_date: {
        required: 'Это обязательное поле',
      },
      birth_place: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      sex: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      previous_last_name: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      tin: {
        required: 'Это обязательное поле',
        number: 'Номер ИНН должен содержать только цифры',
        pattern: 'Номер ИНН должен содержать только цифры',
      },
      snils: {
        required: 'Это обязательное поле',
        snils: 'Введите данные в формате 116-973-386 85',
      },
      passport_number: {
        required: 'Это обязательное поле',
        passport: 'Введите данные в формате 01 02 343543',
      },
      passport_division_code: {
        required: 'Это обязательное поле',
        division_code: 'Введите данные в формате 770-001',
      },
      passport_issued_by: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      passport_issued_date: {
        required: 'Это обязательное поле',
      },
      workplace: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      salary: {
        required: 'Это обязательное поле',
        number: 'Поле должно содержать только цифры',
        pattern: 'Поле должно содержать только цифры',
      },
      reg_zip_code: {
        required: 'Это обязательное поле',
        number: 'Индекс должен содержать только цифры',
        pattern: 'Индекс должен содержать только цифры',
      },
      reg_city: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      reg_street: {
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      reg_building: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      reg_apartment: {
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      fact_zip_code: {
        required: 'Это обязательное поле',
        number: 'Индекс должен содержать только цифры',
        pattern: 'Индекс должен содержать только цифры',
      },
      fact_city: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      fact_street: {
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      fact_building: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      fact_apartment: {
        plain_text: 'Поле не должно содержать HTML-теги',
      },
      email: {
        required: 'Это обязательное поле',
        email: 'Введите e-mail в формате ivanov@gmail.com',
      },
      phone: {
        required: 'Это обязательное поле',
        phone_ru: 'Введите номер в формате +7(xxx)xxx-xx-xx',
      },
      photo_passport_main_spread: {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PNG или JPEG',
        maxsize: 'Размер одного документа максимум 10 МБ',
      },
      photo_client_face_with_passport_main_spread: {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PNG или JPEG',
        maxsize: 'Размер одного документа максимум 10 МБ',
      },
    },
    errorClass: 'error-text',
    errorElement: 'span',
  });

  profileClientForm.find('input, select, textarea').each(function getFromLocalStorage() {
    try {
      if (this.type === 'checkbox'
        && localStorage.getItem(`form_profile_client__${this.name}`) === 'on'
      ) {
        $(this).prop('checked', true);
      } else if (this.value === '') {
        this.value = localStorage.getItem(`form_profile_client__${this.name}`);
      }
    } catch (error) {
      console.log(error);
    }
  });

  profileClientForm.find('input, select, textarea').change(function saveToLocalStorage() {
    try {
      if (this.type === 'checkbox' && $(this).is(':checked')) {
        localStorage.setItem(`form_profile_client__${this.name}`, 'on');
      } else if (this.type === 'checkbox') {
        localStorage.setItem(`form_profile_client__${this.name}`, 'off');
      } else {
        localStorage.setItem(`form_profile_client__${this.name}`, this.value);
      }
    } catch (error) {
      console.log(error);
    }
  });

  if (isLastNameChangedInput.is(':checked')) {
    previousLastNameWrapper.removeClass('hide');
  }

  isLastNameChangedInput.on('change', function onChange() {
    if ($(this).is(':checked')) {
      previousLastNameWrapper.removeClass('hide');
    } else {
      previousLastNameWrapper.addClass('hide');
      $('#js-formProfileClientInputPreviousLastName').val('');
      localStorage.removeItem('form_profile_client__previous_last_name');
    }
  });

  isAddressMatchedInput.on('click', function onClick() {
    if ($(this).is(':checked')) {
      factZipCodeInput
        .removeClass('input_error')
        .removeAttr('aria-describedby')
        .attr('readonly', true)
        .val(regZipCodeInput.val())
        .next('.error-text')
        .remove();
      factCityInput
        .removeClass('input_error')
        .removeAttr('aria-describedby')
        .attr('readonly', true)
        .val(regCityInput.val())
        .next('.error-text')
        .remove();
      factBuildingInput
        .removeClass('input_error')
        .removeAttr('aria-describedby')
        .attr('readonly', true)
        .val(regBuildingInput.val())
        .next('.error-text')
        .remove();
      factStreetInput
        .removeClass('input_error')
        .removeAttr('aria-describedby')
        .attr('readonly', true)
        .val(regStreetInput.val())
        .next('.error-text')
        .remove();
      factApartmentInput
        .removeClass('input_error')
        .removeAttr('aria-describedby')
        .attr('readonly', true)
        .val(regApartmentInput.val())
        .next('.error-text')
        .remove();
    } else {
      factZipCodeInput
        .attr('readonly', false)
        .val('');
      factCityInput
        .attr('readonly', false)
        .val('');
      factBuildingInput
        .attr('readonly', false)
        .val('');
      factStreetInput
        .attr('readonly', false)
        .val('');
      factApartmentInput
        .attr('readonly', false)
        .val('');
    }
  });

  passportDivisionCodeInput.on('change', function onChange() {
    const value = $(this).val();

    if (value !== '') {
      Dadata.getDivisionName(value, (result) => {
        if (typeof result.suggestions !== 'undefined' && result.suggestions.length) {
          setPassportIssuedBy(result.suggestions[result.suggestions.length - 1].value);
        }
      });
    }
  });
});
