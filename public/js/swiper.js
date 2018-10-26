'use strict';

$(document).ready(function(){
  var width = $(window).width();
  var slides;

  width < 576 ? slides = 1 : slides;
  width > 576 && width < 768 ? slides = 2 : slides;
  width > 768 && width < 992 ? slides = 2 : slides;
  width > 992 ? slides = 3 : slides;

  var mySwiper = new Swiper ('.swiper-container', {
    slidesPerView: slides,
    spaceBetween: 30,
    loop: true,
    
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  })

  $(window).resize(function () {
    var width = $(window).width();

    if (width <= 576) {
      mySwiper.params.slidesPerView = 1;
    } else if (width > 576 && width <= 992) {
      mySwiper.params.slidesPerView = 2;
    } else {
      mySwiper.params.slidesPerView = 3;
    }
    mySwiper.update();
  })
  $(window).trigger('resize');

})
