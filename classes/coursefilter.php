<?php

namespace block_mycourse;
use core_course_category;
require_once($CFG->libdir.'/completionlib.php');

defined('MOODLE_INTERNAL') || die();

class coursefilter {

    private $config;

    public function __construct($config) {

        $this->config = $config;
    }

    public function filter_course($course): bool {

        if(empty($course->visible)) {
            return false;
        }

        if(empty($this->progress_filter($course->id))) {
            return false;
        }

        if(empty($this->coursegroup_filter($course->id))) {
            return false;
        }

        if(empty($this->category_filter($course->id))) {
            return false;
        }

        return !empty($this->course_role_filter($course->id));
    }

    private function progress_filter($course): bool {
        global $USER;
        $progress = new \local_mscore\progress($course,$this->config);
        $course = \get_course($course);
        $percentage = $progress->get_progress_percentage();

        $completion = new \completion_info($course);
        $completed = $completion->is_course_complete($USER->id);
        if($this->config->filter_completion_hide_courses_without_configured_module_completion && $this->config->filter_completion > 1) {
            if($percentage === null) {
                return false;
            }
        }
        switch($this->config->filter_completion) {
            case 0:
                return true;
            case 1:
                return $completed;
            case 2:
                return !$completed && $percentage < 100;
            case 3:
                return !$completed && $percentage == 0;
            case 4:
                return !$completed && $percentage < 100 && $percentage > 0;
            default:
            return false;
        }
    }

    private function coursegroup_filter($course): bool {

          global $USER,$CFG;

          require_once($CFG->dirroot.'/lib/grouplib.php');

        if(empty($this->config->filter_course_group) || empty($this->config->filter_course_group_idnumber)) {
            return true;
        }

          $groups = groups_get_user_groups($course, $USER->id);
          $target_group = groups_get_group_by_idnumber($course, $this->config->filter_course_group_idnumber);
          $is_member = false;

        if(!empty($target_group)) {
            if(in_array($target_group->id,$groups[0])) {
                $is_member = true;
            }
            else{
                $is_member = false;
            }
        }
        else {
            $is_member = null;
        }

        if($this->config->filter_course_group == 1 && $is_member == true) {
            return true;
        }
        return $this->config->filter_course_group == 2 && $is_member == false;

    }

    private function category_filter($course): bool {

        $course = get_course($course);

        if(!empty($this->config->exclude_categories)) {
            if(in_array($course->category,$this->config->exclude_categories)) {
                return false;
            }
        }

        if(empty($this->config->top_category)) {
            return true;
        }

        if($this->config->top_category == $course->category) {
            return true;
        }

        $category = core_course_category::get($course->category,IGNORE_MISSING);

        if(empty($category)) {
            return false;
        }
        $path = explode('/',$category->__get('path'));

        foreach($path as $category) {
            if($this->config->top_category == $category) {
                return true;
            }
        }

        return false;
    }

    private function course_role_filter($course): bool {

        if(empty($this->config->filter_course_role)) {
            return true;
        }

        $roles = get_user_roles(\context_course::instance($course));

        foreach($roles as $role) {
            if($this->config->filter_course_role == $role->roleid) {
                return true;
            }
        }
        return false;
    }
}
