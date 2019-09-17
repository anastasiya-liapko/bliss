/* global $ */

$(() => {
  /**
   * Validates passport number.
   */
  $.validator.addMethod('passport', function addRule(value, element) {
    return this.optional(element) || /^\d{2}\s\d{2}\s\d{6}$/.test(value);
  }, 'Введите данные в формате 01 02 343543');

  /**
   * Validates passport issue date.
   */
  $.validator.addMethod('passport_issue_date', function addRule(value, element) {
    if (this.optional(element)) {
      return true;
    }

    const dateParts = value.split('.');
    const date = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);

    return date >= new Date(1997, 9, 1);
  }, 'Паспорт должен быть выдан не позднее 1 октября 1997 года.');

  /**
   * Validates passport division code.
   */
  $.validator.addMethod('division_code', function addRule(value, element) {
    return this.optional(element) || /^\d{3}-\d{3}$/.test(value);
  }, 'Введите данные в формате 770-001');

  /**
   * Validates ru phone number.
   */
  $.validator.addMethod('phone_ru', function addRule(value, element) {
    return this.optional(element) || /^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/.test(value);
  }, 'Введите номер в формате +7(000)000-00-00');

  /**
   * Validates snils.
   */
  $.validator.addMethod('snils', function addRule(value, element) {
    return this.optional(element) || /^\d{3}-\d{3}-\d{3}\s\d{2}$/.test(value);
  }, 'Введите данные в формате 116-973-386 85');

  /**
   * Validates plain text.
   */
  $.validator.addMethod('plain_text', function addRule(value, element) {
    return this.optional(element) || !/(<([^>]+)>)/ig.test(value);
  }, 'Поле не должно содержать HTML-теги');

  /**
   * Validates custom date.
   */
  $.validator.addMethod('date_ru', function addRule(value, element) {
    return this.optional(element) || /^(0[1-9]|[12]\d|3[01])\.((0[1-9]|1[0-2])\.[12]\d{3})$/.test(value);
  }, 'Введите дату в формате 01.01.1970');

  /**
   * Validates full age.
   */
  $.validator.addMethod('full_age', function addRule(value, element) {
    if (this.optional(element)) {
      return true;
    }

    const dateParts = value.split('.');
    const ageDif = Date.now() - new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
    const age = (new Date(ageDif).getUTCFullYear()) - 1970;

    return age >= 18;
  }, 'Возраст должен быть больше 18 лет');

  /**
   * Validates passport date.
   */
  $.validator.addMethod('document_issue_date', function addRule(value, element) {
    if (this.optional(element)) {
      return true;
    }

    const dateParts = value.split('.');
    const dateDif = Date.now() - new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);

    return dateDif > 0;
  }, 'Документ не может быть выдан в будущем');
});
