/* global $ */

/**
 * Helper.
 */
class Helper {
  /**
   * Creates an unique id.
   *
   * @param {int} [length=12] length - The length.
   *
   * @return {string} id The id.
   */
  static getUniqueId(length = 12) {
    const chars = 'abcdefghijklmnopqrstuvwxyz';

    let id = '';

    for (let i = 0; i < length; i += 1) {
      id += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    return id;
  }
}
