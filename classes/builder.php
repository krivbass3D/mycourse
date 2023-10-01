<?php

namespace block_mycourse;

use core_course_category;
use cache;

defined('MOODLE_INTERNAL') || die();

class builder {

    private $config;
    private $courses;
    private $filter;
    private $exporter;
    private $filtered_count;
    private $filtered_courses;
    private $block;
    private $alt_view = 0;
    private $savestates = null;
    private $use_compact_view = false;
    private $slider_config;

    public function __construct($config,$blockid,$alt_view = false, $clear_cache=false,$load_savestate = true, $compact_view = false, $load_compact_savestate = true) {

        $this->config = $config;
        $this->courses = enrol_get_my_courses();
        $this->block = $blockid;
        $this->use_compact_view = $compact_view;
        $this->load_compact_savestate = $load_compact_savestate;

        if(!empty($alt_view)) {

            $this->alt_view = 1;
            $this->config->main_display = $this->config->display;
            $this->config->main_type = $this->config->type;
            $this->config->course_title = $this->config->alt_course_title;
            $this->config->show_summary = $this->config->alt_show_summary;
            $this->config->tiles_per_row = $this->config->alt_tiles_per_row;
            $this->config->display = $this->config->alt_display;
            $this->config->top_category = $this->config->alt_top_category;
            $this->config->show_categories = $this->config->alt_show_categories;
            $this->config->show_courses_before_subcategories = $this->config->alt_show_courses_before_subcategories;
            $this->config->category_compact_view = $this->config->alt_category_compact_view;
            $this->config->course_slider = $this->config->alt_course_slider;
            $this->config->course_slider_direction = $this->config->alt_course_slider_direction;
            $this->config->course_slider_loop = $this->config->alt_course_slider_loop;
            $this->config->course_slider_touch = $this->config->alt_course_slider_touch;
            $this->config->course_slider_slides_per_view = $this->config->alt_course_slider_slides_per_view;
            $this->config->course_slider_slides_per_group = $this->config->alt_course_slider_slides_per_group;
            $this->config->type = $this->config->alt_type;
            $this->config->limit = $this->config->alt_limit;
            $this->config->show_more = $this->config->alt_show_more;
            $this->config->more_limit = $this->config->alt_more_limit;
            $this->config->filter_completion = $this->config->alt_filter_completion;
            $this->config->filter_completion_hide_courses_without_configured_module_completion = $this->config->alt_filter_completion_hide_courses_without_configured_module_completion;
            $this->config->filter_course_group = $this->config->alt_filter_course_group;
            $this->config->filter_course_group_idnumber = $this->config->alt_filter_course_group_idnumber;
            $this->config->filter_course_role = $this->config->alt_filter_course_role;
            $this->config->show_progress = $this->config->alt_show_progress;
            $this->config->progress_type = $this->config->alt_progress_type;
            $this->config->filter_progress = $this->config->alt_filter_progress;
            $this->config->language_restriction = $this->config->alt_language_restriction;
            $this->config->exclude_categories = $this->config->alt_exclude_categories;
            $this->config->show_available_languages = $this->config->alt_show_available_languages;
        }

        //   if(!empty($clear_cache)) {
        //       $this->clear_cache();
        //   }

        $this->filter = new coursefilter($config);
        $this->exporter = new exporter($config);
        $this->filtered_courses = $this->get_filtered_courselist();

        if(!empty($this->config->database) && !empty($load_savestate)) {
            $this->savestates = new savestates($this->block,$this->alt_view);
        }

    }

    /**
     * @return string[]
     */
    public function generate_output($count,$offset,$prev,$level = 0): array {

        if(empty($this->filtered_courses)) {
            return $this->make_dummy();
        }

        if(!empty($this->savestates) && $this->config->type == 1) {
            if(!empty($record = $this->savestates->get_page_savestate())) {
                $offset = $record->value;
            }
        }

        if($this->config->display == 2) {
            $category = $this->config->top_category;
            if(!empty($this->savestates)) {
                if(!empty($record = $this->savestates->get_menu_savestate())) {
                    $category = $record->category;
                }
            }
            $output = $this->load_menu_category($category);

        }
        else if($this->config->show_categories == 1) {
            if(!empty($this->config->course_slider)) {
                $output = $this->get_categorylist_slider($count,$offset,$prev,$level);
            }
            else {
                $output = $this->get_categorylist($count,$offset,$prev,$level);
            }
        }
        else {
            $output = $this->get_courselist($count,$offset,$prev);
        }

        return $output;
    }

    /**
     * @return string[]
     */
    private function get_courselist($count,$offset,$prev): array {

        if(empty($this->config->show_more) || $count == 0) {
            $show_more = 0;
        }
        else {
            $show_more = 1;
        }

        $this->config->course_slider = 0;

        if(empty($prev)) {
            $records = $this->make_courselist($count,$offset);
            $show_prev = $offset != 0;
            $show_next = !$this->end_reached($count,$offset);
            $current = min($this->filtered_count,$count + $offset);
        }
        else {
            $records = $this->make_courselist($count,$offset - $count * 2);
            $show_prev = $offset > $count * 2;
            $show_next = true;
            $current = max($count,$offset - $count);
        }

        /*if(empty($records)) {
            return false;
        }*/

        $template_context = [
            'records' => array_values($records),
            'num_tiles' => $this->config->tiles_per_row ? ' tiles_per_row'.$this->config->tiles_per_row : '',
            'show_alt_view' => $this->config->show_altview,
            'current_view' => $this->alt_view,
            'target_view' => $this->get_target_view(),
            'show_favorites' => $this->config->show_favorites,
            'show_available_languages' => $this->config->show_available_languages,
            'two_icon_switch' => $this->config->two_icon_switch,
            'remove_activities' => $this->config->remove_activities,
            'enable_compact_view' => false,
            'is_compact_view' => false,
            'slider' => $this->config->course_slider,
            'slider_config' => $this->get_slider_config($this->get_slider_savestate()),
            'children' => [],
            'prev' => $show_more == 0 ? false : $show_prev,
            'next' => $show_more == 0 ? false : $show_next,
            'current' => $current,
            'display_type' => $this->config->display == 0 ? 'list' : 'tiles',
            'target_display' => $this->get_target_display()
        ];

        return $template_context;

    }

    /**
     * @return string[]
     */
    private function make_courselist($count,$offset): array {

        $output = [];

        if($offset < 0) {
            $offset = 0;
        }

        foreach($this->filtered_courses as $course) {
            $output[] = $this->exporter->get_coursedata($course);
        }
            $this->filtered_count = count($this->filtered_courses);

        if(!empty($count)) {
            $output = array_slice($output,$offset,$count);
        }
        else {
            $output = array_slice($output,$offset);
        }

        return $output;
    }

    /**
     * @return string[]
     */
    private function get_categorylist($count,$offset,$prev,$level): array {

        if(empty($this->config->show_more) || $count == 0) {
            $show_more = 0;
        }
        else {
            $show_more = 1;
        }

        if(empty($prev)) {
            $records = $this->make_categorylist($this->config->top_category, $count,$offset,$level,!$this->use_compact_view);
            $show_prev = $offset != 0;
            $show_next = !$this->end_reached($count,$offset);
            $current = min($this->filtered_count,$count + $offset);
        }
        else {
            $records = $this->make_categorylist($this->config->top_category, $count,$offset - $count * 2,$level,!$this->use_compact_view);
            $show_prev = $offset > $count * 2;
            $show_next = true;
            $current = max($count,$offset - $count);
        }

        $display_type = $this->get_display_type();

        if(empty($level) && $this->config->display != 2) {
            $header = $this->make_category_header();
        }
        else {
            $header = false;
        }

        /*if(empty($records)) {
            return false;
        }*/

        $template_context = [
            'header' => $header,
            'show_alt_view' => $this->config->show_altview,
            'current_view' => $this->alt_view,
            'target_view' => $this->get_target_view(),
            'num_tiles' => $this->config->tiles_per_row ? ' tiles_per_row'.$this->config->tiles_per_row : '',
            'show_favorites' => $this->config->show_favorites,
            'show_available_languages' => $this->config->show_available_languages,
            'remove_activities' => $this->config->remove_activities,
            'two_icon_switch' => $this->config->two_icon_switch,
            'slider' => $this->config->course_slider,
            'slider_config' => $this->get_slider_config($this->get_slider_savestate()),
            'enable_compact_view' => $this->config->category_compact_view,
            'is_compact_view' => $this->use_compact_view,
            'children' => [],
            'records' => array_values($records),
            'prev' => $show_more == 0 ? false : $show_prev,
            'next' => $show_more == 0 ? false : $show_next,
            'current' => $current,
            'display_type' => $display_type,
            'target_display' => $this->get_target_display()
        ];

        return $template_context;
    }
    /**
     * @return string[]
     */
    private function get_categorylist_slider($count,$offset,$prev,$level): array {

        $top_category = core_course_category::get($this->config->top_category);
        $categories = $top_category->get_children();

        $children = [];

        foreach($categories as $category) {
            $child_courses = $category->get_courses(['recursive' => 1,'idonly' => 1]);
            if(!empty($child_courses)) {
                $intersect = array_intersect($this->filtered_courses,$child_courses);
            }
            else {
                $intersect = false;
            }
            if(!empty($intersect)) {
                $children[] = $this->exporter->get_categorydata($category,$level,$this->savestates);
            }
        }

        $this->filtered_count = count($children);

        if(empty($this->config->show_more) || $count == 0) {
            $show_more = 0;
        }
        else {
            $show_more = 1;
        }

        if(empty($prev)) {
            $show_prev = $offset != 0;
            $show_next = !$this->end_reached($count,$offset);
            $current = min($this->filtered_count,$count + $offset);
        }
        else {
            $show_prev = $offset > $count * 2;
            $show_next = true;
            $current = max($count,$offset - $count);
            $offset = $offset - $count * 2;
        }

        if(!empty($count)) {
            $children = array_slice($children,$offset,$count);
        }
        $records = $this->load_category($this->config->top_category,$level,1);

        $display_type = $this->get_display_type();

        $template_context = [
            'header' => $this->make_category_header(),
            'show_alt_view' => $this->config->show_altview,
            'current_view' => $this->alt_view,
            'target_view' => $this->get_target_view(),
            'num_tiles' => $this->config->tiles_per_row ? ' tiles_per_row'.$this->config->tiles_per_row : '',
            'show_favorites' => $this->config->show_favorites,
            'show_available_languages' => $this->config->show_available_languages,
            'remove_activities' => $this->config->remove_activities,
            'two_icon_switch' => $this->config->two_icon_switch,
            'slider' => $this->config->course_slider,
            'slider_config' => $this->get_slider_config($this->get_slider_savestate()),
            'enable_compact_view' => $this->config->category_compact_view,
            'is_compact_view' => $this->use_compact_view,
            'children' => array_values($children),
            'records' => array_values($records),
            'has_courses' => count($records),
            'prev' => $show_more == 0 ? false : $show_prev,
            'next' => $show_more == 0 ? false : $show_next,
            'current' => $current,
            'display_type' => $display_type,
            'target_display' => $this->get_target_display()
        ];

        return $template_context;
    }

    /**
     * @return string[]
     */
    private function make_categorylist($category_id,$count,$offset,$level,$show_categories = true): array {

        $output = [];

        if($offset < 0) {
            $offset = 0;
        }

        $level++;

        $category = core_course_category::get($category_id);

        $all_courses = $category->get_courses(['recursive' => 1,'idonly' => 1]);
        if(!empty($all_courses)) {
            $intersect = array_intersect($this->filtered_courses,$all_courses);
        }
        else {
            $intersect = false;
        }
        if(empty($intersect)) {
            return false;
        }

        if(empty($this->use_compact_view)) {
            $direct_courses = $category->get_courses(['idonly' => 1]);
            if(!empty($direct_courses)) {
                $intersect = array_intersect($this->filtered_courses,$direct_courses);
            }
            else {
                $intersect = false;
            }
        }
        else {
            $show_categories = false;
        }

        $courses = [];

        if(!empty($intersect)) {
            foreach($intersect as $course) {
                $courses[] = $this->exporter->get_coursedata($course,$level);
            }
        }
          $categories = [];

        if(!empty($show_categories)) {
            $children = $category->get_children();

            foreach($children as $child) {

                $child_courses = $child->get_courses(['recursive' => 1,'idonly' => 1]);
                if(!empty($child_courses)) {
                    $intersect = array_intersect($this->filtered_courses,$child_courses);
                }
                else {
                    $intersect = false;
                }
                if(!empty($intersect)) {
                    $categories[] = $this->exporter->get_categorydata($child,$level,$this->savestates);
                }
            }

        }

        $records = [];

        if($this->config->show_courses_before_subcategories) {
            $records = array_merge($courses,$categories);
        }
        else {
            $records = array_merge($categories,$courses);
        }

        $this->filtered_count = count($records);

        if(!empty($count)) {
            $records = array_slice($records,$offset,$count);
        }
        else {
            $records = array_slice($records,$offset);
        }

        return $records;
    }



    private function end_reached($count,$offset): bool {

        return $this->filtered_count <= ($count + $offset);
    }

    /**
     * @return string[]
     */
    private function get_filtered_courselist(): array {

        global $USER;

        //  $cache = cache::make('block_mycourse', 'filtered_courses');

        //  $filtered_courses = $cache->get('filtered_courses_'.$USER->id.'_'.$this->block);
        //var_Dump($filtered_courses);die();
        //  if(empty($filtered_courses)) {
           $filtered_courses = [];

        foreach($this->courses as $course) {
            if($this->filter->filter_course($course)) {
                $filtered_courses[] = $course->id;
            }
        }
          //  $cache->set('filtered_courses_'.$USER->id.'_'.$this->block,$filtered_courses);

          //  }

        return $filtered_courses;
    }

    /**
     * @return string[]
     */
    public function load_category($id,$level, $raw = 0): array {

        if(!empty($this->savestates) && !empty($this->config->category_compact_view) && $this->config->display != 2 && $this->load_compact_savestate) {
            if(!empty($this->savestates->get_savestate('compact',$id))) {
                $this->use_compact_view = true;
            }
            else {
                $this->use_compact_view = false;
            }
        }
        $children = [];
        $level++;

        if(empty($this->config->course_slider)) {
            $records = $this->make_categorylist($id,0,0,$level);
        }
        else {
            $category = core_course_category::get($id);
            if(empty($this->use_compact_view)) {
                $categories = $category->get_children($id);
            }
            else {
                $categories = [];
            }
            $records  = $this->make_categorylist($id, 0,0,$level,false);
        }

        if(empty($records)) {
            $records = [];
        }
        if(!empty($raw)) {
            return $records;
        }
        else {
            if(!empty($categories)) {
                foreach($categories as $category) {
                    $child_courses = $category->get_courses(['recursive' => 1,'idonly' => 1]);
                    if(!empty($child_courses)) {
                        $intersect = array_intersect($this->filtered_courses,$child_courses);
                    }
                    else {
                        $intersect = false;
                    }
                    if(!empty($intersect)) {
                        $children[] = $this->exporter->get_categorydata($category,$level,$this->savestates);
                    }
                }
            }

            $template_context = [
                'slider' => $this->config->course_slider,
                'slider_config' => $this->get_slider_config($this->get_slider_savestate($id)),
                'category_id' => $id,
                'level' => $level,
                'enable_compact_view' => $level == 0 ? $this->config->category_compact_view : 0,
                'is_compact_view' => $this->use_compact_view,
                'show_favorites' => $this->config->show_favorites,
                'show_available_languages' => $this->config->show_available_languages,
                'remove_activities' => $this->config->remove_activities,
                'children' => $children,
                'records' => array_values($records),
                'has_courses' => count($records)
            ];

            return $template_context;
        }
    }

    private function make_category_header(): ?string {

        if(!empty($this->config->top_category)) {
            $category = core_course_category::get($this->config->top_category);
            return $category->__get('name');
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function load_menu_category($id): array {

          $this->config->course_slider = 0;
          $records = $this->load_category($id,0,1);

          $category = core_course_category::get($id);

          $template_context = [
            'num_tiles' => $this->config->tiles_per_row ? ' tiles_per_row'.$this->config->tiles_per_row : '',
            'show_alt_view' => $this->config->show_altview,
            'current_view' => $this->alt_view,
            'target_view' => $this->get_target_view(),
            'show_back' => $id == $this->config->top_category ? 0 : 1,
            'show_favorites' => $this->config->show_favorites,
            'show_available_languages' => $this->config->show_available_languages,
            'remove_activities' => $this->config->remove_activities,
            'two_icon_switch' => $this->config->two_icon_switch,
            'current' => 0,
            'children' => [],
            'enable_compact_view' => false,
            'is_compact_view' => false,
            'display_type' => 'menu',
            'parent_id' => $category->__get('parent'),
            'header' => $category->__get('name'),
            'records' => empty($records) ? [] : array_values($records),
            'target_display' => $this->get_target_display()
          ];

          return $template_context;
    }

    private function get_target_view(): string {
        $target = '';
        if(!empty($this->alt_view)) {
            if($this->config->main_display == 2) {
                $target = 'menu';
            }
            else {
                if(!empty($this->config->main_type)) {
                    $target = 'page';
                }
                else {
                    $target = 'list';
                }
            }
        }
        else {
            if($this->config->alt_display == 2) {
                $target = 'menu';
            }
            else {
                if(!empty($this->config->alt_type)) {
                    $target = 'page';
                }
                else {
                    $target = 'list';
                }
            }
        }

        return $target;
    }

    private function get_target_display(): string {
        $target = '';
        if(!empty($this->alt_view)) {
            if($this->config->main_display == 2) {
                $target = 'menu';
            }
            else {
                if($this->config->main_display == 1) {
                    $target = 'tiles';
                }
                else {
                    $target = 'list';
                }
            }
        }
        else {
            if($this->config->alt_display == 2) {
                $target = 'menu';
            }
            else {
                if($this->config->alt_display == 1) {
                    $target = 'tiles';
                }
                else {
                    $target = 'list';
                }
            }
        }

        return $target;
    }

    //   private function clear_cache() {

    //      global $USER;

    //      $cache = cache::make('block_mycourse', 'filtered_courses');

    //      $filtered_courses = $cache->get('filtered_courses_'.$USER->id.'_'.$this->block);
    //      if(!empty($filtered_courses)) {
    //                  $cache->delete('filtered_courses_'.$USER->id.'_'.$this->block);
    //      }
    //   }
    /**
     * @return string[]
     */
    private function make_dummy(): array {

        $template_context = [
            'records' => [],
            'num_tiles' => $this->config->tiles_per_row ? ' tiles_per_row'.$this->config->tiles_per_row : '',
            'show_alt_view' => $this->config->show_altview,
            'current_view' => $this->alt_view,
            'target_view' => $this->get_target_view(),
            'show_favorites' => 0,
            'show_available_languages' => 0,
            'remove_activities' => 0,
            'two_icon_switch' => $this->config->two_icon_switch,
            'slider' => $this->config->course_slider,
            'enable_compact_view' => false,
            'is_compact_view' => false,
            'prev' => 0,
            'next' => 0,
            'current' => 0,
            'children' => [],
            'display_type' => $this->config->display == 0 ? 'list' : 'tiles',
            'target_display' => $this->get_target_display()
        ];

        return $template_context;
    }

    private function get_slider_config($first_element): object {

        $slider_config = new \stdClass;

        if(!empty($this->config->course_slider_slides_per_view) && ((int)$this->config->course_slider_slides_per_view < (int)$this->config->course_slider_slides_per_group)) {
            $this->config->course_slider_slides_per_group = $this->config->course_slider_slides_per_view;
        }

        $slider_config->direction = $this->config->course_slider_direction;
        $slider_config->loop = $this->config->course_slider_loop;
        $slider_config->touch = $this->config->course_slider_touch;
        //$slider_config->height = empty($this->config->course_slider_height) ? 'auto' : $this->config->slider_height;
        $slider_config->slides_per_view = empty($this->config->course_slider_slides_per_view) ? 'auto' : $this->config->course_slider_slides_per_view;
        $slider_config->slides_per_group = $this->config->course_slider_slides_per_group;
        $slider_config->initial = $first_element;
        $slider_config->looped_slides = empty($this->config->course_slider_slides_per_view) ? 1 : null;

        return $slider_config;
    }

    private function get_slider_savestate($category = 0): int {

        if(empty($this->config->course_slider) || empty($this->savestates)) {
            return 0;
        }

        if(empty($category)) {
            $category = $this->config->top_category;
        }

        if(empty($category)) {
            return 0;
        }

        $save = $this->savestates->get_savestate('slider',$category);

        if(!empty($save)) {
            return $save->value;
        }
        else {
            return 0;
        }

    }

    private function get_display_type(): string {

        switch($this->config->display) {
            case 0:
                return 'list';
            case 1:
                return 'tiles';
            case 2:
                return 'menu';
            default:
                return 'list';
        }
    }

}
