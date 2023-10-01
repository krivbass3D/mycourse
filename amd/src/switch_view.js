//import log from 'core/log';
import ajax from 'core/ajax';
import templates from 'core/templates';
import config from 'core/config';
import notification from 'core/notification';
import $ from 'jquery';
import item_load from 'block_mycourse/item_load';
import * as slider from 'block_mycourse/course_slider';

export const init = (id) => {

          $('section').on("click",'#inst'+id+' .switch_view', function () {
                  msmycourses_switch_view($(this),id);
                  msmycourses_alt_view_savestate(id);
                  return false;
          });

};

const msmycourses_switch_view = (element,id) =>  {

                        if(element.data('target') == 'menu') {
                                var promises = ajax.call([
                                {
                                        methodname: 'block_mycourse_load_menu_category',
                                        args: {
                                                blockid: id,
                                                current_view: element.data('currentview'),
                                                switch_view: 1
                                        }
                                }
                                ]);
                        }
                        else {

                                var promises = ajax.call([
                                {
                                        methodname: 'block_mycourse_show_more',
                                        args: {
                                                blockid: id,
                                                offset: 0,
                                                prev: 0,
                                                current_view: element.data('currentview'),
                                                switch_view: 1,
                                                load_savestate: 1
                                        }
                                }
                                ]);
                        }

                        promises[0].done(function(response) {

                                    var template_type;

                                    if(element.data('target') == 'list') {
                                        template_type = "block_mycourse/list";
                                    }
                                    if(element.data('target') == 'page') {
                                        template_type = "block_mycourse/page";
                                    }
                                    if(element.data('target') == 'menu') {
                                        template_type = "block_mycourse/menu";
                                    }
                                    if(response.slider) {
                                        if(response.records.length == 0 && response.children.length == 0) {
                                            template_type = "block_mycourse/empty";
                                        }
                                    }
                                    else {
                                        if(response.records.length == 0) {
                                            template_type = "block_mycourse/empty";
                                        }
                                    }
                                    response.config = config;

                                    templates.render(template_type, response).then(function (html, js) {
                                                templates.replaceNode('#inst'+id+' .msmycourses_wrapper', html, js);
                                                var swiper_element = document.getElementById('inst'+id)
                                                            .getElementsByClassName('items')[0].firstElementChild;
                                                if($(swiper_element).hasClass('swiper')) {
                                                    slider.init($(swiper_element).parent(),response.slider_config);
                                                }
                                                $(".block_mycourse .savestate_open").each(function() {
                                                    if (!$(this).hasClass('open')) {
                                                          item_load.savestate_open($(this), id, true);
                                                    }
                                                });
                                            }).fail(notification.exception);

                                    if(response.next) {
                                        templates.render('block_mycourse/more', response).then(function (html, js) {
                                                templates.replaceNode('#inst'+id+' .show_more', html, js);
                                            }).fail(notification.exception);
                                    }
                                    else {
                                        $('#inst'+id+' .show_more').remove();
                                    }



                        }).fail(notification.exception);

};

const msmycourses_alt_view_savestate = (id) => {

          var promises = ajax.call([
          {
                methodname: 'block_mycourse_savestates_alt_view',
                args: {
                          blockid: id
                }
          }
          ]);

          promises[0].done(function() {
          }).fail(notification.exception);
};