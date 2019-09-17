/* global $ */

$(() => {
  if (!$('.body_home').length) {
    return;
  }

  let pageMenuHeight = parseInt($('.page__content').css('padding-top'), 10);

  $(window).resize(() => {
    pageMenuHeight = parseInt($('.page__content').css('padding-top'), 10);
  });

  function setPageHeight() {
    const page = $('.page');
    const sideMenuPage = $('#js-sideMenuPage');
    const curHeight = page.height();
    const newHeight = sideMenuPage.height() + parseInt(pageMenuHeight, 10);

    page.height(curHeight).animate({ height: newHeight }, {
      duration: 200,
      queue: false,
    });
  }

  function setPageHeightIfSideMenuOpen() {
    const page = $('.page');
    const sideMenuPage = $('#js-sideMenuPage');

    if (sideMenuPage.hasClass('open')) {
      setPageHeight();
    } else {
      page.css('height', 'auto');
    }
  }

  function scrollToContent() {
    $('html, body').animate({
      scrollTop: $('#js-sideMenuContent').offset().top - pageMenuHeight,
    }, 500);
  }

  function removeActiveClass() {
    $('.dropdown-link').each((i, elem) => {
      $(elem).removeClass('active');
    });
    $('.dropdown__item').each((i, elem) => {
      $(elem).removeClass('active');
    });
  }

  function closeDropdowns() {
    $('#js-pageMenuToggle').removeClass('is-active');
    $('.dropdown-link').removeClass('open');
  }

  function findSecondMenuPoint(elem) {
    $(elem).addClass('active');

    $('.dropdown-link').each((index, element) => {
      if ($(element).data('link') === $(elem).data('link')) {
        if (($(element).hasClass('dropdown-link_toggle-top') && $(window).width() <= 991)
          || $(element).hasClass('dropdown-link_toggle-bottom')
        ) {
          const item = element.closest('.dropdown__item');
          const dropdown = $(item).find('.dropdown');

          $(dropdown).slideToggle({
            start() {
              setPageHeight();
            },
            done() {
              setPageHeight();
            },
          });

          $(element).toggleClass('open');

          if ($('#js-sideMenuPage').hasClass('open')) {
            setPageHeight();
          }
        }

        if ($(element).hasClass('dropdown-link_side')) {
          const item = element.closest('.dropdown__item');
          const dropdown = $(item).find('.dropdown');

          $(dropdown).slideDown(() => {
            setPageHeight();
          });

          $(item).find('.dropdown-link_toggle').addClass('open');

          if ($('#js-sideMenuPage').hasClass('open')) {
            setPageHeight();
          }
        }

        $(element).addClass('active');
        $(element).parents('.dropdown__item').find('.dropdown__item-link').addClass('active');
        $(element).parents('.dropdown__item').addClass('active');
      }
    });
  }

  $('body').bind('click', (e) => {
    const arrowMobile = $('.arrow_mobile');
    const arrowDesktop = $('.arrow_desktop');
    const sideMenuPage = $('#js-sideMenuPage');

    $('.page').css('overflow', 'hidden');

    if (arrowMobile.has(e.target).length > 0
      || arrowDesktop.has(e.target).length > 0
    ) {
      arrowMobile.removeClass('is-active');
      arrowDesktop.removeClass('is-active');

      sideMenuPage
        .removeClass('open')
        .addClass('close');

      setTimeout(() => {
        $('.page').css('height', 'auto');
      }, 300);

      closeDropdowns();

      removeActiveClass();
    } else {
      setTimeout(setPageHeight, 200);

      $('.side-menu .dropdown-link:not(.dropdown-link_no-click)').each((i, elem) => {
        if (e.target === elem) {
          removeActiveClass();
          findSecondMenuPoint(elem);

          if ($(window).width() <= 991) {
            scrollToContent();
          }
        }
      });

      $('.page-menu .dropdown-link:not(.dropdown-link_no-click)').each((i, elem) => {
        if (e.target === elem) {
          removeActiveClass();
          findSecondMenuPoint(elem);

          const closeMenu = function closeMenu() {
            $('#js-pageMenuToggle').removeClass('is-active');
            $('#js-pageMenu').slideUp('fast', () => {
              setPageHeightIfSideMenuOpen();
            });
          };

          if ($(window).width() <= 991) {
            closeMenu();
            scrollToContent();
          }

          if (!sideMenuPage.hasClass('open')) {
            $('.arrow_mobile').addClass('is-active');
            $('.arrow_desktop').addClass('is-active');

            sideMenuPage.removeClass('close')
              .addClass('open');

            setPageHeight();

            $(window).resize(() => {
              setPageHeightIfSideMenuOpen();
            });
          }
        }
      });
    }
  });

  $(window).resize(() => {
    closeDropdowns();
    setPageHeightIfSideMenuOpen();
  });
});
