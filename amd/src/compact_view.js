//import log from 'core/log';
import ajax from 'core/ajax';
import templates from 'core/templates';
import config from 'core/config';
import notification from 'core/notification';
import $ from 'jquery';
import * as slider from 'block_mycourse/course_slider';

export const init = (id) => {

          $('section').on("click",'#inst'+id+' .target_normal', function () {
                  show_compact_view($(this),id,false);
                  return false;
          });

          $('section').on("click",'#inst'+id+' .target_compact', function () {
                  show_compact_view($(this),id,true);
                  return false;
          });
};

const show_compact_view = (element,id,compact) =>  {
                var current_view = 0;
                var switch_view = element.closest('.msmycourses_wrapper').find('.switch_view');
                var category = element.closest('.category');

                if(switch_view.data('currentview') == 1) {
                    current_view = 1;
                }
                        var promises = ajax.call([
                                {
                                        methodname: 'block_mycourse_load_category',
                                        args: {
                                                blockid: id,
                                                use_compact_view: compact,
                                                current_view: current_view,
                                                category_id: category.data('id'),
                                                level: category.data('level'),
                                                load_savestate: true,
                                                load_compact_savestate: false
                                        }
                                }
                        ]);

                        promises[0].done(function(response) {
                                    category.children('.children').remove();
                                    var children = document.createElement("div");
                                    children.classList.add('children');
                                    category.append(children);
                                    if(response.slider) {
                                          templates.render('block_mycourse/course_slider', response).then(function (html, js) {
                                                      templates.appendNodeContents(category.children('.children'), html, js);
                                                      slider.init(category,response.slider_config);
                                          });
                                    }
                                    else {
                                          $.each(response.records, function(key, record){
                                                  record.config = config;
                                                  record.show_favorites = response.show_favorites;
                                                  record.remove_activities = response.remove_activities;
                                                  record.is_compact_view = response.is_compact_view;
                                                  templates.render('block_mycourse/item', record).then(function (html, js) {
                                                      templates.appendNodeContents(category.children('.children'), html, js);
                                                  }).fail(notification.exception);
                                          });
                                    }

                                    templates.render('block_mycourse/compact_selector', response)
                                            .then(function (html, js) {
                                                templates.replaceNode(category.find('.compact_selector_wrapper'), html, js);
                                            }).fail(notification.exception);
                      });

                      var savestate = ajax.call([
                                      {
                                            methodname: 'block_mycourse_savestates_compact',
                                            args: {
                                                      blockid: id,
                                                      category: category.data('id'),
                                                      current_view: current_view,
                                                      compact: compact
                                            }
                                      }
                        ]);
                        savestate[0].done(function() {
                        }).fail(notification.exception);
};