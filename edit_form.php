<?php

class block_mycourse_edit_form extends block_edit_form {

    protected function specific_definition($mform): void {

        global $CFG;

        $mform->addElement('header', 'basic_options', get_string('basic_options', 'block_mycourse'));
        // block title
        $mform->addElement('text','config_title',get_string('block_title', 'block_mycourse'));
        $mform->setType('config_title', PARAM_TEXT);

        // course title
         $mform->addElement('select','config_course_title',get_string('course_title', 'block_mycourse'),$this->get_course_title_options());
         $mform->setType('config_course_title', PARAM_INT);

          // course display (tiles or list)
         $mform->addElement('select','config_display',get_string('display', 'block_mycourse'),$this->get_display_options());
         $mform->setType('config_display', PARAM_INT);

        // tiles per row
        $mform->addElement('select','config_tiles_per_row',get_string('tiles_per_row', 'block_mycourse'),[0 => 'auto', 1 => '1',2 => '2',3 => '3',4 => '4',5 => '5',6 => '6']);
        $mform->setDefault('config_tiles_per_row', 0);

        $mform->addElement('selectyesno','config_show_summary',get_string('show_summary', 'block_mycourse'));
         $mform->setType('config_show_summary', PARAM_BOOL);

        if(block_mycourse\helper::metadata_installed()) {
            $mform->addElement('selectyesno','config_load_metadata',get_string('load_metadata', 'block_mycourse'));
            $mform->setType('config_load_metadata', PARAM_BOOL);
            $mform->addHelpButton('config_load_metadata','load_metadata', 'block_mycourse');
        }

        if(block_mycourse\helper::favorites_installed()) {

               $mform->addElement('selectyesno','config_show_favorites',get_string('show_favorites', 'block_mycourse'));
            $mform->setType('config_show_favorites', PARAM_BOOL);

            $mform->addElement('selectyesno','config_remove_activities',get_string('remove_activities', 'block_mycourse'));
            $mform->setType('config_remove_activities', PARAM_BOOL);
            $mform->addHelpButton('config_remove_activities','remove_activities', 'block_mycourse');
        }

            $mform->addElement('selectyesno','config_database',get_string('database', 'block_mycourse'));
            $mform->setType('config_database', PARAM_BOOL);
        $mform->addHelpButton('config_database','database', 'block_mycourse');

        if(block_mycourse\helper::certificate_installed()) {
            $mform->addElement('selectyesno','config_show_certificate_link',get_string('show_certificate_link', 'block_mycourse'));
            $mform->setType('config_show_certificate_link', PARAM_BOOL);
        }

        // hide block if no courses are found
             $mform->addElement('selectyesno','config_hide_if_empty',get_string('hide_if_empty', 'block_mycourse'));
             $mform->setType('config_hide_if_empty', PARAM_BOOL);
        $mform->addHelpButton('config_hide_if_empty','hide_if_empty', 'block_mycourse');

        $mform->addElement('selectyesno','config_show_available_languages',get_string('show_available_languages', 'block_mycourse'));
        $mform->setType('config_show_available_languages', PARAM_BOOL);

            // css classes
        $mform->addElement('text','config_css_classes',get_string('css_classes', 'block_mycourse'));
        $mform->setType('config_css_classes', PARAM_TEXT);

        $mform->addElement('header', 'category_options', get_string('category_options', 'block_mycourse'));
        // show categories
            $mform->addElement('selectyesno','config_show_categories',get_string('show_categories', 'block_mycourse'));
            $mform->setType('config_show_categories', PARAM_BOOL);
        $mform->disabledIf('config_show_categories','config_display','eq',2);

        // parent category
        $mform->addElement('select', 'config_top_category', get_string('top_category', 'block_mycourse'), $this->get_category_options());
            $mform->setType('config_top_category', PARAM_INT);

            // show courses before categories
            $mform->addElement('selectyesno','config_show_courses_before_subcategories',get_string('show_courses_before_subcategories', 'block_mycourse'));
            $mform->setType('config_show_courses_before_subcategories', PARAM_BOOL);

            $mform->addElement('selectyesno','config_category_compact_view',get_string('config_category_compact_view', 'block_mycourse'));
            $mform->setType('config_category_compact_view', PARAM_BOOL);
        $mform->addHelpButton('config_category_compact_view','config_category_compact_view', 'block_mycourse');
        $mform->disabledIf('config_category_compact_view','config_display','eq',2);
        $mform->disabledIf('config_category_compact_view','config_show_categories','eq',0);

        $mform->addElement('header', 'show_more_options', get_string('show_more_options', 'block_mycourse'));

        $mform->addElement('select','config_type',get_string('config_type', 'local_mscore'), $this->get_type_options());
        $mform->setType('config_type', PARAM_INT);
        $mform->setDefault('config_type', 0);
        $mform->disabledIf('config_type','config_display','eq',2);

        //Select for number of shown courses/activities
        $mform->addElement('select', 'config_limit', get_string('config_limit', 'local_mscore'), $this->get_limit_options());
        $mform->setType('config_limit', PARAM_INT);
        $mform->setDefault('config_limit', 3);
        $mform->disabledIf('config_limit','config_display','eq',2);

        $mform->addElement('advcheckbox', 'config_show_more', get_string('config_show_more', 'local_mscore'));
        $mform->setDefault('config_show_more', 0);
        $mform->disabledIf('config_show_more', 'config_limit','eq', 0);
        $mform->disabledIf('config_show_more','config_display','eq',2);

        $mform->addElement('select', 'config_more_limit', get_string('config_more_limit', 'local_mscore'), $this->get_limit_options());
        $mform->setType('config_more_limit', PARAM_INT);
        $mform->setDefault('config_more_limit', 3);
        $mform->disabledIf('config_more_limit', 'config_show_more','eq', 0);
        $mform->disabledIf('config_more_limit', 'config_type','eq', 1);
        $mform->disabledIf('config_more_limit','config_display','eq',2);

        $mform->addElement('header', 'course_slider_options', get_string('course_slider_options', 'block_mycourse'));

        // show courses as slider
            $mform->addElement('selectyesno','config_course_slider',get_string('config_course_slider', 'block_mycourse'));
            $mform->setType('config_course_slider', PARAM_BOOL);
        $mform->disabledIf('config_course_slider','config_show_categories','neq',1);
        $mform->disabledIf('config_course_slider','config_display','eq',2);

        // direction
            $mform->addElement('select', 'config_course_slider_direction', get_string('config_course_slider_direction', 'block_mycourse'), ['horizontal' => get_string('horizontal', 'block_mycourse'),'vertical' => get_string('vertical', 'block_mycourse')]);
            $mform->setType('config_course_slider_direction', PARAM_TEXT);
        $mform->disabledIf('config_course_slider_direction','config_show_categories','neq',1);
        $mform->disabledIf('config_course_slider_direction','config_display','eq',2);
        $mform->disabledIf('config_course_slider_direction','config_course_slider','neq',1);

        // loop
            $mform->addElement('advcheckbox','config_course_slider_loop',get_string('config_course_slider_loop', 'block_mycourse'));
            $mform->setType('config_course_slider_loop', PARAM_BOOL);
        $mform->disabledIf('config_course_slider_loop','config_show_categories','neq',1);
        $mform->disabledIf('config_course_slider_loop','config_display','eq',2);
        $mform->disabledIf('config_course_slider_loop','config_course_slider','neq',1);

        // touch
            $mform->addElement('advcheckbox','config_course_slider_touch',get_string('config_course_slider_touch', 'block_mycourse'));
            $mform->setType('config_course_slider_touch', PARAM_BOOL);
        $mform->setDefault('config_course_slider_touch', true);
        $mform->disabledIf('config_course_slider_touch','config_show_categories','neq',1);
        $mform->disabledIf('config_course_slider_touch','config_display','eq',2);
        $mform->disabledIf('config_course_slider_touch','config_course_slider','neq',1);

        // slides_per_view
            $mform->addElement('select', 'config_course_slider_slides_per_view', get_string('config_course_slider_slides_per_view', 'block_mycourse'), [0 => 'auto', 1 => '1',2 => '2',3 => '3',4 => '4',5 => '5',6 => '6']);
            $mform->setType('config_course_slider_slides_per_view', PARAM_INT);
        $mform->setDefault('config_course_slider_slides_per_view', 4);
        $mform->disabledIf('config_course_slider_slides_per_view','config_show_categories','neq',1);
        $mform->disabledIf('config_course_slider_slides_per_view','config_display','eq',2);
        $mform->disabledIf('config_course_slider_slides_per_view','config_course_slider','neq',1);

        // slides_per_group
            $mform->addElement('select', 'config_course_slider_slides_per_group', get_string('config_course_slider_slides_per_group', 'block_mycourse'), [1 => '1',2 => '2',3 => '3',4 => '4',5 => '5',6 => '6']);
            $mform->setType('config_course_slider_slides_per_group', PARAM_INT);
        $mform->setDefault('config_course_slider_slides_per_group', 1);
        $mform->disabledIf('config_course_slider_slides_per_group','config_show_categories','neq',1);
        $mform->disabledIf('config_course_slider_slides_per_group','config_display','eq',2);
        $mform->disabledIf('config_course_slider_slides_per_group','config_course_slider','neq',1);

        $mform->addElement('header', 'filter_options', get_string('filter_options', 'block_mycourse'));

        // filter completion
            $mform->addElement('select','config_filter_completion',get_string('filter_completion', 'block_mycourse'),$this->get_completion_filter_options());
            $mform->setType('config_filter_completion', PARAM_INT);

            // filter completion: courses without configured module completion
            $mform->addElement('selectyesno','config_filter_completion_hide_courses_without_configured_module_completion',get_string('filter_completion_hide_courses_without_configured_module_completion', 'block_mycourse'));
            $mform->setType('config_filter_completion_hide_courses_without_configured_module_completion', PARAM_BOOL);
            $mform->disabledIf('config_filter_completion_hide_courses_without_configured_module_completion', 'config_filter_completion', 'eq', 0);
            $mform->disabledIf('config_filter_completion_hide_courses_without_configured_module_completion', 'config_filter_completion', 'eq', 1);

             // filter course group
            $mform->addElement('select','config_filter_course_group',get_string('filter_course_group', 'block_mycourse'),$this->get_filter_course_group_options());
            $mform->setType('config_filter_course_group', PARAM_INT);

            // filter course group name
            $mform->addElement('text','config_filter_course_group_idnumber',get_string('filter_course_group_idnumber', 'block_mycourse'));
            $mform->setType('config_filter_course_group_idnumber', PARAM_TEXT);
        $mform->disabledIf('config_filter_course_group_idnumber', 'config_filter_course_group', 'eq', 0);

            // filter course role
            $mform->addElement('select','config_filter_course_role',get_string('config_filter_course_role', 'block_mycourse'),$this->get_filter_course_roles());
            $mform->setType('config_filter_course_role', PARAM_INT);

        $autocompleteoptions = [
          'multiple' => true,
          'noselectionstring' => get_string('no_category', 'block_mycourse'),
         ];
        //exclude categories
        $mform->addElement('autocomplete', 'config_exclude_categories', get_string('exclude_categories', 'block_mycourse'), $this->get_category_options(true),$autocompleteoptions);
         $mform->setType('config_exclude_categories', PARAM_INT);

        $mform->addElement('header', 'course_progress_options', get_string('course_progress_options', 'block_mycourse'));

         // show progress
         $mform->addElement('selectyesno','config_show_progress',get_string('show_progress', 'block_mycourse'));
         $mform->setType('config_show_progress', PARAM_BOOL);

         // progress type
         $mform->addElement('select','config_progress_type',get_string('progress_type', 'block_mycourse'),$this->get_progress_type_options());
         $mform->setType('config_progress_type', PARAM_INT);

        // progress filter
         $mform->addElement('select','config_filter_progress',get_string('filter_progress', 'block_mycourse'),$this->get_filter_progress_options());
         $mform->setType('config_filter_progress', PARAM_INT);

        //language filter
        if(block_mycourse\helper::language_installed()) {
            $mform->addElement('select','config_language_restriction',get_string('language_restriction', 'block_mycourse'),$this->get_language_restriction_options());
            $mform->setType('config_language_restriction', PARAM_TEXT);
        }

        $mform->addElement('header', 'alt_view', get_string('switch_view', 'block_mycourse'));

            $mform->addElement('selectyesno','config_show_altview',get_string('enable_switch_view', 'block_mycourse'));
            $mform->setType('config_show_altview', PARAM_BOOL);

             $mform->addElement('selectyesno','config_two_icon_switch',get_string('two_icon_switch', 'block_mycourse'));
            $mform->setType('config_two_icon_switch', PARAM_BOOL);

        $mform->addElement('select','config_alt_course_title',get_string('course_title', 'block_mycourse'),$this->get_course_title_options());
            $mform->setType('config_alt_course_title', PARAM_INT);

               // course display (tiles or list)
             $mform->addElement('select','config_alt_display',get_string('display', 'block_mycourse'),$this->get_display_options());
          $mform->setType('config_alt_display', PARAM_INT);

        // tiles per row
        $mform->addElement('select','config_alt_tiles_per_row',get_string('tiles_per_row', 'block_mycourse'),[0 => 'auto', 1 => '1',2 => '2',3 => '3',4 => '4',5 => '5',6 => '6']);
        $mform->setDefault('config_alt_tiles_per_row', 0);

        $mform->addElement('selectyesno','config_alt_show_summary',get_string('show_summary', 'block_mycourse'));
            $mform->setType('config_alt_course_summary', PARAM_BOOL);

        $mform->addElement('selectyesno','config_alt_show_certificate_link',get_string('show_certificate_link', 'block_mycourse'));
            $mform->setType('config_alt_show_certificate_link', PARAM_BOOL);

        $mform->addElement('selectyesno','config_alt_show_available_languages',get_string('show_available_languages', 'block_mycourse'));
        $mform->setType('config_alt_show_available_languages', PARAM_BOOL);

        $mform->addElement('html','<hr>');
        $mform->addElement('static', 'alt_category_options', '<b>'.get_string('category_options', 'block_mycourse').'</b>');

          // show categories
              $mform->addElement('selectyesno','config_alt_show_categories',get_string('show_categories', 'block_mycourse'));
              $mform->setType('config_alt_show_categories', PARAM_BOOL);
          $mform->disabledIf('config_alt_show_categories','config_alt_display','eq',2);

          // parent category
          $mform->addElement('select', 'config_alt_top_category', get_string('top_category', 'block_mycourse'), $this->get_category_options());
              $mform->setType('config_alt_top_category', PARAM_INT);

              // show courses before categories
              $mform->addElement('selectyesno','config_alt_show_courses_before_subcategories',get_string('show_courses_before_subcategories', 'block_mycourse'));
              $mform->setType('config_alt_show_courses_before_subcategories', PARAM_BOOL);

               $mform->addElement('selectyesno','config_alt_category_compact_view',get_string('config_category_compact_view', 'block_mycourse'));
              $mform->setType('config_alt_category_compact_view', PARAM_BOOL);
          $mform->addHelpButton('config_alt_category_compact_view','config_category_compact_view', 'block_mycourse');
          $mform->disabledIf('config_alt_category_compact_view','config_alt_display','eq',2);
          $mform->disabledIf('config_alt_category_compact_view','config_alt_show_categories','eq',0);

        $mform->addElement('html','<hr>');
        $mform->addElement('static', 'alt_show_more_options', '<b>'.get_string('show_more_options', 'block_mycourse').'</b>');

          $mform->addElement('select','config_alt_type',get_string('config_type', 'local_mscore'), $this->get_type_options());
          $mform->setType('config_alt_type', PARAM_INT);
          $mform->setDefault('config_alt_type', 0);
          $mform->disabledIf('config_alt_type','config_alt_display','eq',2);

          //Select for number of shown courses/activities
          $mform->addElement('select', 'config_alt_limit', get_string('config_limit', 'local_mscore'), $this->get_limit_options());
          $mform->setType('config_alt_limit', PARAM_INT);
          $mform->setDefault('config_alt_limit', 3);
          $mform->disabledIf('config_alt_limit','config_alt_display','eq',2);

          $mform->addElement('advcheckbox', 'config_alt_show_more', get_string('config_show_more', 'local_mscore'));
          $mform->setDefault('config_alt_show_more', 0);
          $mform->disabledIf('config_alt_show_more', 'config_alt_limit','eq', 0);
          $mform->disabledIf('config_alt_show_more','config_alt_display','eq',2);

          $mform->addElement('select', 'config_alt_more_limit', get_string('config_more_limit', 'local_mscore'), $this->get_limit_options());
          $mform->setType('config_alt_more_limit', PARAM_INT);
          $mform->setDefault('config_alt_more_limit', 3);
          $mform->disabledIf('config_alt_more_limit', 'config_alt_show_more','eq', 0);
          $mform->disabledIf('config_alt_more_limit', 'config_alt_type','eq', 1);
          $mform->disabledIf('config_alt_more_limit','config_alt_display','eq',2);

        $mform->addElement('html','<hr>');
        $mform->addElement('static', 'alt_course_slider_options', '<b>'.get_string('course_slider_options', 'block_mycourse').'</b>');

          // show courses as slider
              $mform->addElement('selectyesno','config_alt_course_slider',get_string('config_course_slider', 'block_mycourse'));
              $mform->setType('config_alt_course_slider', PARAM_BOOL);
          $mform->disabledIf('config_alt_course_slider','config_alt_show_categories','neq',1);
          $mform->disabledIf('config_alt_course_slider','config_alt_display','eq',2);

          // direction
              $mform->addElement('select', 'config_alt_course_slider_direction', get_string('config_course_slider_direction', 'block_mycourse'), ['horizontal' => get_string('horizontal', 'block_mycourse'),'vertical' => get_string('vertical', 'block_mycourse')]);
              $mform->setType('config_alt_course_slider_direction', PARAM_TEXT);
          $mform->disabledIf('config_alt_course_slider_direction','config_alt_show_categories','neq',1);
          $mform->disabledIf('config_alt_course_slider_direction','config_alt_display','eq',2);
          $mform->disabledIf('config_alt_course_slider_direction','config_alt_course_slider','neq',1);

          // loop
              $mform->addElement('advcheckbox','config_alt_course_slider_loop',get_string('config_course_slider_loop', 'block_mycourse'));
              $mform->setType('config_alt_course_slider_loop', PARAM_BOOL);
          $mform->disabledIf('config_alt_course_slider_loop','config_alt_show_categories','neq',1);
          $mform->disabledIf('config_alt_course_slider_loop','config_alt_display','eq',2);
          $mform->disabledIf('config_alt_course_slider_loop','config_alt_course_slider','neq',1);

          // touch
              $mform->addElement('advcheckbox','config_alt_course_slider_touch',get_string('config_course_slider_touch', 'block_mycourse'));
              $mform->setType('config_alt_course_slider_touch', PARAM_BOOL);
          $mform->setDefault('config_alt_course_slider_touch', true);
          $mform->disabledIf('config_alt_course_slider_touch','config_alt_show_categories','neq',1);
          $mform->disabledIf('config_alt_course_slider_touch','config_alt_display','eq',2);
          $mform->disabledIf('config_alt_course_slider_touch','config_alt_course_slider','neq',1);

          // slides_per_view
              $mform->addElement('select', 'config_alt_course_slider_slides_per_view', get_string('config_course_slider_slides_per_view', 'block_mycourse'), [0 => 'auto', 1 => '1',2 => '2',3 => '3',4 => '4',5 => '5',6 => '6']);
              $mform->setType('config_alt_course_slider_slides_per_view', PARAM_INT);
          $mform->setDefault('config_alt_course_slider_slides_per_view', 4);
          $mform->disabledIf('config_alt_course_slider_slides_per_view','config_alt_show_categories','neq',1);
          $mform->disabledIf('config_alt_course_slider_slides_per_view','config_alt_display','eq',2);
          $mform->disabledIf('config_alt_course_slider_slides_per_view','config_alt_course_slider','neq',1);

          // slides_per_group
              $mform->addElement('select', 'config_alt_course_slider_slides_per_group', get_string('config_course_slider_slides_per_group', 'block_mycourse'), [1 => '1',2 => '2',3 => '3',4 => '4',5 => '5',6 => '6']);
              $mform->setType('config_alt_course_slider_slides_per_group', PARAM_INT);
          $mform->setDefault('config_alt_course_slider_slides_per_group', 1);
          $mform->disabledIf('config_alt_course_slider_slides_per_group','config_alt_show_categories','neq',1);
          $mform->disabledIf('config_alt_course_slider_slides_per_group','config_alt_display','eq',2);
          $mform->disabledIf('config_alt_course_slider_slides_per_group','config_alt_course_slider','neq',1);

          $mform->addElement('html','<hr>');
          $mform->addElement('static', 'alt_filter_options', '<b>'.get_string('filter_options', 'block_mycourse').'</b>');

          // filter completion
              $mform->addElement('select','config_alt_filter_completion',get_string('filter_completion', 'block_mycourse'),$this->get_completion_filter_options());
              $mform->setType('config_alt_filter_completion', PARAM_INT);

              // filter completion: courses without configured module completion
              $mform->addElement('selectyesno','config_alt_filter_completion_hide_courses_without_configured_module_completion',get_string('filter_completion_hide_courses_without_configured_module_completion', 'block_mycourse'));
              $mform->setType('config_alt_filter_completion_hide_courses_without_configured_module_completion', PARAM_BOOL);
              $mform->disabledIf('config_alt_filter_completion_hide_courses_without_configured_module_completion', 'config_alt_filter_completion', 'eq', 0);
              $mform->disabledIf('config_alt_filter_completion_hide_courses_without_configured_module_completion', 'config_alt_filter_completion', 'eq', 1);

               // filter course group
              $mform->addElement('select','config_alt_filter_course_group',get_string('filter_course_group', 'block_mycourse'),$this->get_filter_course_group_options());
              $mform->setType('config_alt_filter_course_group', PARAM_INT);

              // filter course group name
              $mform->addElement('text','config_alt_filter_course_group_idnumber',get_string('filter_course_group_idnumber', 'block_mycourse'));
              $mform->setType('config_alt_filter_course_group_idnumber', PARAM_TEXT);
          $mform->disabledIf('config_alt_filter_course_group_idnumber', 'config_alt_filter_course_group', 'eq', 0);

                // filter course role
              $mform->addElement('select','config_alt_filter_course_role',get_string('config_filter_course_role', 'block_mycourse'),$this->get_filter_course_roles());
              $mform->setType('config_alt_filter_course_role', PARAM_INT);

          //exclude categories
          $mform->addElement('autocomplete', 'config_alt_exclude_categories', get_string('exclude_categories', 'block_mycourse'), $this->get_category_options(true),$autocompleteoptions);
               $mform->setType('config_alt_exclude_categories', PARAM_INT);

        $mform->addElement('html','<hr>');
        $mform->addElement('static', 'alt_course_progress_options', '<b>'.get_string('course_progress_options', 'block_mycourse').'</b>');

              // show progress
              $mform->addElement('selectyesno','config_alt_show_progress',get_string('show_progress', 'block_mycourse'));
              $mform->setType('config_alt_show_progress', PARAM_BOOL);

              // progress type
              $mform->addElement('select','config_alt_progress_type',get_string('progress_type', 'block_mycourse'),$this->get_progress_type_options());
              $mform->setType('config_alt_progress_type', PARAM_INT);

          // progress filter
              $mform->addElement('select','config_alt_filter_progress',get_string('filter_progress', 'block_mycourse'),$this->get_filter_progress_options());
              $mform->setType('config_alt_filter_progress', PARAM_INT);

          //language filter
        if(block_mycourse\helper::language_installed()) {
            $mform->addElement('select','config_alt_language_restriction',get_string('language_restriction', 'block_mycourse'),$this->get_language_restriction_options());
            $mform->setType('config_alt_language_restriction', PARAM_TEXT);
        }

    }

    /**
     * @return string[]
     */
    public function validation($data, $files): array {

            $errors = parent::validation($data, $files);

        if(!empty($data['config_css_classes'])) {

            if(!preg_match('/^[a-zA-Z0-9_\-\s]*$/', $data['config_css_classes'])) {
                $errors['config_css_classes'] = get_string('notvalidcharacters', 'block_mycourse');
            }
        }

            // cache_helper::purge_by_definition('block_mycourse', 'filtered_courses');
            $savestates = new block_mycourse\savestates(1);
            $savestates->cleanup_all();

            return $errors;
    }


    /**
     * @return string[]
     */
    private function get_display_options(): array {

        $display_options = [
            get_string('config_display_list', 'block_mycourse'),
            get_string('config_display_tiles', 'block_mycourse'),
            get_string('config_display_menu', 'block_mycourse')
        ];

        return $display_options;
    }
    /**
     * @return string[]
     */
    private function get_category_options($exclude= 0): array {

        if(!empty($exclude)) {
            $categories = [];
        }
        else {
            $categories = [0 => get_string('all_categories' , 'block_mycourse')];
        }
          $categories += core_course_category::make_categories_list('', 0, ' / ');

         return $categories;
    }

    /**
     * @return string[]
     */
    private function get_course_title_options(): array {

         $title_options = [
            get_string('config_course_title_fullname', 'block_mycourse'),
            get_string('config_course_title_shortname', 'block_mycourse'),
            get_string('config_course_title_both', 'block_mycourse'),
            get_string('hide')
         ];

         return $title_options;
    }

    /**
     * @return string[]
     */
    private function get_completion_filter_options(): array {

         $filter_options = [
            get_string('config_filter_completion_all', 'block_mycourse'),
            get_string('config_filter_completion_completed', 'block_mycourse'),
            get_string('config_filter_completion_uncompleted', 'block_mycourse'),
            get_string('config_filter_completion_uncompleted_without_module_progress', 'block_mycourse'),
            get_string('config_filter_completion_uncompleted_with_module_progress', 'block_mycourse')
         ];

         return $filter_options;
    }

    /**
     * @return string[]
     */
    private function get_filter_progress_options(): array {

        $filter_progress_options = [
            get_string('config_filter_progress_all_modules', 'block_mycourse'),
            get_string('config_filter_progress_required_modules', 'block_mycourse')
        ];

        return $filter_progress_options;
    }

    /**
     * @return string[]
     */
    private function get_filter_progress_current_state_options(): array {

        $filter_progress_options_current_state = [
            get_string('config_filter_progress_current_state_no', 'block_mycourse'),
            get_string('config_filter_progress_current_state_yes', 'block_mycourse')
        ];

         return $filter_progress_options_current_state;
    }

    /**
     * @return string[]
     */
    private function get_progress_type_options(): array {

        $progress_type_options = [
            get_string('config_progress_type_bar', 'block_mycourse'),
            get_string('config_progress_type_pie', 'block_mycourse')
        ];

        return $progress_type_options;
    }

    /**
     * @return string[]
     */
    private function get_filter_course_group_options(): array {

        $filter_course_group_options = [
            get_string('config_filter_course_group_all', 'block_mycourse'),
            get_string('config_filter_course_group_subscribed', 'block_mycourse'),
            get_string('config_filter_course_group_not_subscribed', 'block_mycourse')
        ];

        return $filter_course_group_options;
    }

    /**
     * @return string[]
     */
    private function get_filter_course_roles(): array {

        $filter_course_roles = [];

        $filter_course_roles[0] = get_string('config_filter_all_roles', 'block_mycourse');

        $roles = get_default_enrol_roles(\context_system::instance());

        foreach($roles as $id => $name) {
            $filter_course_roles[$id] = $name;
        }

        return $filter_course_roles;
    }

    /**
     * @return string[]
     */
    private function get_type_options(): array {
        $sort_options = [
            get_string('config_list', 'local_mscore'),
            get_string('config_page', 'local_mscore')
        ];
        return $sort_options;
    }

    /**
     * @return string[]
     */
    private function get_limit_options(): array {

        $limit_options[0] = get_string('config_limit_all', 'local_mscore');
        for ($i = 1; $i <= 25; $i++) {
            $limit_options[$i] = $i;
        }

        return $limit_options;
    }

    /**
     * @return string[]
     */
    private function get_language_restriction_options(): array {

        $language_options = [];

        $language_options[''] = new lang_string('none');
        $language_options['user'] = new lang_string('userlang', 'block_mycourse');
        $language_options += get_string_manager()->get_list_of_translations();

        return $language_options;
    }

}
