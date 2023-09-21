<?php



class block_msmycourses2 extends block_base {


    /**
     * Assign initial plugin properties.
     */
    public function init(): void {

        $this->title = get_string('plugindisplayname', 'block_msmycourses2');
        $this->content_type = BLOCK_TYPE_TEXT;
    }


    public function specialization(): void {

        // set default config values to prevent silly 'undefined property' warnings
        if(empty($this->config)) {
            $this->config = new StdClass();
        }

        if(!isset($this->config->top_category)) {
            $this->config->top_category = 0;
        }

        if(!isset($this->config->show_categories)) {
            $this->config->show_categories = 0;
        }

        if(!isset($this->config->course_title)) {
            $this->config->course_title = 0;
        }

        if(!isset($this->config->show_summary)) {
            $this->config->show_summary = 0;
        }

        if(empty($this->config->filter_course_group_idnumber)) {
            $this->config->filter_course_group_idnumber = '';
        }

        if(empty($this->config->filter_course_group)) {
            $this->config->filter_course_group = 0;
        }

        if(empty($this->config->filter_completion)) {
            $this->config->filter_completion = 0;
        }

        if(empty($this->config->filter_course_role)) {
            $this->config->filter_course_role = 0;
        }

        if(empty($this->config->filter_completion_hide_courses_without_configured_module_completion)) {
            $this->config->filter_completion_hide_courses_without_configured_module_completion = false;
        }

        if(empty($this->config->filter_progress)) {
            $this->config->filter_progress = 0;
        }

        if(empty($this->config->filter_progress_current_state)) {
            $this->config->filter_progress_current_state = 0;
        }

        if(!isset($this->config->display)) {
            $this->config->display = 0;
        }

        if(!isset($this->config->type)) {
            $this->config->type = 0;
        }

        if(!isset($this->config->limit)) {
            $this->config->limit = 3;
        }

        if(!isset($this->config->show_more)) {
            $this->config->show_more = 0;
        }

        if(!isset($this->config->load_metadata)) {
            $this->config->load_metadata = false;
        }

        if(!isset($this->config->show_favorites)) {
            $this->config->show_favorites = false;
        }

        if(!isset($this->config->remove_activities)) {
            $this->config->remove_activities = false;
        }

        if(!isset($this->config->database)) {
            $this->config->database = false;
        }

        if(!isset($this->config->progress_type)) {
            $this->config->progress_type = 0;
        }

        if(!isset($this->config->show_progress)) {
            $this->config->show_progress = false;
        }

        if(!isset($this->config->tiles_per_row)) {
            $this->config->tiles_per_row = 0;
        }
        if(!isset($this->config->hide_if_empty)) {
            $this->config->hide_if_empty = 0;
        }
        if(!isset($this->config->exclude_categories)) {
            $this->config->config_exclude_categories = 0;
        }
        if(!isset($this->config->alt_exclude_categories)) {
            $this->config->config_alt_exclude_categories = 0;
        }

        if(!isset($this->config->category_compact_view)) {
            $this->config->category_compact_view = false;
        }

        if(!isset($this->config->course_slider)) {
            $this->config->course_slider = false;
        }

        if(!isset($this->config->course_slider_direction)) {
            $this->config->course_slider_direction = 'horizontal';
        }

        if(!isset($this->config->course_slider_loop)) {
            $this->config->course_slider_loop = false;
        }

        if(!isset($this->config->course_slider_touch)) {
            $this->config->course_slider_touch = true;
        }

        if(!isset($this->config->course_slider_slides_per_view)) {
            $this->config->course_slider_slides_per_view = 0;
        }

        if(!isset($this->config->course_slider_slides_per_group)) {
            $this->config->course_slider_slides_per_group = 1;
        }

        if(!isset($this->config->alt_top_category)) {
            $this->config->alt_top_category = 0;
        }

        if(!isset($this->config->show_certificate_link)) {
            $this->config->show_certificate_link = 0;
        }

        if(!isset($this->config->alt_show_certificate_link)) {
            $this->config->alt_show_certificate_link = 0;
        }

        if(!isset($this->config->show_available_languages)) {
            $this->config->show_available_languages = 0;
        }

        if(!isset($this->config->alt_show_available_languages)) {
            $this->config->alt_show_available_languages = 0;
        }

        if(!isset($this->config->show_altview)) {
            $this->config->show_altview = 0;
        }

        if(!isset($this->config->two_icon_switch)) {
            $this->config->two_icon_switch = 0;
        }

        if(!isset($this->config->alt_show_categories)) {
            $this->config->alt_show_categories = 0;
        }

        if(!isset($this->config->alt_course_title)) {
            $this->config->alt_course_title = 0;
        }

        if(empty($this->config->alt_filter_course_group_idnumber)) {
            $this->config->alt_filter_course_group_idnumber = '';
        }

        if(empty($this->config->alt_filter_course_group)) {
            $this->config->alt_filter_course_group = 0;
        }

        if(empty($this->config->alt_filter_completion)) {
            $this->config->alt_filter_completion = 0;
        }

        if(empty($this->config->alt_filter_completion_hide_courses_without_configured_module_completion)) {
            $this->config->alt_filter_completion_hide_courses_without_configured_module_completion = false;
        }

        if(empty($this->config->alt_filter_progress)) {
            $this->config->alt_filter_progress = 0;
        }

        if(empty($this->config->alt_filter_progress_current_state)) {
            $this->config->alt_filter_progress_current_state = 0;
        }

        if(empty($this->config->alt_filter_course_role)) {
            $this->config->alt_filter_course_role = 0;
        }

        if(!isset($this->config->alt_display)) {
            $this->config->alt_display = 0;
        }

        if(!isset($this->config->alt_type)) {
            $this->config->alt_type = 0;
        }

        if(!isset($this->config->alt_limit)) {
            $this->config->alt_limit = 3;
        }

        if(!isset($this->config->alt_show_more)) {
            $this->config->alt_show_more = 0;
        }

        if(!isset($this->config->alt_progress_type)) {
            $this->config->alt_progress_type = false;
        }

        if(!isset($this->config->alt_show_progress)) {
            $this->config->alt_show_progress = false;
        }

        if(!isset($this->config->alt_tiles_per_row)) {
            $this->config->alt_tiles_per_row = 0;
        }

        if(!isset($this->config->alt_category_compact_view)) {
            $this->config->alt_category_compact_view = false;
        }

        if(!isset($this->config->alt_course_slider)) {
            $this->config->alt_course_slider = false;
        }

        if(!isset($this->config->alt_course_slider_direction)) {
            $this->config->alt_course_slider_direction = 'horizontal';
        }

        if(!isset($this->config->alt_course_slider_loop)) {
            $this->config->alt_course_slider_loop = false;
        }

        if(!isset($this->config->alt_course_slider_touch)) {
            $this->config->alt_course_slider_touch = true;
        }

        if(!isset($this->config->alt_course_slider_slides_per_view)) {
            $this->config->alt_course_slider_slides_per_view = 0;
        }

        if(!isset($this->config->alt_course_slider_slides_per_group)) {
            $this->config->alt_course_slider_slides_per_group = 1;
        }

        if(!isset($this->config->alt_course_title)) {
            $this->config->alt_course_title = 0;
        }

        if(!isset($this->config->alt_show_summary)) {
            $this->config->show_summary = 1;
        }

        if(empty($this->config->language_restriction)) {
            $this->config->language_restriction = '';
        }

        if(empty($this->config->alt_language_restriction)) {
            $this->config->alt_language_restriction = '';
        }

        // use configured title string, if not empty
        if(!empty($this->config->title)) {
            $this->title = format_text($this->config->title, FORMAT_HTML);
        }
        else if(!empty($this->config->show_altview)) {
            $this->title = get_string('title', 'block_msmycourses2');
        }

        // update block title
        else if(!empty($this->config->filter_course_group)) {

            if($this->config->filter_course_group == 2) {
                $this->title = get_string('title_not_subscribed', 'block_msmycourses2');
            }

            else {
                $this->title = get_string('title_subscribed', 'block_msmycourses2');
            }
        }

        else if(!empty($this->config->filter_completion)) {

            switch($this->config->filter_completion) {

                case 1:
                    $this->title = get_string('title_completed', 'block_msmycourses2');
                    break;
                case 2:
                case 3:
                    $this->title = get_string('title_uncompleted', 'block_msmycourses2');
                    break;
            }
        }

        else {
            $this->title = get_string('title', 'block_msmycourses2');
        }

    }


    /**
     * Allows the block to load any JS it requires into the page.
     *
     * By default this function simply permits the user to dock the block if it is dockable.
     */
    public function get_required_javascript(): void {

        parent::get_required_javascript();

        if($this->config->display == 2 || ($this->config->alt_display == 2 && $this->config->show_altview)) {
            $this->page->requires->js_call_amd('block_msmycourses2/menu','init',['id' => $this->instance->id]);
        }
        if($this->config->display != 2 || ($this->config->alt_display != 2 && $this->config->show_altview)) {
            $this->page->requires->js_call_amd('block_msmycourses2/show_more','init',['id' => $this->instance->id]);
        }

        if($this->config->show_categories == 1 || ($this->config->alt_show_categories == 1 && $this->config->show_altview == 1)) {
                 $this->page->requires->js_call_amd('block_msmycourses2/collapse', 'init', ['id' => $this->instance->id]);
        }

        if($this->config->show_altview) {
            $this->page->requires->js_call_amd('block_msmycourses2/switch_view','init',['id' => $this->instance->id]);
        }

        if($this->config->show_favorites && block_msmycourses2\helper::favorites_installed()) {
            $this->config->show_favorites = 1;
            $this->page->requires->js_call_amd('block_mssetfavoritecourse/mssetfavoritecourse','init',['id' => $this->instance->id]);
        }
        else {
            $this->config->show_favorites = 0;
        }

        if($this->config->course_slider && $this->config->top_category != 0) {
            $this->page->requires->js_call_amd('block_msmycourses2/top_category_slider_init','init',['id' => $this->instance->id, 'category' => $this->config->top_category, 'current_view' => 0]);
        }

        if($this->config->alt_course_slider && $this->config->alt_top_category != 0) {
            $this->page->requires->js_call_amd('block_msmycourses2/top_category_slider_init','init',['id' => $this->instance->id, 'category' => $this->config->alt_top_category, 'current_view' => 1]);
        }

        $this->page->requires->js_call_amd('block_msmycourses2/compact_view','init',['id' => $this->instance->id]);
        $this->page->requires->js_call_amd('block_msmycourses2/init_load','init',['id' => $this->instance->id]);

    }
    /**
     * This method returns a boolean value that denotes whether the block
     * wants to present a configuration interface to site admins or not.
     *
     * @return bool Returns true
     */
    public function has_config(): bool {
        return false;
    }

    /**
     * This method returns a boolean value, indicating whether the block is
     * allowed to have multiple instances in the same page or not.
     *
     * @return bool Returns false
     */
    public function instance_allow_multiple(): bool {
        return true;
    }

    /**
     * Set the applicable formats for this plugin.
     *
     * @return string[] Array of booleans for each format that is allowed or not
     */
    public function applicable_formats(): array {
        return ['all' => true];
    }

    /**
     * Determins wether the plugin can be docked to the sidebar.
     *
     * @return boolean Returns true
     */
    public function instance_can_be_docked(): bool {
        return true;
    }

    /**
     * Add custom css classes
     * @return string[]
     */
    public function html_attributes(): array {

            $attributes = parent::html_attributes();

        if(!empty($this->config->css_classes)) {
                $attributes['class'] .= ' ' . $this->config->css_classes;
        }

            return $attributes;
    }

    public function get_content(): object {

        global $OUTPUT;

        if(!isloggedin() || isguestuser()) {
            return '';
        }

        if($this->content !== null) {
            return $this->content;
        }

        if(!empty($this->config->hide_if_empty)) {
            $builder = new block_msmycourses2\builder($this->config,$this->instance->id);
            $returnarray = $builder->generate_output($this->config->limit,0,false);

            if (empty(count($returnarray['records'])) && empty(count($returnarray['children']))) {
                    return $this->content;
            }
        }

        $this->content = new stdClass;
        $this->content->text = $OUTPUT->render_from_template('block_msmycourses2/wrapper', ['spinner' => \local_mscore_helper::get_spinner()]);

        return $this->content;
    }

}
