/* global $ */

/**
 * Form.
 */
class Dadata {
  /**
   * Gets the bank info by the bik.
   *
   * @return {void}
   */
  static getBankInfoByBik(bik, callback) {
    $.ajax({
      url: '//suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/bank',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: 'Token 9ebe20528635d393d6373433ab86d50d2e60c507',
      },
      data: JSON.stringify({ query: bik }),
      success: callback,
    });
  }

  /**
   * Gets the bank info by the bik.
   *
   * @return {void}
   */
  static getDivisionName(divisionCode, callback) {
    $.ajax({
      url: '//suggestions.dadata.ru/suggestions/api/4_1/rs/findById/fms_unit',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: 'Token 9ebe20528635d393d6373433ab86d50d2e60c507',
      },
      data: JSON.stringify({ query: divisionCode }),
      success: callback,
    });
  }

  /**
   * Cleans the address.
   *
   * @param {string} address - The address.
   * @param {function} callback - The callback function.
   *
   * @return {void}
   */
  static cleanAddress(address, callback) {
    $.ajax({
      url: `//${window.location.hostname}/profile-shop/clean-address`,
      method: 'POST',
      header: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      data: { address },
      success: callback,
    });
  }
}
