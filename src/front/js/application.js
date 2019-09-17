/* global $, Form */

$(() => {
  $('.js-applicationChangeStatus').on('click', function onClick() {
    const action = $(this).data('action');

    if (action) {
      Form.send(action);
    }
  });
});
