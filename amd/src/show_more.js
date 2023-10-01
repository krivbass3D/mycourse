//import log from 'core/log';
import ajax from 'core/ajax';
import templates from 'core/templates';
import config from 'core/config';
import notification from 'core/notification';
import $ from 'jquery';
import item_load from 'block_mycourse/item_load';

export const init = (id) => {

          $('section').on("click",'#inst'+id+' .show_more', function () {
                  msmycourses_show_more($(this),id);
                  return false;
          });

          $('section').on("click",'#inst'+id+' .next', function () {
                  msmycourses_page_shift($(this),id);
                  return false;
          });

          $('section').on("click", '#inst'+id+' .prev',function () {
                  msmycourses_page_shift($(this),id);
                  return false;
          });
};

const msmycourses_show_more = (element,id) =>  {
                        var promises = ajax.call([
                                {
                                        methodname: 'block_mycourse_show_more',
                                        args: {
                                                blockid: id,
                                                offset: element.data('current'),
                                                switch_view: 0,
                                                current_view: element.data('currentview')
                                        }
                                }
                        ]);

                        promises[0].done(function(response) {

                                    if(response.slider) {
                                        $.each(response.children, function(key, record){
                                                  record.config = config;
                                                  record.show_favorites = response.show_favorites;
                                                  record.remove_activities = response.remove_activities;
                                                  record.enable_compact_view = response.enable_compact_view;
                                            templates.render('block_mycourse/child_category', record).then(function (html, js) {
                                            templates.appendNodeContents($('#inst'+id+' .items').children('.categories'), html, js);
                                                  }).fail(notification.exception);
                                          });
                                    }
                                    else {
                                          $.each(response.records, function(key, record){
                                                  record.config = config;
                                                  record.show_favorites = response.show_favorites;
                                                  record.remove_activities = response.remove_activities;
                                                  templates.render('block_mycourse/item', record).then(function (html, js) {
                                                      templates.appendNodeContents('#inst'+id+' .items', html, js);
                                                  }).fail(notification.exception);
                                          });
                                    }
                                    if(response.next) {
                                        templates.render('block_mycourse/more', response).then(function (html, js) {
                                                templates.replaceNode('#inst'+id+' .show_more', html, js);
                                            }).fail(notification.exception);
                                    }
                                    else {
                                        $('#inst'+id+' .show_more').remove();
                                    }

                                    if(response.show_alt_view) {
                                        templates.render('block_mycourse/switch_view', response).then(function (html, js) {
                                                templates.replaceNode('#inst'+id+' .switch_view_wrapper', html, js);
                                            }).fail(notification.exception);
                                    }

                        }).fail(notification.exception);
};

const msmycourses_page_shift = (element,id) => {
                        var promises = ajax.call([
                                {
                                        methodname: 'block_mycourse_show_more',
                                        args: {
                                                blockid: id,
                                                offset: element.data('current'),
                                                prev: element.data('prev'),
                                                current_view: element.data('currentview')
                                        }
                                }
                        ]);

                        promises[0].done(function(response) {

                                      response.config = config;
                                      templates.render('block_mycourse/page', response).then(function (html, js) {
                                                templates.replaceNode('#inst'+id+' .msmycourses_wrapper', html, js);
                                                $(".block_mycourse .savestate_open").each(function() {
                                                    if (!$(this).hasClass('open')) {
                                                          item_load.savestate_open($(this), id, true);
                                                    }
                                                });
                                      }).fail(notification.exception);

                                      if(response.show_alt_view) {
                                        templates.render('block_mycourse/switch_view', response).then(function (html, js) {
                                                templates.replaceNode('#inst'+id+' .switch_view_wrapper', html, js);
                                            }).fail(notification.exception);
                                      }

                                      var savestate = ajax.call([
                                      {
                                            methodname: 'block_mycourse_savestates_page',
                                            args: {
                                                      blockid: id,
                                                      current: response.first_element,
                                                      current_view: element.data('currentview'),
                                            }
                                      }
                                      ]);

                                      savestate[0].done(function() {
                                      }).fail(notification.exception);

                        }).fail(notification.exception);
};
