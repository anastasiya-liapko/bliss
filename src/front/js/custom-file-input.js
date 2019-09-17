/* global $, Helper */

$(() => {
  const form = $('form');

  /**
   * Gets files names
   *
   * @param {object} files - The file list.
   *
   * @return {array}
   */
  function getFilesNames(files) {
    return Object.keys(files).map(key => files[key].name);
  }

  /**
   * Removes an input with a label.
   *
   * @param {array} filesNamesWrappers
   *
   * @return {void}
   */
  function removeFileNames(filesNamesWrappers) {
    filesNamesWrappers.forEach((item) => {
      item.remove();
    });
  }

  /**
   * Clones an input with a label.
   *
   * @param {object} customFileInput - The jQuery element.
   * @param {object} customFileLabel - The jQuery element.
   * @param {object} customFile - The jQuery element.
   *
   * @return {void}
   */
  function cloneInput(customFileInput, customFileLabel, customFile) {
    const customFileInputId = customFileInput.attr('id');
    const uniqueId = Helper.getUniqueId();
    const newCustomFileInputId = `${customFileInputId.split('_')[0]}_${uniqueId}`;

    customFileInput
      .clone()
      .val(null)
      .removeClass('input_error')
      .attr('id', newCustomFileInputId)
      .prependTo(customFile);

    customFileLabel
      .clone()
      .removeClass('input_error')
      .attr('for', newCustomFileInputId)
      .prependTo(customFile);

    $(`#${customFileInputId}-error`).remove();

    customFileLabel.hide();
  }

  /**
   * Adds the file names.
   *
   * @param {array} filesNames - Files names.
   * @param {object} customFileInput - The jQuery element.
   * @param {object} customFileLabel - The jQuery element.
   * @param {object} customFile - The jQuery element.
   * @param {boolean} isClone - Is replace names.
   *
   * @return {void}
   */
  function addFileNames(
    filesNames,
    customFileInput,
    customFileLabel,
    customFile,
    isClone = true,
  ) {
    let filesNamesContainer = customFile.children('.custom-file__files-names');

    if (!filesNamesContainer.length) {
      filesNamesContainer = $('<div>', { class: 'custom-file__files-names' })
        .appendTo(customFile);
    }

    if (!isClone) {
      filesNamesContainer.html('');
    }

    const filesNamesWrappers = [];

    for (let i = 0; i < filesNames.length; i += 1) {
      filesNamesWrappers.push($('<div>', { class: 'custom-file__file-name', text: filesNames[i] }));
    }

    filesNamesWrappers.forEach((item, i) => {
      if (i === 0) {
        $('<button>', {
          class: 'custom-file__close-btn',
          html: '&#10006;',
        }).on('click', () => {
          removeFileNames(filesNamesWrappers);

          if (isClone) {
            customFileInput.remove();
            customFileLabel.remove();
          } else {
            customFileInput.val(null);
          }
        }).prependTo(item);
      }

      item.appendTo(filesNamesContainer);
    });
  }

  form.on('change', '.custom-file-input', function onChange() {
    const customFileInput = $(this);
    const customFileLabel = customFileInput.prev('.custom-file-label');
    const customFile = customFileInput.parent('.custom-file');
    const { files } = this;

    if (files.length > 0) {
      const filesNames = getFilesNames(files);
      const isMultiple = customFileInput.hasClass('custom-file-input__multiple');

      if (isMultiple) {
        cloneInput(customFileInput, customFileLabel, customFile);
      }

      addFileNames(filesNames, customFileInput, customFileLabel, customFile, isMultiple);
    } else {
      customFile.children('.custom-file__files-names').remove();
    }
  });
});
