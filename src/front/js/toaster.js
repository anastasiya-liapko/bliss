/* global $ */

/**
 * Toaster.
 */
class Toaster {
  /**
   * Creates a toast.
   *
   * @param {string} message - The message.
   * @param {string} [type = 'success'] - The type.
   *
   * @return {void}
   */
  static create(message, type = 'success') {
    if (message === '') return;

    const toastContainer = $('.toaster');
    const toast = $(`<div class="toaster__item toaster__item_active">
                        <span class="toaster__close-btn">×</span>${message}</div>`);

    toastContainer.append(toast);
    toast.addClass(`toaster__item_status_${type}`);

    setTimeout(() => {
      toast.remove();
    }, 8000);

    toastContainer.on('click', '.toaster__close-btn', function onClick() {
      $(this).parent().remove();
    });
  }
}
