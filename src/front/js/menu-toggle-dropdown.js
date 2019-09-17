/* global $ */

$(() => {
  let pageMenuHeight;

  /**
   * Sets the page menu height.
   *
   * @return {void}
   */
  function setPageMenuHeight() {
    pageMenuHeight = parseInt($('.page__content').css('padding-top'), 10);
  }

  /**
   * Toggles sticking of menu.
   *
   * @return {void}
   */
  function toggleMenuSticking() {
    if ($(window).scrollTop() > 34) {
      $('.page-menu').addClass('sticky');
      $('.fixed-menu').addClass('sticky');
    } else {
      $('.page-menu').removeClass('sticky');
      $('.fixed-menu').removeClass('sticky');
    }
  }

  $(window).on('loan resize', () => {
    setPageMenuHeight();
    toggleMenuSticking();
  });

  $(window).on('scroll', () => {
    toggleMenuSticking();
  });

  $('#js-pageMenuToggle').on('click', function onClick() {
    $(this).toggleClass('is-active');

    $('#js-pageMenu').slideToggle('fast', () => {
      const sideMenuPage = $('#js-sideMenuPage');

      if (sideMenuPage.hasClass('open')) {
        $('.page').css('height', sideMenuPage.height() + parseInt(pageMenuHeight, 10));
      } else {
        $('.page').css('height', 'auto');
      }
    });
  });
});
