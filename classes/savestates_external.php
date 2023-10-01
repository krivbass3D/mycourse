<?php

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");

class block_mycourse_savestates_external extends external_api {

    public static function alt_view_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of mycourse block', VALUE_DEFAULT),
        ]);
    }

    /**
     * @return string[]
     */
    public static function alt_view($blockid): ?array {

        global $DB;
        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
            return null;
        }

        if (!$block = block_instance('mycourse', $instance)) {
            return null;
        }

        if(!empty($block->config->database)) {

            $savestate = new block_mycourse\savestates($blockid);

            if(!empty($savestate->get_altview_savestate())) {
                $savestate->remove_savestate('alt_view',0,0);
            }
            else {
                $savestate->add_savestate('alt_view',0);
            }
        }

        return  ['status' => true];

    }

    public static function alt_view_returns(): external_function_parameters {
        return new external_function_parameters([
            'status'  => new external_value(PARAM_BOOL, 'true equals favorite, false equals not favorite')
        ]);
    }

    public static function menu_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of mycourse block', VALUE_DEFAULT),
            'category_id' => new external_value(PARAM_INT, 'currently shown menu category', VALUE_DEFAULT),
            'current_view' => new external_value(PARAM_BOOL, 'standard or alternate display type',VALUE_DEFAULT,0),
        ]);
    }

    /**
     * @return string[]
     */
    public static function menu($blockid,$category_id,$current_view): ?array {

        global $DB;
        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
            return null;
        }

        if (!$block = block_instance('mycourse', $instance)) {
            return null;
        }

        if(!empty($block->config->database)) {

            $savestate = new block_mycourse\savestates($blockid,$current_view);
            $savestate->add_savestate('menu',$category_id);

        }

        return  ['status' => true];
    }

    public static function menu_returns(): external_function_parameters {
        return new external_function_parameters([
            'status'  => new external_value(PARAM_BOOL, 'true equals favorite, false equals not favorite')
        ]);
    }

    public static function page_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of mycourse block', VALUE_DEFAULT),
            'current' => new external_value(PARAM_INT, 'currently shown menu category', VALUE_DEFAULT,0),
            'current_view' => new external_value(PARAM_BOOL, 'standard or alternate display type',VALUE_DEFAULT,0),
        ]);
    }

    /**
     * @return string[]
     */
    public static function page($blockid,$current,$current_view): ?array {

        global $DB;
        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
            return null;
        }

        if (!$block = block_instance('mycourse', $instance)) {
            return null;
        }

        if(!empty($block->config->database)) {

            if(empty($current)) {
                $current = 0;
            }

            $count = $current_view ? $block->config->alt_more_limit : $block->config->more_limit;
            $current -= $count;
            $savestate = new block_mycourse\savestates($blockid,$current_view);
            $savestate->add_savestate('page',0,$current);

        }

        return  ['status' => true];
    }

    public static function page_returns(): external_function_parameters {
        return new external_function_parameters([
            'status'  => new external_value(PARAM_BOOL, 'true equals favorite, false equals not favorite')
        ]);
    }

    public static function collapse_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of mycourse block', VALUE_DEFAULT),
            'category' => new external_value(PARAM_INT, 'category', VALUE_DEFAULT),
            'current_view' => new external_value(PARAM_BOOL, 'standard or alternate display type',VALUE_DEFAULT,0),
            'type' => new external_value(PARAM_TEXT, 'open or close', VALUE_DEFAULT),
        ]);
    }

    /**
     * @return string[]
     */
    public static function collapse($blockid,$category,$current_view,$type): ?array {

        global $DB;
        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
            return null;
        }

        if (!$block = block_instance('mycourse', $instance)) {
            return null;
        }

        if(!empty($block->config->database)) {

            $savestate = new block_mycourse\savestates($blockid,$current_view);

            if($type == 'close') {
                $savestate->remove_savestate('collapse',$category);
            }
            else if($type == 'open') {
                $savestate->add_savestate('collapse',$category);
            }
        }

        return  ['status' => true];
    }

    public static function collapse_returns(): external_function_parameters {
        return new external_function_parameters([
            'status'  => new external_value(PARAM_BOOL, 'true equals favorite, false equals not favorite')
        ]);
    }

    public static function compact_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of mycourse block', VALUE_DEFAULT),
          'category' => new external_value(PARAM_INT, 'category', VALUE_DEFAULT),
          'current_view' => new external_value(PARAM_BOOL, 'standard or alternate display type',VALUE_DEFAULT,0),
          'compact' => new external_value(PARAM_BOOL, 'compact view or not', VALUE_DEFAULT),
        ]);
    }

    /**
     * @return string[]
     */
    public static function compact($blockid,$category,$current_view,$compact): array {

        global $DB;
        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
            return false;
        }

        if (!$block = block_instance('mycourse', $instance)) {
            return false;
        }

        if(!empty($block->config->database)) {

            $savestate = new block_mycourse\savestates($blockid,$current_view);

            if(empty($compact)) {
                $savestate->remove_savestate('compact',$category);
            }
            else {
                $savestate->add_savestate('compact',$category);
            }
        }

        return  ['status' => true];
    }

    public static function compact_returns(): external_function_parameters {
        return new external_function_parameters([
            'status'  => new external_value(PARAM_BOOL, 'true equals favorite, false equals not favorite')
        ]);
    }

    public static function slider_parameters(): external_function_parameters {
        return new external_function_parameters([
            'blockid' => new external_value(PARAM_INT, 'id of mycourse block', VALUE_DEFAULT),
            'category' => new external_value(PARAM_INT, 'category', VALUE_DEFAULT),
            'current_view' => new external_value(PARAM_BOOL, 'standard or alternate display type',VALUE_DEFAULT,0),
            'current' => new external_value(PARAM_INT, 'current element', VALUE_DEFAULT),
        ]);
    }

    /**
     * @return string[]
     */
    public static function slider($blockid,$category,$current_view,$current): ?array {

        global $DB;
        /*These lines validate the context, which is required for the webservice to work. */
        $context = context_block::instance($blockid);
        self::validate_context($context);

        if (!$instance = $DB->get_record('block_instances', ['id' => $blockid])) {
            return null;
        }

        if (!$block = block_instance('mycourse', $instance)) {
            return null;
        }

        if(!empty($block->config->database)) {

            $savestate = new block_mycourse\savestates($blockid,$current_view);

            if(!empty($current)) {
                $savestate->add_savestate('slider',$category, $current);
            }
            else {
                $savestate->remove_savestate('slider',$category);
            }
        }

        return  ['status' => true];

    }

    public static function slider_returns(): external_function_parameters {
        return new external_function_parameters([
            'status'  => new external_value(PARAM_BOOL, 'true equals favorite, false equals not favorite')
        ]);
    }
}
