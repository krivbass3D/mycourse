//import log from 'core/log';
import ajax from 'core/ajax';
import templates from 'core/templates';
import config from 'core/config';
import notification from 'core/notification';
import $ from 'jquery';
import item_load from 'block_mycourse/item_load';

export const init = (id) => {

    var promises = ajax.call([
            {
                    methodname: 'block_mycourse_initial_load',
                    args: {
                            blockid: id,
                    }
            }
    ]);

    promises[0].done(function(response) {

        response.config = config;
        //log.debug(response);
        templates.render('block_mycourse/'+response.template_type, response).then(function (html, js) {
                  templates.replaceNode('#inst'+id+' .msmycourses_wrapper', html, js);
                  $(".block_mycourse .savestate_open").each(function() {
                      if (!$(this).hasClass('open')) {
                            item_load.savestate_open($(this), id, true);
                      }
                  });
        }).fail(notification.exception);

    }).fail(notification.exception);
};