// import log from 'core/log';
import ajax from 'core/ajax';
import templates from 'core/templates';
import config from 'core/config';
import notification from 'core/notification';
import $ from 'jquery';
import * as slider from 'block_mycourse/course_slider';

class item_load {

static msmycourses_item_load = (item,id,load_savestate) => {

                var current_view = 0;
                var switch_view = $(item).closest('.msmycourses_wrapper').find('.switch_view');

                if(switch_view.data('currentview') == 1) {
                    current_view = 1;
                }

                var promise = ajax.call([
                {
                        methodname: 'block_mycourse_load_category',
                                      args: {
                                              blockid: id,
                                              category_id: $(item).data('id'),
                                              level: $(item).data('level'),
                                              current_view: current_view,
                                              load_savestate: load_savestate,
                                      }
                        }
                ]);

    promise[0].done(function (response) {
                      if(response.slider == 0) {
                          var count = 0;
                          $.each(response.records, function(key, record){
                                    record.config = config;
                                    record.show_favorites = response.show_favorites;
                                    record.remove_activities = response.remove_activities;
                                    record.is_compact_view = response.is_compact_view;
                                    templates.render('block_mycourse/item', record).then(function (html, js) {
                                                    templates.appendNodeContents($(item).children('.children'), html, js);
                                                    count++;
                                                    if(count == response.records.length && load_savestate) {
                                                            $(".block_mycourse .savestate_open").each(function() {
                                                                if (!$(this).hasClass('open')) {
                                                                    item_load.savestate_open($(this), id, false, true);
                                                                }
                                                            });
                                                    }
                                    }).fail(notification.exception);
                          });
                        }
                        else {
                                response.config = config;
                                templates.render('block_mycourse/course_slider', response).then(function (html, js) {
                                                      templates.appendNodeContents($(item).children('.children'), html, js);
                                                      slider.init($(item),response.slider_config);
                                                      if(load_savestate) {
                                                            $(".block_mycourse .savestate_open").each(function() {
                                                                if (!$(this).hasClass('open')) {
                                                                    item_load.savestate_open($(this), id, false, true);
                                                                }
                                                            });
                                                      }
                                }).fail(notification.exception);
                        }

                            if(response.is_compact_view) {
                                            templates.render('block_mycourse/compact_selector', response)
                                            .then(function (html, js) {
                                                templates.replaceNode($(item).find('.compact_selector_wrapper'), html, js);
                                            }).fail(notification.exception);
                            }

                                /*if($(item).data('level') > 0) {
                                    $(item).find('.compact_selector_wrapper').remove();
                                }*/
                      }).fail(notification.exception);

              return promise;

};

static savestate_open = (item, id, resize) => {

                if (typeof resize === 'undefined') { resize = true; }
                item.addClass('open');
                if(item.find('.children').children().length == 0) {
                              item_load.msmycourses_item_load(item,id,true);
                }

                if(resize) {$(window).trigger("resize");}
                return false; //extra, and to make sure the function has consistent return points
        };

}
export default item_load;
