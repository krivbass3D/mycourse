import Swiper from 'local_mscore/swiper';
//import log from 'core/log';
import ajax from 'core/ajax';
import notification from 'core/notification';
/*eslint-disable*/
export const init = (category,sliderconfig,top_category = 0) => {

    var element = category.find('.swiper')[0];
    
    new Swiper(element, {
    // Optional parameters
    allowTouchMove: sliderconfig.touch,
    direction: sliderconfig.direction,
    loop: sliderconfig.loop,
    autoHeight:false,
    grabCursor: true,
    initialSlide: sliderconfig.initial,
    slidesPerGroup: sliderconfig.slides_per_group, 
    breakpointsBase: 'container',
    slidesPerView: sliderconfig.slides_per_view,
    loopedSlides: sliderconfig.looped_slides,
    preloadImages: false,
    lazy: true,
    watchSlidesProgress: true,
    loopFillGroupWithBlank: true,
  
    /*// If we need pagination
    pagination: {
      el: '.swiper-pagination',
    },*/
  
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  
    // And if we need scrollbar
    /*scrollbar: {
      el: '.swiper-scrollbar',
    },*/
    /*breakpoints: {
      // when window width is >= 320px
      640: {
        slidesPerView: 2,
        spaceBetween: 20
      },
      // when window width is >= 480px
      960: {
        slidesPerView: 3,
        spaceBetween: 30
      },
      // when window width is >= 640px
      1280: {
        slidesPerView: 4,
        spaceBetween: 40
      }
    }*/
    
  on: {
    slideChange: function (swiper) {

      let category;
      
      if(swiper.el) {
      if(top_category != 0) {
          category = top_category;
      }
      else {
          category = swiper.el.closest('.item').dataset.id;
      }
      
      if(switchview = swiper.el.closest('.msmycourses_wrapper').getElementsByClassName('switch_view')[0]) {
          current_view = switchview.dataset.currentview;
      }
      else {
          current_view = 0
      }
      var promises = ajax.call([
          {
                methodname: 'block_msmycourses2_savestates_slider',
                args: {
                          blockid: swiper.el.closest('.block_msmycourses2').id.substring(4),
                          category: category,
                          current_view: current_view,
                          current: swiper.realIndex
                }
          }
          ]);

          promises[0].done(function() {
      }).fail(notification.exception);
    }
    },
  },
  });
};
