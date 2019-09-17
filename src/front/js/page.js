/* global $, Form */

$(() => {
  const page = $('#js-page');
  const ajaxData = {};
  const ajaxTimeout = page.data('ajax-timeout');
  const ajaxAction = page.data('ajax-action');

  if (ajaxTimeout) {
    ajaxData.timeout = ajaxTimeout;
  }

  if (ajaxAction) {
    ajaxData.action = ajaxAction;
    Form.ajax(ajaxData);
  }
});
