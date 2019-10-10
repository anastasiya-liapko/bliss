/* global $, Toaster, Sentry */

/**
 * Form.
 */
class Form {
  /**
   * Sends a request by ajax.
   *
   * @param {string} action - The action.
   * @param {object} [data={}] - The data.
   * @param {object} [submitButton={}] - The submit button.
   *
   * @return {void}
   */
  static send(action, data = {}, submitButton = {}) {
    let processData = false;
    let contentType = false;
    let submitButtonText = '';

    if (data.ajax && data.ajax.processData) {
      ({ processData } = data);
    }

    if (data.ajax && data.ajax.contentType) {
      ({ contentType } = data);
    }

    if (submitButton.length) {
      submitButtonText = submitButton.text();
      submitButton.text('Подождите...');
      submitButton.prop('disabled', true);
    }

    $.ajax({
      data,
      dataType: 'json',
      processData,
      contentType,
      method: 'POST',
      url: action,
      success(result) {
        if (submitButton.length) {
          submitButton.text(submitButtonText);
          submitButton.prop('disabled', false);
        }

        // Opens a modal.
        if (result.openModal) {
          Form.openModal(result.openModal);
        }

        // Closes a modal.
        if (result.closeModal) {
          Form.closeModal(result.closeModal);
        }

        // Starts a timer.
        if (result.timer) {
          Form.startTimer(result.timer);
        }

        // Shows an errors.
        if (result.errors) {
          for (let i = 0; i < result.errors.length; i += 1) {
            setTimeout(Toaster.create, 300 * i, result.errors[i], 'error');
          }
        }

        // Shows a message.
        if (result.message) {
          Toaster.create(result.message.body, result.message.type);
        }

        // Sends an ajax request.
        if (result.ajax) {
          Form.ajax(result.ajax);
        }

        // Redirects.
        if (result.redirect) {
          if (result.redirect.timeout) {
            setTimeout(() => {
              window.location.href = result.redirect.url;
            }, result.redirect.timeout * 1000);
          } else {
            window.location.href = result.redirect.url;
          }
        }
      },
      error(jqXHR) {
        if (submitButton) {
          submitButton.text(submitButtonText);
          submitButton.prop('disabled', false);
        }

        Toaster.create('Произошла ошибка, попробуйте ещё раз.', 'error');

        if (typeof Sentry !== 'undefined') {
          Sentry.withScope((scope) => {
            scope.setExtra('responseText', jqXHR.responseText);
            scope.setLevel('error');
            Sentry.captureMessage('Form send error.\n'
              + `Code: ${jqXHR.status}\n`
              + `Status: ${jqXHR.statusText}\n`
              + `Response: ${jqXHR.responseText}`);
          });
        }
      },
    });
  }

  /**
   * Changes a status of a submit button.
   *
   * @param {object} form - The form.
   * @param {object} button - The button.
   *
   * @return {void}
   */
  static changeButtonStatus(form, button) {
    if (form.validate().checkForm()) {
      button.prop('disabled', false);
    } else {
      button.prop('disabled', true);
    }
  }

  /**
   * Opens a modal.
   *
   * @param {object} data - The data.
   * @param {string} data.id - The id.
   * @param {string} data.backdrop - The backdrop.
   * @param {boolean} data.keyboard - The keyboard.
   *
   * @return {void}
   */
  static openModal(data) {
    $(data.id).modal({
      backdrop: data.backdrop || 'static',
      keyboard: data.keyboard || false,
    });
  }

  /**
   * Closes a modal.
   *
   * @param {object} data - The data.
   * @param {string} data.id - The id.
   *
   * @return {void}
   */
  static closeModal(data) {
    $(data.id).modal('hide');
  }

  /**
   * Starts a timer.
   *
   * @param {object} data - The data.
   * @param {string} data.id - The id.
   * @param {object} data.progressBar - The progress bar.
   * @param {string} data.progressBar.id The progress bar id.
   * @param {int} data.progressBar.endAfter - The end after.
   * @param {int} data.end - The end.
   *
   * @return {void}
   */
  static startTimer(data) {
    if (!data.progressBar) return;

    const progressBar = $(data.progressBar.id);
    const progressBarInner = progressBar.find('div');

    $(data.id).countdown(data.end * 1000)
      .on('update.countdown', function onUpdate(event) {
        $(this).html(event.strftime('%M:%S'));

        if (data.progressBar) {
          progressBarInner
            .width((data.progressBar.endAfter - event.offset.totalSeconds) * progressBar.width()
              / data.progressBar.endAfter);
        }
      })
      .on('finish.countdown', function onFinish(event) {
        $(this).html(event.strftime('00:00'));
        $(this).parent().addClass('timer_flash');

        if (data.progressBar) {
          progressBarInner.width('100%');
        }
      });
  }

  /**
   * Sends an ajax request.
   *
   * @param {object} data - The data.
   *
   * @return {void}
   */
  static ajax(data) {
    if (data.timeout) {
      setTimeout(() => {
        Form.send(data.action);
      }, data.timeout * 1000);
    } else {
      Form.send(data.action);
    }
  }
}
