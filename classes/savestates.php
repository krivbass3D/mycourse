<?php

namespace block_msmycourses2;

use stdClass;

defined('MOODLE_INTERNAL') || die();

class savestates {

    private $block;
    private $alt_view;
    private $user;
    private $valid_types = ['alt_view','collapse','page','compact','menu','slider'];

    public function __construct($block,$alt_view = 0, $user = 0) {
        global $USER;

        $this->block = $block;

        if(empty($user)) {
            $user = $USER->id;
        }

        $this->user = $user;
        $this->alt_view = $alt_view;
    }

    private function check_type($type): bool {

        return !in_array($type,$this->valid_types);
    }

    public function add_savestate($type, $category, $value = 0): bool {

          global $DB;

        if(empty($this->check_type($type))) {
            return false;
        }

        $object = new stdClass;
        $object->id = null;
        $object->user = $this->user;
        $object->block = $this->block;
        $object->type = $type;
        $object->category = $category;
        $object->alt_view = $this->alt_view;
        $object->value = $value;
        $object->timecreated = time();

        if($id = $this->entry_exists($type, $category, $value)) {
            $object->id = $id;
            $this->modify_savestate($object);
        }
        else {
            $DB->insert_record('block_msmycourses2_savestate',$object);
        }
    }

    public function remove_savestate($type, $category): ?object {

        global $DB;

        if(empty($this->check_type($type))) {
              return null;
        }

        $id = $this->entry_exists($type, $category);

        if(empty($id)) {
            return null;
        }

        $DB->delete_records('block_msmycourses2_savestate', ['id' => $id]);
    }

    public function get_savestates($type = 0): ?object {

        global $DB;

        if(empty($type)) {
            return $DB->get_records('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'alt_view' => $this->alt_view]);
        }
        else {
            if(empty($this->check_type($type))) {
                return null;
            }
            return $DB->get_records('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'alt_view' => $this->alt_view, 'type' => $type]);
        }
    }

    public function get_savestate($type, $category): ?object {

        global $DB;

        if(empty($this->check_type($type))) {
               return null;
        }
        return $DB->get_record('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'alt_view' => $this->alt_view, 'type' => $type, 'category' => $category]);
    }

    public function get_alt_view_savestate(): object {

        global $DB;

        return $DB->get_records('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'type' => 'alt_view']);
    }

    private function modify_savestate($object): void {

        global $DB;

        $DB->update_record('block_msmycourses2_savestate', $object);

    }

    private function entry_exists($type,$category, $value = 0): int {

        global $DB;

        if($type == 'alt_view') {
            $record = $DB->get_record('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'type' => $type]);
        }
        else if ($type == 'menu') {
            $record = $DB->get_record('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'type' => $type, 'alt_view' => $this->alt_view]);
        }
        else {
            $record = $DB->get_record('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'type' => $type, 'alt_view' => $this->alt_view, 'category' => $category]);
        }

        return $record->id;
    }

    public function get_altview_savestate(): ?object {

        global $DB;

        return $DB->get_record('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'type' => 'alt_view']) ?: null;
    }

    public function get_menu_savestate(): ?object {

        global $DB;

        return $DB->get_record('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'type' => 'menu', 'alt_view' => $this->alt_view]) ?: null;
    }

    public function get_page_savestate(): ?object {

        global $DB;

        return $DB->get_record('block_msmycourses2_savestate', ['block' => $this->block, 'user' => $this->user, 'type' => 'page', 'alt_view' => $this->alt_view]) ?: null;
    }

    public function cleanup_userdata(): void {

        global $DB;

        foreach($this->valid_types as $type) {
            if($type !== 'collapse') {
                  $DB->delete_records('block_msmycourses2_savestate', ['user' => $this->user, 'type' => $type]);
            }
        }

    }

    public function cleanup_blockdata(): void {

        global $DB;

        foreach($this->valid_types as $type) {
            if($type !== 'collapse') {
                  $DB->delete_records('block_msmycourses2_savestate', ['block' => $this->block, 'alt_view' => $this->alt_view, 'type' => $type]);
            }
        }
    }

    public function cleanup_all(): void {

        global $DB;

        foreach($this->valid_types as $type) {
            if($type !== 'collapse') {
                  $DB->delete_records('block_msmycourses2_savestate', ['type' => $type]);
            }
        }

    }
}
