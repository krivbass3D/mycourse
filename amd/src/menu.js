import log from 'core/log';
import ajax from 'core/ajax';
import templates from 'core/templates';
import config from 'core/config';
import notification from 'core/notification';
import $ from 'jquery';

export const init = (id) => {
      $('section').on("click",'#inst'+id+'.block_msmycourses2 .menu .category', function () {
            menu_open($(this), id);
            menu_savestate($(this),id);
            return false;
    });
};

const menu_open = (item, blockid) => {

                var current_view = 0;
                var switch_view = item.closest('.msmycourses_wrapper').find('.switch_view');

                if(switch_view.data('currentview') == 1) {
                    current_view = 1;
                }

                var promises = ajax.call([
                                {
                                      methodname: 'block_msmycourses2_load_menu_category',
                                      args: {
                                              blockid: blockid,
                                              category_id: item.data('id'),
                                              current_view: current_view
                                      }
                                }
                                ]);

                                promises[0].done(function(response) {

                                            response.config = config;
                                            templates.render('block_msmycourses2/menu', response).then(function (html, js) {
                                                templates.replaceNode('#inst'+blockid+' .msmycourses_wrapper', html, js);
                                            }).fail(function(ex){log.debug(ex);});
                               }).fail(function(e) {
                               log.debug(e);
                                });

              return false; //extra, and to make sure the function has consistent return points
};

const menu_savestate = (item,id) => {

          var current_view = 0;
          var switch_view = item.closest('.msmycourses_wrapper').find('.switch_view');

          if(switch_view.data('currentview') == 1) {
                    current_view = 1;
          }

          var promises = ajax.call([
          {
                methodname: 'block_msmycourses2_savestates_menu',
                args: {
                          blockid: id,
                          category_id: item.data('id'),
                          current_view: current_view,
                }
          }
          ]);

          promises[0].done(function() {
          }).fail(notification.exception);
};