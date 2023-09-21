<?php

namespace block_msmycourses2;

use stdClass;
use context_course;
use local_msmetadata_core;
use context_coursecat;
use moodle_url;
use core_course_category;

defined('MOODLE_INTERNAL') || die();

class exporter {

    private $config;

    public function __construct($config) {

        $this->config = $config;
    }

    public function get_coursedata($courseid,$level = 0): object {

        global $CFG;

        require_once($CFG->libdir.'/filelib.php');

        $course = get_course($courseid);
        $context = context_course::instance($course->id);
        $courseinlist = new \core_course_list_element($course);

        $template_object = new stdClass;
        $template_object->id = $course->id;
        $template_object->image = false;
        foreach ($courseinlist->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                $pathcomponents = [
                    '/pluginfile.php',
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename()
                ];
                $path = implode('/', $pathcomponents);
                $image_url = new \moodle_url($path);
                $template_object->image = $image_url->out();
                break;
            }
        }

        switch ($this->config->course_title) {
            case 0: $template_object->title = format_string($course->fullname);
                break;
            case 1: $template_object->title = format_string($course->shortname);
                break;
            case 2: $template_object->title = format_string($course->fullname.' '.$course->shortname);
                break;
            case 3: $template_object->title = '';
        }

        if(!empty($this->config->show_summary)) {
            $course->summary = file_rewrite_pluginfile_urls($course->summary, 'pluginfile.php', $context->id, 'course', 'summary', null);
            $template_object->summary = format_text($course->summary, $course->summaryformat, [], $course->id);
        }
        else {
            $template_object->summary = '';
        }
        $progress = new stdClass;
        $progress_calc = new \local_mscore\progress($courseid, $this->config);
        if($this->config->show_progress) {
            $progress->percentage = $progress_calc->get_progress_percentage();
            $progress->total_modules = $progress_calc->get_total_modules();
            $progress->completed_modules = $progress_calc->get_completed_modules();
            $progress->uncompleted_percentage = 100 - $progress->percentage;
            $progress->is_completed = $progress->percentage == 100;
            $progress->is_started = ($progress->percentage < 100 && $progress->percentage > 0) ?: false;
        }
        else {
            $progress->percentage = null;
            $progress->total_modules = null;
            $progress->completed_modules = null;
            $progress->uncompleted_percentage = null;
            $progress->is_completed = null;
            $progress->is_started = null;

        }

        $template_object->progress = $progress;
        if($progress->percentage === null || empty($this->config->show_progress)) {
            $template_object->show_progress = false;
        }
        else {
            $template_object->show_progress = true;
        }

        if($progress_calc->get_progress_percentage() == 100 && helper::certificate_installed() && $this->config->show_certificate_link) {
            $template_object->certificate_link = $this->get_certificate_link($course);
        }
        else {
            $template_object->certificate_link = false;
        }

        $template_object->progress_type = $this->config->progress_type;

        $template_object->metadata = [];
        if(!empty($this->config->load_metadata) && helper::metadata_installed()) {
            $metadata_raw = local_msmetadata_core::get_course_values($course->id);

            foreach ($metadata_raw as $metadata) {
                $field = new stdClass;
                $field->id = $metadata->id;
                $field->title = $metadata->title;
                $field->values = $metadata->values;
                $template_object->metadata[] = $field;
            }
        }

        $template_object->is_favorite = 0;

        if(!empty($this->config->show_favorites) && helper::favorites_installed()) {
            $template_object->is_favorite = \block_msfavorites_helper::is_favorite_course($courseid) ? 'course_is_favorite' : 'course_not_favorite';
        }
        $template_object->savestate_open = false;
        $template_object->is_course = true;
        $template_object->level = $level;
        $template_object->path = null;
        $template_object->available_languages = [];

        if($this->config->show_available_languages) {
            $format = course_get_format($course);

            if($format->get_format() == 'msmultiformat') {
                $template_object->available_languages = $format->available_languages_context()->languages;

                if (!is_array($template_object->available_languages)) {
                    $template_object->available_languages = [];
                }

                foreach($template_object->available_languages as $language) {
                    $language->icon_url = $language->icon_url->out(false);
                }
            }
        }

        $template_object->has_languages = empty($template_object->available_languages) ?: true;

        if(!empty($this->config->category_compact_view)) {
            $category = core_course_category::get($course->category);
            $pathstring = $category->__get('path');
            $path = explode('/', $pathstring);
            $categories = [];
            $top_category = core_course_category::get($this->config->top_category);
            $valid_categories = $top_category->get_all_children_ids();
            $valid_categories[] = $this->config->top_category;
            foreach($path as $key => $element) {
                if(in_array($element,$valid_categories)) {
                    $category = core_course_category::get($element);
                    $categories[] = $category->__get('name');
                }
            }
            unset($categories[0]);
            $template_object->path = implode(' > ',$categories);
        }

        return $template_object;

    }

    public function get_categorydata($category,$level,$savestate): stdClass {

        global $CFG;

        require_once($CFG->libdir.'/filelib.php');

        $template_object = new stdClass;

        $context = context_coursecat::instance($category->__get('id'));

        $template_object->is_course = false;
        $template_object->title = $category->__get('name');
        $template_object->summary = '';
        $template_object->id = $category->__get('id');
        $template_object->level = $level;
        $template_object->metadata = [];
        $template_object->available_languages = [];
        $template_object->has_languages = false;
        $template_object->show_progress = false;
        $template_object->progress_type = null;
        $template_object->is_favorite = 0;
        $summary = file_rewrite_pluginfile_urls($category->__get('description'), 'pluginfile.php', $context->id, 'coursecat', 'description', null);
        $template_object->summary = format_text($summary, $category->__get('descriptionformat'));
        $template_object->savestate_open = false;
        $template_object->path = null;

        if(!empty($savestate)) {
            if(!empty($savestate->get_savestate('collapse',$category->__get('id')))) {
                $template_object->savestate_open = true;
            }
        }

        return $template_object;
    }

    private function get_certificate_link($course): ?object {

        $modinfo = get_fast_modinfo($course);

        $cms = $modinfo->get_cms();

        foreach($cms as $cmid => $cm) {
            if($cm->__get('modname') == 'mscertificate') {
                return $cmid;
            }
        }

        return null;
    }
}
