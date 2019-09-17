/* global $, Form, Dadata */

$(() => {
  const profileShopForm = $('#js-formProfileShop');

  const legalNameInput = $('#js-formProfileShopInputLegalName');
  const tinInput = $('#js-formProfileShopInputTin');
  const cioInput = $('#js-formProfileShopInputCio');
  const binInput = $('#js-formProfileShopInputBin');
  const bikInput = $('#js-formProfileShopInputBik');
  const bankNameInput = $('#js-formProfileShopInputBankName');
  const correspondentAccountInput = $('#js-formProfileShopInputCorrespondentAccount');
  const settlementAccountInput = $('#js-formProfileShopInputSettlementAccount');
  const isLicensedActivityInput = $('#js-formProfileShopInputIsLicensedActivity');
  const registrationAddressInput = $('#js-formProfileShopInputRegistrationAddress');
  const factAddressInput = $('#js-formProfileShopInputFactAddress');
  const legalAddressInput = $('#js-formProfileShopInputLegalAddress');
  const bossBasisActsIssuedDateInput = $('#js-formProfileShopInputBossBasisActsIssuedDate');
  const bossPassportNumberInput = $('#js-formProfileShopInputBossPassportNumber');
  const bossPassportIssuedDateInput = $('#js-formProfileShopInputBossPassportIssuedDate');
  const bossPassportDivisionCodeInput = $('#js-formProfileShopInputBossPassportDivisionCode');
  const bossPassportIssuedByInput = $('#js-formProfileShopInputBossPassportIssuedBy');
  const bossBirthDateInput = $('#js-formProfileShopInputBossBirthDate');
  const bossBirthPlaceInput = $('#js-formProfileShopInputBossBirthPlace');
  const bossBasisActsInput = $('#js-formProfileShopInputBossBasisActs');
  const phoneInput = $('#js-formProfileShopInputPhone');

  const legalNameWrapper = $('#js-formProfileShopWrapperLegalName');
  const cioWrapper = $('#js-formProfileShopWrapperCio');
  const licenseTypeWrapper = $('#js-formProfileShopWrapperLicenseType');
  const licenseNumberWrapper = $('#js-formProfileShopWrapperLicenseNumber');
  const legalAddressWrapper = $('#js-formProfileShopWrapperLegalAddress');
  const registrationAddressWrapper = $('#js-formProfileShopWrapperRegistrationAddress');
  const bossPositionWrapper = $('#js-formProfileShopWrapperBossPosition');
  const bossBasisActsWrapper = $('#js-formProfileShopWrapperBossBasisActs');
  const bossBasisActsNumberWrapper = $('#js-formProfileShopWrapperBossBasisActsNumber');
  const bossBasisActsIssuedDateWrapper = $('#js-formProfileShopWrapperBossBasisActsIssuedDate');
  const documentSnilsWrapper = $('#js-formProfileShopWrapperDocumentSnils');
  const documentPassportWrapper = $('#js-formProfileShopWrapperDocumentPassport');
  const documentStatuteWithTaxMarkWrapper = $('#js-formProfileShopWrapperDocumentStatuteWithTaxMark');
  const documentParticipantsDecisionWrapper = $('#js-formProfileShopWrapperDocumentParticipantsDecision');
  const documentOrderOnAppointmentWrapper = $('#js-formProfileShopWrapperDocumentOrderOnAppointment');
  const documentStatuteOfCurrentEditionWrapper = $('#js-formProfileShopWrapperDocumentStatuteOfCurrentEdition');

  const submitButton = $('#js-formProfileShopBtnSubmit');

  tinInput.inputmask('9{12}');
  cioInput.inputmask('9{9}');
  binInput.inputmask('9{15}');
  bikInput.inputmask('9{9}');
  correspondentAccountInput.inputmask('9{20}');
  settlementAccountInput.inputmask('9{20}');
  bossPassportNumberInput.inputmask('99 99 999999');
  bossPassportDivisionCodeInput.inputmask('999-999');
  phoneInput.inputmask('+7(999)999-99-99');
  bossBasisActsIssuedDateInput.inputmask({
    regex: '^(0[1-9]|[12]\\d|3[01])\\.((0[1-9]|1[0-2])\\.[12]\\d{3})$',
  });
  bossPassportIssuedDateInput.inputmask({
    regex: '^(0[1-9]|[12]\\d|3[01])\\.((0[1-9]|1[0-2])\\.[12]\\d{3})$',
  });
  bossBirthDateInput.inputmask({
    regex: '^(0[1-9]|[12]\\d|3[01])\\.((0[1-9]|1[0-2])\\.[12]\\d{3})$',
  });

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
   * Sets the registration address.
   *
   * @param {string} value The value.
   *
   * @return {void}
   */
  function setRegistrationAddress(value) {
    registrationAddressInput.val(value);
  }

  /**
   * Sets the fact address.
   *
   * @param {string} value The value.
   *
   * @return {void}
   */
  function setFactAddress(value) {
    factAddressInput.val(value);
  }

  /**
   * Sets the legal address.
   *
   * @param {string} value The value.
   *
   * @return {void}
   */
  function setLegalAddress(value) {
    legalAddressInput.val(value);
  }

  /**
   * Sets the boss birth place.
   *
   * @param {string} value The value.
   *
   * @return {void}
   */
  function setBossBirthPlace(value) {
    bossBirthPlaceInput.val(value);
  }

  /**
   * Sets the bank name.
   *
   * @param {string} value The value.
   *
   * @return {void}
   */
  function setBankName(value) {
    bankNameInput.val(value);

    removeError(bankNameInput);
  }

  /**
   * Sets the correspondent account input value.
   *
   * @param {string} value The value.
   *
   * @return {void}
   */
  function setCorrespondentAccount(value) {
    correspondentAccountInput.val(value);

    removeError(correspondentAccountInput);
  }

  /**
   * Sets the boss passport issued by input value.
   *
   * @param {string} value The value.
   *
   * @return {void}
   */
  function setBossPassportIssuedBy(value) {
    bossPassportIssuedByInput.val(value);

    removeError(bossPassportIssuedByInput);
  }

  /**
   * Shows license elements.
   *
   * @return {void}
   */
  function showLicenseElements() {
    licenseTypeWrapper.show();
    licenseNumberWrapper.show();
  }

  /**
   * Hides license elements.
   *
   * @return {void}
   */
  function hideLicenseElements() {
    licenseTypeWrapper.hide();
    licenseNumberWrapper.hide();
  }

  /**
   * Shows basis acts elements.
   *
   * @return {void}
   */
  function showBasisActsElements() {
    bossBasisActsNumberWrapper.show();
    bossBasisActsIssuedDateWrapper.show();
  }

  /**
   * Hides basis acts elements.
   *
   * @return {void}
   */
  function hideBasisActsElements() {
    bossBasisActsNumberWrapper.hide();
    bossBasisActsIssuedDateWrapper.hide();
  }

  /**
   * Toggle entrepreneur elements.
   *
   * @return {void}
   */
  function toggleEntrepreneurElements() {
    $.each([
      legalNameWrapper,
      cioWrapper,
      legalAddressWrapper,
      bossPositionWrapper,
      bossBasisActsWrapper,
      bossBasisActsNumberWrapper,
      bossBasisActsIssuedDateWrapper,
      documentStatuteWithTaxMarkWrapper,
      documentParticipantsDecisionWrapper,
      documentOrderOnAppointmentWrapper,
      documentStatuteOfCurrentEditionWrapper,
    ], (index, value) => {
      value.hide();
    });

    tinInput.inputmask('9{12}');
    binInput.inputmask('9{15}');

    documentPassportWrapper
      .find('.label')
      .text('Копия паспорта, заверенная подписью и печатью ИП (развороты с фотографией и '
        + 'пропиской)');

    registrationAddressWrapper.show();
    documentSnilsWrapper.show();
  }

  /**
   * Toggle LLC elements.
   *
   * @return {void}
   */
  function toggleLlcElements() {
    $.each([
      legalNameWrapper,
      cioWrapper,
      legalAddressWrapper,
      bossPositionWrapper,
      bossBasisActsWrapper,
      documentStatuteWithTaxMarkWrapper,
      documentParticipantsDecisionWrapper,
      documentOrderOnAppointmentWrapper,
      documentStatuteOfCurrentEditionWrapper,
    ], (index, value) => {
      value.show();
    });

    tinInput.inputmask('9{10}');
    binInput.inputmask('9{13}');

    documentPassportWrapper
      .find('.label')
      .text('Копия паспорта, заверенная подписью и печатью директора (развороты с фотографией и '
        + 'пропиской) генерального директора');

    registrationAddressWrapper.hide();
    documentSnilsWrapper.hide();

    if (bossBasisActsInput.val() === 'proxy'
      || bossBasisActsInput.val() === 'trust_management_agreement'
    ) {
      bossBasisActsNumberWrapper.show();
      bossBasisActsIssuedDateWrapper.show();
    }
  }

  /**
   * Replaces quotes.
   *
   * @param {string} value - The string for replace.
   *
   * @return {string}
   */
  function replaceQuotes(value) {
    return value
      .replace(/(^&quot;|^'|^")/g, '«')
      .replace(/(\s&quot;|\s'|\s")/g, ' «')
      .replace(/(&quot;\s|'\s|"\s)/g, '» ')
      .replace(/(&quot;$|'$|"$)/g, '»');
  }

  profileShopForm.validate({
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
      type: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      vat: {
        required: true,
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      legal_name: {
        required() {
          return profileShopForm.find('[name="type"]:checked').val() === 'llc';
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
      cio: {
        required: {
          depends() {
            return profileShopForm.find('[name="type"]:checked').val() === 'llc';
          },
        },
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      bin: {
        required: true,
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      is_licensed_activity: {
        required: true,
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      license_type: {
        required: {
          depends() {
            return +isLicensedActivityInput.val() === 1;
          },
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      license_number: {
        required: {
          depends() {
            return +isLicensedActivityInput.val() === 1;
          },
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      category_id: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      legal_address: {
        required: {
          depends() {
            return profileShopForm.find('[name="type"]:checked').val() === 'llc';
          },
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      registration_address: {
        required: {
          depends() {
            return profileShopForm.find('[name="type"]:checked').val() === 'entrepreneur';
          },
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      fact_address: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      bik: {
        required: true,
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      bank_name: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      correspondent_account: {
        required: true,
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      settlement_account: {
        required: true,
        number: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_full_name: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_position: {
        required: {
          depends() {
            return profileShopForm.find('[name="type"]:checked').val() === 'llc';
          },
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_basis_acts: {
        required: {
          depends() {
            return profileShopForm.find('[name="type"]:checked').val() === 'llc';
          },
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_basis_acts_number: {
        required: {
          depends() {
            const bossBasisActsInputValue = bossBasisActsInput.val();

            return bossBasisActsInputValue === 'proxy'
              || bossBasisActsInputValue === 'trust_management_agreement';
          },
        },
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_basis_acts_issued_date: {
        required: {
          depends() {
            const bossBasisActsInputValue = bossBasisActsInput.val();

            return bossBasisActsInputValue === 'proxy'
              || bossBasisActsInputValue === 'trust_management_agreement';
          },
        },
        date_ru: true,
        document_issue_date: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_passport_number: {
        required: true,
        passport: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_passport_issued_date: {
        required: true,
        date_ru: true,
        document_issue_date: true,
        passport_issue_date: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_passport_division_code: {
        required: true,
        division_code: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_passport_issued_by: {
        required: true,
        plain_text: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_birth_date: {
        required: true,
        date_ru: true,
        full_age: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      boss_birth_place: {
        required: true,
        plain_text: true,
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
      email: {
        required: true,
        email: true,
        normalizer(value) {
          return $.trim(value);
        },
      },
      'document_snils[]': {
        required() {
          return profileShopForm.find('[name="type"]:checked').val() === 'entrepreneur'
            && profileShopForm.find('[name="document_snils[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
      'document_passport[]': {
        required() {
          return profileShopForm.find('[name="document_passport[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
      'document_statute_with_tax_mark[]': {
        required() {
          return profileShopForm.find('[name="type"]:checked').val() === 'llc'
            && profileShopForm.find('[name="document_statute_with_tax_mark[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
      'document_participants_decision[]': {
        required() {
          return profileShopForm.find('[name="type"]:checked').val() === 'llc'
            && profileShopForm.find('[name="document_participants_decision[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
      'document_bin[]': {
        required() {
          return profileShopForm.find('[name="document_bin[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
      'document_order_on_appointment[]': {
        required() {
          return profileShopForm.find('[name="type"]:checked').val() === 'llc'
            && profileShopForm.find('[name="document_order_on_appointment[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
      'document_statute_of_current_edition[]': {
        required() {
          return profileShopForm.find('[name="type"]:checked').val() === 'llc'
            && profileShopForm.find('[name="document_statute_of_current_edition[]"]').length <= 1;
        },
        accept: 'application/pdf,image/jpg,image/jpeg',
        maxsize: 31457280,
      },
    },
    messages: {
      type: {
        required: 'Это обязательное поле',
      },
      vat: {
        required: 'Это обязательное поле',
      },
      legal_name: {
        required: 'Это обязательное поле',
      },
      tin: {
        required: 'Это обязательное поле',
        number: 'Поле должно содержать 10 либо 12 цифр',
        pattern: 'Поле должно содержать 10 либо 12 цифр',
      },
      cio: {
        required: 'Это обязательное поле',
        number: 'Поле должно содержать 9 цифр',
        pattern: 'Поле должно содержать 9 цифр',
      },
      bin: {
        required: 'Это обязательное поле',
        number: 'Поле должно содержать 13 либо 15 цифр',
        pattern: 'Поле должно содержать 13 либо 15 цифр',
      },
      is_licensed_activity: {
        required: 'Это обязательное поле',
      },
      license_type: {
        required: 'Это обязательное поле',
      },
      license_number: {
        required: 'Это обязательное поле',
      },
      category_id: {
        required: 'Это обязательное поле',
      },
      legal_address: {
        required: 'Это обязательное поле',
      },
      registration_address: {
        required: 'Это обязательное поле',
      },
      fact_address: {
        required: 'Это обязательное поле',
      },
      bik: {
        required: 'Это обязательное поле',
        number: 'Поле должно содержать 9 цифр',
        pattern: 'Поле должно содержать 9 цифр',
      },
      bank_name: {
        required: 'Это обязательное поле',
      },
      correspondent_account: {
        required: 'Это обязательное поле',
        number: 'Поле должно содержать 20 цифр',
        pattern: 'Поле должно содержать 20 цифр',
      },
      settlement_account: {
        required: 'Это обязательное поле',
        number: 'Поле должно содержать 20 цифр',
        pattern: 'Поле должно содержать 20 цифр',
      },
      boss_full_name: {
        required: 'Это обязательное поле',
      },
      boss_position: {
        required: 'Это обязательное поле',
      },
      boss_basis_acts: {
        required: 'Это обязательное поле',
      },
      boss_basis_acts_number: {
        required: 'Это обязательное поле',
      },
      boss_basis_acts_issued_date: {
        required: 'Это обязательное поле',
      },
      boss_passport_number: {
        required: 'Это обязательное поле',
      },
      boss_passport_issued_date: {
        required: 'Это обязательное поле',
      },
      boss_passport_division_code: {
        required: 'Это обязательное поле',
      },
      boss_passport_issued_by: {
        required: 'Это обязательное поле',
      },
      boss_birth_date: {
        required: 'Это обязательное поле',
      },
      boss_birth_place: {
        required: 'Это обязательное поле',
      },
      email: {
        required: 'Это обязательное поле',
        email: 'Введите e-mail в формате ivanov@gmail.com',
      },
      phone: {
        required: 'Это обязательное поле',
      },
      'document_snils[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
      'document_passport[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
      'document_statute_with_tax_mark[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
      'document_participants_decision[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
      'document_bin[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
      'document_order_on_appointment[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
      'document_statute_of_current_edition[]': {
        required: 'Это обязательное поле',
        accept: 'Файл должен быть в формате PDF или JPEG',
        maxsize: 'Размер одного документа максимум 30 МБ',
      },
    },
    errorClass: 'error-text',
    errorElement: 'span',
  });

  profileShopForm.on('change', 'input[name="type"]', function onChange() {
    if ($(this).val() === 'entrepreneur') {
      toggleEntrepreneurElements();
    } else {
      toggleLlcElements();
    }
  });

  legalNameInput.on('change', function onChange() {
    const value = $(this).val();

    if (value !== '') {
      $(this).val(replaceQuotes(value));
    }
  });

  isLicensedActivityInput.on('change', function onChange() {
    if (parseInt($(this).val(), 10) === 1) {
      showLicenseElements();
    } else {
      hideLicenseElements();
    }
  });

  bossBasisActsInput.on('change', function onChange() {
    if ($(this).val() === 'proxy' || $(this).val() === 'trust_management_agreement') {
      showBasisActsElements();
    } else {
      hideBasisActsElements();
    }
  });

  bikInput.on('change', function onChange() {
    const value = $(this).val();

    if (value !== '') {
      Dadata.getBankInfoByBik(value, (result) => {
        if (typeof result.suggestions !== 'undefined' && result.suggestions.length) {
          setBankName(replaceQuotes(result.suggestions[0].value));
          setCorrespondentAccount(result.suggestions[0].data.correspondent_account);
        }
      });
    }
  });

  bankNameInput.on('change', function onChange() {
    const value = $(this).val();

    if (value !== '') {
      $(this).val(replaceQuotes(value));
    }
  });

  registrationAddressInput.on('change', function onChange() {
    const value = $(this).val();

    if (value !== '') {
      Dadata.cleanAddress(value, (result) => {
        if (result.data.success) {
          setRegistrationAddress(result.data.address);
        }
      });
    }
  });

  factAddressInput.on('change', function onChange() {
    const value = $(this).val();

    if (value !== '') {
      Dadata.cleanAddress(value, (result) => {
        if (result.data.success) {
          setFactAddress(result.data.address);
        }
      });
    }
  });

  legalAddressInput.on('change', function onChange() {
    const value = $(this).val();

    if (value !== '') {
      Dadata.cleanAddress(value, (result) => {
        if (result.data.success) {
          setLegalAddress(result.data.address);
        }
      });
    }
  });

  bossBirthPlaceInput.on('change', function onChange() {
    const value = $(this).val();

    if (value !== '') {
      Dadata.cleanAddress(value, (result) => {
        if (result.data.success) {
          setBossBirthPlace(result.data.address);
        }
      });
    }
  });

  bossPassportDivisionCodeInput.on('change', function onChange() {
    const value = $(this).val();

    if (value !== '') {
      Dadata.getDivisionName(value, (result) => {
        if (typeof result.suggestions !== 'undefined' && result.suggestions.length) {
          setBossPassportIssuedBy(result.suggestions[result.suggestions.length - 1].value);
        }
      });
    }
  });
});
