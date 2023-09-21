//import log from 'core/log';
import ajax from 'core/ajax';
//import templates from 'core/templates';
//import config from 'core/config';
import notification from 'core/notification';
import $ from 'jquery';
import item_load from 'block_msmycourses2/item_load';

export const init = (id) => {

    $('section').on("click",'#inst'+id+'.block_msmycourses2 .collapse_item', function (e) {
            item_collapse_click($(this).parent(), id, e);
            return false;
    });

    $('#inst'+id+'.block_msmycourses2 .savestate_open').each(function() {
        if (!$(this).hasClass('open')) {
            item_collapse($(this), id, false, true);
        }
    });
};

const item_collapse = (item, id, resize,load_savestate) => {
                var current_view = 0;
                var switch_view = $(item).closest('.msmycourses_wrapper').find('.switch_view');

                if(switch_view.data('currentview') == 1) {
                    current_view = 1;
                }

                if (typeof resize === 'undefined') { resize = true; }
                if ($(item).hasClass('open')) {
                        item.removeClass('open');
                        if(resize) {$(window).trigger("resize");}
                        var savestate = ajax.call([
                                      {
                                            methodname: 'block_msmycourses2_savestates_collapse',
                                            args: {
                                                      blockid: id,
                                                      category: $(item).data('id'),
                                                      current_view: current_view,
                                                      type: 'close'
                                            }
                                      }
                        ]);

                        savestate[0].done(function() {
                        }).fail(notification.exception);
                        return true; //extra, and to make sure the function has consistent return points
                } else {
                        item.addClass('open');
                        if(item.find('.children').children().length == 0) {
                              item_load.msmycourses_item_load(item,id,load_savestate);
                        }
                        var savestate = ajax.call([
                                      {
                                            methodname: 'block_msmycourses2_savestates_collapse',
                                            args: {
                                                      blockid: id,
                                                      category: $(item).data('id'),
                                                      current_view: current_view,
                                                      type: 'open'
                                            }
                                      }
                        ]);

                        savestate[0].done(function() {
                        }).fail(notification.exception);
                if(resize) {$(window).trigger("resize");}
                return false; //extra, and to make sure the function has consistent return points
                }
        };

const item_collapse_click =(obj, id, event) => {
                event.stopPropagation();
                event.preventDefault();

                item_collapse(obj,id,false,false);
};

