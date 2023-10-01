import ajax from 'core/ajax';
//import log from 'core/log';
import * as slider from 'block_mycourse/course_slider';
import $ from 'jquery';

export const init = (id,category,current_view) => {
        var element = document.getElementById('inst'+id).getElementsByClassName('items')[0].firstElementChild;
        if($(element).hasClass('swiper')) {

                var promise = ajax.call([
                {
                        methodname: 'block_mycourse_load_category',
                                      args: {
                                              blockid: id,
                                              category_id: category,
                                              level: 0,
                                              current_view: current_view,
                                              load_savestate: true,
                                      }
                        }
                ]);

                promise[0].done(function(response) {
                      slider.init($(element).parent(),response.slider_config,category);
                });
        }
};