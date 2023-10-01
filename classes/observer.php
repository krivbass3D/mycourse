<?php

defined('MOODLE_INTERNAL') || die();

class block_mycourse_observer {

    public static function user_enrolment_created(\core\event\user_enrolment_created $event): void {

        //   self::clear_cache($event->relateduserid);
          self::clear_user_savestates($event->relateduserid);

    }

    public static function course_module_completion_updated(\core\event\course_module_completion_updated $event): void {

        // self::clear_cache($event->relateduserid);
        self::clear_user_savestates($event->relateduserid);
    }

    public static function module_completed(\core\event\module_completed $event): void {

        // self::clear_cache($event->relateduserid);
        self::clear_user_savestates($event->relateduserid);
    }

    public static function user_enrolment_deleted(\core\event\user_enrolment_deleted $event): void {

        //   self::clear_cache($event->relateduserid);
          self::clear_user_savestates($event->relateduserid);
    }

    public static function groups_member_added(\core\event\groups_member_added $event): void {

        //   self::clear_cache($event->relateduserid);
          self::clear_user_savestates($event->relateduserid);

    }

    public static function groups_member_removed(\core\event\groups_member_removed $event): void {

        //   self::clear_cache($event->relateduserid);
          self::clear_user_savestates($event->relateduserid);
    }

    public static function course_deleted(\core\event\course_deleted $event): void {

        //   cache_helper::purge_by_definition('block_mycourse', 'filtered_courses');
          self::clear_block_savestates($event->objectid);
    }

    public static function course_updated(\core\event\course_updated $event): void {

        //   cache_helper::purge_by_definition('block_mycourse', 'filtered_courses');
          self::clear_block_savestates($event->objectid);
    }

    // private static function clear_cache($id) {

    //     $cache = cache::make('block_mycourse', 'filtered_courses');
    //     $records = self::get_block_instances();

    //     foreach ($records as $record) {
    //         $filtered_courses = $cache->get('filtered_courses_'.$id.'_'.$record->id);

    //          if(!empty($filtered_courses)) {
    //              $result = $cache->delete('filtered_courses_'.$id.'_'.$record->id);
    //          }
    //      }
    // }

    private static function clear_user_savestates($userid): void {

        $savestate = new block_mycourse\savestates(0,0,$userid);
        $savestate->cleanup_userdata();

    }

    private static function clear_block_savestates($courseid): void {

        $records = self::get_block_instances();

        foreach($records as $record) {

            $block = block_instance('mycourse', $record);
            $category = core_course_category::get($block->config->top_category,MUST_EXIST,true,2);
            $all_courses = $category->get_courses(['recursive' => 1,'idonly' => 1]);

            if(in_array($courseid,$all_courses)) {
                $savestate = new block_mycourse\savestates($record->id);
                $savestate->cleanup_blockdata();
            }

            if(!empty($block->config->show_altview)) {
                $category = core_course_category::get($block->config->alt_top_category,MUST_EXIST,true,2);
                $all_courses = $category->get_courses(['recursive' => 1,'idonly' => 1]);

                if(in_array($courseid,$all_courses)) {
                    $savestate = new block_mycourse\savestates($record->id,1);
                    $savestate->cleanup_blockdata();
                }
            }
        }

    }

    /**
     * @return object[]
     */
    private static function get_block_instances(): array {

        global $DB;

        return $DB->get_records('block_instances',['blockname' => 'mycourse'],'id');

    }

}
