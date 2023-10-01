<?php

defined('MOODLE_INTERNAL') || die();


function xmldb_block_mycourse_install(): void {

    global $DB;

    if($DB->record_exists('block_instances', ['blockname' => 'msmycourses'])) {
          $instances = $DB->get_records('block_instances', ['blockname' => 'msmycourses']);

        foreach ($instances as $instance) {

            $old_id = $instance->id;
            $instance->blockname = 'mycourse';
            $instance->id = null;
            $instance->timecreated = time();
            $instance->timemodified = time();

            $config = unserialize(base64_decode($instance->configdata));

            if(!empty($config)) {

                if(isset($config->course_title)) {
                    switch($config->course_title) {
                        case 'system':
                        case 'fullname': $config->course_title = '0';
break;
                        case 'shortname': $config->course_title = '1';
break;
                        case 'both': $config->course_title = '2';
break;
                    }
                }
                else {
                    $config->course_title = '0';
                }
                  unset($config->tiles_equal_width);
                  unset($config->filter_progress_current_state);
                  unset($config->collapse);
                  $config->load_metadata = '0';
                  $config->limit = '0';
                  $config->type = '0';
                  $config->show_more = '0';
                  $config->more_limit = '3';

                if(!isset($config->hide_if_empty)) {
                    $config->hide_if_empty = '0';
                }
                if(!isset($config->tiles_per_row)) {
                    $config->tiles_per_row = '0';
                }
                if(!isset($config->show_categories)) {
                    $config->show_categories = '0';
                }
                if(!isset($config->top_category)) {
                    $config->top_category = '0';
                }
                if(!empty($config->category_collapse_all_open)) {
                    $config->category_all_open = '1';
                }
                else {
                    $config->category_all_open = '0';
                }
                if(!isset($config->filter_completion_hide_courses_without_configured_module_completion)) {
                    $config->filter_completion_hide_courses_without_configured_module_completion = '0';
                }
                if(!isset($config->filter_course_group)) {
                    $config->filter_course_group = '0';
                }
                if(!isset($config->filter_course_group_idnumber)) {
                    $config->filter_course_group_idnumber = '';
                }
                if(!isset($config->show_progress)) {
                    $config->show_progress = '0';
                }
                if(!isset($config->progress_type)) {
                    $config->progress_type = '0';
                }
                if(!isset($config->filter_progress)) {
                    $config->filter_progress = '0';
                }

                  $instance->configdata = base64_encode(serialize($config));
            }

            $new_id = $DB->insert_record('block_instances',$instance);

            if($DB->record_exists('block_positions', ['blockinstanceid' => $old_id])) {

                $positions = $DB->get_records('block_positions',['blockinstanceid' => $old_id]);

                foreach($positions as $position) {
                    $position->id = null;
                    $position->blockinstanceid = $new_id;
                    $DB->insert_record('block_positions',$position);
                }
            }

        }
    }

}
