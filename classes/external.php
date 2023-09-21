<?php

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");

class block_msmycourses2_external extends external_api {

    public static function show_more_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of msmycourses2 block', VALUE_DEFAULT),
            'offset'  => new external_value(PARAM_INT, 'result offset', VALUE_DEFAULT),
            'prev'    => new external_value(PARAM_INT, 'whether to go back',VALUE_DEFAULT,0),
            'current_view' => new external_value(PARAM_INT, 'standard or alternate display type',VALUE_DEFAULT,0),
            'switch_view' => new external_value(PARAM_INT, 'switch or keep display type',VALUE_DEFAULT,0),
            'load_savestate' => new external_value(PARAM_BOOL, 'whether a savestate should be loaded',VALUE_DEFAULT,0),
        ]);
    }

    /**
     * @return string[]
     */
    public static function show_more($blockid,$offset,$prev,$current_view,$switch_view,$load_savestate): array {

        global $DB;

        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);
        $alt_view = false;

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
                  return false;
        }

        if (!$block = block_instance('msmycourses2', $instance)) {
                  return false;
        }

        if(!empty($switch_view)) {
            if(empty($current_view)) {
                $alt_view = true;
                if($block->config->alt_type == 0) {
                    if($block->config->alt_limit == 0) {
                        $count = 0;
                    }
                    else {
                        $count = max($offset,$block->config->alt_limit);
                    }
                }
                else {
                    $count = $block->config->alt_limit;
                }
            }
            else {
                if($block->config->type == 0) {
                    if($block->config->limit == 0) {
                        $count = 0;
                    }
                    else {
                        $count = max($offset,$block->config->limit);
                    }
                }
                else {
                    $count = $block->config->limit;
                }
            }
              $offset = 0;
        }
        else {
            if(!empty($current_view)) {
                $alt_view = true;
                $count = $block->config->alt_type == 0 ? $block->config->alt_more_limit : $block->config->alt_limit;
            }
            else {
                $count = $block->config->type == 0 ? $block->config->more_limit : $block->config->limit;
            }
        }

        $builder = new block_msmycourses2\builder($block->config,$blockid,$alt_view,$switch_view,$load_savestate);
        return $builder->generate_output($count,$offset,$prev);

    }

    public static function show_more_returns(): external_function_parameters {
        return new external_function_parameters([
            'records'  => new external_multiple_structure(new external_single_structure([
                'id'                    => new external_value(PARAM_INT, 'Course/Category Id'),
                'image'                 => new external_value(PARAM_TEXT, 'Course image',VALUE_OPTIONAL),
                'title'                  => new external_value(PARAM_TEXT, 'Course/Category name'),
                'summary'               => new external_value(PARAM_RAW, 'Course summary'),
                'show_progress'         => new external_value(PARAM_BOOL, 'show course progress'),
                'savestate_open'         => new external_value(PARAM_BOOL, 'whether category open is saved'),
                'progress_type'          => new external_value(PARAM_INT, 'progress diagram type'),
                'path'                  => new external_value(PARAM_TEXT, 'category path'),
                'certificate_link'       => new external_value(PARAM_TEXT, 'certificate download link',VALUE_DEFAULT),
                'progress'               => new external_single_structure([
                    'percentage' => new external_value(PARAM_INT, 'Percentage'),
                    'total_modules' => new external_value(PARAM_INT, 'total modules'),
                    'completed_modules' => new external_value(PARAM_INT, 'completed modules'),
                    'uncompleted_percentage' => new external_value(PARAM_INT, 'uncompleted Percentage'),
                    'is_completed' => new external_value(PARAM_BOOL, 'course is complete'),
                    'is_started' => new external_value(PARAM_BOOL, 'course is started'),
                ],'progress_object',VALUE_DEFAULT),
                'metadata'              => new external_multiple_structure(new external_single_structure([
                    'id'                     => new external_value(PARAM_INT, 'Metadata field Id'),
                    'title'                  => new external_value(PARAM_TEXT, 'Metadata field title'),
                    'values'                 => new external_multiple_structure(new external_single_structure([
                        'id'                   => new external_value(PARAM_INT, 'Metadata value Id'),
                        'value'                => new external_value(PARAM_TEXT, 'Metadata field value'),
                    ],'value array', VALUE_DEFAULT)),
                ],'metadata',VALUE_DEFAULT)),
                'available_languages'       => new external_multiple_structure(new external_single_structure([
                    'lang' => new external_value(PARAM_TEXT, 'language',VALUE_DEFAULT),
                    'lang_name' => new external_value(PARAM_TEXT, 'language name',VALUE_DEFAULT),
                    'name' => new external_value(PARAM_TEXT, 'name',VALUE_DEFAULT),
                    'icon_url' => new external_value(PARAM_RAW, 'icon url',VALUE_DEFAULT),
                ],'available_languages object',VALUE_DEFAULT)),
                'is_favorite' => new external_value(PARAM_TEXT, 'course is favorite'),
                'is_course' => new external_value(PARAM_BOOL, 'object is a course'),
                'level' => new external_value(PARAM_INT, 'tree level', VALUE_OPTIONAL),
                'has_languages' => new external_value(PARAM_BOOL, 'whether the course has langauges set'),
            ],'course object',VALUE_DEFAULT)),
            'prev'        => new external_value(PARAM_BOOL, 'whether a previous arrow is shown',VALUE_REQUIRED),
            'slider'   => new external_value(PARAM_BOOL, 'whether course slider is active',VALUE_REQUIRED),
            'slider_config' => new external_single_structure([
                'direction'                => new external_value(PARAM_TEXT, 'slider direction'),
                'loop'                     => new external_value(PARAM_BOOL, 'enable looping'),
                'touch'                    => new external_value(PARAM_BOOL, 'enable touch control'),
                'slides_per_view'          => new external_value(PARAM_TEXT, 'number of courses shown'),
                'slides_per_group'         => new external_value(PARAM_INT, 'number of courses per click'),
                'initial'                  => new external_value(PARAM_INT, 'initial course'),
                'looped_slides'            => new external_value(PARAM_INT, 'number of looped slides'),
            ],'slider_config',VALUE_DEFAULT),
            'has_courses'     => new external_value(PARAM_INT, 'number of courses',VALUE_DEFAULT,0),
            'children'     => new external_multiple_structure(new external_single_structure([
                'id'                    => new external_value(PARAM_INT, 'Course/Category Id'),
                'title'                  => new external_value(PARAM_TEXT, 'Course/Category name'),
                'summary'               => new external_value(PARAM_RAW, 'Course summary',VALUE_OPTIONAL),
                'level' => new external_value(PARAM_INT, 'tree level', VALUE_OPTIONAL),
                'savestate_open'         => new external_value(PARAM_BOOL, 'whether category open is saved'),
            ],'children category object',VALUE_DEFAULT)),
            'header'    => new external_value(PARAM_TEXT, 'header category name',VALUE_DEFAULT),
            'next'        => new external_value(PARAM_BOOL, 'whether a more button or next arrow is shown',VALUE_REQUIRED),
            'current'     => new external_value(PARAM_INT, 'current offset',VALUE_REQUIRED),
            'num_tiles'   => new external_value(PARAM_TEXT, 'number of tiles',VALUE_DEFAULT),
            'enable_compact_view' => new external_value(PARAM_BOOL, 'whether compact view is allowed',VALUE_REQUIRED),
            'is_compact_view' => new external_value(PARAM_BOOL, 'whether compact view is currently shown',VALUE_REQUIRED),
            'show_favorites' => new external_value(PARAM_BOOL, 'whether a favorite star is shown',VALUE_DEFAULT,0),
            'show_available_languages' => new external_value(PARAM_BOOL, 'whether available languages are shown',VALUE_DEFAULT,0),
            'remove_activities' => new external_value(PARAM_BOOL, 'whether all favorited activizes are removed',VALUE_DEFAULT,0),
            'two_icon_switch' => new external_value(PARAM_BOOL, 'whether switch view is shown with 1 or 2 icons',VALUE_DEFAULT,0),
            'show_alt_view' => new external_value(PARAM_INT,'whether to show the switch view button', VALUE_DEFAULT),
            'current_view' => new external_value(PARAM_INT,'whether we currently view normal or alternate', VALUE_DEFAULT),
            'target_view'  => new external_value(PARAM_TEXT,'target view type for switching views', VALUE_DEFAULT),
            'target_display'  => new external_value(PARAM_TEXT,'target display type for switching views', VALUE_DEFAULT),
            'display_type' => new external_value(PARAM_TEXT, 'display type', VALUE_REQUIRED)
        ]);
    }


    public static function load_menu_category_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of msmycourses2 block', VALUE_DEFAULT),
            'category_id'  => new external_value(PARAM_INT, 'result offset', VALUE_DEFAULT),
            'current_view' => new external_value(PARAM_INT, 'standard or alternate display type',VALUE_DEFAULT,0),
            'switch_view' => new external_value(PARAM_INT, 'switch or keep display type',VALUE_DEFAULT,0)
        ]);
    }

    /**
     * @return string[]
     */
    public static function load_menu_category($blockid, $category_id,$current_view,$switch_view): array {

        global $DB;

        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
                  return false;
        }

        if (!$block = block_instance('msmycourses2', $instance)) {
                  return false;
        }

        $alt_view = false;

        if(!empty($switch_view)) {
            if(!empty($current_view)) {
                $category_id = $block->config->top_category;
                if(!empty($block->config->database)) {
                    $savestate = new block_msmycourses2\savestates($blockid,$alt_view);
                    if(!empty($record = $savestate->get_menu_savestate())) {
                        $category_id = $record->category;
                    }
                }
            }
            else {
                $category_id = $block->config->alt_top_category;
                $alt_view = true;
                if(!empty($block->config->database)) {
                    $savestate = new block_msmycourses2\savestates($blockid,$alt_view);
                    if(!empty($record = $savestate->get_menu_savestate(1))) {
                        $category_id = $record->category;
                    }
                }
            }
        }
        else {
            if(!empty($current_view)) {
                $alt_view = true;
            }
        }

        $builder = new block_msmycourses2\builder($block->config,$blockid,$alt_view,$switch_view,false);
        return $builder->load_menu_category($category_id);
    }

    public static function load_menu_category_returns(): external_function_parameters {
        return new external_function_parameters([
            'show_back' => new external_value(PARAM_INT, 'show back button'),
            'parent_id'    => new external_value(PARAM_INT, 'parent Id'),
            'header'    => new external_value(PARAM_TEXT, 'Current Category name'),
            'current'     => new external_value(PARAM_INT, 'current offset',VALUE_REQUIRED),
            'num_tiles' => new external_value(PARAM_TEXT, 'number of tiles per row'),
            'show_favorites' => new external_value(PARAM_BOOL, 'whether a favorite star is shown',VALUE_DEFAULT,0),
            'show_available_languages' => new external_value(PARAM_BOOL, 'whether available languages are shown',VALUE_DEFAULT,0),
            'remove_activities' => new external_value(PARAM_BOOL, 'whether all favorited activizes are removed',VALUE_DEFAULT,0),
            'two_icon_switch' => new external_value(PARAM_BOOL, 'whether switch view is shown with 1 or 2 icons',VALUE_DEFAULT,0),
            'show_alt_view' => new external_value(PARAM_INT,'whether to show the switch view button', VALUE_DEFAULT),
            'current_view' => new external_value(PARAM_INT,'whether we currently view normal or alternate', VALUE_DEFAULT),
            'target_view'  => new external_value(PARAM_TEXT,'target view type for switching views', VALUE_DEFAULT),
            'target_display'  => new external_value(PARAM_TEXT,'target display type for switching views', VALUE_DEFAULT),
            'display_type' => new external_value(PARAM_TEXT, 'display type', VALUE_REQUIRED),
            'records'      => new external_multiple_structure( new external_single_structure([
                'id'                    => new external_value(PARAM_INT, 'Course/Category Id'),
                'image'                 => new external_value(PARAM_TEXT, 'Course image',VALUE_OPTIONAL),
                'title'                  => new external_value(PARAM_TEXT, 'Course/Category name'),
                'savestate_open'         => new external_value(PARAM_BOOL, 'whether category open is saved'),
                'summary'               => new external_value(PARAM_RAW, 'Course summary',VALUE_OPTIONAL),
                'show_progress'         => new external_value(PARAM_BOOL, 'show course progress',VALUE_OPTIONAL),
                'progress_type'          => new external_value(PARAM_INT, 'progress diagram type'),
                'path'                  => new external_value(PARAM_TEXT, 'category path'),
                'certificate_link'       => new external_value(PARAM_TEXT, 'certificate download link',VALUE_DEFAULT),
                'progress'               => new external_single_structure([
                    'percentage' => new external_value(PARAM_INT, 'Percentage'),
                    'total_modules' => new external_value(PARAM_INT, 'total modules'),
                    'completed_modules' => new external_value(PARAM_INT, 'completed modules'),
                    'uncompleted_percentage' => new external_value(PARAM_INT, 'uncompleted Percentage'),
                    'is_completed' => new external_value(PARAM_BOOL, 'course is complete'),
                    'is_started' => new external_value(PARAM_BOOL, 'course is started'),
                ],'progress_object',VALUE_DEFAULT),
                'available_languages'    => new external_multiple_structure(new external_single_structure([
                    'lang' => new external_value(PARAM_TEXT, 'language',VALUE_DEFAULT),
                    'lang_name' => new external_value(PARAM_TEXT, 'language name',VALUE_DEFAULT),
                    'name' => new external_value(PARAM_TEXT, 'name',VALUE_DEFAULT),
                    'icon_url' => new external_value(PARAM_RAW, 'icon url',VALUE_DEFAULT),
                ],'available_languages object',VALUE_DEFAULT)),
                'metadata'              => new external_multiple_structure(new external_single_structure([
                    'id'                     => new external_value(PARAM_INT, 'Metadata field Id'),
                    'title'                  => new external_value(PARAM_TEXT, 'Metadata field title'),
                    'values'                 => new external_multiple_structure(new external_single_structure([
                        'id'                   => new external_value(PARAM_INT, 'Metadata value Id'),
                        'value'                => new external_value(PARAM_TEXT, 'Metadata field value'),
                    ],'value array', VALUE_DEFAULT)),
                ],'metadata',VALUE_DEFAULT)),
                'is_favorite' => new external_value(PARAM_TEXT, 'course is favorite'),
                'is_course' => new external_value(PARAM_BOOL, 'object is a course'),
                'has_languages' => new external_value(PARAM_BOOL, 'whether the course has langauges set'),
            ],'category/course object',VALUE_DEFAULT))
        ]);
    }

    public static function load_category_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of msmycourses2 block', VALUE_DEFAULT),
            'category_id'  => new external_value(PARAM_INT, 'result offset', VALUE_DEFAULT),
            'level' => new external_value(PARAM_INT, 'hierarchy level', VALUE_DEFAULT),
            'current_view' => new external_value(PARAM_INT, 'standard or alternate display type',VALUE_DEFAULT,0),
            'load_savestate'        => new external_value(PARAM_BOOL, 'whether a previous arrow is shown',VALUE_DEFAULT,0),
            'use_compact_view'        => new external_value(PARAM_BOOL, 'whether a previous arrow is shown',VALUE_DEFAULT,0),
            'load_compact_savestate'        => new external_value(PARAM_BOOL, 'whether a previous arrow is shown',VALUE_DEFAULT,0),
        ]);
    }

    /**
     * @return string[]
     */
    public static function load_category($blockid, $category_id,$level,$current_view,$load_savestate = false,$use_compact_view = false,$load_compact_savestate = true): array {

        global $DB;

        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
                  return false;
        }

        if (!$block = block_instance('msmycourses2', $instance)) {
                  return false;
        }

        $alt_view = false;

        if(empty($count)) {
            $count = 0;
        }
        if(!empty($current_view)) {
                  $alt_view = true;
        }

        $builder = new block_msmycourses2\builder($block->config,$blockid,$alt_view,false,$load_savestate,$use_compact_view,$load_compact_savestate);
        return $builder->load_category($category_id,$level,0,$count,$offset,$prev);
    }

    public static function load_category_returns(): external_function_parameters {
        return new external_function_parameters([
            'show_favorites' => new external_value(PARAM_BOOL, 'whether a favorite star is shown',VALUE_DEFAULT,0),
            'remove_activities' => new external_value(PARAM_BOOL, 'whether all favorited activizes are removed',VALUE_DEFAULT,0),
            'show_available_languages' => new external_value(PARAM_BOOL, 'whether available languages are shown',VALUE_DEFAULT,0),
            'slider'   => new external_value(PARAM_BOOL, 'whether course slider is used',VALUE_REQUIRED),
            'slider_config' => new external_single_structure([
                'direction'                => new external_value(PARAM_TEXT, 'slider direction'),
                'loop'                     => new external_value(PARAM_BOOL, 'enable looping'),
                'touch'                    => new external_value(PARAM_BOOL, 'enable touch control'),
                'slides_per_view'          => new external_value(PARAM_TEXT, 'number of courses shown'),
                'slides_per_group'         => new external_value(PARAM_INT, 'number of courses per click'),
                'initial'                  => new external_value(PARAM_INT, 'initial course'),
                'looped_slides'            => new external_value(PARAM_INT, 'number of looped slides'),
            ],'slider_config',VALUE_DEFAULT),
            'category_id' => new external_value(PARAM_INT, 'category_id',VALUE_DEFAULT,0),
            'has_courses'     => new external_value(PARAM_INT, 'number of courses',VALUE_DEFAULT,0),
            'enable_compact_view' => new external_value(PARAM_BOOL, 'whether compact view is allowed',VALUE_REQUIRED),
            'is_compact_view' => new external_value(PARAM_BOOL, 'whether compact view is currently shown',VALUE_REQUIRED),
            'children'     => new external_multiple_structure(new external_single_structure([
                'id'                    => new external_value(PARAM_INT, 'Course/Category Id'),
                'title'                  => new external_value(PARAM_TEXT, 'Course/Category name'),
                'summary'               => new external_value(PARAM_RAW, 'Course summary',VALUE_OPTIONAL),
                'level' => new external_value(PARAM_INT, 'tree level', VALUE_OPTIONAL),
                'savestate_open'         => new external_value(PARAM_BOOL, 'whether category open is saved'),
            ],'children category object',VALUE_DEFAULT)),
            'records'      => new external_multiple_structure(new external_single_structure([
                'id'                    => new external_value(PARAM_INT, 'Course/Category Id'),
                'image'                 => new external_value(PARAM_TEXT, 'Course image',VALUE_OPTIONAL),
                'title'                  => new external_value(PARAM_TEXT, 'Course/Category name'),
                'summary'               => new external_value(PARAM_RAW, 'Course summary',VALUE_OPTIONAL),
                'show_progress'         => new external_value(PARAM_BOOL, 'show course progress',VALUE_OPTIONAL),
                'progress_type'          => new external_value(PARAM_INT, 'progress diagram type'),
                'savestate_open'         => new external_value(PARAM_BOOL, 'whether category open is saved'),
                'certificate_link'       => new external_value(PARAM_TEXT, 'certificate download link',VALUE_DEFAULT),
                'path'                  => new external_value(PARAM_TEXT, 'category path'),
                'progress'               => new external_single_structure([
                    'percentage' => new external_value(PARAM_INT, 'Percentage'),
                    'total_modules' => new external_value(PARAM_INT, 'total modules'),
                    'completed_modules' => new external_value(PARAM_INT, 'uncompleted modules'),
                    'uncompleted_percentage' => new external_value(PARAM_INT, 'uncompleted Percentage'),
                    'is_completed' => new external_value(PARAM_BOOL, 'course is complete'),
                    'is_started' => new external_value(PARAM_BOOL, 'course is started'),
                ],'progress_object',VALUE_DEFAULT),
                'available_languages'       => new external_multiple_structure(new external_single_structure([
                    'lang' => new external_value(PARAM_TEXT, 'language',VALUE_DEFAULT),
                    'lang_name' => new external_value(PARAM_TEXT, 'language name',VALUE_DEFAULT),
                    'name' => new external_value(PARAM_TEXT, 'name',VALUE_DEFAULT),
                    'icon_url' => new external_value(PARAM_RAW, 'icon url',VALUE_DEFAULT),
                ],'available_languages object',VALUE_DEFAULT)),
                'metadata'              => new external_multiple_structure(new external_single_structure([
                    'id'                     => new external_value(PARAM_INT, 'Metadata field Id'),
                    'title'                  => new external_value(PARAM_TEXT, 'Metadata field title'),
                    'values'                 => new external_multiple_structure(new external_single_structure([
                        'id'                   => new external_value(PARAM_INT, 'Metadata value Id'),
                        'value'                => new external_value(PARAM_TEXT, 'Metadata field value'),
                    ],'value array', VALUE_DEFAULT)),
                ],'metadata',VALUE_DEFAULT)),
                    'is_favorite' => new external_value(PARAM_TEXT, 'course is favorite'),
                    'is_course' => new external_value(PARAM_BOOL, 'object is a course'),
                    'has_languages' => new external_value(PARAM_BOOL, 'whether the course has langauges set'),
                    'level' => new external_value(PARAM_INT, 'tree level', VALUE_OPTIONAL),
            ],'category/course object',VALUE_DEFAULT))
        ]);
    }

    public static function initial_load_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of msmycourses2 block', VALUE_DEFAULT),
        ]);
    }

    /**
     * @return string[]
     */
    public static function initial_load($blockid): array {

        global $DB;

        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);
        $alt_view = false;

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
                return false;
        }

        if (!$block = block_instance('msmycourses2', $instance)) {
                return false;
        }
        $alt_view = false;

        if(!empty($block->config->database)) {
            $savestate = new block_msmycourses2\savestates($blockid);
            if(!empty($savestate->get_altview_savestate())) {
                $alt_view = true;
            }
        }

        $builder = new block_msmycourses2\builder($block->config,$blockid,$alt_view);
        $returnarray = $builder->generate_output($block->config->limit,0,false);

        if (empty(count($returnarray['records'])) && empty(count($returnarray['children']))) {
            $returnarray['template_type'] = 'empty';
        }
        else {
            if($block->config->display == 2) {
                $returnarray['template_type'] = 'menu';
            }
            else {
                if($block->config->type == 0) {
                    $returnarray['template_type'] = 'list';
                }
                else {
                    $returnarray['template_type'] = 'page';
                }
            }
        }

        return $returnarray;

    }

    public static function initial_load_returns(): external_function_parameters {
        return new external_function_parameters([
            'records'      => new external_multiple_structure(new external_single_structure([
                'id'                    => new external_value(PARAM_INT, 'Course/Category Id'),
                'image'                 => new external_value(PARAM_TEXT, 'Course image',VALUE_OPTIONAL),
                'title'                  => new external_value(PARAM_TEXT, 'Course/Category name'),
                'summary'               => new external_value(PARAM_RAW, 'Course summary'),
                'show_progress'         => new external_value(PARAM_BOOL, 'show course progress'),
                'savestate_open'         => new external_value(PARAM_BOOL, 'whether category open is saved'),
                'progress_type'          => new external_value(PARAM_INT, 'progress diagram type'),
                'path'                  => new external_value(PARAM_TEXT, 'category path'),
                'certificate_link'       => new external_value(PARAM_TEXT, 'certificate download link',VALUE_DEFAULT),
                'progress'               => new external_single_structure([
                    'percentage' => new external_value(PARAM_INT, 'Percentage'),
                    'total_modules' => new external_value(PARAM_INT, 'total modules'),
                    'completed_modules' => new external_value(PARAM_INT, 'completed modules'),
                    'uncompleted_percentage' => new external_value(PARAM_INT, 'uncompleted Percentage'),
                    'is_completed' => new external_value(PARAM_BOOL, 'course is complete'),
                    'is_started' => new external_value(PARAM_BOOL, 'course is started'),
                ],'progress_object',VALUE_DEFAULT),
                'available_languages'       => new external_multiple_structure(new external_single_structure([
                    'lang' => new external_value(PARAM_TEXT, 'language',VALUE_DEFAULT),
                    'lang_name' => new external_value(PARAM_TEXT, 'language name',VALUE_DEFAULT),
                    'name' => new external_value(PARAM_TEXT, 'name',VALUE_DEFAULT),
                    'icon_url' => new external_value(PARAM_RAW, 'icon url',VALUE_DEFAULT),
                ],'available_languages object',VALUE_DEFAULT)),
                'metadata'              => new external_multiple_structure(new external_single_structure([
                    'id'                     => new external_value(PARAM_INT, 'Metadata field Id'),
                    'title'                  => new external_value(PARAM_TEXT, 'Metadata field title'),
                    'values'                 => new external_multiple_structure(new external_single_structure([
                        'id'                   => new external_value(PARAM_INT, 'Metadata value Id'),
                        'value'                => new external_value(PARAM_TEXT, 'Metadata field value'),
                    ],'value array', VALUE_DEFAULT)),
                ],'metadata',VALUE_DEFAULT)),
                'is_favorite' => new external_value(PARAM_TEXT, 'course is favorite'),
                'is_course' => new external_value(PARAM_BOOL, 'object is a course'),
                'has_languages' => new external_value(PARAM_BOOL, 'whether the course has langauges set'),
                'level' => new external_value(PARAM_INT, 'tree level', VALUE_OPTIONAL),
            ],'course object',VALUE_DEFAULT)),
            'prev'        => new external_value(PARAM_BOOL, 'whether a previous arrow is shown',VALUE_DEFAULT),
            'slider'   => new external_value(PARAM_BOOL, 'whether course slider is active',VALUE_DEFAULT),
            'slider_config' => new external_single_structure([
                'direction'                => new external_value(PARAM_TEXT, 'slider direction'),
                'loop'                     => new external_value(PARAM_BOOL, 'enable looping'),
                'touch'                    => new external_value(PARAM_BOOL, 'enable touch control'),
                'slides_per_view'          => new external_value(PARAM_TEXT, 'number of courses shown'),
                'slides_per_group'         => new external_value(PARAM_INT, 'number of courses per click'),
                'initial'                  => new external_value(PARAM_INT, 'initial course'),
                'looped_slides'            => new external_value(PARAM_INT, 'number of looped slides'),
            ],'slider_config',VALUE_DEFAULT),
            'has_courses'     => new external_value(PARAM_INT, 'number of courses',VALUE_DEFAULT,0),
            'children'     => new external_multiple_structure(new external_single_structure([
                'id'                    => new external_value(PARAM_INT, 'Course/Category Id'),
                'title'                  => new external_value(PARAM_TEXT, 'Course/Category name'),
                'summary'               => new external_value(PARAM_RAW, 'Course summary',VALUE_OPTIONAL),
                'level' => new external_value(PARAM_INT, 'tree level', VALUE_OPTIONAL),
                'savestate_open'         => new external_value(PARAM_BOOL, 'whether category open is saved'),
            ],'children category object',VALUE_DEFAULT)),
            'header'    => new external_value(PARAM_TEXT, 'header category name',VALUE_DEFAULT),
            'next'        => new external_value(PARAM_BOOL, 'whether a more button or next arrow is shown',VALUE_DEFAULT),
            'current'     => new external_value(PARAM_INT, 'current offset',VALUE_REQUIRED),
            'num_tiles'   => new external_value(PARAM_TEXT, 'number of tiles',VALUE_DEFAULT),
            'enable_compact_view' => new external_value(PARAM_BOOL, 'whether compact view is allowed',VALUE_REQUIRED),
            'is_compact_view' => new external_value(PARAM_BOOL, 'whether compact view is currently shown',VALUE_REQUIRED),
            'show_favorites' => new external_value(PARAM_BOOL, 'whether a favorite star is shown',VALUE_DEFAULT,0),
            'show_available_languages' => new external_value(PARAM_BOOL, 'whether available languages are shown',VALUE_DEFAULT,0),
            'remove_activities' => new external_value(PARAM_BOOL, 'whether all favorited activizes are removed',VALUE_DEFAULT,0),
            'two_icon_switch' => new external_value(PARAM_BOOL, 'whether switch view is shown with 1 or 2 icons',VALUE_DEFAULT,0),
            'show_alt_view' => new external_value(PARAM_INT,'whether to show the switch view button', VALUE_DEFAULT),
            'current_view' => new external_value(PARAM_INT,'whether we currently view normal or alternate', VALUE_DEFAULT),
            'target_view'  => new external_value(PARAM_TEXT,'target view type for switching views', VALUE_DEFAULT),
            'target_display'  => new external_value(PARAM_TEXT,'target display type for switching views', VALUE_DEFAULT),
            'display_type' => new external_value(PARAM_TEXT, 'display type', VALUE_REQUIRED),
            'template_type' => new external_value(PARAM_TEXT, 'template type', VALUE_REQUIRED),
        ]);
    }
}
